<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=vivegope_bd', 'root', '9210292Dc#BM');
    echo "DB Connected successfully\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(", ", $tables) . "\n";
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
