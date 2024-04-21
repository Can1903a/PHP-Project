<?php
include 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['urunID'])) {
        $urunID = $_POST['urunID'];
        $musteriID = $_SESSION['Musteri_id'];

        // Eğer ürün zaten sepette varsa, sadece miktarını artır
        $checkQuery = "SELECT * FROM sepet WHERE Musteriler_id = $musteriID AND Urunler_id = $urunID";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $updateQuery = "UPDATE sepet SET adet = adet + 1 WHERE Sepet_id = " . $row['Sepet_id'];
            $conn->query($updateQuery);
        } else {
            // Eğer ürün sepette yoksa, yeni bir sepet öğesi eklenir
            $insertQuery = "INSERT INTO sepet (Musteriler_id, Urunler_id, adet) VALUES ($musteriID, $urunID, 1)";
            $conn->query($insertQuery);
        }

        // Sepete ekleme veya güncelleme başarılı olduğunda sepet.php sayfasına yönlendir
        header("Location: sepet.php");
        exit();
    } else {
        $message = 'Ürün ID bilgisi alınamadı.';
    }
} else {
    $message = 'Geçersiz istek methodu.';
}

?>
<script>;
 alert("' . $message . '");;
</script>;
