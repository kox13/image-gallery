<?php
require_once 'Controller.php';

const MAX_IMAGES_PER_PAGE = 15;

class GalleryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model('Gallery');
    }

    public function showFavourites()
    {
        if(empty($_SESSION['user']))
            $this->redirect('/gallery');

        if(empty($_SESSION['view_data']))
            $_SESSION['view_data'] = ['action' =>'/favourites/delete', 'page_action' => '/gallery/favourites'];
        else
            $_SESSION['view_data'] += ['action' =>'/favourites/delete', 'page_action' => '/gallery/favourites'];

        if(empty($_SESSION['favourites'])){
            $this->view->data = [];
        } else {
            $page = $_GET['page'];

            $images = $this->model->query('images', 'getFavourites', [$_SESSION['favourites'], $page, MAX_IMAGES_PER_PAGE]);

            if($this->isSuccessful($images)) {
                $this->view->data['pages'] = ceil(count($_SESSION['favourites']) / MAX_IMAGES_PER_PAGE);
                $this->prepareImages($images);
            }
        }

        $this->view->renderView('gallery', 'gallery');
    }

    public function showAll()
    {
        if(empty($_SESSION['view_data']))
            $_SESSION['view_data'] = ['action' =>'/favourites/add', 'page_action' => '/gallery'];
        else
            $_SESSION['view_data'] += ['action' =>'/favourites/add', 'page_action' => '/gallery'];

        $page = $_GET['page'];

        $images = $this->model->query('images', 'getImages', [$page, MAX_IMAGES_PER_PAGE]);
        $imageCountResult = $this->model->query('images', 'getPublicImageCount');
        $imageCount = is_array($imageCountResult) ? 0 : $imageCountResult;
        $pages = ceil($imageCount / MAX_IMAGES_PER_PAGE);

        if($this->isSuccessful($images))
            $this->prepareImages($images);

        $this->view->data['pages'] = $pages;

        $this->view->renderView('gallery', 'gallery');
    }

    public function showUsersImages(){
        if(empty($_SESSION['user'])) {
            $this->redirect('/gallery');
            exit;
        }

        if(empty($_SESSION['view_data']))
            $_SESSION['view_data'] = ['action' =>'/favourites/add', 'page_action' => '/gallery/my_images'];
        else
            $_SESSION['view_data'] += ['action' =>'/favourites/add', 'page_action' => '/gallery/my_images'];

        $userId = $_SESSION['user']['id'];
        $page = $_GET['page'];

        $pages = ceil($this->model->query('images', 'getUsersImageCount', [$userId]) / MAX_IMAGES_PER_PAGE);

        $this->view->data['pages'] = $pages;

        $images = $this->model->query('images', 'getUsersImages', [$userId, $page, MAX_IMAGES_PER_PAGE]);

        if($this->isSuccessful($images))
            $this->prepareImages($images);

        $this->view->renderView('gallery', 'gallery');
    }

    public function add()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->processInsert();
            $this->redirect('/gallery/add');
            exit;
        }

        $this->view->renderView('galleryForm', 'form');
    }

    private function processInsert(){
        $file = $_FILES['file'] ?? [];
        $author = $_POST['author'] ?? '';
        $title = $_POST['title'] ?? '';
        $watermark = $_POST['watermark'] ?? '';
        $private = !empty($_POST['privacy']) ? filter_var($_POST['privacy'], FILTER_VALIDATE_BOOLEAN) : false;
        $userId = !empty($_SESSION['user']) ? $_SESSION['user']['id'] : null;

        if(!$this->validateInsertInput($file, $author, $title, $watermark))
            return;

        $originalImage = imagecreatefromstring(file_get_contents($file['tmp_name']));

        $originalImagePath = $this->saveImage($file, $originalImage);
        $watermarkImagePath = $this->createWatermarkImage($watermark, $file, $originalImage);
        $thumbnailImagePath = $this->createThumbnail($file, $originalImage);
        
        imagedestroy($originalImage);

        $type = $this->getImageType($file);

        $_SESSION['view_data'] = $this->model->query('images', 'insertImage',
            [$author, $title, $originalImagePath, $watermarkImagePath, $thumbnailImagePath, $private, $userId, $type]);
    }

    private function validateInsertInput($file, $author, $title, $watermark): bool
    {
        if(empty($file) || empty($author) || empty($title) || empty($watermark))
            $this->addError('Fields cannot be empty');

        if ($file['type'] != 'image/jpeg' && $file['type'] != 'image/png')
            $this->addError('Image can only be in jpg/png format');

        if ($file['size'] > 1024 * 1024 || $file['error'] === UPLOAD_ERR_INI_SIZE)
            $this->addError("Image size cannot exceed 1MB");

        if($file['error'] !== UPLOAD_ERR_OK && $file['error'] !== UPLOAD_ERR_INI_SIZE)
            $this->addError("Something went wrong");

        if(!empty($_SESSION['view_data']['error']))
            return false;

        return true;
    }

    private function createThumbnail($file, $originalImage): string
    {
        $width = 200;
        $height = 125;

        $resizedImage = imagecreatetruecolor($width, $height);

        imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $width, $height, imagesx($originalImage), imagesy($originalImage));

        $imagePath = $this->saveImage($file, $resizedImage, 't');

        imagedestroy($resizedImage);

        return $imagePath;
    }

    private function createWatermarkImage($watermark, $file, $originalImage): string
    {
        $imageWidth = imagesx($originalImage);
        $imageHeight = imagesy($originalImage);
        
        $image = imagecreatetruecolor($imageWidth, $imageHeight);
        imagecopy($image, $originalImage, 0, 0, 0, 0, $imageWidth, $imageHeight);

        $fontSizePercentage = 5;
        $fontSize = ($imageWidth * $fontSizePercentage) / 100;
        $fontColor = imagecolorallocate($image, 255, 255, 255);
        $fontPath = 'static/fonts/Lato-Bold.ttf';

        $textX = $imageWidth / 2;
        $textY = $imageHeight / 2;

        imagettftext($image, $fontSize, 0, $textX, $textY, $fontColor, $fontPath, $watermark);

        $imagePath = $this->saveImage($file, $image, 'w');

        imagedestroy($image);

        return $imagePath;
    }

    private function saveImage($file, $image, $type = ""): string
    {
        $ext = $this->getImageType($file);

        static $name;
        static $counter;

        if(!$name || $counter === 3) {
            $name = md5(basename($file['tmp_name']));
            $counter = 0;
        }

        $counter++;

        $imagePath = "images/{$name}_$type.$ext";

        if($ext === 'jpeg')
            imagejpeg($image, $imagePath);
        else
            imagepng($image, $imagePath);

        return $imagePath;
    }

    private function prepareImages($images){
        $this->view->data['images'] = [];

        if(!empty($images)) {
            foreach ($images as $image) {
                $thumbnailPath = $image['thumbnail'];
                $watermarkPath = $image['watermark'];
                $id = $image['_id'];
                $title = $image['title'];
                $author = $image['author'];
                $private = $image['private'];
                $favourite = false;

                if($_SERVER['REDIRECT_URL'] !== '/gallery/favourites' && isset($_SESSION['favourites']))
                    $favourite = in_array($id, $_SESSION['favourites']);

                $this->view->data['images'][] = [
                    'id' => $id,
                    'watermark_path' => $watermarkPath,
                    'thumbnail_path' => $thumbnailPath,
                    'title' => $title,
                    'author' => $author,
                    'private' => $private,
                    'favourite' => $favourite
                ];
            }
        }
    }

    private function getImageType($file)
    {
        $fileTypeParts = explode('/', $file['type']);
        return end($fileTypeParts);
    }
}
