<?php
$page_title = "Detail Gunung - Pegunungan Indonesia";
include 'includes/header.php';
include 'config/database.php';

$mountain = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM mountains WHERE id = ?");
    $stmt->execute([$id]);
    $mountain = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($mountain) {
        $page_title = $mountain['nama_gunung'] . " - Pegunungan Indonesia";
    }
}

if (!$mountain) {
    header('Location: mountains.php');
    exit;
}
?>

<main class="main-content">
    <div class="container">
        <nav style="margin: 1rem 0;">
            <a href="mountains.php" class="btn" style="color: white;">← Kembali ke Daftar</a>
        </nav>

        <div class="detail-container">
            <div class="detail-image">
                <?php if($mountain['gambar']): ?>
                    <img src="assets/images/<?php echo $mountain['gambar']; ?>" alt="<?php echo $mountain['nama_gunung']; ?>">
                <?php else: ?>
                    <div class="no-image">
                        <i class="fas fa-mountain"></i>
                        <p>Tidak ada gambar</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="detail-content">
                <h1><?php echo htmlspecialchars($mountain['nama_gunung']); ?></h1>
                
                <div class="detail-info">
                    <div class="info-item">
                        <i class="fas fa-mountain"></i>
                        <span><strong>Tinggi:</strong> <?php echo number_format($mountain['tinggi']); ?> mdpl</span>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong>Lokasi:</strong> <?php echo htmlspecialchars($mountain['lokasi']); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-map"></i>
                        <span><strong>Provinsi:</strong> <?php echo htmlspecialchars($mountain['provinsi']); ?></span>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-signal"></i>
                        <span><strong>Tingkat Kesulitan:</strong> 
                            <span class="badge <?php 
                                echo match($mountain['tingkat_kesulitan']) {
                                    'Mudah' => 'badge-success',
                                    'Sedang' => 'badge-warning',
                                    'Sulit' => 'badge-danger',
                                    'Sangat Sulit' => 'badge-dark'
                                };
                            ?>">
                                <?php echo $mountain['tingkat_kesulitan']; ?>
                            </span>
                        </span>
                    </div>
                </div>

                <?php if($mountain['deskripsi']): ?>
                    <div class="description">
                        <h3>Deskripsi</h3>
                        <p><?php echo nl2br(htmlspecialchars($mountain['deskripsi'])); ?></p>
                    </div>
                <?php endif; ?>

                <div class="detail-actions">
                    <a href="admin/edit.php?id=<?php echo $mountain['id']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="admin/delete.php?id=<?php echo $mountain['id']; ?>" class="btn btn-danger" 
                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    margin: 2rem 0;
}

.detail-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.no-image {
    width: 100%;
    height: 400px;
    background: #f8f9fa;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.no-image i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.detail-content h1 {
    color:rgb(252, 245, 245);
    margin-bottom: 2rem;
}

.detail-info {
    margin: 2rem 0;
}

.info-item {
    display: flex;
    align-items: center;
    margin: 1rem 0;
    font-size: 1.1rem;
}

.info-item i {
    width: 30px;
    color:rgb(255, 255, 255);
    margin-right: 1rem;
}
.info-item span {
    color: white;
   
}


.description {
    margin: 2rem 0;
}

.description h3 {
    color:rgb(243, 243, 243);
    margin-bottom: 1rem;
}

.description p {
    color: white;
    line-height: 1.8;
    font-size: 1.1rem;
}

.detail-actions {
    margin-top: 3rem;
    display: flex;
    gap: 1rem;
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-weight: bold;
}

.badge-success { background: #27ae60; color: white; }
.badge-warning { background: #f39c12; color: white; }
.badge-danger { background: #e74c3c; color: white; }
.badge-dark { background: #2c3e50; color: white; }

@media (max-width: 768px) {
    .detail-container {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .detail-actions {
        flex-direction: column;
    }
}
</style>

<?php include 'includes/footer.php'; ?>