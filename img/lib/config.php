<?php
    session_start();
    // error_reporting(0);

    global $pdo;

    try {
        if($_SERVER['HTTP_HOST'] === 'localhost'){
            $pdo= new PDO("mysql:dbname=musashi_painel;host=localhost", "root", "");
        }else{
            $pdo= new PDO("mysql:dbname=musashi;host=3.93.179.81", "musashi", "wu5@sh!");
        }

    }catch(PDOException $e) {
        echo "FALHOU:".$e->getMessage();
        exit;
    }

    if(isset($_SESSION['musashi_cod_usu'])){
        $sql = $pdo->prepare("SELECT * FROM login WHERE codigo = :u");
        $sql->bindValue(":u", $_SESSION['musashi_cod_usu']);
        $sql->execute();

        $ConfUsu = $sql->fetch();
    }
?>