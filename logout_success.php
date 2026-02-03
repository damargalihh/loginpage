<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Berhasil - Hotspot UMPKU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="refresh" content="5;url=login.php">
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animated">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="navbar-brand">
                <img src="https://via.placeholder.com/45x45/1a5f7a/ffffff?text=UM" alt="Logo UMPKU">
                <div class="brand-text">
                    <span>Hotspot UMPKU</span>
                    <span class="brand-sub">Internet Kampus</span>
                </div>
            </a>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card">
            <div class="card-body" style="text-align: center; padding: 50px 35px;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--success-color), #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px;">
                    <i class="fas fa-check" style="font-size: 3rem; color: white;"></i>
                </div>
                
                <h1 style="color: var(--success-color); margin-bottom: 15px;">Logout Berhasil!</h1>
                <p style="color: var(--gray-600); margin-bottom: 30px;">
                    Anda telah berhasil keluar dari sistem.<br>
                    Terima kasih telah menggunakan layanan Hotspot UMPKU.
                </p>

                <div class="alert alert-info" style="text-align: left;">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Catatan Penting:</strong>
                        <ul style="margin: 10px 0 0 20px; padding: 0;">
                            <li>Koneksi internet Anda telah diputuskan</li>
                            <li>Jika ingin menggunakan internet kembali, silakan login ulang</li>
                            <li>Tutup browser untuk keamanan di komputer publik</li>
                        </ul>
                    </div>
                </div>

                <p style="color: var(--gray-500); margin-bottom: 25px;">
                    <i class="fas fa-clock"></i> Anda akan dialihkan ke halaman login dalam <span id="countdown">5</span> detik...
                </p>

                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="login.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login Kembali
                    </a>
                    <a href="index.php" class="btn btn-outline">
                        <i class="fas fa-home"></i> Ke Beranda
                    </a>
                </div>

                <div class="footer-text" style="margin-top: 30px;">
                    <p>&copy; 2024 Universitas Muhammadiyah Palangkaraya</p>
                    <p>UPT Teknologi Informasi dan Komunikasi</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Countdown timer
        let countdown = 5;
        const countdownEl = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownEl.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'login.php';
            }
        }, 1000);
    </script>
</body>
</html>
