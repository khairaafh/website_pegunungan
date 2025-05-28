<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Pegunungan Indonesia'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo" style="text-decoration: none;">
                <h2 style="font-style: italic;"><i class="fas fa-mountain"></i> Mountnesia</h2>
            </a>
            <div class="nav-menu">
                <a href="index.php" class="nav-link">Beranda</a>
                <a href="mountains.php" class="nav-link">Daftar Gunung</a>
                <a href="about.php" class="nav-link">Tentang</a>
            </div>
        </div>
    </nav>