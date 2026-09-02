<?php

try {
    
    $dbPath = __DIR__ . '\baseTigo'; 
    
    if (!file_exists($dbPath)) {
        die("❌ Error: El archivo de base de datos no existe en la ruta: " . $dbPath);
    }
    
    $pdo = new PDO('sqlite:' . $dbPath);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec('PRAGMA foreign_keys = ON');

} catch (PDOException $e) {
    die("❌ Error de conexión a SQLite: " . $e->getMessage());
}
?>