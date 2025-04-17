<?php
session_start();
require 'db.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the item ID is provided in the URL
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Validate input
    if (empty($item_id)) {
        echo "Item ID is required.";
        exit();
    }

    // Remove item from the database
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $item_id); // Bind the item ID as an integer

    if ($stmt->execute()) {
        echo "Item removed successfully.";
        header("Location: item_manager.php");
    } else {
        echo "Error removing item: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No item ID provided.";
}

$conn->close();
?>
