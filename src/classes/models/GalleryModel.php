<?php
require_once __DIR__ . "/../Database.php";
class GalleryModel extends Database
{
    public function getPublicImageCount($params, $db)
    {
        $result = $db->count(['private' => false]);
        if (is_object($result)) {
            return (int)($result->n ?? $result->count ?? 0);
        } elseif (is_array($result)) {
            return (int)($result['n'] ?? $result['count'] ?? reset($result) ?? 0);
        }
        return (int)$result;
    }

    public function getUsersImageCount($params, $db)
    {
        list($userId) = $params;

        $result = $db->count(['owner' => new MongoDB\BSON\ObjectId($userId)]);
        if (is_object($result)) {
            return (int)($result->n ?? $result->count ?? 0);
        } elseif (is_array($result)) {
            return (int)($result['n'] ?? $result['count'] ?? reset($result) ?? 0);
        }
        return (int)$result;
    }

    public function getImages($params, $db)
    {
        list($page, $maxRecords) = $params;

        $skip = ($page - 1) * $maxRecords;

        return $db->find(['private' => false], ['limit' => $maxRecords, 'skip' => $skip])->toArray();
    }

    public function getUsersImages($params, $db): array
    {
        list($userId, $page, $maxRecords) = $params;

        $skip = ($page - 1) * $maxRecords;

        return $db->find(['owner' => new MongoDB\BSON\ObjectId($userId)], ['limit' => $maxRecords, 'skip' => $skip])->toArray();
    }

    public function insertImage($params, $db): array
    {
        list($author, $title, $originalImagePath, $watermarkImagePath, $thumbnailImagePath, $private, $ownerId, $type) = $params;

        $result = $db->insertOne(
            [
                'image' => $originalImagePath,
                'author' => $author,
                'title' => $title,
                'watermark' => $watermarkImagePath,
                'thumbnail' => $thumbnailImagePath,
                'private' => $private,
                'type' => $type,
                'owner' => $ownerId ? new MongoDB\BSON\ObjectId($ownerId) : null
            ]
        );

        if ($result->isAcknowledged())
            return ['success' => true, 'message' => 'Image added successfully'];
        else
            return ['success' => false, 'error' => 'Failed to add the image'];
    }

    public function getFavourites($params, $db)
    {
        list($favourites, $page, $maxRecords) = $params;

        $skip = ($page - 1) * $maxRecords;

        $favourites = array_map(function ($id) {
            return new MongoDB\BSON\ObjectId($id);
        }, $favourites);

        return $db->find(['_id' => ['$in' => $favourites]], ['limit' => $maxRecords, 'skip' => $skip])->toArray();
    }
}
