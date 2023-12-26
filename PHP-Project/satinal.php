<?php
include 'database.php';
include 'bootstrap.php';
session_start();

// Kullanıcı giriş yapmamışsa, giriş yapma sayfasına yönlendir
if (!isset($_SESSION['Musteri_id'])) {
    header("Location: login.php");
    exit();
}

// Kullanıcı giriş yapmışsa
if (isset($_SESSION['Musteri_id'])) {
    $welcomeMessage = "Hoşgeldiniz, " . $_SESSION['Musteri_id'];
    $logoutLink = "<a class='nav-link' href='/PHP-Project/logout.php'>Çıkış Yap</a>";
    $loginLink = ""; // Giriş yap linkini görünmez yap
    $signupLink = ""; // Kayıt ol linkini görünmez yap
} else {
    $welcomeMessage = "";
    $loginLink = "<a class='nav-link' aria-current='page' href='/PHP-Project/login.php'>Giriş Yap</a>";
    $signupLink = "<a class='nav-link' href='/PHP-Project/singup.php'>Kayıt Ol</a>";
    $logoutLink = ""; // Çıkış yap linkini görünmez yap
}

// Ürün ID'sini al
if (isset($_GET['id'])) {
  $urunID = $_GET['id'];

  // Veritabanında bu ürünü kontrol et
  $urunQuery = "SELECT * FROM urunler WHERE Urunler_id = $urunID";
  $urunResult = $conn->query($urunQuery);

  if ($urunResult->num_rows > 0) {
      $urun = $urunResult->fetch_assoc();
      $urunAdi = $urun['Urunler_Adi'];
      $urunFiyat = $urun['Urunler_Fiyat'];
  } else {
      echo 'Ürün bulunamadı.';
      exit();
  }
} else {
  echo 'Ürün ID belirtilmedi.';
  exit();
}

// Satın alma işlemleri
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ödeme bilgilerini al
  $kartNumarasi = $_POST['kart_numarasi'];
  $sonKullanmaTarihi = $_POST['son_kullanma_tarihi'];
  $cvv = $_POST['cvv'];

  // Ödeme işlemleri burada gerçekleştirilmelidir, ancak güvenlik nedeniyle bir ödeme işleme sağlayıcı kullanmalısınız

  $mesaj = "Satın alma işlemi başarıyla gerçekleştirildi. Teşekkür ederiz!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PHP-Project/css/style.css">
    <title>SatinAl</title>
</head>
<style>
        .footer{

top : 400px;

}
</style>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="/PHP-Project/index.php">Urunler</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link" href="/PHP-Project/index.php">Ana Sayfa</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/PHP-Project/urunler.php">Ürünler</a>
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

      <div class="container mt-5 satinal-container">
    <h2>Satın Al</h2>
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $urun['Resim_URL']; ?>" alt="<?php echo $urunAdi; ?>" style="max-width: 100%;">
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <p><strong>Ürün Adı:</strong> <?php echo $urunAdi; ?></p>
                <p class="fiyat"><strong>Fiyat:</strong> <?php echo $urunFiyat; ?> TL</p>
            </div>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="kart_numarasi" class="form-label">Kart Numarası</label>
                    <input type="text" class="form-control" id="kart_numarasi" name="kart_numarasi" pattern="\d{16}" placeholder="1234 5678 9012 3456" title="Kart numarası 16 haneli olmalıdır." required>
                </div>
                <div class="mb-3">
                    <label for="son_kullanma_tarihi" class="form-label">Son Kullanma Tarihi</label>
                    <input type="text" class="form-control" id="son_kullanma_tarihi" placeholder="yyyy-aa-dd" name="son_kullanma_tarihi" required>
                </div>
                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" pattern="\d{3}" required>
                </div>
                <form method="post" action="satinal.php?id=<?php echo $urunID; ?>">
            </form>
            <?php
            if (isset($mesaj)) {
                echo '<div class="alert alert-success mt-3" role="alert">' . $mesaj . '</div>';
            }
            ?>
            </div>
        </div>
    
    </div>
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