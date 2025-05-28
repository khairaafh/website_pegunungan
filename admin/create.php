<?php
$page_title = "Tambah Gunung - Pegunungan Indonesia";
include '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_gunung = trim($_POST['nama_gunung']);
    $tinggi = intval($_POST['tinggi']);
    $lokasi = trim($_POST['lokasi']);
    $provinsi = trim($_POST['provinsi']);
    $tingkat_kesulitan = $_POST['tingkat_kesulitan'];
    $deskripsi = trim($_POST['deskripsi']);
    
    // Handle file upload
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            if ($_FILES['gambar']['size'] <= 5 * 1024 * 1024) {
                $gambar = time() . '_' . $file_name;
                $upload_path = '../assets/images/' . $gambar;
                
                if (!move_uploaded_file($file_tmp, $upload_path)) {
                    $error = 'Gagal mengupload gambar.';
                }
            } else {
                $error = 'Ukuran file maksimal 5MB.';
            }
        } else {
            $error = 'Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.';
        }
    }
    
    if (!$error && $nama_gunung && $tinggi && $lokasi && $provinsi && $tingkat_kesulitan) {
        try {
            $stmt = $pdo->prepare("INSERT INTO mountains (nama_gunung, tinggi, lokasi, provinsi, tingkat_kesulitan, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama_gunung, $tinggi, $lokasi, $provinsi, $tingkat_kesulitan, $deskripsi, $gambar]);
            $success = 'Data gunung berhasil ditambahkan!';
        } catch(PDOException $e) {
            $error = 'Gagal menambahkan data: ' . $e->getMessage();
        }
    } elseif (!$error) {
        $error = 'Semua field wajib diisi!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../admin/assets/style.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-mountain"></i> Tambah Gunung Baru</h1>
            <p>Lengkapi data gunung untuk menambahkan ke database</p>
        </div>

        <div class="breadcrumb">
            <a href="../mountains.php"><i class="fas fa-home"></i> Beranda</a>
            <i class="fas fa-chevron-right"></i>
            <span>Tambah Gunung</span>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <h2 class="section-title">
                <i class="fas fa-plus-circle"></i>
                Informasi Gunung
            </h2>

            <form method="POST" enctype="multipart/form-data" id="mountainForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_gunung">
                            Nama Gunung <span class="required-asterisk">*</span>
                        </label>
                        <i class="form-icon fas fa-mountain"></i>
                        <input type="text" id="nama_gunung" name="nama_gunung" class="form-control" 
                               placeholder="Contoh: Gunung Merapi" required>
                    </div>

                    <div class="form-group">
                        <label for="tinggi">
                            Tinggi (mdpl) <span class="required-asterisk">*</span>
                        </label>
                        <i class="form-icon fas fa-ruler-vertical"></i>
                        <input type="number" id="tinggi" name="tinggi" class="form-control" 
                               placeholder="tulis tinggi disini" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="lokasi">
                            Lokasi <span class="required-asterisk">*</span>
                        </label>
                        <i class="form-icon fas fa-map-marker-alt"></i>
                        <input type="text" id="lokasi" name="lokasi" class="form-control" 
                               placeholder="Kabupaten/Kota" required>
                    </div>

                    <div class="form-group">
                        <label for="provinsi">
                            Provinsi <span class="required-asterisk">*</span>
                        </label>
                        <i class="form-icon fas fa-map"></i>
                        <input type="text" id="provinsi" name="provinsi" class="form-control" 
                               placeholder="Provinsi" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tingkat Kesulitan <span class="required-asterisk">*</span></label>
                    <div class="difficulty-selector">
                        <div class="difficulty-option difficulty-easy">
                            <input type="radio" id="mudah" name="tingkat_kesulitan" value="Mudah" required>
                            <label for="mudah" class="difficulty-label">
                                <i class="fas fa-smile"></i><br>
                                <strong>Mudah</strong>
                            </label>
                        </div>
                        <div class="difficulty-option difficulty-medium">
                            <input type="radio" id="sedang" name="tingkat_kesulitan" value="Sedang">
                            <label for="sedang" class="difficulty-label">
                                <i class="fas fa-meh"></i><br>
                                <strong>Sedang</strong>
                            </label>
                        </div>
                        <div class="difficulty-option difficulty-hard">
                            <input type="radio" id="sulit" name="tingkat_kesulitan" value="Sulit">
                            <label for="sulit" class="difficulty-label">
                                <i class="fas fa-frown"></i><br>
                                <strong>Sulit</strong>
                            </label>
                        </div>
                        <div class="difficulty-option difficulty-extreme">
                            <input type="radio" id="sangat_sulit" name="tingkat_kesulitan" value="Sangat Sulit">
                            <label for="sangat_sulit" class="difficulty-label">
                                <i class="fas fa-skull"></i><br>
                                <strong>Sangat Sulit</strong>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <i class="form-icon fas fa-align-left"></i>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" 
                              placeholder="Ceritakan tentang gunung ini, jalur pendakian, pemandangan, dll..."></textarea>
                </div>

                <div class="form-group">
                    <label>Gambar Gunung</label>
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file" id="gambar" name="gambar" class="file-upload-input" accept="image/*">
                        <div class="file-upload-content">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <div class="upload-text">Klik atau drag & drop gambar di sini</div>
                            <div class="upload-subtext">JPG, PNG, GIF maksimal 5MB</div>
                        </div>
                    </div>
                    <div class="file-preview" id="filePreview">
                        <div class="preview-info">
                            <i class="fas fa-check-circle"></i>
                            <span id="fileName"></span>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="../mountains.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Gunung
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script src="../admin/assets/script.js"></script>
</body>
</html>

