<?php
session_start();
require 'db.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the item ID is provided in the URL
if (isset($_GET['id'])) {
    $request_id = $_GET['id'];

    // Validate input
    if (empty($request_id)) {
        echo "Item ID is required.";
        exit();
    }

    // Remove the request from the database
    $stmt = $conn->prepare("DELETE FROM material_request WHERE id = ?");
    $stmt->bind_param("i", $request_id);

    if ($stmt->execute()) {
        header("Location: material_request.php");
        exit();
    } else {
        echo "Error removing request: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "No item ID provided.";
}

$conn->close();
?>
