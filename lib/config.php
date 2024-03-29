<?php
session_start();

function Blq(){
    echo '
        <style>
            .blq{
                position:fixed;
                top:0;
                bottom:0;
                background:#eee;
                width:100%;
                text-align:center;
            }
            .blq h1{
                margin-top:10%;
            }
        </style>
        <div class="blq"><h1>SISTEMA EM MANUTENÇÃO</h1></div>
    ';

}

function utf8decode($v){
    return $v;
}

// Blq(); exit();

global $pdo;

date_default_timezone_set('America/Manaus');

try {
    if ($_SERVER['HTTP_HOST'] === 'localhost') {
        $pdo = new PDO("mysql:dbname=musashi_painel;host=localhost", "root", "");
    } else {
        $pdo = new PDO("mysql:dbname=musashi;host=34.239.130.95", "musashi", "wu5@sh!", array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
          ));
    }

} catch (PDOException $e) {
    echo "FALHOU:" . $e->getMessage();
    exit;
}


if (!$_SESSION['musashi_cod_forn'] and !$_SESSION['musashi_cod_usu'] and !$home) {
    echo "<script>window.location.href='./'</script>";
    exit();
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