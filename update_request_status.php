<?php
session_start();
require 'db.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the item ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Validate input
    if (empty($id)) {
        echo "Item ID is required.";
        exit();
    }

    if ($_GET['status'] == 'pending')
    {
        $status = 'done';
    }
    else if ($_GET['status'] == 'done')
    {
        $status = 'cancelled';
    }
    else if ($_GET['status'] == 'cancelled')
    {
        $status = 'out of stock';
    }
    else if ($_GET['status'] == 'out of stock')
    {
        $status = 'pending';
    }
    // Update the status in the database
    $stmt = $conn->prepare("UPDATE material_request SET status = ?, last_changed = NOW() WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: material_request.php");
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}
?>
