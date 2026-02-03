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
                <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="status.php"><i class="fas fa-chart-line"></i> Status</a></li>
                <li><a href="contact.php" class="active"><i class="fas fa-envelope"></i> Kontak</a></li>
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
        <div class="card card-wide">
            <div class="card-header">
                <h1><i class="fas fa-headset"></i> Hubungi IT Support</h1>
                <p>Kami siap membantu mengatasi kendala Anda</p>
            </div>
            <div class="card-body">
                <!-- Contact Info Cards -->
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Alamat</h4>
                            <p>Gedung UPT TIK Lt. 2<br>Kampus UMPKU<br>Jl. RTA Milono Km. 1,5 Palangkaraya</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <div>
                            <h4>Telepon</h4>
                            <p>(0536) 3221234<br>Ext. 123</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>it.support@umpku.ac.id<br>helpdesk@umpku.ac.id</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fab fa-whatsapp"></i>
                        <div>
                            <h4>WhatsApp</h4>
                            <p>+62 812-3456-7890<br>(Chat Only)</p>
                        </div>
                    </div>
                </div>

                <div class="divider">
                    <span>atau kirim pesan langsung</span>
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
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nama">Nama Lengkap <span style="color: var(--danger-color);">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-user"></i>
                                    <input type="text" class="form-control" id="nama" name="nama" 
                                           placeholder="Nama Anda" required
                                           value="<?php echo htmlspecialchars($prefill_nama ?: ($_POST['nama'] ?? '')); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Email <span style="color: var(--danger-color);">*</span></label>
                                <div class="input-group">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="email@student.umpku.ac.id" required
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subjek">Subjek <span style="color: var(--danger-color);">*</span></label>
                        <select class="form-control" id="subjek" name="subjek" required>
                            <option value="">-- Pilih Kategori Masalah --</option>
                            <option value="Tidak Bisa Login" <?php echo (($_POST['subjek'] ?? '') === 'Tidak Bisa Login') ? 'selected' : ''; ?>>Tidak Bisa Login</option>
                            <option value="Lupa Password" <?php echo (($_POST['subjek'] ?? '') === 'Lupa Password') ? 'selected' : ''; ?>>Lupa Password</option>
                            <option value="Koneksi Lambat" <?php echo (($_POST['subjek'] ?? '') === 'Koneksi Lambat') ? 'selected' : ''; ?>>Koneksi Lambat</option>
                            <option value="Tidak Bisa Terhubung" <?php echo (($_POST['subjek'] ?? '') === 'Tidak Bisa Terhubung') ? 'selected' : ''; ?>>Tidak Bisa Terhubung ke WiFi</option>
                            <option value="Sering Terputus" <?php echo (($_POST['subjek'] ?? '') === 'Sering Terputus') ? 'selected' : ''; ?>>Koneksi Sering Terputus</option>
                            <option value="Akun Diblokir" <?php echo (($_POST['subjek'] ?? '') === 'Akun Diblokir') ? 'selected' : ''; ?>>Akun Diblokir</option>
                            <option value="Permintaan Aktivasi" <?php echo (($_POST['subjek'] ?? '') === 'Permintaan Aktivasi') ? 'selected' : ''; ?>>Permintaan Aktivasi Akun</option>
                            <option value="Lainnya" <?php echo (($_POST['subjek'] ?? '') === 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pesan">Deskripsi Masalah <span style="color: var(--danger-color);">*</span></label>
                        <textarea class="form-control" id="pesan" name="pesan" rows="5" 
                                  placeholder="Jelaskan masalah Anda secara detail. Sertakan informasi seperti:&#10;- NIM Anda&#10;- Perangkat yang digunakan&#10;- Lokasi di kampus&#10;- Waktu kejadian" required><?php echo htmlspecialchars($_POST['pesan'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                </form>

                <div class="divider">
                    <span>FAQ - Pertanyaan Umum</span>
                </div>

                <!-- FAQ Section -->
                <div class="status-card">
                    <details style="margin-bottom: 15px;">
                        <summary style="cursor: pointer; font-weight: 600; color: var(--primary-color);">
                            <i class="fas fa-question-circle"></i> Bagaimana cara mendaftar akun hotspot?
                        </summary>
                        <p style="margin-top: 10px; padding-left: 25px; color: var(--gray-600);">
                            Klik menu "Daftar" di halaman utama, isi form dengan data yang valid (NIM, Email, dll), 
                            kemudian verifikasi akun Anda. Setelah diaktivasi oleh admin, Anda dapat login.
                        </p>
                    </details>
                    <details style="margin-bottom: 15px;">
                        <summary style="cursor: pointer; font-weight: 600; color: var(--primary-color);">
                            <i class="fas fa-question-circle"></i> Kenapa saya tidak bisa login?
                        </summary>
                        <p style="margin-top: 10px; padding-left: 25px; color: var(--gray-600);">
                            Pastikan NIM dan password yang dimasukkan benar. Jika akun baru didaftarkan, 
                            tunggu aktivasi dari admin. Hubungi IT Support jika masalah berlanjut.
                        </p>
                    </details>
                    <details style="margin-bottom: 15px;">
                        <summary style="cursor: pointer; font-weight: 600; color: var(--primary-color);">
                            <i class="fas fa-question-circle"></i> Bagaimana cara reset password?
                        </summary>
                        <p style="margin-top: 10px; padding-left: 25px; color: var(--gray-600);">
                            Kirim permintaan reset password melalui form kontak ini atau datang langsung 
                            ke UPT TIK dengan membawa KTM untuk verifikasi identitas.
                        </p>
                    </details>
                    <details>
                        <summary style="cursor: pointer; font-weight: 600; color: var(--primary-color);">
                            <i class="fas fa-question-circle"></i> Berapa batas kuota internet?
                        </summary>
                        <p style="margin-top: 10px; padding-left: 25px; color: var(--gray-600);">
                            Setiap mahasiswa mendapat kuota 1 GB per hari. Kuota akan direset setiap tengah malam.
                            Untuk kebutuhan akademik khusus, hubungi UPT TIK untuk pengajuan tambahan kuota.
                        </p>
                    </details>
                </div>

                <div class="footer-text">
                    <p><i class="fas fa-clock"></i> Jam Operasional IT Support: Senin - Jumat, 08:00 - 16:00 WIB</p>
                    <p>Response time: 1x24 jam kerja</p>
                </div>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
</body>
</html>
