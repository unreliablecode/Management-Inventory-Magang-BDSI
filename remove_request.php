<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Validate input
    if (empty($id)) {
        echo "ID is required.";
        exit();
    }

    // Remove the request from the database
    $stmt = $conn->prepare("DELETE FROM material_request WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: requests_dashboard.php");
        exit();
    } else {
        echo "Error removing request: " . $conn->error;
    }
}
?>
