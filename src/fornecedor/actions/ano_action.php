<?php
    require_once "../../../lib/config.php";
    
    global $pdo;
    
    $m = date("m", strtotime($_POST["data"]));
    $Y = date("Y", strtotime($_POST["data"]));

    $verify = $pdo->prepare("SELECT * FROM avaliacao_anual WHERE codigo_fornecedor = {$_POST['codigo_fornecedor']} AND ano = {$Y}");
    $verify->execute();

    if($verify->rowCount() == 0 and !$_POST['excluir']){
        $create_year = $pdo->prepare("INSERT INTO avaliacao_anual SET
        codigo_fornecedor = {$_POST['codigo_fornecedor']},
        ano = {$Y},
        eficiencia = 0,
        quality = 0,
        delivery = 0,
        classificacao = 0,
        posicao = 0,
        status = '0',
        qualificacao_ipf = 'OTIMO',
        qualificacao_iaf = 'OTIMO',
        qualificacao_iqf = 'OTIMO'
        ");

        $create_year->execute();

    }else{
        $ano = $verify->fetch();

        $update = $pdo->prepare("UPDATE avaliacao_anual SET status = '1' WHERE codigo = {$ano['codigo']}");
        $update->execute();

        $medias = $pdo->prepare("SELECT AVG(eficiencia) as eficiencia, AVG(delivery) as delivery, AVG(quality) as quality, (AVG(eficiencia) + AVG(delivery) + AVG(quality))/3 as classificacao FROM avaliacao_mensal 
        WHERE codigo_fornecedor = {$_POST['codigo_fornecedor']}
        AND ano = {$Y}
        AND status = 1");
    
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
    
        $update = $pdo->prepare("UPDATE avaliacao_anual SET
        codigo_fornecedor = {$_POST['codigo_fornecedor']},
        ano = {$Y},
        eficiencia = {$result['eficiencia']},
        quality = {$result['quality']},
        delivery = {$result['delivery']},
        classificacao = {$result['classificacao']},
        qualificacao_ipf = '{$q_ipf}',
        qualificacao_iqf = '{$q_iqf}',
        qualificacao_iaf = '{$q_iaf}'
        WHERE codigo_fornecedor = {$_POST['codigo_fornecedor']}
        AND ano = {$Y}");
    
        $update->execute();

        $max = $pdo->prepare("SELECT codigo, classificacao, ano, posicao FROM avaliacao_anual WHERE ano = {$Y} ORDER BY classificacao DESC");
        $max->execute();

        $p = 1;
        $pp = false;
        while($d = $max->fetch()){
            if($p == 1){
                $update = $pdo->prepare("UPDATE avaliacao_anual SET posicao = 1 WHERE codigo = :c");
                $update->bindValue(":c", $d['codigo']);
                $update->execute();

                $pp = $d['classificacao'];
            }

            if($d['classificacao'] == $pp){
                $update = $pdo->prepare("UPDATE avaliacao_anual SET posicao = 1 WHERE codigo = {$d['codigo']}");
                $update->execute();
            }else{
                $update = $pdo->prepare("UPDATE avaliacao_anual SET posicao = {$p} WHERE codigo = {$d['codigo']}");
                $update->execute();
            }
            $p++;
        }
    }
?>