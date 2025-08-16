<?php
$username = "PHP";
$password = "112233";
$connection_string = "localhost/XEPDB1";

$conn = oci_connect($username, $password, $connection_string, 'AL32UTF8');
if (!$conn) {
    $e = oci_error();
    die("Connection failed: " . $e['message']);
}

$results = [];
$searchTerm = '';
$error = '';

if (isset($_POST['search'])) {
    $searchTerm = trim($_POST['search']);

    if (strlen($searchTerm) < 3) {
        $error = "Введите хотя бы 3 символа.";
    } else {
        $termLike = '%' . strtolower($searchTerm) . '%';

        $sqlComments = "SELECT DISTINCT post_id FROM comments WHERE LOWER(body) LIKE :term";
        $stmt = oci_parse($conn, $sqlComments);
        oci_bind_by_name($stmt, ":term", $termLike);
        oci_execute($stmt);

        $postIds = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $postIds[] = $row['POST_ID'];
        }
        oci_free_statement($stmt);

        if (!empty($postIds)) {
            // Get post titles for these IDs
            $inClause = implode(',', $postIds);
            $sqlPosts = "SELECT id, title FROM posts WHERE id IN ($inClause)";
            $stmtPosts = oci_parse($conn, $sqlPosts);
            oci_execute($stmtPosts);

            while ($row = oci_fetch_assoc($stmtPosts)) {
                $results[] = $row['TITLE'];
            }
            oci_free_statement($stmtPosts);
        }
    }
}

oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Поиск постов</title>
<style>
body { font-family: Arial, sans-serif; margin: 30px; }
input[type="text"] { width: 300px; padding: 5px; }
button { padding: 5px 10px; }
.result { margin-top: 10px; font-weight: bold; }
.error { color: red; }
</style>
</head>
<body>

<h1>Поиск постов</h1>
<form method="post">
    <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Введите текст">
    <button type="submit">Найти</button>
</form>

<?php if ($error): ?>
<p class="error"><?= $error ?></p>
<?php endif; ?>

<?php if ($results): ?>
<h2>Подходящие посты:</h2>
<?php foreach ($results as $title): ?>
<div class="result"><?= htmlspecialchars($title) ?></div>
<?php endforeach; ?>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error): ?>
<p>Не найдено.</p>
<?php endif; ?>

</body>
</html>
