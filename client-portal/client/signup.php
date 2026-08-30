<?php
session_start();

if (isset($_SESSION['client_id'])) {
    header('Location: dashboard.php');
    exit();
}

require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        try {
            $checkStmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
            $checkStmt->execute([$email]);
            
            if ($checkStmt->fetch()) {
                $error = 'This email is already registered.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insertStmt = $pdo->prepare("INSERT INTO clients (name, email, password) VALUES (?, ?, ?)");
                $insertStmt->execute([$name, $email, $hashed_password]);
                
                $success = 'Account created successfully! Redirecting to login page...';
            }
        } catch (PDOException $e) {
            $error = 'Something went wrong. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Client Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <!-- Auto Redirect: 3 second baad index.php (login) par bhej dega -->
    <?php if ($success): ?>
        <meta http-equiv="refresh" content="3;url=index.php">
    <?php endif; ?>
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <h2>Create Account</h2>
            <p>Sign up to access your client portal</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
                <!-- Yahan bhi index.php kar diya hai -->
                <a href="index.php" class="btn btn-primary btn-block" style="text-align:center; margin-top:10px; display:block;">Go to Login Page</a>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <div style="position: relative;">
                            <input type="password" name="password" id="signup_password" required style="width: 100%; padding-right: 45px; box-sizing: border-box;">
                            <button type="button" onclick="toggleSignupPassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; padding: 5px;">
                                <i class="fas fa-eye" id="signupToggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div style="position: relative;">
                            <input type="password" name="confirm_password" id="confirm_password" required style="width: 100%; padding-right: 45px; box-sizing: border-box;">
                            <button type="button" onclick="toggleConfirmPassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; padding: 5px;">
                                <i class="fas fa-eye" id="confirmToggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </button>
                </form>
            <?php endif; ?>
            
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: var(--text-muted);">
                Already have an account? <a href="index.php" style="color: var(--primary); text-decoration: none; font-weight: 600;">Sign In</a>
            </p>
        </div>
    </div>
    
    <script>
    function toggleSignupPassword() {
        const passwordInput = document.getElementById('signup_password');
        const toggleIcon = document.getElementById('signupToggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    function toggleConfirmPassword() {
        const passwordInput = document.getElementById('confirm_password');
        const toggleIcon = document.getElementById('confirmToggleIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>