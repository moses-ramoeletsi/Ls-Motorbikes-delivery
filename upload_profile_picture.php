<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/'; // Make sure this matches the actual folder name in your project
        $file_name = $user_id . '_' . basename($_FILES['profile_picture']['name']);
        $upload_path = $upload_dir . $file_name;
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
            $sql_update_profile_picture = "UPDATE users SET profile_picture = '$file_name' WHERE id = '$user_id'";
            if ($conn->query($sql_update_profile_picture) === TRUE) {
                echo "<p>Profile picture uploaded successfully.</p>";
            } else {
                echo "Error updating profile picture: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Please select a valid file.";
    }
}

$conn->close();
?>
