<?php
require './config.php';

$usernameErr = null;
$emailErr = null;
$passwordErr = null;
$successMsg = 'Already login?';

$usernameStatus = null;
$emailStatus = null;
$passwordStatus = null;

if (isset($_POST['submitBtn'])) {
    $username = null;
    $email = null;
    $password;
    $errMsg = "<small style='color:red'>Fill this field</small>";
    if ($_POST['username'] == '') {
        $usernameErr = $errMsg;
    } else {
        $username = $_POST['username'];
        $usernameStatus = true;
    }

    if ($_POST['email'] == '') {
        $emailErr = $errMsg;
    } else {
        $email = $_POST['email'];
        $emailStatus = true;
    }

    if ($_POST['password'] == '') {
        $passwordErr = $errMsg;
    } else {
        $password = $_POST['password'];
        $passwordStatus = true;
    }

    if ($usernameStatus && $emailStatus && $passwordStatus) {

        $sql = "SELECT COUNT(email) AS num FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':email', $email);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['num'] > 0) {
            echo "<script>alert('Email Already exists!');</script>";
        } else {
            $hashPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users(name, email, password) VALUES (:name, :email, :password)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':name', $username);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password', $hashPassword);

            $result = $stmt->execute();

            if ($result) {
                $successMsg = "Registration Success! Please";
            }
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
    <title>Register Form</title>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <h2>Register</h2>
            <form class="" action="register.php" method="POST">
                <div class="form-group">
                    <label for="username">Name</label>
                    <input type="text" name="username" id="username" class="form-control" value="">
                    <?php echo $usernameErr ?>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="">
                    <?php echo $emailErr ?>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" value="">
                    <?php echo $passwordErr ?>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-sm btn-primary mt-2" value="Submit" class=" form-control" name="submitBtn">
                </div>
                <sm class=" d-inline-block mt-1"><?php echo $successMsg ?></sm>
                <a href="./login.php" class="text-decoration-none">Login</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>