<?php

require 'vendor/autoload.php';
require 'config.php';
require 'database/dbs.php';


$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['logger'] = function($c) {

    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('./logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
//db config
$container['db'] = function ($c) {
    try {
        $db = $c['settings']['db'];
        $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
            $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (\Throwable $th) {
        throw new \Exception('Database Connection Error');
        
    }
};

$app->get('/books/{id}', function ($request, $response, $args) {
    $books = new \api\books\Books($this->db);
    $data = $books->getBook($args['id']);
    
    return $response->withJson($data);
});
$app->get('/books', function ($request, $response, $args) {
    $books = new \api\books\Books($this->db);
    $data = $books->getBooks();
    
    return $response->withJson($data);
});
$app->run();

