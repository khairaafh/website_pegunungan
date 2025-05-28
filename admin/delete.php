<?php
include '../config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        // Get image filename before deleting
        $stmt = $pdo->prepare("SELECT gambar FROM mountains WHERE id = ?");
        $stmt->execute([$id]);
        $mountain = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete the record
        $stmt = $pdo->prepare("DELETE FROM mountains WHERE id = ?");
        $stmt->execute([$id]);
        
        // Delete image file if exists
        if ($mountain && $mountain['gambar'] && file_exists('../assets/images/' . $mountain['gambar'])) {
            unlink('../assets/images/' . $mountain['gambar']);
        }
        
        header('Location: ../mountains.php?message=Data berhasil dihapus');
    } catch(PDOException $e) {
        header('Location: ../mountains.php?error=Gagal menghapus data: ' . $e->getMessage());
    }
} else {
    header('Location: ../mountains.php');
}
exit;
?>