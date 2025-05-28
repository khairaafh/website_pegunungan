<?php
$page_title = "Beranda - Pegunungan Indonesia";
include 'includes/header.php';
include 'config/database.php';

// Ambil 3 gunung terpopuler
$stmt = $pdo->query("SELECT * FROM mountains ORDER BY tinggi DESC LIMIT 3");
$featured_mountains = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil semua gunung untuk section daftar
$stmt_all = $pdo->query("SELECT * FROM mountains ORDER BY nama_gunung ASC");
$all_mountains = $stmt_all->fetchAll(PDO::FETCH_ASSOC);

// Hitung statistik
$total_mountains = count($all_mountains);
$highest_mountain = $pdo->query("SELECT MAX(tinggi) as max_height FROM mountains")->fetch();
$provinces_count = $pdo->query("SELECT COUNT(DISTINCT provinsi) as province_count FROM mountains")->fetch();
?>

<main class="main-content">
    <section class="hero">
        <!-- Background Image Slider -->
        <div class="hero-slider">
            <div class="hero-slide active" style="background-image: url('assets/images/gunung1.jpg')"></div>
            <div class="hero-slide" style="background-image: url('assets/images/gunung2.jpg')"></div>
            <div class="hero-slide" style="background-image: url('assets/images/gunung3.jpg')"></div>
            <div class="hero-slide" style="background-image: url('assets/images/gunung4.jpg')"></div>
            <div class="hero-slide" style="background-image: url('assets/images/gunung5.jpg')"></div>
        </div>

        <!-- Navigation Arrows -->
        <div class="hero-nav prev">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="hero-nav next">
            <i class="fas fa-chevron-right"></i>
        </div>

        <!-- Content -->
        <div class="hero-content">
            <h1><i class="fas fa-mountain"></i> Jelajahi Pegunungan Indonesia</h1>
            <p>Temukan keindahan dan informasi lengkap tentang gunung-gunung terbaik di Indonesia yang menakjubkan. Dari puncak tertinggi hingga pemandangan terindah.</p>
            <a href="mountains.php" class="hero-btn">
                <i class="fas fa-mountain"></i>
                Mulai Penjelajahan
            </a>
        </div>

        <!-- Slide Indicators -->
        <div class="hero-indicators">
            <div class="hero-indicator active" onclick="currentSlide(1)"></div>
            <div class="hero-indicator" onclick="currentSlide(2)"></div>
            <div class="hero-indicator" onclick="currentSlide(3)"></div>
            <div class="hero-indicator" onclick="currentSlide(4)"></div>
            <div class="hero-indicator" onclick="currentSlide(5)"></div>
        </div>
    </section>

    <div class="container">
        <!-- Statistics Section -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_mountains; ?></div>
                    <div class="stat-label">Total Gunung</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-number"><?php echo number_format($highest_mountain['max_height']); ?></div>
                    <div class="stat-label">Tertinggi (mdpl)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="stat-number"><?php echo $provinces_count['province_count']; ?></div>
                    <div class="stat-label">Provinsi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-hiking"></i>
                    </div>
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Jalur Pendakian</div>
                </div>
            </div>
        </section>

        <!-- Featured Mountains Section -->
        <section class="featured-section">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                Gunung Tertinggi di Indonesia
            </h2>
            <div class="card-grid">
                <?php foreach($featured_mountains as $index => $mountain): ?>
                    <div class="card">
                        <div class="card-image-container">
                            <img src="assets/images/<?php echo $mountain['gambar']; ?>" alt="<?php echo $mountain['nama_gunung']; ?>">
                            <div class="card-badge">
                                <i class="fas fa-medal"></i>
                                #<?php echo $index + 1; ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3>
                                <i class="fas fa-mountain"></i>
                                <?php echo $mountain['nama_gunung']; ?>
                            </h3>
                            <div class="card-meta">
                                <div class="meta-item">
                                    <i class="fas fa-ruler-vertical"></i>
                                    <?php echo number_format($mountain['tinggi']); ?> mdpl
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo $mountain['provinsi']; ?>
                                </div>
                            </div>
                            <div class="difficulty-badge difficulty-<?php echo strtolower(str_replace(' ', '-', $mountain['tingkat_kesulitan'])); ?>">
                                <i class="fas fa-signal"></i>
                                <?php echo $mountain['tingkat_kesulitan']; ?>
                            </div>
                            <p><?php echo substr($mountain['deskripsi'], 0, 120); ?>...</p>
                            <div class="btn-group">
                                <a href="detail.php?id=<?php echo $mountain['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-eye"></i>
                                    Lihat Detail
                                </a>
                              
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- All Mountains List Section -->
        <section class="mountains-list-section">
            <h2 class="section-title">
                <i class="fas fa-list"></i>
                Daftar Lengkap Gunung Indonesia
            </h2>
            
        

            <!-- Mountains Grid -->
            <div class="mountains-grid" id="mountainsGrid">
                <?php foreach($all_mountains as $mountain): ?>
                    <div class="mountain-item" data-name="<?php echo strtolower($mountain['nama_gunung']); ?>" 
                         data-province="<?php echo $mountain['provinsi']; ?>" 
                         data-difficulty="<?php echo $mountain['tingkat_kesulitan']; ?>">
                        <div class="mountain-card">
                            <div class="mountain-image">
                                <img src="assets/images/<?php echo $mountain['gambar']; ?>" alt="<?php echo $mountain['nama_gunung']; ?>">
                                <div class="mountain-overlay">
                                    <div class="mountain-height">
                                        <i class="fas fa-mountain"></i>
                                        <?php echo number_format($mountain['tinggi']); ?> mdpl
                                    </div>
                                </div>
                            </div>
                            <div class="mountain-info">
                                <h3><?php echo $mountain['nama_gunung']; ?></h3>
                                <div class="mountain-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo $mountain['lokasi']; ?>, <?php echo $mountain['provinsi']; ?>
                                </div>
                                <div class="mountain-difficulty">
                                    <span class="difficulty-badge difficulty-<?php echo strtolower(str_replace(' ', '-', $mountain['tingkat_kesulitan'])); ?>">
                                        <i class="fas fa-signal"></i>
                                        <?php echo $mountain['tingkat_kesulitan']; ?>
                                    </span>
                                </div>
                                <p class="mountain-description" style="color: black;">
                                    <?php echo substr($mountain['deskripsi'], 0, 100); ?>...
                                </p>
                                <div class="mountain-actions">
                                    <a href="detail.php?id=<?php echo $mountain['id']; ?>" class="btn btn-primary btn-detail">
                                        <i class="fas fa-info-circle"></i>
                                        Detail
                                    </a>
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="no-results" style="display: none;">
                <div class="no-results-content">
                    <i class="fas fa-search"></i>
                    <h3>Tidak ada gunung yang ditemukan</h3>
                    <p>Coba ubah kata kunci pencarian atau filter yang digunakan.</p>
                </div>
            </div>
        </section>

        <!-- Info Section -->
        <section class="info-section">
            <div class="info-container">
                <div class="info-content">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Tentang Pegunungan Indonesia
                    </h2>
                    <p>
                        Indonesia memiliki lebih dari 400 gunung berapi, menjadikannya salah satu negara dengan aktivitas vulkanik tertinggi di dunia. 
                        Pegunungan di Indonesia tidak hanya menawarkan keindahan alam yang memukau, tetapi juga kekayaan biodiversitas yang luar biasa.
                    </p>
                    <p>
                        Website ini menyediakan informasi lengkap tentang berbagai gunung di Indonesia, mulai dari data geografis, 
                        tingkat kesulitan pendakian, hingga tips dan panduan untuk para pendaki. Anda juga dapat mengelola data gunung 
                        melalui sistem CRUD yang tersedia.
                    </p>
                    <div class="info-features">
                        <div class="feature-item">
                            <i class="fas fa-database"></i>
                            <span>Database Lengkap</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-search"></i>
                            <span>Pencarian Mudah</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-edit"></i>
                            <span>Kelola Data</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Mobile Friendly</span>
                        </div>
                    </div>
                    <div class="info-actions">
                        <a href="mountains.php" class="btn btn-primary">
                            <i class="fas fa-list"></i> 
                            Lihat Semua Gunung
                        </a>
                        <a href="add.php" class="btn btn-success">
                            <i class="fas fa-plus"></i> 
                            Tambah Gunung Baru
                        </a>
                    </div>
                </div>
                <div class="info-image">
                    <img src="assets/images/indonesia-mountains.jpg" alt="Pegunungan Indonesia">
                </div>
            </div>
        </section>
    </div>
</main>


<?php include 'includes/footer.php'; ?>