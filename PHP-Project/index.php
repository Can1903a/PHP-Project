<!-- PHP KODLARI -->
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
        
    }

    $logoutLink = "<a class='nav-link' href='/PHP-Project/logout.php'>Çıkış Yap</a>";
    $loginLink = ""; // Giriş yap linkini görünmez yap
    $signupLink = ""; // Kayıt ol linkini görünmez yap
}

// Kategorileri çekme
$categoryQuery = "SELECT * FROM kategoriler";
$categoryResult = $conn->query($categoryQuery);


$ustKategorilerQuery = "SELECT * FROM ustkategori";
$ustKategorilerResult = $conn->query($ustKategorilerQuery);

// Rastgele bir kategori seçme
$randomCategoryQuery = "SELECT * FROM kategoriler ORDER BY RAND() LIMIT 1";
$randomCategoryResult = $conn->query($randomCategoryQuery);
$selectedCategory = $randomCategoryResult->fetch_assoc();

// Seçilen kategoriye ait ürünleri çekme
$selectedCategoryID = $selectedCategory['Kategori_id'];
$selectedCategoryProductsQuery = "SELECT * FROM urunler WHERE Kategori_id = $selectedCategoryID ORDER BY RAND()";
$selectedCategoryProductsResult = $conn->query($selectedCategoryProductsQuery);


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
    <style>
        a {
            text-decoration: none;
        }
        .footer{
            position: static;
        }
        
    </style>
    
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
                <?php echo $loginLink; ?>
                <?php echo $signupLink; ?>
                <?php echo $welcomeMessage; ?>
                <?php echo $logoutLink; ?>
            </ul>
        </div>
    </div>
</nav>
<nav class="nav nav-altı-kategoriler">
    <?php
    while ($ustKategori = $ustKategorilerResult->fetch_assoc()) {
        echo '<div class="nav-item dropdown">';
        echo '<a class="nav-link nav-altı-kategoriler-item dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">' . $ustKategori['UstKategori_Adi'] . '</a>';
        echo '<div class="dropdown-menu">';

        // Bağlı olduğu kategorileri çekmek için sorgu yapılmalı
        $bagliKategoriQuery = "SELECT * FROM kategoriler WHERE UstKategori_id = " . $ustKategori['UstKategori_id'];
        $bagliKategoriResult = $conn->query($bagliKategoriQuery);

        while ($bagliKategori = $bagliKategoriResult->fetch_assoc()) {
            echo '<a class="dropdown-item" href="/PHP-Project/urunler.php?category=' . $bagliKategori['Kategori_id'] . '">' . $bagliKategori['Kategori_Adi'] . '</a>';

        }

        echo '</div>';
        echo '</div>';
    }
    ?>
</nav>
<!-- Swiper -->
<!-- Sabit alan içindeki slider container -->



<div class="tutturulmus-konum">
<!-- HTML kodu -->
<div class="swiper-container">
    <div class="swiper-wrapper">
        <?php
        $randomProductsQuery = "SELECT * FROM urunler ORDER BY RAND() LIMIT 5";
        $randomProductsResult = $conn->query($randomProductsQuery);

        while ($randomProduct = $randomProductsResult->fetch_assoc()) {
            echo '<div class="swiper-slide">';
            echo '<img src="' . $randomProduct['Resim_URL'] . '" alt="' . $randomProduct['Urunler_Adi'] . '">';
            echo '<div class="product-details">';
            echo '<h3>' . $randomProduct['Urunler_Adi'] . '</h3>';
            echo '<p class="price">₺' . number_format($randomProduct['Urunler_Fiyat'], 2) . '</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
    <!-- Ek kontroller -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>

</div>

<div class="anasayfa-kategori">
    <div class="kategori-wrapper">
        <div class="kategori-header">
            <h2><?= $selectedCategory['Kategori_Adi'] ?></h2>
            <div class="d-flex flex-wrap" id="productList">
                <?php
                $count = 0;

                // İlk satırda $discountRate ve $originalPrice tanımlanmış gibi görünüyor, ancak bu değerler her ürün için değişebilir
                // Bu nedenle, bu değerleri her bir ürün için almanız gerekecek
                while ($product = $selectedCategoryProductsResult->fetch_assoc()) {
                    $discountRate = $product['IndirimOrani'];
                    $originalPrice = $product['Urunler_Fiyat'];

                    echo '<div class="col-md-2">';
                    echo '<a href="/PHP-Project/urun-detay.php?id=' . $product['Urunler_id'] . '">';
                    echo '<img class="card-img-top" src="' . $product['Resim_URL'] . '" alt="' . $product['Urunler_Adi'] .'" style="max-width: 400px; max-height: 400px;">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $product['Urunler_Adi'] . '</h5>';
                    echo '<p class="card-text">' . $product['Urunler_Aciklama'] . '</p>';
                
                    if ($discountRate > 0) {
                        $discountedPrice = $originalPrice - ($originalPrice * $discountRate);
                        $discountPercentage = $discountRate * 100;
                        echo '<p class="card-text text-center"><span style="text-decoration: line-through;">₺' . number_format($originalPrice, 2) . '</span> <span style="font-weight: bold;">₺' . number_format($discountedPrice, 2) . '</span><br></p>';
                    } else {
                        echo '<p class="card-text text-center"><strong> ₺' . number_format($product['Urunler_Fiyat'], 2) . '</strong></p>';
                    }
                
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                    $count++;

                    if ($count % 6 == 0 && $count < $selectedCategoryProductsResult->num_rows) {
                        echo '<div class="w-100"></div>';
                        echo '<div class="col-md-12 text-center">';
                        echo '<form action="urunler.php" method="get">';
                        echo '<input type="hidden" name="category" value="' . $selectedCategory['Kategori_id'] . '">';
                        echo '<input type="hidden" name="start" value="' . $count . '">';
                        echo '<button type="submit" class="btn btn-primary mt-2">Daha Fazla Görüntüle</button>';
                        echo '</form>';
                        echo '</div>';
                        break;
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>



<div class="container-fluid bg-dark text-light mt-5">
    <div class="container">
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

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="/PHP-Project/js/SwiperSlider.js"></script>


</body>
</html>
