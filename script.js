function editDelivery(deliveryId) {
    var modal = document.getElementById("editModal");
    var selectCategory = document.getElementById("selectCategory");

    modal.style.display = "block";

    document.getElementById("editForm").onsubmit = function (event) {
        event.preventDefault();

        var newCategory = selectCategory.value;

        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                modal.style.display = "none";

                location.reload();
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
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
}

function deleteDelivery(deliveryId) {
    var confirmDelete = confirm("Are you sure you want to delete this delivery?");
    
    if (confirmDelete) {
        var xmlhttp = new XMLHttpRequest();
        
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                location.reload(); 
            }
        };
        
        xmlhttp.open("GET", "delete_delivery.php?delivery_id=" + deliveryId, true);
        xmlhttp.send();
    }
}
