<?php
error_reporting(E_ALL);
try {
	$pdo = new PDO('mysql:host=localhost;dbname=noticias', 'root', '12345', []);
} catch (PDOException $e) {
    echo '<h1>Não foi possível conectar ao banco!!</h1>' . $e->getMessage();
    exit();
}
?>