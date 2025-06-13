<?php
    session_start();
    require "koneksi.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    
    <style>
        body {
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main {
            height: 100vh;
        }

        .login-box {
            width: 400px;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-box img.logo {
            width: 80px;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
        }

        .btn-login {
            background-color: #28a745;
            border: none;
            border-radius: 8px;
        }

        .btn-login:hover {
            background-color: #218838;
        }

        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="main d-flex flex-column justify-content-center align-items-center">
        <div class="login-box">
            <img src="image/logo.png" alt="Logo" class="logo">
            <h4 class="mb-4">Silahkan Login</h4>
            <form action="" method="post">
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <button class="btn btn-login text-white form-control" type="submit" name="loginbtn">Login</button>
            </form>

            <?php
                if(isset($_POST['loginbtn'])){
                    $username = htmlspecialchars($_POST['username']);
                    $password = htmlspecialchars($_POST['password']);
                    
                    $query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
                    $countdata = mysqli_num_rows($query);
                    $data = mysqli_fetch_array($query);
                    
                    if($countdata>0){
                        if ($password === $data['password']) {
                            $_SESSION['username'] = $data['username'];
                            $_SESSION['role'] = $data['role'];
                            $_SESSION['login'] = true;
                            
                            if($data['role'] == 'admin'){
                                header('location: adminpanel/index.php');
                            } else {
                                header('location: index2.php');
                            }
                            exit();
                        } else {
                            echo '<div class="alert alert-warning">Password salah</div>';
                        }
                    } else {
                        echo '<div class="alert alert-warning">Akun tidak tersedia</div>';
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>
