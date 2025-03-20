<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requester_name = trim($_POST['requester_name']);
    $item = trim($_POST['item']);
    
    $errors = [];

    // Validate input
    if (empty($requester_name)) {
        $errors['requester_name'] = 'Requester name is required.';
    }
    if (empty($item)) {
        $errors['item'] = 'Item is required.';
    }

    var_dump(empty($errors));

    // If no errors, insert request into database
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO material_request (requester_name, item) VALUES (?, ?)");
        $stmt->bind_param("ss", $requester_name, $item);
        if ($stmt->execute()) {
            header("Location: requests_dashboard.php");
            exit();
        } else {
            $errors['general'] = 'Failed to add request. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Material Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Add Material Request</h2>
    <form action="" method="post">
        <div>
            <label for="requester_name">Requester Name:</label>
            <input type="text" name="requester_name" value="<?php echo isset($requester_name) ? $requester_name : ''; ?>">
            <span><?php echo isset($errors['requester_name']) ? $errors['requester_name'] : ''; ?></span>
        </div>
        <div>
            <label for="item">Item:</label>
            <input type="text" name="item" value="<?php echo isset($item) ? $item : ''; ?>">
            <span><?php echo isset($errors['item']) ? $errors['item'] : ''; ?></span>
        </div>
        <div>
            <button type="submit">Add Request</button>
        </div>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </form>
</body>
</html>
