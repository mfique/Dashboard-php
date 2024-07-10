<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cat_php";

$conn = new mysqli($servername, $username, $password, $dbname);

// Signup
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $cv_file = $_FILES["cv"]["name"];
    $cv_tmp = $_FILES["cv"]["tmp_name"];
    $cv_path = "uploads/" . $cv_file;
    move_uploaded_file($cv_tmp, $cv_path);

    $sql = "INSERT INTO users (username, email, password, cv_path) VALUES ('$username', '$email', '$password', '$cv_path')";
    $conn->query($sql);
}

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            session_start();
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
        }
    }
}

// Logout
if (isset($_GET["logout"])) {
    session_start();
    session_destroy();
    header("Location: index.php");
    exit;
}

// CRUD operations
// (implementation left as an exercise for the reader)

// Download CV
if (isset($_GET["download_cv"])) {
    $user_id = $_GET["download_cv"];
    $sql = "SELECT cv_path FROM users WHERE id = '$user_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cv_path = $row["cv_path"];
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=" . basename($cv_path));
        readfile($cv_path);
        exit;
    }
}
?>
