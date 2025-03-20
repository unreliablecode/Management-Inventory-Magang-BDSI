// requests_dashboard.php
<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM material_request");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Requests</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Material Requests</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Requester Name</th>
                <th>Item</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($request = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $request['id']; ?></td>
                    <td><?php echo $request['requester_name']; ?></td>
                    <td><?php echo $request['item']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="add_request.php">Add Request</a>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
