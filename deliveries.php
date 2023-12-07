<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE id = '$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();

    echo "User ID: " . $user['id'] . "<br>";
    echo "Username: " . $user['username'] . "<br>";
    echo "Email: " . $user['email'] . "<br>";

    echo '<a href="signout.php">Sign Out</a>';
} else {
    echo "User not found!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Deliveries</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Your Deliveries</h2>

    <div class="card-container">
        <?php
        // Display deliveries for the current user in cards
        $sql_user_deliveries = "SELECT * FROM delivery_info WHERE user_id = '$user_id'";
        $result_user_deliveries = $conn->query($sql_user_deliveries);

        if ($result_user_deliveries->num_rows > 0) {
            while ($delivery = $result_user_deliveries->fetch_assoc()) {
                echo '<div class="card">';
                echo "<h3>Delivery ID: " . $delivery['id'] . "</h3>";
                echo "<p>Bike ID: " . $delivery['bike_id'] . "</p>";
                echo "<p>Delivery Category: " . $delivery['delivery_category'] . "</p>";
                echo "<p>Delivery Location: " . $delivery['delivery_location'] . "</p>";
                // Add more details as needed
                echo "</div>";
            }
        } else {
            echo "<p>No deliveries found.</p>";
        }
        ?>
    </div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
