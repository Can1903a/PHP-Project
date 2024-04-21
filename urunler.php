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

$randomCategoryQuery = "SELECT * FROM kategoriler ORDER BY RAND() LIMIT 1";
$randomCategoryResult = $conn->query($randomCategoryQuery);
$selectedCategory = $randomCategoryResult->fetch_assoc();

$productsPerPage = 12;

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

// Filtrelerden gelen değerleri al
$filterOptions = isset($_POST['options']) ? $_POST['options'] : array();

// Filtreleme sorgusunu oluştur
$filterQuery = "";
if (!empty($filterOptions)) {
    $filterQuery .= " AND urunler.Urunler_id IN (SELECT Urunler_id FROM ozellikdegerler WHERE ";
    foreach ($filterOptions as $option) {
        $filterQuery .= "Ozellik_Deger = '$option' OR ";
    }
    $filterQuery = rtrim($filterQuery, "OR ");
    $filterQuery .= " GROUP BY Urunler_id HAVING COUNT(*) = " . count($filterOptions) . ")";
}

// Ürünleri veritabanından çek
$productQuery = "SELECT * FROM urunler";
// Eğer bir kategori seçilmişse, sadece o kategoriye ait ürünleri çek
if ($selectedCategoryID != 0) {
    $productQuery .= " WHERE Kategori_id = $selectedCategoryID";
    $currentPage = 1;
}
$productQuery .= $filterQuery . " LIMIT $startIndex, $productsPerPage";
$productResult = $conn->query($productQuery);
// Filtreleme sorgusunu oluştur
$filterQuery = "";
if (!empty($filterOptions)) {
    $filterQuery .= " AND urunler.Urunler_id IN (SELECT Urun_id FROM UrunDegerleri WHERE ";
    foreach ($filterOptions as $option) {
        $filterQuery .= "Deger_id = '$option' OR ";
    }
    $filterQuery = rtrim($filterQuery, "OR ");
    $filterQuery .= " GROUP BY Urunler_id HAVING COUNT(*) = " . count($filterOptions) . ")";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/PHP-Project/css/style.css">
    <link rel="stylesheet" href="/PHP-Project/css/search.css">
    <link rel="stylesheet" href="/PHP-Project/css/filtre.css">
    <title>Ürünler</title>
    <style>
        a {
            text-decoration: none;
        }
        .clearfix {
    clear: both;
}
.footer {
    position: relative;
    clear: both;
    background-color: #343a40;
    color: white;
    text-align: center;
    padding: 10px 0;
    margin-top: 20px; /* Filtre bölümünden 20 piksel aşağıda başlamasını sağlar */
}
 .navbar {
     position: unset;
 }
 .filter-container {
    overflow-y: auto;
    max-height: calc(100vh - 200px); /* Önerilen maksimum yükseklik */
}


    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/PHP-Project/index.php">Ürünler</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="container-searchbox">
            <form class="d-flex" action="/PHP-Project/urunler.php" method="GET">
                <input
                    type="text"
                    id="search"
                    placeholder="Search..."
                    aria-label="Search"
                    name="q"/>
                <button class="btn btn-outline-light" type="submit" id="searchButton">Ara</button>
                <!-- Suggestions will be displayed in the following div. -->
                <div id="display"></div>
            </form>
        </div>
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

<div class="filter-wrapper">
    <div class="filter-container">
    <h4>Filtreler</h4>
        <form action="" method="post">
            <div id="accordion">
            <?php
                    // Kategoriye ait özellikleri veritabanından çek
                    if (isset($_GET['category'])) {
                        $selectedCategoryID = $_GET['category'];

                        $propertyQuery = "SELECT ozellikler.Ozellik_id, ozellikler.Ozellik_Adi
                                        FROM ozellikler
                                        INNER JOIN ozellikkategori ON ozellikler.Ozellik_id = ozellikkategori.Ozellik_id
                                        WHERE ozellikkategori.Kategori_id = $selectedCategoryID";
                        $propertyResult = $conn->query($propertyQuery);

                        if ($propertyResult->num_rows > 0) {
                            while ($property = $propertyResult->fetch_assoc()) {
                                echo '<div class="card">';
                                echo '<div class="card-header">' . $property['Ozellik_Adi'] . '</div>';
                                echo '<div class="card-body">';
                                // Özellik değerlerini checkbox olarak listeleme
                                $optionQuery = "SELECT * FROM ozellikdegerler WHERE Ozellik_id = " . $property['Ozellik_id'];
                                $optionResult = $conn->query($optionQuery);
                                while ($option = $optionResult->fetch_assoc()) {
                                    echo '<div class="form-check">';
                                    echo '<input class="form-check-input" type="checkbox" name="options[]" value="' . $option['Deger_id'] . '" id="' . $property['Ozellik_id'] . '_' . $option['Deger_id'] . '">';
                                    echo '<label class="form-check-label" for="' . $property['Ozellik_id'] . '_' . $option['Deger_id'] . '">' . $option['Ozellik_deger'] . '</label>';
                                    echo '</div>';
                                }
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Bu kategoriye ait özellik bulunamadı.</p>';
                        }
                    }
                ?>

            </div>
            <button type="submit" class="btn btn-primary mt-3">Filtrele</button>
        </form>
    </div>
    </div>
        <!-- Orta kısımda ürünler -->
        <div class="urunlist">
        <div class="col-lg-11">
            <div class="row">
                <!-- Ürünler -->
                <div class="col-md-12">
                    <!-- Ürün kartları burada listelenecek -->
                    <div class="row">
                        <?php
                        while ($product = $productResult->fetch_assoc()) {
                            // Her ürün için orijinal fiyatı ve indirim oranını tanımla
                            $originalPrice = $product['Urunler_Fiyat'];
                            $discountRate = $product['IndirimOrani'];

                            echo '<div class="col-md-3 mb-3">';
                            echo '<div class="card">';
                            echo '<a href="/PHP-Project/urun-detay.php?id=' . $product['Urunler_id'] . '">';

                            $imageData = $product['Resim_BLOB'];
                            $imageSrc = 'data:image/jpeg;base64,' . base64_encode($imageData);

                            echo '<img class="card-img-top" src="' . $imageSrc . '" alt="' . $product['Urunler_Adi'] . '" style="max-width: 400px; max-height: 400px;">';
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
            </div>
        </div>
    </div>

<div class="pagination-wrapper">
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
    <div class="clearfix"></div> <!-- Buraya ekleyin -->
    </div>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>
</body>
</html>
