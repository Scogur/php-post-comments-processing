<?php
function getData($url){
    $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit;
}

curl_close($ch);

return json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Decode Error: " . json_last_error_msg() . "\n";
    exit;
}
}

$posts = "https://jsonplaceholder.typicode.com/posts";
$comments = "https://jsonplaceholder.typicode.com/comments";

printf("Загрузка файлов...\n");

$postsData = getData($posts);
$commentsData = getData($comments);


$username = "PHP";
$password = "112233";
$connection_string = "localhost/XEPDB1";

$conn = oci_connect($username, $password, $connection_string, 'AL32UTF8');

if (!$conn) {
    $e = oci_error();
    die("Ошибка подключения: " . $e['message']);
}
printf("✅ Успешное подключение к Oracle\n");


$sqlPost = "INSERT INTO posts (id, user_id, title, body) VALUES (:id, :user_id, :title, :body)";
$stmtPost = oci_parse($conn, $sqlPost);

foreach ($postsData as $post) {
    oci_bind_by_name($stmtPost, ":id", $post['id']);
    oci_bind_by_name($stmtPost, ":user_id", $post['userId']);
    oci_bind_by_name($stmtPost, ":title", $post['title']);
    oci_bind_by_name($stmtPost, ":body", $post['body']);
    oci_execute($stmtPost, OCI_NO_AUTO_COMMIT);
}
oci_commit($conn);

$sqlComment = "INSERT INTO comments (id, post_id, name, email, body) 
               VALUES (:id, :post_id, :name, :email, :body)";
$stmtComment = oci_parse($conn, $sqlComment);

foreach ($commentsData as $comment) {
    oci_bind_by_name($stmtComment, ":id", $comment['id']);
    oci_bind_by_name($stmtComment, ":post_id", $comment['postId']);
    oci_bind_by_name($stmtComment, ":name", $comment['name']);
    oci_bind_by_name($stmtComment, ":email", $comment['email']);
    oci_bind_by_name($stmtComment, ":body", $comment['body']);
    oci_execute($stmtComment, OCI_NO_AUTO_COMMIT);
}
oci_commit($conn);

oci_free_statement($stmtPost);
oci_free_statement($stmtComment);
oci_close($conn);

printf("✅ Загружено %d записей и %d комментариев", count($postsData), count($commentsData));
