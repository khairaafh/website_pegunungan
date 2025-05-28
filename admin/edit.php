<?php
$page_title = "Edit Gunung - Pegunungan Indonesia";
include '../config/database.php';

$error = '';
$success = '';
$mountain = null;

// Get mountain data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM mountains WHERE id = ?");
    $stmt->execute([$id]);
    $mountain = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$mountain) {
        header('Location: ../mountains.php');
        exit;
    }
} else {
    header('Location: ../mountains.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_gunung = trim($_POST['nama_gunung']);
    $tinggi = intval($_POST['tinggi']);
    $lokasi = trim($_POST['lokasi']);
    $provinsi = trim($_POST['provinsi']);
    $tingkat_kesulitan = $_POST['tingkat_kesulitan'];
    $deskripsi = trim($_POST['deskripsi']);
    $gambar = $mountain['gambar']; // Keep existing image by default
    
    // Handle file upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_ext, $allowed_ext)) {
            // Delete old image if exists
            if ($mountain['gambar'] && file_exists('../assets/images/' . $mountain['gambar'])) {
                unlink('../assets/images/' . $mountain['gambar']);
            }
            
            $gambar = time() . '_' . $file_name;
            $upload_path = '../assets/images/' . $gambar;
            
            if (!move_uploaded_file($file_tmp, $upload_path)) {
                $error = 'Gagal mengupload gambar.';
            }
        } else {
            $error = 'Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.';
        }
    }
    
    if (!$error && $nama_gunung && $tinggi && $lokasi && $provinsi) {
        try {
            $stmt = $pdo->prepare("UPDATE mountains SET nama_gunung = ?, tinggi = ?, lokasi = ?, provinsi = ?, tingkat_kesulitan = ?, deskripsi = ?, gambar = ? WHERE id = ?");
            $stmt->execute([$nama_gunung, $tinggi, $lokasi, $provinsi, $tingkat_kesulitan, $deskripsi, $gambar, $id]);
            $success = 'Data gunung berhasil diupdate!';
            
            // Refresh mountain data
            $stmt = $pdo->prepare("SELECT * FROM mountains WHERE id = ?");
            $stmt->execute([$id]);
            $mountain = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $error = 'Gagal mengupdate data: ' . $e->getMessage();
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
            <h1><i class="fas fa-edit"></i> Edit Gunung</h1>
            <p>Update informasi gunung <?php echo htmlspecialchars($mountain['nama_gunung']); ?></p>
        </div>

        <div class="breadcrumb">
            <a href="../mountains.php"><i class="fas fa-home"></i> Beranda</a>
            <i class="fas fa-chevron-right"></i>
            <span>Edit Gunung</span>
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
                <i class="fas fa-edit"></i>
                Update Informasi Gunung
            </h2>

            <form method="POST" enctype="multipart/form-data" id="mountainForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nama_gunung">
                            Nama Gunung <span class="required-asterisk">*</span>
                        </label>
                        <i class="form-icon fas fa-mountain"></i>
                        <input type="text" id="nama_gunung" name="nama_gunung" class="form-control" 
                               value="<?php echo htmlspecialchars($mountain['nama_gunung']); ?>" 
                               placeholder="Contoh: Gunung Merapi" required>
                    </div>

                    <div class="form-group">
                        <label for="tinggi">
                            Tinggi (mdpl) <span class="required-asterisk">*</span>
                        </label>
                        <i class="form-icon fas fa-ruler-vertical"></i>
                        <input type="number" id="tinggi" name="tinggi" class="form-control" 
                               value="<?php echo $mountain['tinggi']; ?>" 
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
                               value="<?php echo htmlspecialchars($mountain['lokasi']); ?>" 
                               placeholder="Kabupaten/Kota" required>
                    </div>

                    <div class="form-group">
                        <label for="provinsi">
                            Provinsi <span class="required-asterisk">*</span>
                        </label>
                        <i class="form-icon fas fa-map"></i>
                        <input type="text" id="provinsi" name="provinsi" class="form-control" 
                               value="<?php echo htmlspecialchars($mountain['provinsi']); ?>" 
                               placeholder="Provinsi" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tingkat Kesulitan <span class="required-asterisk">*</span></label>
                    <div class="difficulty-selector">
                        <div class="difficulty-option difficulty-easy">
                            <input type="radio" id="mudah" name="tingkat_kesulitan" value="Mudah" 
                                   <?php echo $mountain['tingkat_kesulitan'] == 'Mudah' ? 'checked' : ''; ?> required>
                            <label for="mudah" class="difficulty-label">
                                <i class="fas fa-smile"></i><br>
                                <strong>Mudah</strong>
                            </label>
                        </div>
                        <div class="difficulty-option difficulty-medium">
                            <input type="radio" id="sedang" name="tingkat_kesulitan" value="Sedang"
                                   <?php echo $mountain['tingkat_kesulitan'] == 'Sedang' ? 'checked' : ''; ?>>
                            <label for="sedang" class="difficulty-label">
                                <i class="fas fa-meh"></i><br>
                                <strong>Sedang</strong>
                            </label>
                        </div>
                        <div class="difficulty-option difficulty-hard">
                            <input type="radio" id="sulit" name="tingkat_kesulitan" value="Sulit"
                                   <?php echo $mountain['tingkat_kesulitan'] == 'Sulit' ? 'checked' : ''; ?>>
                            <label for="sulit" class="difficulty-label">
                                <i class="fas fa-frown"></i><br>
                                <strong>Sulit</strong>
                            </label>
                        </div>
                        <div class="difficulty-option difficulty-extreme">
                            <input type="radio" id="sangat_sulit" name="tingkat_kesulitan" value="Sangat Sulit"
                                   <?php echo $mountain['tingkat_kesulitan'] == 'Sangat Sulit' ? 'checked' : ''; ?>>
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
                              placeholder="Ceritakan tentang gunung ini, jalur pendakian, pemandangan, dll..."><?php echo htmlspecialchars($mountain['deskripsi']); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Gambar Gunung</label>
                    
                    <?php if($mountain['gambar']): ?>
                        <div class="current-image">
                            <div class="current-image-label">
                                <i class="fas fa-image"></i>
                                Gambar Saat Ini
                            </div>
                            <img src="../assets/images/<?php echo $mountain['gambar']; ?>" alt="Current Mountain Image">
                        </div>
                    <?php endif; ?>
                    
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file" id="gambar" name="gambar" class="file-upload-input" accept="image/*">
                        <div class="file-upload-content">
                            <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                            <div class="upload-text">
                                <?php echo $mountain['gambar'] ? 'Ganti gambar atau drag & drop di sini' : 'Klik atau drag & drop gambar di sini'; ?>
                            </div>
                            <div class="upload-subtext">JPG, PNG, GIF maksimal 5MB</div>
                            <?php if($mountain['gambar']): ?>
                                <div class="upload-subtext" style="margin-top: 0.5rem; color: #f39c12;">
                                    <i class="fas fa-info-circle"></i>
                                    Biarkan kosong jika tidak ingin mengubah gambar
                                </div>
                            <?php endif; ?>
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
                        <i class="fas fa-save"></i> Update Gunung
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

