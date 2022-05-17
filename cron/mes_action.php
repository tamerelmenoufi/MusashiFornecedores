<?php

    $m = date("m", strtotime($dCron['data_registro']));
    $Y = date("Y", strtotime($dCron['data_registro']));

    $btw1 = date("Y-m-d", mktime(0, 0, 0, $m, 1, $Y));
    $btw2 =  date("Y-m-d", mktime(0, 0, 0, $m-11, 1, $Y));
    //Dados do intervalo das datas

    echo "<hr>";

    echo $v = "SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = {$dCron['codigo_fornecedor']}
    AND mes = {$m} AND ano = {$Y}";

    $verify = $pdo->prepare($v);
    $verify->execute();

    if($verify->rowCount() == 0){

    echo "<hr>";

        echo $v  = "INSERT INTO avaliacao_mensal SET
        codigo_fornecedor = {$dCron['codigo_fornecedor']},
        mes = {$m},
        ano = {$Y},
        eficiencia = 0,
        quality = 0,
        delivery = 0,
        classificacao = 0,
        posicao = 0,
        status = '0',
        qualificacao_ipf = 'OTIMO',
        qualificacao_iaf = 'OTIMO',
        qualificacao_iqf = 'OTIMO'";

        $create_month = $pdo->prepare($v);

        $create_month->execute();


        $verify->execute();

        $mes = $verify->fetch();

    }else{

        $mes = $verify->fetch();
    }

        echo "<hr>";

        echo $v = "UPDATE avaliacao_mensal SET status = '1' WHERE codigo = {$mes['codigo']}";
        $update = $pdo->prepare($v);
        $update->execute();

        echo "<hr>";

        echo $v = "SELECT
                AVG(eficiencia) as eficiencia,
                AVG(100 - total_demerito_delivery) as delivery,
                AVG(100 - total_demerito_quality) as quality,
                (AVG(eficiencia) + AVG(100 - total_demerito_delivery) + AVG(100 - total_demerito_quality))/3 as classificacao
        FROM registros_diarios
        WHERE codigo_fornecedor = {$dCron['codigo_fornecedor']} AND status = 1 AND data_registro between '{$btw2}' AND '{$btw1}'";

        $medias = $pdo->prepare($v);

        $medias->execute();

        $result = $medias->fetch();

        if($result['classificacao'] < 84.99){
            $q_ipf = 'DEFICIENTE';
        }elseif($result['classificacao'] > 84.99 && $result['classificacao'] < 93.99){
            $q_ipf = 'REGULAR';
        }elseif($result['classificacao'] > 93.99 && $result['classificacao'] < 98.99){
            $q_ipf = 'BOM';
        }elseif($result['classificacao'] > 98.99 && $result['classificacao'] <= 100.00){
            $q_ipf = 'OTIMO';
        }

        if($result['quality'] < 77.99){
            $q_iqf = 'DEFICIENTE';
        }elseif($result['quality'] > 77.99 && $result['quality'] < 91.99){
            $q_iqf = 'REGULAR';
        }elseif($result['quality'] > 91.99 && $result['quality'] < 98.99){
            $q_iqf = 'BOM';
        }elseif($result['quality'] > 98.99 && $result['quality'] <= 100.00){
            $q_iqf = 'OTIMO';
        }

        if($result['delivery'] < 91.99){
            $q_iaf = 'DEFICIENTE';
        }elseif($result['delivery'] > 91.99 && $result['delivery'] < 95.99){
            $q_iaf = 'REGULAR';
        }elseif($result['delivery'] > 95.99 && $result['delivery'] < 98.99){
            $q_iaf = 'BOM';
        }elseif($result['delivery'] > 98.99 && $result['delivery'] <= 100.00){
            $q_iaf = 'OTIMO';
        }

        echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET
        codigo_fornecedor = {$dCron['codigo_fornecedor']},
        mes = {$m},
        ano = {$Y},
        eficiencia = {$result['eficiencia']},
        quality = {$result['quality']},
        delivery = {$result['delivery']},
        classificacao = {$result['classificacao']},
        qualificacao_ipf = '{$q_ipf}',
        qualificacao_iqf = '{$q_iqf}',
        qualificacao_iaf = '{$q_iaf}'
        WHERE codigo_fornecedor = {$dCron['codigo_fornecedor']}
        AND mes = {$m} AND ano = {$Y}";

        $update = $pdo->prepare($v);

        $update->execute();

        //Inclusão das posições Geral

        echo "<hr>";

        echo $v  = "SELECT codigo, classificacao, ano, mes, posicao FROM avaliacao_mensal
        WHERE mes = {$m} AND ano = {$Y} ORDER BY classificacao DESC";

        $max = $pdo->prepare($v);
        $max->execute();

        $p = 1;
        $pp = 0;
        while($d = $max->fetch()){
            if($p == 1){

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao = 1 WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();

                $pp = $d['classificacao'];
            }

            if($d['classificacao'] == $pp){

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao = 1 WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();
            }else{

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao = {$p} WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();
            }
            $p++;
        }

        //Inclusão das posições Quality
        $max = $pdo->prepare("SELECT codigo, quality, ano, mes, posicao_quality FROM avaliacao_mensal
        WHERE mes = {$m} AND ano = {$Y} ORDER BY quality DESC");
        $max->execute();

        $pQ = 1;
        $ppQ = 0;
        while($d = $max->fetch()){
            if($pQ == 1){

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao_quality = 1 WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();

                $ppQ = $d['quality'];
            }

            if($d['quality'] == $ppQ){

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao_quality = 1 WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();
            }else{

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao_quality = {$pQ} WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();
            }
            $pQ++;
        }


        //Inclusão das posições Delivery

        echo "<hr>";

        echo $v  = "SELECT codigo, delivery, ano, mes, posicao_delivery FROM avaliacao_mensal
        WHERE mes = {$m} AND ano = {$Y} ORDER BY delivery DESC";

        $max = $pdo->prepare($v);
        $max->execute();

        $pD = 1;
        $ppD = 0;
        while($d = $max->fetch()){
            if($pD == 1){

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao_delivery = 1 WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();

                $ppD = $d['delivery'];
            }

            if($d['delivery'] == $ppD){

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao_delivery = 1 WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();
            }else{

                echo "<hr>";

        echo $v  = "UPDATE avaliacao_mensal SET posicao_delivery = {$pD} WHERE codigo = {$d['codigo']}";

                $update = $pdo->prepare($v);
                $update->execute();
            }
            $pD++;
        }

?>