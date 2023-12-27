<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al
    $musteriAdi = $_POST['Musteriler_Adi'];
    $musteriSoyadi = $_POST['Musteriler_Soyadi'];
    $musteriTc = $_POST['Musteriler_Tc'];
    $musteriSifre = $_POST['Musteriler_Sifre'];
    $musteriAdres = $_POST['Musteriler_Adres'];
    $musteriEmail = $_POST['Musteriler_Email'];
    $musteriTelefon = $_POST['Musteriler_Telefon'];


    // SQL sorgusunu hazırla ve veritabanına ekle
    $sql = "INSERT INTO musteriler (Musteriler_Adi, Musteriler_Soyadi, Musteriler_Tc, Musteriler_Sifre, Musteriler_Adres, Musteriler_Email, Musteriler_Telefon)
            VALUES ('$musteriAdi', '$musteriSoyadi', '$musteriTc', '$musteriSifre', '$musteriAdres', '$musteriEmail', '$musteriTelefon')";

    if ($conn->query($sql) === TRUE) {
      header("Location: /PHP-Project/index.php");
      exit();
        } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
    }

    // Veritabanı bağlantısını kapat
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PHP-Project/css/style.css">
    <title>KayıtOl</title>
</head>
<body>
<?php include 'bootstrap.php'; ?>
<?php include 'database.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Singin</a>
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
              <li class="nav-item">
                <a class="nav-link" href="/PHP-Project/login.php">Giriş Yap</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/PHP-Project/singup.php">Kayıt Ol</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <div class="kutu">
      <div class="kayit">
      <div class="container mt-4">
        <h2 class="text-center">Kayıt Ol</h2>
        <form action="singup.php" method="post">
            <div class="mb-3">
                <input type="text" placeholder="İsim" class="form-control" id="adi" name="Musteriler_Adi" required>
            </div>
            <div class="mb-3">
                <input type="text" placeholder="Soyisim" class="form-control" id="soyadi" name="Musteriler_Soyadi" required>
            </div>
            <div class="mb-3">
                <input type="text" placeholder="TC Kimlik Numarası" class="form-control" id="tc" name="Musteriler_Tc" required>
            </div>
            <div class="mb-3">
                <input type="text" placeholder="Telefon(5** *** ** **)" class="form-control" id="telefon" name="Musteriler_Telefon" required>
            </div>
            <div class="mb-3">
                <input type="password" placeholder="Şifre" class="form-control" id="sifre" name="Musteriler_Sifre" required>
            </div>
            <div class="mb-3">
                <textarea class="form-control" placeholder="Adres" id="adres" name="Musteriler_Adres" required></textarea>
            </div>
            <div class="mb-3">
                <input type="email" placeholder="Email  " class="form-control" id="email" name="Musteriler_Email" required>
            </div>
            <div class="text-center">
            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
            </div>
            </div>
        </form>
    </div>
    </div>
    <div class="footer">
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