<?php
$page_title = "Daftar Gunung - Pegunungan Indonesia";
include 'includes/header.php';
include 'config/database.php';

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where_clause = '';
$params = [];

if($search) {
    $where_clause = "WHERE nama_gunung LIKE ? OR lokasi LIKE ? OR provinsi LIKE ?";
    $params = ["%$search%", "%$search%", "%$search%"];
}

$stmt = $pdo->prepare("SELECT * FROM mountains $where_clause ORDER BY nama_gunung ASC");
$stmt->execute($params);
$mountains = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="main-content">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0;">
            <h1 style="color: white;">Daftar Gunung Indonesia</h1>
            <a href="admin/create.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Gunung
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" style="margin-bottom: 2rem;">
            <div style="display: flex; gap: 1rem;">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Cari gunung..." class="form-control" style="flex: 1;">
                <button type="submit" class="btn">
                    <i class="fas fa-search"></i> Cari
                </button>
                <?php if($search): ?>
                    <a href="mountains.php" class="btn btn-warning">Reset</a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Mountains Table -->
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Gunung</th>
                        <th>Tinggi (mdpl)</th>
                        <th>Lokasi</th>
                        <th>Provinsi</th>
                        <th>Tingkat Kesulitan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($mountains) > 0): ?>
                        <?php foreach($mountains as $index => $mountain): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($mountain['nama_gunung']); ?></td>
                                <td><?php echo number_format($mountain['tinggi']); ?></td>
                                <td><?php echo htmlspecialchars($mountain['lokasi']); ?></td>
                                <td><?php echo htmlspecialchars($mountain['provinsi']); ?></td>
                                <td>
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
                                </td>
                                <td>
                                    <a href="detail.php?id=<?php echo $mountain['id']; ?>" class="btn btn-sm">Detail</a>
                                    <a href="admin/edit.php?id=<?php echo $mountain['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="admin/delete.php?id=<?php echo $mountain['id']; ?>" class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">
                                <?php echo $search ? "Tidak ada hasil untuk pencarian '$search'" : "Belum ada data gunung"; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<style>
.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.875rem;
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: bold;
}

.badge-success { background: #27ae60; color: white; }
.badge-warning { background: #f39c12; color: white; }
.badge-danger { background: #e74c3c; color: white; }
.badge-dark { background: #2c3e50; color: white; }
</style>

<?php include 'includes/footer.php'; ?>