<?php
    require "../lib/config.php";

    global $pdo;

    $sqlCron = $pdo->prepare("SELECT * FROM `registros_diarios` order by data_registro asc limit 5");
    $sqlCron->execute();
    while($dCron = $sqlCron->fetch()){
        set_time_limit(90);
        echo "---------------------------------------------------------------------------------";
        echo $dCron['codigo_fornecedor'].' - '.$dCron['data_registro']."<br>";
        include('mes_action.php');
        echo "---------------------------------------------------------------------------------";
    }

?>