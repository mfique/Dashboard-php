<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    $username = $conn->real_escape_string($_POST["username"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $cv_file = $_FILES["cv"]["name"];
    $cv_tmp = $_FILES["cv"]["tmp_name"];
    $cv_path = "uploads/" . basename($cv_file);
    
    if (move_uploaded_file($cv_tmp, $cv_path)) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, cv_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $cv_path);

        if ($stmt->execute()) {
            echo "Signup successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to upload CV.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="file" name="cv" required>
    <button type="submit" name="signup">Sign Up</button>
</form>
