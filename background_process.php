<?php
include 'connection.php';

// Retrieve delivery time from the AJAX request
$delivery_time = $_POST['delivery_time'];

// Sleep for 30 seconds
sleep(30);

// Update the status to "Active"
$sql_update_status = "UPDATE delivery_info SET delivery_status = 'Active' WHERE delivery_time = '$delivery_time'";
if ($conn->query($sql_update_status) === TRUE) {
    echo "Delivery status has been set to 'Active' after 30 seconds.";
} else {
    echo "Error updating delivery status: " . $conn->error;
}

$conn->close();
?>
