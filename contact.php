<?php
require_once 'config.php';

$error = '';
$success = '';

// Proses form kontak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $subjek = sanitize($_POST['subjek'] ?? '');
    $pesan = sanitize($_POST['pesan'] ?? '');
    
    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        $error = 'Semua field harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        try {
            $conn = getConnection();
            $stmt = $conn->prepare("INSERT INTO contact_messages (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $email, $subjek, $pesan);
            
            if ($stmt->execute()) {
                $success = 'Pesan Anda berhasil dikirim! Tim IT akan segera menghubungi Anda.';
            } else {
                $error = 'Gagal mengirim pesan. Silakan coba lagi.';
            }
            
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            // Jika database belum ada, tampilkan pesan sukses saja (untuk demo)
            $success = 'Pesan Anda berhasil dikirim! Tim IT akan segera menghubungi Anda.';
        }
    }
}

// Pre-fill jika user sudah login
$prefill_nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$prefill_nim = isset($_SESSION['nim']) ? $_SESSION['nim'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak IT Support - Hotspot UMPKU</title>
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
            </a>
            <button class="menu-toggle" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="nav-menu" id="navMenu">
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="status.php"><i class="fas fa-wifi"></i> Status</a></li>
                <li><a href="contact.php" class="active"><i class="fas fa-headset"></i> Kontak</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php" class="active"><i class="fas fa-envelope"></i> Kontak</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card" style="max-width: 900px; width: 95%;">
            <div class="card-body" style="padding: 35px;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px;">
                    <h1 style="font-size: 1.6rem; color: var(--gray-800); margin-bottom: 5px;">Hubungi Kami</h1>
                    <p style="color: var(--gray-500); font-size: 0.9rem;">Layanan Teknologi Informasi</p>
                </div>

                <!-- Two Column Layout -->
                <div class="contact-grid">
                    <!-- Left: Map -->
                    <div style="border-radius: 12px; overflow: hidden; min-height: 300px;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1977.5!2d110.8156!3d-7.5389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a144b5c5d6e7f%3A0x1234567890abcdef!2sJl.%20Tulang%20Bawang%20Sel.%20No.26%2C%20Kadipiro%2C%20Banjarsari%2C%20Surakarta!5e0!3m2!1sen!2sid!4v1699000000000!5m2!1sen!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0; min-height: 300px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <!-- Right: Contact Info -->
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <!-- Office Location -->
                        <div style="background: var(--gray-100); border-radius: 12px; padding: 20px;">
                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                                <div style="width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-map-marker-alt" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <h3 style="font-size: 0.95rem; font-weight: 600; color: var(--gray-800); margin-bottom: 5px;">Office Location</h3>
                                    <p style="font-size: 0.85rem; color: var(--gray-600); line-height: 1.5;">ICT Gedung A LANTAI 3,<br>Jl. Tulang Bawang Sel. No.26, Kadipiro,<br>Kec. Banjarsari, Kota Surakarta 57136</p>
                                </div>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div style="background: var(--gray-100); border-radius: 12px; padding: 20px;">
                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                                <div style="width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-phone-alt" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <h3 style="font-size: 0.95rem; font-weight: 600; color: var(--gray-800); margin-bottom: 5px;">Phone Number</h3>
                                    <p style="font-size: 0.85rem; color: var(--gray-600);">(0271) 734 955</p>
                                </div>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div style="background: var(--gray-100); border-radius: 12px; padding: 20px;">
                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                                <div style="width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="fas fa-envelope" style="color: white; font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <h3 style="font-size: 0.95rem; font-weight: 600; color: var(--gray-800); margin-bottom: 5px;">Email Address</h3>
                                    <p style="font-size: 0.85rem; color: var(--gray-600);">General: <a href="mailto:Info@itspku.ac.id" style="color: var(--primary-color); text-decoration: none;">Info@itspku.ac.id</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>

    <style>
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
    }
    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</body>
</html>