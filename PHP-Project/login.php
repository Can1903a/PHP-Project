<?php
include 'database.php';
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al
    $musteriEmail = $_POST['Musteriler_Email'];
    $musteriSifre = $_POST['Musteriler_Sifre'];

    // Veritabanında bu kullanıcıyı kontrol et
    $sql = "SELECT * FROM musteriler WHERE Musteriler_Email = '$musteriEmail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $musteriid = $row['Musteriler_id'];

        // Şifre kontrolü
        if (password_verify($musteriSifre, $row['Musteriler_Sifre']) || $musteriEmail == $row['Musteriler_Email']) {
            // Giriş başarılı, oturumu başlat
            $_SESSION['Musteri_id'] = $row['Musteri_id'];
            $_SESSION['Musteri_id'] = $musteriid;
            header("Location: /PHP-Project/index.php");
            exit();
        } else {
            echo "Hatalı şifre.";
        }
    } else {
        echo "Hatalı email.";
    }
}
?>



  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="/PHP-Project/css/style.css">
      <title>Giriş</title>
  </head>
  <body>
  <?php include 'bootstrap.php'; ?>
 <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">E-Ticaret</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
        <div class="content">
        <div class="kutu-login">
        <div class="login">
        <div class="container mt-4">
          <h2 class="text-center">Giriş Yap</h2>
          <form action="login.php" method="post">
              <div class="mb-3">
                  <input type="email" placeholder="Email  " class="form-control" id="email" name="Musteriler_Email" required>
              </div>
              <div class="mb-3">
                  <input type="password" placeholder="Şifre" class="form-control" id="sifre" name="Musteriler_Sifre" required>
              </div>
              <div class="text-center">
              <button type="submit" class="btn btn-primary">Giriş Yap</button>
              </div>
              </div>
          </form>
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