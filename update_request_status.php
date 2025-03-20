<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Validate input
    if (empty($id) || empty($status)) {
        echo "ID and status are required.";
        exit();
    }

    // Update the status in the database
    $stmt = $conn->prepare("UPDATE material_request SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: requests_dashboard.php");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}
?>
