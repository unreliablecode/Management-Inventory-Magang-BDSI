// login.php
<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $errors = [];

    // Validate input
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    // If no errors, check credentials
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $errors['password'] = 'Invalid password.';
            }
        } else {
            $errors['username'] = 'No user found with that username.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Login</h2>
    <form action="" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo isset($username) ? $username : ''; ?>">
            <span><?php echo isset($errors['username']) ? $errors['username'] : ''; ?></span>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password">
            <span><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</body>
</html>
