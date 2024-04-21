<?php
// Include the database configuration file.
include "database.php";

// Check if the "search" variable is set in the POST request.
if (isset($_POST['search'])) {
    // Assign the search box value to the $arama variable.
    $arama = $_POST['search'];

    // Define the search query.
    $Query = "SELECT Urunler_Adi, Urunler_id FROM urunler WHERE Urunler_Adi LIKE '%$arama%' LIMIT 5";

    // Execute the query.
    $ExecQuery = mysqli_query($conn, $Query);

    // Create an unordered list to display the results.
    echo '<ul>';
    
    // Fetch the results from the database.
    while ($Result = mysqli_fetch_array($ExecQuery)) {
        // Create list items and links to product details page.
        echo '<li><a href="urun-detay.php?id=' . $Result['Urunler_id'] . '">' . $Result['Urunler_Adi'] . '</a></li>';
    }

    echo '</ul>';
}
?>
