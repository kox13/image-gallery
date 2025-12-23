<?php
require_once __DIR__ . '/../Database.php';
class UserModel extends Database
{
    public function getUsers($params, $db)
    {
        $result = $db->find()->toArray();

        foreach ($result as $user) {
            var_dump($user);
        }
    } // DEBUG

    public function loginUser($params, $db): array
    {
        list($username, $password) = $params;

        $user = $db->findOne(['username' => $username]);

        if (empty($user) || !password_verify($password, $user->hash))
            return ['success' => false, 'error' => "Invalid username or password"];
        else
            return ['success' => true, 'message' => 'Logged in successfully', 'user' => ['id' => $user->_id, 'username' => $user->username]];
    }

    public function registerUser($params, $db): array
    {
        list($username, $password, $email) = $params;

        $result = $db->insertOne([
            'username' => $username,
            'email' => $email,
            'hash' => password_hash($password, PASSWORD_BCRYPT),
            'favourites' => []
        ]);

        if (!$result->isAcknowledged())
            return ['success' => false, 'error' => "Failed to register the user"];

        return ['success' => true, 'message' => 'Registered successfully'];
    }

    public function getUser($value, $field = 'username')
    {
        $db = $this->connect('users');
        return $db->findOne([$field => $value]);
    }

    public function addFavourites($params, $db): array
    {
        list($imageIds, $userId) = $params;

        $result = $db->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($userId)],
            ['$addToSet' => ['favourites' => ['$each' => $imageIds]]]
        );

        if ($result->getModifiedCount() > 0)
            return ['success' => true, 'message' => 'Image(s) added to favourites'];
        else
            return ['success' => false, 'error' => 'Failed to add the image(s) to favourites'];
    }

    public function deleteFavourites($params, $db): array
    {
        list($toDelete, $userId) = $params;

        $result = $db->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($userId)],
            ['$pull' => ['favourites' => ['$in' => $toDelete]]]
        );

        if ($result->getModifiedCount() > 0)
            return ['success' => true, 'message' => 'Image(s) removed from favourites'];
        else
            return ['success' => false, 'error' => 'Failed to remove the image(s) from favourites'];
    }

    public function getFavourites($params, $db)
    {
        list($userId) = $params;

        $result = $db->findOne(['_id' => new MongoDB\BSON\ObjectId($userId)], ['projection' => ['favourites' => 1]]);

        return $result['favourites']->getArrayCopy();
    }
}