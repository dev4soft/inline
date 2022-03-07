<?php

$config = require_once '../config/config.php';

require_once '../src/dbconnect.php';

$db = new DbConnect($config);

function load_data($stmt, $data, $params_name) {

    $count = 0;

    foreach ($data as $row) {

        foreach ($params_name as $param) {
            $stmt->bindParam($param, $row[$param]);
        }

        if ($stmt->execute()) {
            $count += 1;
        }
    }

    return $count;
}

$data = file_get_contents('https://jsonplaceholder.typicode.com/posts');
$posts = json_decode($data, true);
$stmt = $db->prepare('insert into posts (id, userId, title, body) values (:id, :userId, :title, :body)');

$posts_count = load_data(
    $stmt,
    $posts,
    [
        'id',
        'userId',
        'title',
        'body',
    ]
);

$data = file_get_contents('https://jsonplaceholder.typicode.com/comments');
$comments = json_decode($data, true);
$stmt = $db->prepare('insert into comments (id, postId, name, email, body) values (:id, :postId, :name, :email, :body)');

$comments_count = load_data(
    $stmt,
    $comments,
    [
        'id',
        'postId',
        'name',
        'email',
        'body',
    ]
);

echo "Загружено ${posts_count} записей и ${comments_count} комментариев" . PHP_EOL;

