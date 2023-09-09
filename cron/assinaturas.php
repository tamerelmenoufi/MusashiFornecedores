<?php
    require "../lib/config.php";

    global $pdo;

    $query = "select *, min(ordem) as maximo from assinaturas where status != '1' group by codigo_avaliacao_mensal, doc order by codigo asc";