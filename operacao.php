<?php
    require "./lib/config.php";


    $sql = $pdo->prepare("SELECT * FROM `registros_diarios` order by data_registro asc");
    $sql->execute();
    while($d = $sql->fetch()){
        echo $d['codigo_fornecedor'].' - '.$d['data_registro']."<br>";
    }


?>
