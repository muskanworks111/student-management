<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header("Location: index.php");
    exit();
}
require_once 'config/db.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($id)) {
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: students.php");
exit();
?>