<!-- PHP KODLARI -->

<?php
include 'database.php';
 include 'bootsrap.php';
// Kategorileri çekme
$categoryQuery = "SELECT * FROM kategoriler";
$categoryResult = $conn->query($categoryQuery);

// Ürünleri çekme (örneğin, kategori id'si 1 olan ürünleri çekme)
$productQuery = "SELECT * FROM urunler WHERE Kategori_id = 1";
$productResult = $conn->query($productQuery);
?>

<!-- PHP KODLARI -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
  <link rel="stylesheet" href="/PHP-Project/css/style.css">
  <title>E-Ticaret Sitesi</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">E-Ticaret</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/PHP-Project/index.php">Ana Sayfa</a>
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
                <a class="nav-link" href="/PHP-Project/singin.php">Kayıt Ol</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- Swiper -->
    <!-- Sabit alan içindeki slider container -->
    <div class="tutturulmus-konum">
        <div class="slider-container">
            <!-- Swiper -->
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <!-- Slider içeriği -->
                    <div class="swiper-slide">Slide 1</div>
                    <div class="swiper-slide">Slide 2</div>
                    <div class="swiper-slide">Slide 3</div>
                    <!-- İhtiyacınıza göre daha fazla slide ekleyebilirsiniz -->
                </div>
                <!-- Navigasyon düğmeleri -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- Sayfa noktaları (pagination) -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
<!-- Swiper JS -->

<div class="content">
    <!-- Sayfa içeriği buraya gelecek -->
</div>

<!-- Footer -->
<footer class="bg-dark text-light text-center py-3">
    <p>&copy; 2023 E-Ticaret</p>
</footer>



<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="/PHP-Project/js/SwiperSlider.js"></script>
    </body>
</html>
