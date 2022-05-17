<?php
    require "../lib/config.php";

    global $pdo;

    $sqlCron = $pdo->prepare("SELECT * FROM `registros_diarios` order by data_registro asc limit 2");
    $sqlCron->execute();
    while($dCron = $sqlCron->fetch()){
        echo $dCron['codigo_fornecedor'].' - '.$dCron['data_registro']."<br>";
        include('mes_action.php');
    }

?>