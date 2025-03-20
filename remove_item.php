<?php
session_start();
require 'db.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];

    // Validate input
    if (empty($item_id)) {
        echo "Item ID is required.";
        exit();
    }

    // Remove item from the database
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo "Item removed successfully.";
    } else {
        echo "Error removing item: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    // If not a POST request, redirect to inventory dashboard or show an error
    header("Location: inventory_dashboard.php");
    exit();
}
?>
