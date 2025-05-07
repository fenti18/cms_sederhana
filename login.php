<?php
session_start();
require_once 'config/database.php';
require_once 'config/functions.php';

if (isset($_POST['login'])) {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

// Handle breakthrough
if (isset($_POST['breakthrough'])) {
    $_SESSION['user_id'] = 1; // Set default user ID
    $_SESSION['username'] = 'Guest';
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMS Sederhana | Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <style>
        :root {
            --pastel-green: #98D8AA;
            --light-cream: #F7E1D7;
            --soft-green: #A8E6CF;
            --warm-cream: #FFD3B6;
            --dark-green: #4A8B6F;
        }

        body {
            background: linear-gradient(135deg, var(--pastel-green), var(--soft-green));
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Source Sans Pro', sans-serif;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--light-cream) 0%, transparent 70%);
            opacity: 0.3;
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .login-box {
            width: 360px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in-out;
            position: relative;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-logo {
            margin-bottom: 20px;
            text-align: center;
        }

        .login-logo a {
            color: var(--dark-green);
            font-size: 32px;
            text-decoration: none;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .login-card-body {
            padding: 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
        }

        .login-box-msg {
            text-align: center;
            color: var(--dark-green);
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-green);
            font-weight: 500;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 0;
            position: relative;
        }

        .form-control {
            height: 42px;
            border-radius: 0;
            border: none;
            border-bottom: 2px solid var(--pastel-green);
            padding: 8px 15px 8px 40px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: transparent;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--dark-green);
            box-shadow: none;
            outline: none;
        }

        .input-group-text {
            position: absolute;
            left: 0;
            top: 0;
            height: 42px;
            width: 40px;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-green);
            z-index: 1;
        }

        .input-group-text i {
            font-size: 14px;
            color: var(--dark-green);
        }

        .form-control::placeholder {
            color: #999;
            font-size: 14px;
        }

        .password-toggle {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--dark-green);
            cursor: pointer;
            padding: 0;
            z-index: 2;
        }

        .login-card-body {
            padding: 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
        }

        .btn-primary {
            background: var(--dark-green);
            border: none;
            height: 42px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background: #3a7a5f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 139, 111, 0.3);
        }

        .btn-breakthrough {
            background: var(--warm-cream);
            border: none;
            height: 42px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-top: 10px;
            color: var(--dark-green);
            border-radius: 10px;
        }

        .btn-breakthrough:hover {
            background: #ffc4a3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 211, 182, 0.3);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
        }

        .alert-danger {
            background: #ffebee;
            color: #c62828;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-text {
            background: linear-gradient(45deg, var(--dark-green), var(--pastel-green));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .register-link {
            text-align: center;
            margin-top: 15px;
            color: var(--dark-green);
        }

        .register-link a {
            color: var(--dark-green);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 15px 0;
            color: var(--dark-green);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid var(--pastel-green);
        }

        .divider span {
            padding: 0 10px;
            font-size: 14px;
            font-weight: 500;
        }

        /* Floating animation for background elements */
        .floating {
            position: absolute;
            width: 50px;
            height: 50px;
            background: var(--light-cream);
            border-radius: 50%;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
            100% {
                transform: translateY(0) rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <!-- Floating background elements -->
    <div class="floating" style="top: 20%; left: 20%;"></div>
    <div class="floating" style="top: 60%; left: 80%; animation-delay: -2s;"></div>
    <div class="floating" style="top: 80%; left: 30%; animation-delay: -4s;"></div>
    <div class="floating" style="top: 30%; left: 70%; animation-delay: -6s;"></div>

    <div class="login-box">
        <div class="login-logo">
            <a href="index.php"><span class="brand-text">CMS</span> Sederhana</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Welcome back! Please login to your account</p>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post" class="form-container">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-user"></i>
                            </div>
                            <input type="text" class="form-control" id="username" placeholder="Enter your username" name="username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="login" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                            </button>
                        </div>
                    </div>
                </form>
                <div class="divider">
                    <span>or</span>
                </div>
                <form action="" method="post">
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="breakthrough" class="btn btn-breakthrough btn-block">
                                <i class="fas fa-rocket mr-2"></i> Breakthrough to Dashboard
                            </button>
                        </div>
                    </div>
                </form>
                <div class="register-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </div>
            </div>
        </div>
    </div>
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.classList.remove('fa-eye');
                toggleButton.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleButton.classList.remove('fa-eye-slash');
                toggleButton.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html> 