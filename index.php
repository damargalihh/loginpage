<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotspot UMPKU - Universitas Muhammadiyah Palangkaraya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
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
            <button class="menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card card-wide">
            <div class="card-body">
                <div class="hero-section">
                    <img src="https://via.placeholder.com/100x100/1a5f7a/ffffff?text=UM" alt="Logo" style="width: 100px; height: 100px; border-radius: 50%; margin-bottom: 20px;">
                    <h1>Selamat Datang di Hotspot UMPKU</h1>
                    <p>Layanan internet gratis untuk seluruh civitas akademika Universitas Muhammadiyah Palangkaraya. Nikmati akses internet cepat dan stabil untuk mendukung kegiatan akademik Anda.</p>
                    
                    <div class="quick-actions">
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                        </a>
                        <a href="register.php" class="btn btn-outline">
                            <i class="fas fa-user-plus"></i> Buat Akun
                        </a>
                    </div>
                </div>

                <div class="features">
                    <div class="feature-item">
                        <i class="fas fa-bolt"></i>
                        <h3>Cepat & Stabil</h3>
                        <p>Koneksi internet berkecepatan tinggi untuk kebutuhan belajar dan penelitian</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Aman & Terpercaya</h3>
                        <p>Sistem keamanan terjamin untuk melindungi privasi pengguna</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-clock"></i>
                        <h3>24/7 Tersedia</h3>
                        <p>Layanan internet tersedia sepanjang waktu untuk akses tanpa batas</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-headset"></i>
                        <h3>Dukungan IT</h3>
                        <p>Tim IT siap membantu mengatasi kendala koneksi Anda</p>
                    </div>
                </div>

                <div class="divider">
                    <span>Cara Menggunakan</span>
                </div>

                <div class="features">
                    <div class="feature-item">
                        <i class="fas fa-wifi" style="color: var(--info-color);"></i>
                        <h3>1. Hubungkan WiFi</h3>
                        <p>Sambungkan perangkat ke jaringan "UMPKU-Hotspot"</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-user-circle" style="color: var(--warning-color);"></i>
                        <h3>2. Buka Browser</h3>
                        <p>Halaman login akan muncul otomatis</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-key" style="color: var(--success-color);"></i>
                        <h3>3. Login</h3>
                        <p>Masukkan NIM dan password Anda</p>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-globe" style="color: var(--primary-color);"></i>
                        <h3>4. Jelajahi Internet</h3>
                        <p>Nikmati akses internet tanpa batas</p>
                    </div>
                </div>

                <div class="footer-text">
                    <p>&copy; 2024 Universitas Muhammadiyah Palangkaraya. All Rights Reserved.</p>
                    <p>UPT Teknologi Informasi dan Komunikasi</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Scroll to Top Button -->
    <button class="scroll-top" id="scrollTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="js/main.js"></script>
</body>
</html>
