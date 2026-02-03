<?php
require_once 'config.php';

$error = '';
$success = '';

// Proses Registrasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = sanitize($_POST['nim'] ?? '');
    $nama = sanitize($_POST['nama_lengkap'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $fakultas = sanitize($_POST['fakultas'] ?? '');
    $prodi = sanitize($_POST['prodi'] ?? '');
    $no_hp = sanitize($_POST['no_hp'] ?? '');
    
    // Validasi
    if (empty($nim) || empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok!';
    } elseif (!preg_match('/^[A-Za-z0-9]+$/', $nim)) {
        $error = 'NIM hanya boleh berisi huruf dan angka!';
    } else {
        try {
            $conn = getConnection();
            
            // Cek apakah NIM atau email sudah terdaftar
            $stmt = $conn->prepare("SELECT id FROM users WHERE nim = ? OR email = ?");
            $stmt->bind_param("ss", $nim, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = 'NIM atau Email sudah terdaftar!';
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user baru
                $stmt2 = $conn->prepare("INSERT INTO users (nim, nama_lengkap, email, password, fakultas, prodi, no_hp, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'active')");
                $stmt2->bind_param("sssssss", $nim, $nama, $email, $hashed_password, $fakultas, $prodi, $no_hp);
                
                if ($stmt2->execute()) {
                    $success = 'Pendaftaran berhasil! Silakan login untuk mengakses internet.';
                } else {
                    $error = 'Gagal mendaftar. Silakan coba lagi.';
                }
                $stmt2->close();
            }
            
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}

// Daftar Fakultas
$fakultas_list = [
    'Fakultas Keguruan dan Ilmu Pendidikan',
    'Fakultas Teknik dan Informatika',
    'Fakultas Kesehatan',
    'Fakultas Ekonomi dan Bisnis',
    'Fakultas Agama Islam',
    'Fakultas Pertanian dan Kehutanan'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Hotspot UMPKU</title>
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
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php" class="active"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php"><i class="fas fa-headset"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card" style="max-width: 480px;">
            <div class="card-body" style="padding: 40px;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <img src="logogram.png" alt="Logo" style="width: 70px; height: 70px; margin-bottom: 15px;">
                    <h1 style="font-size: 1.5rem; color: var(--gray-800);">Daftar Akun</h1>
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
                <a href="login.php" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login Sekarang
                </a>
                <?php else: ?>

                <form action="register.php" method="POST" id="registerForm">
                    <div class="form-group">
                        <label for="nim">NIM</label>
                        <input type="text" class="form-control" id="nim" name="nim" placeholder="Nomor Induk Mahasiswa" required
                               value="<?php echo isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama sesuai KTM" required
                               value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email aktif" required
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required minlength="6">
                            <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Daftar
                    </button>
                </form>

                <div class="footer-text" style="margin-top: 25px;">
                    <p>Sudah punya akun? <a href="login.php" class="link">Login</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
    <script>
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok!');
            }
        });
    </script>
</body>
</html>