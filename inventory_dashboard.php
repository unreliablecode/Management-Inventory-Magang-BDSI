// inventory_dashboard.php
<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM inventory");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Inventory Dashboard</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo $item['item_name']; ?></td>
                    <td><?php echo $item['description']; ?></td>
                    <td><?php echo $item['status']; ?></td>
                    <td>
                        <a href="remove_item.php?id=<?php echo $item['id']; ?>">Remove</a>
                        <a href="update_item_status.php?id=<?php echo $item['id']; ?>">Update Status</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="add_item.php">Add Item</a>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
