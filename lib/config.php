<?php
session_start();

global $pdo;

try {
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
        $pdo = new PDO("mysql:dbname=musashi_painel;host=localhost", "root", "");
    } else {
        $pdo = new PDO("mysql:dbname=musashi;host=34.239.130.95", "musashi", "wu5@sh!");
    }

} catch (PDOException $e) {
    echo "FALHOU:" . $e->getMessage();
    exit;
}

if (isset($_SESSION['musashi_cod_usu'])) {
    $sql = $pdo->prepare("SELECT * FROM login WHERE codigo = :u");
    $sql->bindValue(":u", $_SESSION['musashi_cod_usu']);
    $sql->execute();

    $ConfUsu = $sql->fetch();

} elseif (isset($_SESSION['musashi_cod_forn'])) {
    $sql = $pdo->prepare("SELECT nome FROM fornecedores WHERE codigo = :u");
    $sql->bindValue(":u", $_SESSION['musashi_cod_forn']);
    $sql->execute();

    $ConfForn = $sql->fetch();
}
?>