<?php
session_start();
require 'db.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Handle registration
if (isset($_POST['register'])) {
    $userid = $_POST['userid'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];

    $query = "INSERT INTO users (user_id, name, password, email, mobile, position) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssss', $userid, $name, $password, $email, $phone, $position);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful! Please log in.";
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
    }
}

// Handle login
if (isset($_POST['login'])) {
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['position'] = $user['position'];

            // Log user activity
            $activity = "User logged in";
            $activity_date = date('Y-m-d H:i:s');
            $activity_query = "INSERT INTO user_activity (user_id, activity, activity_date) VALUES (?, ?, ?)";
            $activity_stmt = $conn->prepare($activity_query);
            $activity_stmt->bind_param('sss', $user['user_id'], $activity, $activity_date);
            $activity_stmt->execute();

            // Redirect to home page
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = "Invalid password.";
        }
    } else {
        $_SESSION['error'] = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="form-container">
                <div id="login-form" class="form-box active">
                    <h2>Login</h2>
                    <?php if (isset($_SESSION['error'])): ?>
                        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                    <?php endif; ?>
                    <form action="login.php" method="post">
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" name="userid" placeholder="User ID" required>
                        </div>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" name="login">Login</button>
                        <p class="switch-text">Don't have an account? <a href="#" id="show-register">Register</a></p>
                    </form>
                </div>

                <div id="register-form" class="form-box">
    <h2>Register</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <p class="success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <div class="input-group" style="margin-bottom: 5px;">
            <i class="fas fa-id-badge"></i>
            <input type="text" name="userid" placeholder="User ID" required>
        </div>
        <div class="input-group" style="margin-bottom: 5px;">
            <i class="fas fa-user"></i>
            <input type="text" name="name" placeholder="Name" required>
        </div>
        <div class="input-group" style="margin-bottom: 5px;">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="input-group" style="margin-bottom: 5px;">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group" style="margin-bottom: 5px;">
            <i class="fas fa-phone"></i>
            <input type="text" name="phone" placeholder="Phone" required>
        </div>
        <div class="input-group" style="margin-bottom: 5px;">
            <i class="fas fa-briefcase"></i>
            <select name="position" required>
                <option value="" disabled selected>Select Position</option>
                <option value="Customer">Customer</option>
                <option value="Staff">Staff</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <button type="submit" name="register">Register</button>
        <p class="switch-text">Already have an account? <a href="#" id="show-login">Login</a></p>
    </form>
</div>

            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const showLoginLink = document.getElementById('show-login');
        const showRegisterLink = document.getElementById('show-register');

        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
        });

        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
        });
    </script>
</body>
</html>
