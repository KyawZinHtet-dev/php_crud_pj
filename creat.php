<?php
require './config.php';

session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    echo "
        <script>
        alert('Register and Login first!');
        window.location.href = 'login.php';
        </script>";
}

$titleStatus = null;
$descStatus = null;
$dtStatus = null;
$fileStatus = null;

$titleErr = null;
$descErr = null;
$fileErr = null;

if (isset($_POST['submitBtn'])) {
    $title = null;
    $description = null;
    $dateTime = null;
    $image = null;

    $errMsg = "<small style='color:red'>Fill this field</small>";
    $fileErrMsg = "<small style='color:red'>Chose a image</small>";

    if ($_POST['title'] == '') {
        $titleErr = $errMsg;
    } else {
        $title = $_POST['title'];
        $titleStatus = true;
    }

    if ($_POST['description'] == '') {
        $descErr = $errMsg;
    } else {
        $description = $_POST['description'];
        $descStatus = true;
    }

    if ($_POST['dateTime'] == '') {
        $dtStatus = false;
    } else {
        $dateTime = $_POST['dateTime'];
        $dtStatus = true;
    }

    if ($_FILES['image']['size'] == 0) {
        $fileErr = $fileErrMsg;
    } else {

        $fileName = $_FILES['image']['name'];

        $savePath = './images/' . $fileName;
        $tempPath = $_FILES['image']['tmp_name'];

        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png') {
            $image = $_FILES['image']['name'];
            $fileStatus = true;
        } else {
            $fileErr = "<small style='color:red'>File type is not support!</small>";
            $fileStatus = false;
        }
    }

    if ($titleStatus && $descStatus && $dtStatus && $fileStatus) {
        $sql = "INSERT INTO posts(title, description, image, created_at) VALUES (:title , :description, :image, :dateTime)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':dateTime', $dateTime);
        $stmt->bindValue(':image', $image);
        $stmt->execute();

        $imageUpload = move_uploaded_file($tempPath, $savePath);

        echo ("<script>alert('Post Creating Success!');</script>");
        echo ("
        <script>
            window.location.href = './index.php';
        </script>
        ");
    }

    if ($titleStatus && $descStatus && $fileStatus && !($dtStatus)) {
        $sql = "INSERT INTO posts(title, description, image) VALUES (:title , :description, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':image', $image);
        $stmt->execute();

        $imageUpload = move_uploaded_file($tempPath, $savePath);

        echo "<script>alert('Post Creating Success!');</script>";
        echo ("
        <script>
            window.location.href = './index.php';
        </script>
        ");
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
    <title>Creat Page</title>
</head>

<body>
    <div class="card">
        <div class="card-body">
                <h2>Creat Post</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group mt-1">
                    <label for="Title" class=" form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="">
                    <?php echo $titleErr ?>
                </div>
                <div class="form-group mt-1">
                    <label for="description" class=" form-label">Description</label>
                    <textarea name="description" id="description" cols="30" rows="10" class=" form-control"></textarea>
                    <?php echo $descErr ?>
                </div>
                <div class="form-group mt-1">
                    <label for="image" class=" form-label">Image</label>
                    <input type="file" name="image" id="image" class=" form-control">
                    <?php echo $fileErr; ?>
                </div>
                <div class="form-group mt-1">
                    <label for="dateTime" class=" form-label">Date and Time</label>
                    <input type="date" name="dateTime" id="dateTime" class="form-control">
                </div>
                <div class="form-group">
                    <a href="./index.php" class="btn btn-sm btn-warning mt-3">&lt;Back </a>
                    <input type="submit" class="btn btn-sm btn-primary mt-3 float-end" value="Submit" name="submitBtn">
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>