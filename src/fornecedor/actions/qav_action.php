<?php
    require_once "../../../lib/config.php";

    global $pdo;

    $Y = $_POST['ano'];
    $M = $_POST['mes'];

    if($_POST['qav'] && ($_POST['qav'] > 0 || $_POST['qav'] != '')){
        $sql = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = :cf AND ano = :y AND mes = :m");

        echo "SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = {$_POST['codigo_fornecedor']} AND ano = {$Y} AND mes = {$M}";
        
        $sql->bindValue(":cf", $_POST['codigo_fornecedor']);
        $sql->bindValue(":y", $Y);
        $sql->bindValue(":m", $M);
        $sql->execute();

        if($sql->rowCount() > 0){
            $update = $pdo->prepare("UPDATE avaliacao_mensal SET qav = :qv, qav_data = NOW() WHERE codigo_fornecedor = :cf
            AND ano = :y
            AND mes = :m");
            $update->bindValue(":qv", $_POST['qav']);;
            $update->bindValue(":cf", $_POST['codigo_fornecedor']);
            $update->bindValue(":y", $Y);
            $update->bindValue(":m", $M);

            echo "UPDATE avaliacao_mensal SET qav = {$_POST['qav']}, qav_data = NOW() WHERE codigo_fornecedor = {$_POST['codigo_fornecedor']}
            AND ano = {$Y}
            AND mes = {$M}";


            $update->execute();
        }
    }else{
        echo "Não encontrado";
    }
?>