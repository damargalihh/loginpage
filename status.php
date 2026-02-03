<?php
require_once 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nim = $_SESSION['nim'];
$nama = $_SESSION['nama'];
$login_time = $_SESSION['login_time'];

// Hitung waktu sesi
$session_duration = time() - $login_time;
$hours = floor($session_duration / 3600);
$minutes = floor(($session_duration % 3600) / 60);
$seconds = $session_duration % 60;

// Ambil data user lengkap
try {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Ambil history login
    $stmt2 = $conn->prepare("SELECT * FROM login_history WHERE user_id = ? ORDER BY login_time DESC LIMIT 5");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $history_result = $stmt2->get_result();
    $login_history = $history_result->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
    
    $conn->close();
} catch (Exception $e) {
    $user = null;
    $login_history = [];
}

// Simulasi data penggunaan (dalam implementasi nyata, ini dari MikroTik API)
$data_used = rand(100, 500); // MB
$data_limit = 1024; // 1 GB
$data_percentage = ($data_used / $data_limit) * 100;

$ip_address = $_SERVER['REMOTE_ADDR'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Koneksi - Hotspot UMPKU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .progress-bar {
            width: 100%;
            height: 10px;
            background: var(--gray-200);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success-color), var(--primary-color));
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        .progress-bar-fill.warning {
            background: linear-gradient(90deg, var(--warning-color), #ff9800);
        }
        .progress-bar-fill.danger {
            background: linear-gradient(90deg, var(--danger-color), #c82333);
        }
    </style>
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
                <li><a href="status.php" class="active"><i class="fas fa-chart-line"></i> Status</a></li>
                <li><a href="contact.php"><i class="fas fa-envelope"></i> Kontak</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="card card-wide">
            <div class="card-header">
                <h1><i class="fas fa-wifi"></i> Status Koneksi</h1>
                <p>Pantau status koneksi internet Anda</p>
            </div>
            <div class="card-body">
                <!-- Profile Section -->
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($nama, 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo htmlspecialchars($nama); ?></h3>
                        <p><i class="fas fa-id-card"></i> NIM: <?php echo htmlspecialchars($nim); ?></p>
                        <?php if ($user): ?>
                        <p><i class="fas fa-building"></i> <?php echo htmlspecialchars($user['fakultas'] ?? 'Belum diisi'); ?></p>
                        <?php endif; ?>
                    </div>
                    <div style="margin-left: auto;">
                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Online</span>
                    </div>
                </div>

                <!-- Status Card -->
                <div class="status-card">
                    <div class="status-header">
                        <div class="status-indicator">
                            <span class="status-dot online"></span>
                            <span style="font-weight: 600; color: var(--success-color);">Terhubung ke Internet</span>
                        </div>
                        <span style="color: var(--gray-600); font-size: 0.9rem;">
                            <i class="fas fa-clock"></i> Sesi dimulai: <?php echo date('H:i:s', $login_time); ?>
                        </span>
                    </div>
                    <div class="status-info">
                        <div class="status-item">
                            <label><i class="fas fa-network-wired"></i> IP Address</label>
                            <span><?php echo $ip_address; ?></span>
                        </div>
                        <div class="status-item">
                            <label><i class="fas fa-stopwatch"></i> Durasi Sesi</label>
                            <span id="sessionDuration"><?php echo sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); ?></span>
                        </div>
                        <div class="status-item">
                            <label><i class="fas fa-calendar-alt"></i> Tanggal Login</label>
                            <span><?php echo date('d M Y', $login_time); ?></span>
                        </div>
                        <div class="status-item">
                            <label><i class="fas fa-user-check"></i> Status Akun</label>
                            <span class="badge badge-success">Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Usage Stats -->
                <h3 style="margin-bottom: 15px; color: var(--gray-800);"><i class="fas fa-chart-pie"></i> Penggunaan Data</h3>
                <div class="status-card">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color);"><?php echo $data_used; ?> MB</span>
                            <span style="color: var(--gray-600);"> / <?php echo $data_limit; ?> MB</span>
                        </div>
                        <span style="color: var(--gray-600);"><?php echo round($data_percentage); ?>% terpakai</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-bar-fill <?php echo $data_percentage > 80 ? 'danger' : ($data_percentage > 50 ? 'warning' : ''); ?>" 
                             style="width: <?php echo $data_percentage; ?>%"></div>
                    </div>
                </div>

                <div class="usage-stats">
                    <div class="stat-card">
                        <i class="fas fa-download"></i>
                        <span class="value"><?php echo rand(50, 300); ?> MB</span>
                        <span class="label">Download</span>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));">
                        <i class="fas fa-upload"></i>
                        <span class="value"><?php echo rand(10, 100); ?> MB</span>
                        <span class="label">Upload</span>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #6f42c1, #9b59b6);">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="value"><?php echo rand(5, 20); ?> Mbps</span>
                        <span class="label">Kecepatan</span>
                    </div>
                </div>

                <!-- Login History -->
                <h3 style="margin: 30px 0 15px; color: var(--gray-800);"><i class="fas fa-history"></i> Riwayat Login</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal & Waktu</th>
                                <th>IP Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($login_history)): ?>
                                <?php foreach ($login_history as $history): ?>
                                <tr>
                                    <td><?php echo date('d M Y, H:i:s', strtotime($history['login_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($history['ip_address']); ?></td>
                                    <td><span class="badge badge-success">Berhasil</span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td><?php echo date('d M Y, H:i:s', $login_time); ?></td>
                                    <td><?php echo $ip_address; ?></td>
                                    <td><span class="badge badge-success">Berhasil</span></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; margin-top: 30px; flex-wrap: wrap;">
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout / Putuskan Koneksi
                    </a>
                    <a href="contact.php" class="btn btn-secondary">
                        <i class="fas fa-headset"></i> Laporkan Masalah
                    </a>
                    <button class="btn btn-outline" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh Status
                    </button>
                </div>

                <div class="footer-text">
                    <p><i class="fas fa-info-circle"></i> Jika mengalami masalah koneksi, silakan logout dan login kembali.</p>
                </div>
            </div>
        </div>
    </main>

    <script src="js/main.js"></script>
    <script>
        // Update session duration every second
        let sessionStart = <?php echo $login_time; ?>;
        
        function updateSessionDuration() {
            let now = Math.floor(Date.now() / 1000);
            let duration = now - sessionStart;
            
            let hours = Math.floor(duration / 3600);
            let minutes = Math.floor((duration % 3600) / 60);
            let seconds = duration % 60;
            
            document.getElementById('sessionDuration').textContent = 
                String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
        }
        
        setInterval(updateSessionDuration, 1000);
    </script>
</body>
</html>
