<?php
include 'database.php';
include 'bootstrap.php';
session_start();



$welcomeMessage = "";
$logoutLink = "";
$loginLink = "<a class='nav-link' aria-current='page' href='/PHP-Project/login.php'>Giriş Yap</a>";
$signupLink = "<a class='nav-link' href='/PHP-Project/singup.php'>Kayıt Ol</a>";

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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PHP-Project/css/style.css">
    <title>Hakkında</title>
</head>

<body>

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
                        <a class="nav-link active" aria-current="page" href="/PHP-Project/hakkında.php">Hakkında</a>
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

<div class="sabit">
    <div class="container my-5 hakkında-container">
        <div class="text-center">
            <h2>Hakkımızda</h2>
            <div>
                <p>XYZ E-Ticaret, 2010 yılında kurulmuş bir e-ticaret platformudur. Amacımız müşterilere en kaliteli
                    ürünleri uygun fiyatlarla sunmak ve harika bir alışveriş deneyimi sağlamaktır.</p>
                <p>Misyonumuz, müşterilere yüksek kaliteli ürünler sunmak ve onların beklentilerini aşmaktır. Vizyonumuz ise
                    müşteri memnuniyetini her şeyin önünde tutarak sürekli büyümektir.</p>
                <p>Değerlerimiz arasında müşteri odaklılık, kalite, dürüstlük ve sosyal sorumluluk bulunmaktadır. Ekibimiz,
                    bu değerlere bağlı kalarak sizlere en iyi hizmeti sunmaktan gurur duyar.</p>
                <p>XYZ E-Ticaret olarak, müşterilerimizin memnuniyeti bizim için en önemli önceliktir. Sizlere güvenli ve
                    keyifli bir alışveriş deneyimi yaşatmak için buradayız.</p>
            </div>
            
        </div>
        
        </div>
    </div>



    <div class="footer">

    <!-- Footer container -->
    <div class="container-fluid bg-dark text-light fixed-bottom">
        <div class="row py-1">
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