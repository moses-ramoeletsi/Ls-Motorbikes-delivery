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

    // Display profile picture or avatar with JavaScript toggle
    echo '<div id="profile-container" onclick="toggleForm()">';
    if (!empty($user['profile_picture'])) {
        $profile_picture_path = 'uploads/' . $user['profile_picture'];
        echo '<img id="profile-picture" src="' . $profile_picture_path . '" alt="Profile Picture" style="max-width: 100px; max-height: 100px;">';
    } else {
        // Display avatar and provide the option to update the profile picture
        $avatar_path = 'path_to_default_avatar.jpg'; // Replace with the path to your default avatar image
        echo '<img id="avatar" src="' . $avatar_path . '" alt="Avatar" style="max-width: 150px; max-height: 150px;">';
    }
    echo '</div>';
    echo '<a href="order_history.php">Order History</a> <br>';

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
    <link rel="stylesheet" href="style.css">
    <style>
        #profile-form {
            display: none;
        }
    </style>
    <script>
        function toggleForm() {
            var form = document.getElementById('profile-form');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
        }

        function updateDeliveryStatus(deliveryTime) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'background_process.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                    // Check if the response contains 'Active' and show a notification
                    if (xhr.responseText.includes('Active')) {
                        showNotification('Delivery is now Active!');
                    }
                }
            };
            xhr.send('delivery_time=' + encodeURIComponent(deliveryTime));
        }

        function showNotification(message) {
            // Implement your notification logic here (e.g., using a library or custom code)
            alert(message);
        }

        // Periodically check the delivery status (every 10 seconds, adjust as needed)
        setInterval(function () {
            // Get the delivery time from the last stored delivery (you might need to modify this logic)
            var lastDeliveryTime = document.getElementById('last-delivery-time').innerText;
            updateDeliveryStatus(lastDeliveryTime);
        }, 10000);  // Check every 10 seconds
    </script>
</head>
<body>
    <div id="profile-form">
        <h2>Profile Picture</h2>
        <form action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
            <label for="profile_picture">Upload Profile Picture:</label>
            <input type="file" name="profile_picture" accept="image/*">
            <input type="submit" value="Upload">
        </form>
    </div>

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

            $delivery_time = date('Y-m-d H:i:s');
            $delivery_status = date('status');

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
                echo "Time: " . $delivery_time . "<br>";

                $sql_insert_delivery = "INSERT INTO delivery_info (user_id, bike_id, delivery_category, delivery_location,delivery_time,delivery_status)
                 VALUES ('$user_id', '$selected_bike_id', '$delivery_category','$delivery_location','$delivery_time','Pending')";
                if ($conn->query($sql_insert_delivery) === TRUE) {
                    echo "<p>Delivery information has been stored in the database.</p>";
                    echo '<span id="last-delivery-time" style="display:none;">' . $delivery_time . '</span>';
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
                        echo '</select>' . "<br>";
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
$conn->close();
?>

