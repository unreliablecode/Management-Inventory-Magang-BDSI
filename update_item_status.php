<?php
session_start();
require 'db.php'; // Include your database connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = intval($_POST['item_id']);
    $status = trim($_POST['status']);
    $taken_by = trim($_POST['taken_by']);
    $errors = [];

    // Validate input
    if (empty($item_id)) {
        $errors['item_id'] = 'Item ID is required.';
    }

    if ($status !== 'available' && $status !== 'taken') {
        $errors['status'] = 'Invalid status.';
    }

    if ($status === 'taken' && empty($taken_by)) {
        $errors['taken_by'] = 'Name of the person taking the item is required.';
    }

    // If there are no errors, proceed to update the item status
    if (empty($errors)) {
        // Prepare the SQL statement
        if ($status === 'taken') {
            $stmt = $conn->prepare("UPDATE inventory SET status = ?, taken_by = ? WHERE id = ?");
            $stmt->bind_param("ssi", $status, $taken_by, $item_id);
        } else {
            $stmt = $conn->prepare("UPDATE inventory SET status = ?, taken_by = NULL WHERE id = ?");
            $stmt->bind_param("si", $status, $item_id);
        }

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to inventory dashboard or show success message
            header("Location: inventory_dashboard.php?message=Item status updated successfully.");
            exit();
        } else {
            $errors['database'] = 'Failed to update item status. Please try again.';
        }
    }
}

// Fetch the item details for the form (optional)
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$item = null;

if ($item_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item Status</title>
</head>
<body>
    <h2>Update Item Status</h2>

    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="update_item_status.php" method="post">
        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
        <div>
            <label for="status">Status:</label>
            <select name="status" required>
                <option value="available" <?php echo ($item['status'] === 'available') ? 'selected' : ''; ?>>Available</option>
                <option value="taken" <?php echo ($item['status'] === 'taken') ? 'selected' : ''; ?>>Taken</option>
            </select>
        </div>
        <div>
            <label for="taken_by">Taken By (if applicable):</label>
            <input type="text" name="taken_by" value="<?php echo ($item['status'] === 'taken') ? $item['taken_by'] : ''; ?>">
        </div>
        <div>
            <button type="submit">Update Status</button>
        </div>
    </form>

    <a href="inventory_dashboard.php">Back to Inventory Dashboard</a>
</body>
</html>
