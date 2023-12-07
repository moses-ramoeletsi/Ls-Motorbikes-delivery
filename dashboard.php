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
    <title>Dashboard</title>
</head>
<body>

    <h2>Bike Search</h2>

    <form action="" method="post">
        <label for="bike_model">Bike Model:</label>
        <input type="text" name="bike_model" value="<?php echo isset($_POST['bike_model']) ? $_POST['bike_model'] : ''; ?>">
        <input type="submit" value="Search">
    </form>
    <h3><a href="deliveries.php">My Order</a></h3>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['selected_bike_id'])) {
            $selected_bike_id = $_POST['selected_bike_id'];
            $delivery_category = $_POST['delivery_category'];
            $delivery_location = $_POST['delivery_location'];

            $sql_selected_bike = "SELECT * FROM bikes WHERE bike_Id = '$selected_bike_id'";
            $result_selected_bike = $conn->query($sql_selected_bike);

            if ($result_selected_bike->num_rows > 0) {
                $selected_bike = $result_selected_bike->fetch_assoc();
                echo "<h3>Selected Bike Information:</h3>";
                echo "Bike ID: " . $selected_bike['bike_Id'] . "<br>";
                echo "Bike Model: " . $selected_bike['model'] . "<br>";
                echo "Availability: " . ($selected_bike['available'] ? 'Available' : 'Not Available') . "<br>";
                echo "Delivery Category: " . $delivery_category . "<br>";
                echo "Delivery Location: " . $delivery_location . "<br>";

                // Insert data into the delivery_info table
                $sql_insert_delivery = "INSERT INTO delivery_info (user_id, bike_id, delivery_category, delivery_location) VALUES ('$user_id', '$selected_bike_id', '$delivery_category','$delivery_location')";
                if ($conn->query($sql_insert_delivery) === TRUE) {
                    echo "<p>Delivery information has been stored in the database.</p>";
                } else {
                    echo "Error inserting delivery information: " . $conn->error;
                }
            } else {
                echo "<p>Selected bike not found.</p>";
            }
        } else {
            $bike_model = isset($_POST['bike_model']) ? $_POST['bike_model'] : '';
            $sql_search_bike = "SELECT * FROM bikes WHERE model LIKE '%$bike_model%'";
            $result_search_bike = $conn->query($sql_search_bike);

            if ($result_search_bike->num_rows > 0) {
                echo "<h3>Search Results:</h3>";
                while ($bike = $result_search_bike->fetch_assoc()) {
                    echo "Bike ID: " . $bike['bike_Id'] . "<br>";
                    echo "Bike Model: " . $bike['model'] . "<br>";
                    echo "Availability: " . ($bike['available'] ? 'Available' : 'Not Available') . "<br>";

                    if ($bike['available']) {
                        echo '<form action="" method="post">';
                        echo '<input type="hidden" name="selected_bike_id" value="' . $bike['bike_Id'] . '">';
                        echo '<label for="delivery_category">Delivery Category:</label>';
                        echo '<select name="delivery_category">';
                        echo '<option value="food_delivery">Food Delivery</option>';
                        echo '<option value="grocery_delivery">Grocery Delivery</option>';
                        echo '<option value="package_delivery">Package Delivery</option>';
                        echo '</select>' . "<br>";
                        echo '<label for="delivery_location">Delivery Location:</label>';
                        echo '<select name="delivery_location">';
                        echo '<option value="Abia">Abia</option>';
                        echo '<option value="Likalaneng">Likalaneng</option>';
                        echo '<option value="Lilala">Lilala</option>';
                        echo '<option value="Lithabaneng">Lithabaneng</option>';
                        echo '</select>'. "<br>";
                        echo '<input type="submit" value="Confirm Delivery">';
                        echo '</form>';
                    }

                    echo "<hr>";
                }
            } else {
                echo "<p>No bikes found.</p>";
            }
        }
    }
    ?>

</body>
</html>

<?php

// Close the database connection
$conn->close();

?>
