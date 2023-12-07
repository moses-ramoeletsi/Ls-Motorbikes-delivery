function editDelivery(deliveryId) {
    // Show a modal with a form to choose the new delivery category
    var modal = document.getElementById("editModal");
    var selectCategory = document.getElementById("selectCategory");

    // Set up the modal and display it
    modal.style.display = "block";

    // Handle the form submission
    document.getElementById("editForm").onsubmit = function (event) {
        event.preventDefault();

        // Get the selected category from the dropdown
        var newCategory = selectCategory.value;

        // Use AJAX to send a request to the server for editing
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // Close the modal
                modal.style.display = "none";

                // Reload the page or update the card container
                location.reload(); // You can also update the card container without reloading the entire page
            }
        };

        xmlhttp.open(
            "GET",
            "edit_delivery.php?delivery_id=" +
                deliveryId +
                "&new_category=" +
                encodeURIComponent(newCategory),
            true
        );
        xmlhttp.send();
    };

    // Close the modal if the user clicks outside of it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

function deleteDelivery(deliveryId) {
    var confirmDelete = confirm("Are you sure you want to delete this delivery?");
    
    if (confirmDelete) {
        // Use AJAX to send a request to the server for deletion
        var xmlhttp = new XMLHttpRequest();
        
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                // Reload the page or update the card container
                location.reload(); // You can also update the card container without reloading the entire page
            }
        };
        
        xmlhttp.open("GET", "delete_delivery.php?delivery_id=" + deliveryId, true);
        xmlhttp.send();
    }
}
