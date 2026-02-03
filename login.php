<?php
require_once 'config.php';

$error = '';
$success = '';

// Cek jika sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: status.php');
    exit;
}

// Proses Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = sanitize($_POST['nim'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($nim) || empty($password)) {
        $error = 'NIM dan Password harus diisi!';
    } else {
        try {
            $conn = getConnection();
            $stmt = $conn->prepare("SELECT id, nim, nama_lengkap, password, status FROM users WHERE nim = ?");
            $stmt->bind_param("s", $nim);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                if ($user['status'] !== 'active') {
                    $error = 'Akun Anda belum diaktivasi. Silakan hubungi admin.';
                } elseif (password_verify($password, $user['password'])) {
                    // Login berhasil
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nim'] = $user['nim'];
                    $_SESSION['nama'] = $user['nama_lengkap'];
                    $_SESSION['login_time'] = time();
                    
                    // Simpan login history
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $mac = isset($_SERVER['HTTP_X_MAC_ADDRESS']) ? $_SERVER['HTTP_X_MAC_ADDRESS'] : 'Unknown';
                    $stmt2 = $conn->prepare("INSERT INTO login_history (user_id, ip_address, mac_address) VALUES (?, ?, ?)");
                    $stmt2->bind_param("iss", $user['id'], $ip, $mac);
                    $stmt2->execute();
                    
                    // Redirect ke MikroTik login atau status page
                    header('Location: status.php');
                    exit;
                } else {
                    $error = 'Password salah!';
                }
            } else {
                $error = 'NIM tidak ditemukan!';
            }
            
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotspot UMPKU</title>
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
                <li><a href="login.php" class="active"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Kontak</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card">
            <div class="card-header">
                <img src="https://via.placeholder.com/80x80/ffffff/1a5f7a?text=UM" alt="Logo" class="logo">
                <h1>Login Hotspot</h1>
                <p>Masuk untuk mengakses internet kampus</p>
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
                </div>
                <?php endif; ?>

                <form action="login.php" method="POST" id="loginForm">
                    <div class="form-group">
                        <label for="nim">NIM (Nomor Induk Mahasiswa)</label>
                        <div class="input-group">
                            <i class="fas fa-id-card"></i>
                            <input type="text" class="form-control" id="nim" name="nim" 
                                   placeholder="Masukkan NIM Anda" required
                                   value="<?php echo isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <i class="fas fa-lock"></i>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Masukkan password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat saya di perangkat ini</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>
                </form>

                <div class="divider">
                    <span>atau</span>
                </div>

                <div style="text-align: center;">
                    <p style="margin-bottom: 15px; color: var(--gray-600);">Belum punya akun?</p>
                    <a href="register.php" class="btn btn-outline btn-block">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                </div>

                <div class="footer-text">
                    <p><a href="#" class="link">Lupa Password?</a></p>
                    <p style="margin-top: 15px;">Butuh bantuan? <a href="contact.php" class="link">Hubungi IT Support</a></p>
                </div>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
</body>
</html>
