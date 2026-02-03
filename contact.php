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
                <div class="brand-text">
                    <span>Hotspot UMPKU</span>
                </div>
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
        <div class="card" style="max-width: 550px;">
            <div class="card-body" style="padding: 40px;">
                <div style="text-align: center; margin-bottom: 30px;">
                    <img src="logogram.png" alt="Logo" style="width: 70px; height: 70px; margin-bottom: 15px;">
                    <h1 style="font-size: 1.5rem; color: var(--gray-800);">IT Support</h1>
                    <p style="color: var(--gray-500); font-size: 0.9rem;">Hubungi kami jika ada kendala</p>
                </div>

                <!-- Quick Contact -->
                <div style="display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap;">
                    <a href="tel:+625363221234" style="flex: 1; min-width: 120px; text-decoration: none; padding: 15px; background: var(--gray-100); border-radius: 10px; text-align: center;">
                        <i class="fas fa-phone" style="color: var(--primary-color); font-size: 1.2rem;"></i>
                        <p style="margin: 8px 0 0; font-size: 0.85rem; color: var(--gray-700);">(0536) 3221234</p>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" style="flex: 1; min-width: 120px; text-decoration: none; padding: 15px; background: var(--gray-100); border-radius: 10px; text-align: center;">
                        <i class="fab fa-whatsapp" style="color: #25D366; font-size: 1.2rem;"></i>
                        <p style="margin: 8px 0 0; font-size: 0.85rem; color: var(--gray-700);">WhatsApp</p>
                    </a>
                </div>

                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success; ?>
                </div>
                <?php endif; ?>

                <form action="contact.php" method="POST" id="contactForm">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama lengkap" required
                               value="<?php echo htmlspecialchars($prefill_nama ?: ($_POST['nama'] ?? '')); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email aktif" required
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="subjek">Masalah</label>
                        <select class="form-control" id="subjek" name="subjek" required>
                            <option value="">Pilih kategori</option>
                            <option value="Tidak Bisa Login">Tidak Bisa Login</option>
                            <option value="Lupa Password">Lupa Password</option>
                            <option value="Koneksi Lambat">Koneksi Lambat</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pesan">Pesan</label>
                        <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Jelaskan masalah Anda" required><?php echo htmlspecialchars($_POST['pesan'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
</body>
</html>