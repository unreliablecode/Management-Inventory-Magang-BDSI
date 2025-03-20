// add_item.php
<?php
session_start();
require 'db.php'; // Ensure this file contains your database connection logic

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = trim($_POST['item_name']);
    $description = trim($_POST['description']);
    $errors = [];

    // Validate input
    if (empty($item_name)) {
        $errors['item_name'] = 'Item name is required.';
    }

    if (empty($description)) {
        $errors['description'] = 'Description is required.';
    }

    // If there are no errors, proceed to insert the item into the database
    if (empty($errors)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO inventory (item_name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $item_name, $description);

        if ($stmt->execute()) {
            // Redirect to inventory dashboard or show success message
            header("Location: inventory_dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
</head>
<body>
    <h2>Add Item</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div>
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" value="<?php echo isset($item_name) ? htmlspecialchars($item_name) : ''; ?>">
            <span><?php echo isset($errors['item_name']) ? $errors['item_name'] : ''; ?></span>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea name="description"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
            <span><?php echo isset($errors['description']) ? $errors['description'] : ''; ?></span>
        </div>
        <div>
            <button type="submit">Add Item</button>
        </div>
    </form>
    <a href="inventory_dashboard.php">Back to Inventory Dashboard</a>
</body>
</html>
