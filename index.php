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
                <img src="logo_web_umpku_color.png" alt="Logo UMPKU">
                <div class="brand-text">
                    <span>Hotspot UMPKU</span>
                </div>
            </a>
            <button class="menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php"><i class="fas fa-headset"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card" style="max-width: 500px;">
            <div class="card-body" style="text-align: center; padding: 50px 40px;">
                <!-- Logo -->
                <img src="logogram.png" alt="Logo UMPKU" style="width: 120px; height: 120px; margin-bottom: 30px; filter: drop-shadow(0 10px 30px rgba(229, 57, 53, 0.3));">
                
                <!-- Title -->
                <h1 style="font-size: 1.8rem; color: var(--gray-800); margin-bottom: 10px; font-weight: 700;">
                    Hotspot UMPKU
                </h1>
                <p style="color: var(--gray-500); margin-bottom: 40px; font-size: 0.95rem;">
                    Layanan Internet Kampus<br>
                    <span style="font-size: 0.85rem;">Universitas Muhammadiyah Palangkaraya</span>
                </p>

                <!-- Action Buttons -->
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <a href="login.php" class="btn btn-primary btn-block" style="padding: 16px 28px; font-size: 1.05rem;">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </a>
                    <a href="register.php" class="btn btn-outline btn-block" style="padding: 16px 28px; font-size: 1.05rem;">
                        <i class="fas fa-user-plus"></i> Buat Akun Baru
                    </a>
                </div>

                <!-- Divider -->
                <div class="divider" style="margin: 35px 0;">
                    <span>Bantuan</span>
                </div>

                <!-- Help Link -->
                <a href="contact.php" class="link" style="display: inline-flex; align-items: center; gap: 8px; color: var(--gray-600);">
                    <i class="fas fa-headset"></i> Hubungi IT Support
                </a>

                <!-- Footer -->
                <div class="footer-text" style="margin-top: 40px;">
                    <p style="font-size: 0.8rem;">&copy; 2024 UM Palangkaraya</p>
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
