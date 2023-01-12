<?php
require './config.php';

$emailErr = null;
$passwordErr = null;
$emailStatus = null;
$passwordStatus = null;
session_start();

if (!empty($_POST)) {

    if ($_POST['email'] == '') {
        $emailErr = "<small style = 'color:red'>Email field empty! Fill email</small>";
    } else {
        $email = $_POST['email'];
        $emailStatus = true;
    }

    if ($_POST['password'] == '') {
        $passwordErr = "<small style = 'color:red'>Password field empty! Fill password</small>";
    } else {
        $password = $_POST['password'];
        $passwordStatus = true;
    }

    if ($emailStatus && $passwordStatus) {
        $sql = "SELECT * FROM users WHERE email = :email";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $validPassword = password_verify($password, $user['password']);
            if ($validPassword) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['logged_in'] = time();

                header('location:./index.php');
                exit();
            } else {
                $passwordErr = "<small style = 'color:red'>Incorret Password! Try again.</small>";
            }
        } else {
            $emailErr = "<small style = 'color:red'>Incorret Email or Email not register yet!</small>";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <h2>Login</h2>
            <form class=" form-group" method="POST">
                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class=" form-control">
                    <?php echo $emailErr ?>
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class=" form-control">
                    <?php echo $passwordErr ?>
                </div>
                <div>
                    <input type="submit" name="loginBtn" class="btn btn-sm btn-primary mt-2">
                </div>
            </form>
            <p class=" mt-2">Not register yet? <a href="./register.php">Register</a></p>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>