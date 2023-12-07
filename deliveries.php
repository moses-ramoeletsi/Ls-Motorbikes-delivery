<?php
session_start();
include 'connection.php';

// Check if the modal should be hidden
$hideEditModal = isset($_SESSION['hide_edit_modal']) ? $_SESSION['hide_edit_modal'] : false;

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

    echo '<a href="signout.php">Sign Out</a> .<br>';
    echo '<a href="dashboard.php">Back</a>';
} else {
    echo "User not found!";
}

// Set the session variable to hide the edit modal
$_SESSION['hide_edit_modal'] = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Deliveries</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
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
                echo '<div class="card" id="deliveryCard_' . $delivery['id'] . '">';
                echo "<h3>Delivery ID: " . $delivery['id'] . "</h3>";
                echo "<p>Bike ID: " . $delivery['bike_id'] . "</p>";
                echo "<p>Delivery Category: " . $delivery['delivery_category'] . "</p>";
                echo "<p>Delivery Location: " . $delivery['delivery_location'] . "</p>";

                echo '<button onclick="editDelivery(' . $delivery['id'] . ')">Edit</button>';                
                echo '<button onclick="deleteDelivery(' . $delivery['id'] . ')">Delete</button>';
                echo "</div>";
            }
        } else {
            echo "<p>No deliveries found.</p>";
        }
        ?>
    </div>

    <div id="editModal" class="modal" <?php echo $hideEditModal ? 'style="display:none;"' : ''; ?>>
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            <h2>Edit Delivery Category</h2>
            <form id="editForm">
                <label for="selectCategory">Choose a category:</label>
                <select id="selectCategory" name="category">
                    <option value="food_delivery">Food Delivery</option>
                    <option value="grocery_delivery">Grocery Delivery</option>
                    <option value="package_delivery">Package Delivery</option>
                    <!-- You can add more options as needed -->
                </select>
                <br>
                <p>Or enter a custom category:</p>
                <input type="text" id="customCategory" name="customCategory" placeholder="Custom Category">
                <br>
                <input type="submit" value="Save">
            </form>
        </div>
    </div>

    <!-- Script to hide the edit modal on page reload -->
    <script>
        if (performance.navigation.type === 1) {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
