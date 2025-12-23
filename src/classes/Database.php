<?php
require '../../vendor/autoload.php';

const DB_CONNECTION_ERR = ['success' => false, 'error' => 'Failed to connect to the database, try again'];

class Database
{
    private $databaseName = "gallery";
    private $uri = "mongodb://mongodb:27017/gallery";

    protected function connect($collection)
    {
        try {
            $connection = new MongoDB\Client($this->uri);

            return $connection->{$this->databaseName}->selectCollection($collection);
        } catch (MongoDB\Driver\Exception\ConnectionException $e) {
            throw new Exception("Failed to connect to the database");
        }
    }

    public function query($collection, $callback, $params = [])
    {
        try {
            $db = $this->connect($collection);

            return call_user_func([$this, $callback], $params, $db);
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Something went wrong, try again'];
        }
    }
}