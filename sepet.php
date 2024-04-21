    <?php
    include 'database.php';
    include 'bootstrap.php';
    session_start();
    // Kullanıcı giriş yapmamışsa, giriş yapma sayfasına yönlendir
    if (!isset($_SESSION['Musteri_id'])) {
        header("Location: login.php");
        exit();
    }

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


    if (isset($_GET['sil'])) {
        $silinecekUrunID = $_GET['sil'];

        // Ürünü sepette bul ve sadece bir adetini sil
        $musteriID = $_SESSION['Musteri_id'];
        $checkQuery = "SELECT * FROM sepet WHERE Musteriler_id = $musteriID AND Urunler_id = $silinecekUrunID";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            $row = $checkResult->fetch_assoc();
            $urunAdet = $row['adet'];

            if ($urunAdet > 1) {
                // Eğer üründen birden fazla varsa, sadece bir adetini azalt
                $updateQuery = "UPDATE sepet SET adet = adet - 1 WHERE Sepet_id = " . $row['Sepet_id'];
                $conn->query($updateQuery);
            } else {
                // Eğer üründen sadece bir tane varsa, ürünü tamamen sil
                $deleteQuery = "DELETE FROM sepet WHERE Sepet_id = " . $row['Sepet_id'];
                $conn->query($deleteQuery);
            }
        }

        header("Location: sepet.php");
        exit();
    }
    if (isset($_POST['satinal'])) {
        header("Location: satinal.php");
        exit();
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/PHP-Project/css/style.css">
        <title>Sepet</title>
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
                        <a class="nav-link" href="/PHP-Project/index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/PHP-Project/urunler.php">Ürünler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/PHP-Project/sepet.php">Sepet</a>
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

    <div class="container sepet">
        <h2>Sepetim</h2>
        <?php
        $musteriID = $_SESSION['Musteri_id'];
        $sepetQuery = "SELECT sepet.*, urunler.Urunler_Adi, urunler.Urunler_Fiyat FROM sepet JOIN urunler ON sepet.Urunler_id = urunler.Urunler_id WHERE Musteriler_id = $musteriID";
        $sepetResult = $conn->query($sepetQuery);

        if ($sepetResult->num_rows > 0) {
            $toplamFiyat = 0;
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col">Ürün Adı</th>';
            echo '<th scope="col">Fiyat</th>';
            echo '<th scope="col">Adet</th>';
            echo '<th scope="col">Toplam Fiyat</th>';
            echo '<th scope="col">İşlemler</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $sepetResult->fetch_assoc()) {
                $urunAdet = $row['adet'];
                $urunFiyat = $row['Urunler_Fiyat'];
                $toplamFiyatUrun = $urunAdet * $urunFiyat;
                $toplamFiyat += $toplamFiyatUrun;

                echo '<tr>';
                echo '<td>' . $row['Urunler_Adi'] . '</td>';
                echo '<td>' . $row['Urunler_Fiyat'] . ' TL</td>';
                echo '<td>' . $urunAdet . '</td>';
                echo '<td>' . $toplamFiyatUrun . ' TL</td>';
                echo '<td><a href="sepet.php?sil=' . $row['Urunler_id'] . '" class="btn btn-danger">Sil</a></td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '<p>Toplam Fiyat: ' . $toplamFiyat . ' TL</p>';
            echo '<a href="satinal.php?toplam=' . $toplamFiyat . '" class="btn btn-success">Satın Al</a>';
        } else {
            echo '<p>Sepetiniz boş.</p>';
        }
        ?>
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
