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
    } elseif (!preg_match('/^[0-9]+$/', $nim)) {
        $error = 'NIM hanya boleh berisi angka!';
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
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php" class="active"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card card-wide">
            <div class="card-header">
                <img src="https://via.placeholder.com/80x80/ffffff/1a5f7a?text=UM" alt="Logo" class="logo">
                <h1>Daftar Akun Baru</h1>
                <p>Buat akun untuk mengakses layanan hotspot kampus</p>
            </div>
            <div class="card-body">
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
                    <br><br>
                    <a href="login.php" class="btn btn-success">
                        <i class="fas fa-sign-in-alt"></i> Login Sekarang
                    </a>
                </div>
                <?php else: ?>

                <!-- Progress Steps -->
                <div class="steps">
                    <div class="step active">
                        <span class="step-number">1</span>
                        <span>Data Diri</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <span class="step-number">2</span>
                        <span>Verifikasi</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <span>Selesai</span>
                    </div>
                </div>

                <form action="register.php" method="POST" id="registerForm">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nim">NIM <span style="color: var(--danger-color);">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-id-card"></i>
                                    <input type="text" class="form-control" id="nim" name="nim" 
                                           placeholder="Contoh: 2021010001" required
                                           value="<?php echo isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap <span style="color: var(--danger-color);">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-user"></i>
                                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                           placeholder="Nama sesuai KTM" required
                                           value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Email <span style="color: var(--danger-color);">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="email@student.umpku.ac.id" required
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="no_hp">No. HP/WhatsApp</label>
                                <div class="input-group">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" class="form-control" id="no_hp" name="no_hp" 
                                           placeholder="08xxxxxxxxxx"
                                           value="<?php echo isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="fakultas">Fakultas</label>
                                <select class="form-control" id="fakultas" name="fakultas">
                                    <option value="">-- Pilih Fakultas --</option>
                                    <?php foreach ($fakultas_list as $fak): ?>
                                    <option value="<?php echo $fak; ?>" <?php echo (isset($_POST['fakultas']) && $_POST['fakultas'] === $fak) ? 'selected' : ''; ?>>
                                        <?php echo $fak; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="prodi">Program Studi</label>
                                <div class="input-group">
                                    <i class="fas fa-graduation-cap"></i>
                                    <input type="text" class="form-control" id="prodi" name="prodi" 
                                           placeholder="Nama Program Studi"
                                           value="<?php echo isset($_POST['prodi']) ? htmlspecialchars($_POST['prodi']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="password">Password <span style="color: var(--danger-color);">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Minimal 6 karakter" required minlength="6">
                                    <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                                        <i class="fas fa-eye" id="toggleIcon1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="confirm_password">Konfirmasi Password <span style="color: var(--danger-color);">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                           placeholder="Ulangi password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                                        <i class="fas fa-eye" id="toggleIcon2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">Saya menyetujui <a href="#" class="link">Syarat & Ketentuan</a> penggunaan layanan hotspot UMPKU</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </button>
                </form>

                <div class="divider">
                    <span>atau</span>
                </div>

                <div style="text-align: center;">
                    <p style="color: var(--gray-600);">Sudah punya akun?</p>
                    <a href="login.php" class="btn btn-outline btn-block" style="margin-top: 10px;">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </a>
                </div>
                <?php endif; ?>

                <div class="footer-text">
                    <p>Butuh bantuan? <a href="contact.php" class="link">Hubungi IT Support</a></p>
                </div>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
    <script>
        // Password match validation
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
