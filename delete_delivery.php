<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delivery_id'])) {
    $deliveryId = $_GET['delivery_id'];

    $sql_delete = "DELETE FROM delivery_info WHERE id = '$deliveryId' AND user_id = '{$_SESSION['user_id']}'";
    $result_delete = $conn->query($sql_delete);

    if ($result_delete) {
        echo "Delivery deleted successfully!";
    } else {
        echo "Error deleting delivery: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}

// Close the database connection
$conn->close();
?>
