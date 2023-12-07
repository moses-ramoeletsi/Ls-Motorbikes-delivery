<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delivery_id']) && isset($_GET['new_category'])) {
    $deliveryId = $_GET['delivery_id'];
    $newCategory = $_GET['new_category'];

    $sql_update = "UPDATE delivery_info SET delivery_category = '$newCategory' WHERE id = '$deliveryId' AND user_id = '{$_SESSION['user_id']}'";
    $result_update = $conn->query($sql_update);

    if ($result_update) {
        echo "Delivery updated successfully!";
    } else {
        echo "Error updating delivery: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}

$conn->close();
?>
