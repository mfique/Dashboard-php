<?php
include 'db.php';

if (isset($_GET["download_cv"])) {
    $user_id = $conn->real_escape_string($_GET["download_cv"]);
    $stmt = $conn->prepare("SELECT cv_path FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cv_path = $row["cv_path"];
        if (file_exists($cv_path)) {
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=" . basename($cv_path));
            readfile($cv_path);
            exit();
        } else {
            echo "File not found.";
        }
    } else {
        echo "No CV found for this user.";
    }

    $stmt->close();
}
?>
