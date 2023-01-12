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

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = $id");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$title = $row['title'];
$desc = $row['description'];
$time = $row['created_at'];
$currentImage = $row['image'];

$fileErr = null;


if (isset($_POST['submitBtn'])) {
    $newTitle = $_POST['title'];
    $newDesc = $_POST['description'];
    $newDT = $_POST['dateTime'];

    if ($_FILES['image']['size'] == 0) {

        $sql = "UPDATE posts SET title = :title, description = :description, image = :currentImage, created_at = :dateTime WHERE id = $id ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $newTitle);
        $stmt->bindValue(':description', $newDesc);
        $stmt->bindValue(':dateTime', $newDT);
        $stmt->bindValue(':currentImage', $currentImage);
        $stmt->execute();

        echo "
        <script>
        alert('Post Update Success!');
        window.location.href = './index.php';
        </script>";
    } else {

        $fileName = $_FILES['image']['name'];

        $savePath = './images/' . $fileName;
        $tempPath = $_FILES['image']['tmp_name'];

        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        if ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'png') {

            $sql = "UPDATE posts SET title = :title, description = :description, image = :fileName, created_at = :dateTime WHERE id = $id ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $newTitle);
            $stmt->bindValue(':description', $newDesc);
            $stmt->bindValue(':dateTime', $newDT);
            $stmt->bindValue(':fileName', $fileName);
            $stmt->execute();

            $imageUpload = move_uploaded_file($tempPath, $savePath);

            echo "
            <script>
            alert('Post Update Success!');
            window.location.href = './index.php';
            </script>";
        } else {
            $fileErr = "<small style='color:red'>File type is not support!</small>";
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
    <title>Edit Page</title>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <h2>Edit Post</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="">
                    <label for="Title" class=" form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?php echo $title; ?>">
                </div>
                <div class="form-group">
                    <label for="description" class=" form-label">Description</label>
                    <textarea name="description" id="description" cols="30" rows="10" class=" form-control"> <?php echo $desc; ?> </textarea>
                </div>
                <div class="form-group mt-1">
                    <img src="./images/<?php echo $currentImage; ?>" alt="Image not found!" class=" w-25 h-25 mt-2 mb-2">
                    <label for="image" class=" form-label d-block">Image</label>
                    <input type="file" name="image" id="image" class=" form-control">
                    <?php echo $fileErr; ?>
                    <div class="form-group">
                        <label for="dateTime" class=" form-label">Date and Time</label>
                        <input type="date" value="<?php echo date('Y-m-d', strtotime($time)); ?>" name="dateTime" id="dateTime" class="form-control">
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