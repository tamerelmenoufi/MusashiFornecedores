<?php
    require_once "../../../lib/config.php";

    global $pdo;

    $Y = $_POST['ano'];

    if($_POST['qav'] && ($_POST['qav'] > 0 || $_POST['qav'] != '')){
        $sql = $pdo->prepare("SELECT * FROM avaliacao_anual WHERE codigo_fornecedor = :cf AND ano = :y");
        $sql->bindValue(":cf", $_POST['codigo_fornecedor']);
        $sql->bindValue(":y", $Y);
        $sql->execute();

        if($sql->rowCount() > 0){
            $update = $pdo->prepare("UPDATE avaliacao_anual SET qav = :qv, qav_data = NOW() WHERE codigo_fornecedor = :cf
            AND ano = :y");
            $update->bindValue(":qv", $_POST['qav']);;
            $update->bindValue(":cf", $_POST['codigo_fornecedor']);
            $update->bindValue(":y", $Y);

            $update->execute();
        }
    }else{
        echo "Não encontrado";
    }
?>