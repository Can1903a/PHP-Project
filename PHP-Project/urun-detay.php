<?php
include 'database.php';
include 'bootstrap.php';
session_start();

$welcomeMessage = "";
$logoutLink = "";
$loginLink = "<a class='nav-link' aria-current='page' href='/PHP-Project/login.php'>Giriş Yap</a>";
$signupLink = "<a class='nav-link' href='/PHP-Project/signup.php'>Kayıt Ol</a>";

// Kullanıcı giriş yapmışsa
if (isset($_SESSION['Musteri_id'])) {
  $musteriID = $_SESSION['Musteri_id'];

  // Müşteri bilgilerini çek
  $musteriQuery = "SELECT * FROM musteriler WHERE Musteriler_id = $musteriID";
  $musteriResult = $conn->query($musteriQuery);

  if ($musteriResult->num_rows > 0) {
      $musteri = $musteriResult->fetch_assoc();
      $isim = $musteri['Musteriler_Adi']; // "Musteriler_Adi" sütun adını kullanarak adı çekin
      $welcomeMessage = "<h1 id='hosgeldin' class='welcome-message'>Hoşgeldiniz, " . $isim . "</h1>";
      echo $welcomeMessage;
  }

  $logoutLink = "<a class='nav-link' href='/PHP-Project/logout.php'>Çıkış Yap</a>";
  $loginLink = ""; // Giriş yap linkini görünmez yap
  $signupLink = ""; // Kayıt ol linkini görünmez yap
}

$urunID = $_GET['id'];
$urunQuery = "SELECT * FROM urunler WHERE Urunler_id = $urunID";
$urunResult = $conn->query($urunQuery);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['urunID'])) {
  $urunID = $_POST['urunID'];
  $musteriID = $_SESSION['Musteri_id'];

  // Önceki ürünleri sil
  $silQuery = "DELETE FROM sepet WHERE Musteriler_id = $musteriID";
  $conn->query($silQuery);

  // Şimdi sadece belirtilen ürünü ekle
  $insertQuery = "INSERT INTO sepet (Musteriler_id, Urunler_id, adet) VALUES ($musteriID, $urunID, 1)";
  $conn->query($insertQuery);

  // Satın alma başarılı olduğunda satinal2.php sayfasına yönlendir
  header("Location: satinal2.php");
  exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PHP-Project/css/style.css">
    <title>Ürün Detay</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/PHP-Project/index.php">Ürünler</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/PHP-Project/index.php">Ana Sayfa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/PHP-Project/urunler.php">Ürünler</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/PHP-Project/sepet.php">Sepet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/PHP-Project/hakkında.php">Hakkında</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/PHP-Project/iletişim.php">İletişim</a>
                </li>
                <?php echo $loginLink; ?>
                <?php echo $signupLink; ?>
                <?php echo $welcomeMessage; ?>
                <?php echo $logoutLink; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="detay">
    <div class="detayrow">
        <?php

        if ($urunResult->num_rows > 0) {
            $urun = $urunResult->fetch_assoc();
            $urunAdi = $urun['Urunler_Adi'];
            $urunAciklama = $urun['Urunler_Aciklama'];
            $urunResimURL = $urun['Resim_URL'];
            $urunFiyat = $urun['Urunler_Fiyat'];

            // Sol tarafta görsel
            echo '<div class="detay-resim">';
            echo '<img src="' . $urunResimURL . '" alt="' . $urunAdi . '" style="max-width: 400px; max-height: 400px;">';
            echo '</div>';

            // Sağ tarafta metin
            echo '<div class="icerik">';
            echo '<h2>' . $urunAdi . '</h2>';
            echo '<p>' . $urunAciklama . '</p>';
            echo '<p><strong>Fiyat: TL' . $urunFiyat . '</strong></p>';

            // Satın Al butonu
            echo '<a href="satinal2.php?id=' . $urunID . '" class="buy-button">Satın Al</a>';

            // Sepete Ekle butonu
            echo '<form method="post" action="sepeteEkle.php">';
            echo '<input type="hidden" name="urunID" value="' . $urunID . '">';
            echo '<input type="submit" value="Sepete Ekle" class="buy-button">';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<p>Ürün bulunamadı.</p>';
        }
        ?>
    </div>
</div>

<script>
function addToCart(urunID) {
    // Ürünü sepete ekleme işlemleri
    fetch('sepeteEkle.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'urunID=' + urunID,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Sepete ekleme başarılı olduğunda sepet.php sayfasına yönlendirme
            window.location.href = '/PHP-Project/sepet.php';
        } else {
            alert('Ürün eklenirken bir hata oluştu.');
        }
    })
    .catch(error => console.error('Hata:', error));
}
</script>

<div class="footer">
    <!-- Footer container -->
    <div class="container-fluid bg-dark text-light">
        <div class="row py-4">
            <div class="col-md-6">
                <h5>İletişim Bilgileri</h5>
                <p>Adres: 123 Bootstrap Street, İstanbul</p>
                <p>Email: info@example.com</p>
                <p>Telefon: (555) 123-4567</p>
            </div>
            <div class="col-md-6">
                <h5>Hızlı Bağlantılar</h5>
                <ul class="list-unstyled">
                    <li><a href="#">Anasayfa</a></li>
                    <li><a href="#">Hakkımızda</a></li>
                    <li><a href="#">Hizmetlerimiz</a></li>
                    <li><a href="#">İletişim</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

</body>
</html>
