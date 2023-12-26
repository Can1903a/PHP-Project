<?php
include 'database.php';
include 'bootstrap.php';
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

// Kategorileri çekme
$categoryQuery = "SELECT * FROM kategoriler";
$categoryResult = $conn->query($categoryQuery);

$ustKategorilerQuery = "SELECT * FROM ustkategori";
$ustKategorilerResult = $conn->query($ustKategorilerQuery);

$randomCategoryQuery = "SELECT * FROM kategoriler ORDER BY RAND() LIMIT 1";
$randomCategoryResult = $conn->query($randomCategoryQuery);
$selectedCategory = $randomCategoryResult->fetch_assoc();

$productsPerPage = 9;

// Şu anki sayfa numarasını al
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = intval($_GET['page']);
} else {
    $currentPage = 1;
}
if (isset($_GET['category'])) {
  $selectedCategoryID = $_GET['category'];
} else {
  $selectedCategoryID = 0;
}
// Başlangıç indeksi
$startIndex = ($currentPage - 1) * $productsPerPage;

// Ürünleri veritabanından çek
$productQuery = "SELECT * FROM urunler";
// Eğer bir kategori seçilmişse, sadece o kategoriye ait ürünleri çek
if ($selectedCategoryID != 0) {
  $productQuery .= " WHERE Kategori_id = $selectedCategoryID";
  $currentPage = 1;
}
$productQuery .= " LIMIT $startIndex, $productsPerPage";
$productResult = $conn->query($productQuery);

?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/PHP-Project/css/style.css">
    <title>Urunler</title>
    
    <style>
        a {
            text-decoration: none;
        }
    </style>
</head>
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
<div class="container mt-4">
    <div class="row">
        <!-- Sol tarafta kategoriler -->
        <div class="col-md-3">
            <h4>Kategoriler</h4>
            <ul class="list-group">
                <?php
                // Kategorileri veritabanından çek
                $categoryQuery = "SELECT * FROM kategoriler";
                $categoryResult = $conn->query($categoryQuery);

                while ($category = $categoryResult->fetch_assoc()) {
                    echo '<li class="list-group-item"><a href="?category=' . $category['Kategori_id'] . '&page=' . $currentPage . '">' . $category['Kategori_Adi'] . '</a></li>';
                }
                ?>
            </ul>
        </div>

        <!-- Orta kısımda ürünler -->
        <div class="col-md-9">
            <h4>Ürünler</h4>
            <div class="row">
                <?php
                while ($product = $productResult->fetch_assoc()) {
                    // Her ürün için orijinal fiyatı ve indirim oranını tanımla
                    $originalPrice = $product['Urunler_Fiyat'];
                    $discountRate = $product['IndirimOrani'];

                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<a href="/PHP-Project/urun-detay.php?id=' . $product['Urunler_id'] . '">';
                    echo '<img class="card-img-top" src="' . $product['Resim_URL'] . '" alt="' . $product['Urunler_Adi'] . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title text-center">' . $product['Urunler_Adi'] . '</h5>';
                    echo '<p class="card-text text-center">' . $product['Urunler_Aciklama'] . '</p>';

                    // Eğer indirim oranı sıfırdan büyükse (yüzde indirim varsa), hem orijinal fiyatı hem de indirimli fiyatı göster
                    if ($discountRate > 0) {
                        $discountedPrice = $originalPrice - ($originalPrice * $discountRate);
                        $discountPercentage = $discountRate * 100;
                        echo '<p class="card-text text-center"><span style="text-decoration: line-through;">₺' . number_format($originalPrice, 2) . '</span> <span style="font-weight: bold;">₺' . number_format($discountedPrice, 2) . '</span><br></p>';
                    } else {
                        // İndirim oranı sıfırsa veya negatifse, sadece orijinal fiyatı göster                  
                        echo '<p class="card-text text-center"><strong> ₺' . number_format($product['Urunler_Fiyat'], 2) . '</strong></p>';
                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Sayfalama -->
        <div class="text-center">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?category=<?php echo $selectedCategoryID; ?>&page=<?php echo $currentPage - 1; ?>" tabindex="-1">Previous</a>
                </li>
                <?php
                $totalPages = ceil($conn->query("SELECT COUNT(*) FROM urunler")->fetch_assoc()['COUNT(*)'] / $productsPerPage);

                // Sayfa numaralarını göster
                for ($i = max(1, $currentPage - 2); $i <= min($currentPage + 1, $totalPages); $i++) {
                    echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?category=' . $selectedCategoryID . '&page=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }
                ?>
                <li class="page-item <?php echo ($currentPage == $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?category=<?php echo $selectedCategoryID; ?>&page=<?php echo $currentPage + 1; ?>">Next</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Ayıran container -->
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
