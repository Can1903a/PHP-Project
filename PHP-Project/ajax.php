<?php
// Include the database configuration file.
include "database.php";
// Retrieve the value of the "search" variable from "script.js".
if (isset($_POST['search'])) {
   // Assign the search box value to the $Name variable.
   $arama = $_POST['search'];
   // Define the search query.
   $Query = "SELECT Urunler_Adi, Urunler_id FROM urunler WHERE Urunler_Adi LIKE '%$arama%' LIMIT 5";
   // Execute the query.
   $ExecQuery = MySQLi_query($conn, $Query);
   // Create an unordered list to display the results.
   echo '
<ul>
   ';
   // Fetch the results from the database.
   while ($Result = MySQLi_fetch_array($ExecQuery)) {
       ?>
   <!-- Create list items.
        Call the JavaScript function named "fill" found in "script.js".
        Pass the fetched result as a parameter. -->
    <li>
    <a href="urun-detay.php?id=<?php echo $Result['Urunler_id']; ?>">
            <?php echo $Result['Urunler_Adi']; ?>
        </a>
    </li>
   <!-- The following PHP code is only for closing parentheses. Do not be confused. -->
   <?php
}}
?>
</ul>