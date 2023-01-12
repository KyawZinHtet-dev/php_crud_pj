<?php
session_start();

if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
    echo "
        <script>
        alert('Register and Login first!');
        window.location.href = 'login.php';
        </script>";
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
    <title>Blog Page</title>
</head>

<body>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-secondary">
                <div class=" mb-2">
                    <a href="./creat.php" class="btn btn-sm btn-success">Create Post</a>
                    <a href="./logout.php" class="btn btn-sm btn-danger float-end">Logout</a>
                </div>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="20%">Title</th>
                        <th width="30%">Description</th>
                        <th width="20%">Created_at</th>
                        <th width="20%">Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require './config.php';

                    $stmt = $pdo->prepare("SELECT * FROM posts");
                    $stmt->execute();
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($row as $data) {

                        $time = date('d/M/Y', strtotime($data['created_at']));

                        echo "
                        <tr>
                            <td>{$data['id']}</td>
                            <td>{$data['title']}</td>
                            <td>{$data['description']}</td>
                            <td>{$time}</td>
                            <td>
                                <a href='./edit.php?id={$data['id']}'>Edit</a>
                                <a href='./delete.php?id={$data['id']}'>Delete</a>
                            </td>
                        </tr>
                        ";
                    }


                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
    
</html>