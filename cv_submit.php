<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "group_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['cv']['name']);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a actual PDF
    if ($fileType != "pdf") {
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        $msg = "Sorry, only PDF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $target_file)) {
            $sql = "INSERT INTO cvs (name, email, role, cv_file) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $role, $target_file);

            if (mysqli_stmt_execute($stmt)) {
                $msg = "Your CV has been submitted successfully!";
                header("Location: index.php");
                exit();
            } else {
                $msg = "Sorry, there was an error submitting your CV.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $msg = "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Submission Result</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert alert-info mt-5">
                    <?php echo $msg; ?>
                </div>
                <a href="index.php" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
