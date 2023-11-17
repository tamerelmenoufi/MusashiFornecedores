<?php
    require_once "../../../lib/config.php";
    global $pdo;

    if(!isset($_POST['ano'])){
        $Y = date("Y");
    }else{
        $Y = $_POST['ano'];
    }

    if(!isset($_POST['mes'])){
        $M = date("m");
    }else{
        $M = $_POST['mes'];
    }

    $array_valores = [];
    $array_codigo = [];
    $array_cor = [];
    $array_border = [];

    $array_valores1 = [];
    $array_codigo1 = [];
    $array_cor1 = [];
    $array_border1 = [];

    for($i=11; $i>=0; $i--){

        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

        $q = "SELECT f.nome,
        f.codigo as fornecedor_codigo,
        ava.ano,
        /*ava.classificacao,*/
        ava.quality,
        ava.delivery,
        ((ava.quality+ava.delivery)/2) as classificacao,
        ava.posicao,
        (SELECT TIMESTAMPDIFF(MONTH,min(data_registro),NOW()) from registros_diarios where codigo_fornecedor = ava.codigo_fornecedor) as qt_meses

        FROM avaliacao_mensal ava
        LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
        WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' AND f.situacao = '1' AND f.deletado != '1' ORDER BY ava.classificacao DESC";

        $query = $pdo->prepare($q);
        $query->execute();

        if($query->rowCount() > 0){
            while($d = $query->fetch()) {

                $fornecedor[$d['fornecedor_codigo']] =  $fornecedor[$d['fornecedor_codigo']] + $d['classificacao'];
                $nome[$d['fornecedor_codigo']] = $d['nome'];
                $qt_meses[$d['fornecedor_codigo']] = $d['qt_meses'];
            }

            foreach($fornecedor as $ind => $valor){

                // $array_codigo[$d['fornecedor_codigo']] =  "'".$d['nome']/*str_pad($d['fornecedor_codigo'], 4, "0", STR_PAD_LEFT)*/."'";
                // $array_valores[$d['fornecedor_codigo']] = "'".$d['classificacao']."'";

                // if($d['classificacao'] < 84.99){
                //     $array_cor[$d['fornecedor_codigo']] = '"#dc3545"'; /// DEFICIENTE
                //     $array_border[$d['fornecedor_codigo']] = '"#dc3545"';
                // }elseif($d['classificacao'] > 84.99 && $d['classificacao'] < 93.99){///// REGULAR
                //     $array_cor[$d['fornecedor_codigo']] = '"#ffc107"';
                //     $array_border[$d['fornecedor_codigo']] = '"#ffc107"';
                // }elseif($d['classificacao'] > 93.99 && $d['classificacao'] < 98.99){ //// BOM
                //     $array_cor[$d['fornecedor_codigo']] = '"#007bff"';
                //     $array_border[$d['fornecedor_codigo']] = '"#6610f2"';
                // }elseif($d['classificacao'] > 98.99 && $d['classificacao'] <= 100.00){ ///OTIMO
                //     $array_cor[$d['fornecedor_codigo']] = '"#28a745"';
                //     $array_border[$d['fornecedor_codigo']] = '"#198754"';
                // }

                if($qt_meses[$ind] > 0){
                    $valor = number_format($valor/(($qt_meses[$ind] >= 12)?12:$qt_meses[$ind]),2);
                }else{
                    $valor = 100;
                }
                

                $array_codigo1[$ind] =  "'".$nome[$ind]/*str_pad($d['fornecedor_codigo'], 4, "0", STR_PAD_LEFT)*/."'";
                $array_valores1[$ind] = (($valor <= 100)?$valor:100.00);

                if($valor <= 84.99){
                    $array_cor1[$ind] = '"#dc3545"'; /// DEFICIENTE
                    $array_border1[$ind] = '"#dc3545"';
                }elseif($valor >= 85.00 && $valor <= 93.99){///// REGULAR
                    $array_cor1[$ind] = '"#ffc107"';
                    $array_border1[$ind] = '"#ffc107"';
                }elseif($valor >= 94.00 && $valor <= 98.99){ //// BOM
                    $array_cor1[$ind] = '"#007bff"';
                    $array_border1[$ind] = '"#6610f2"';
                }elseif($valor >= 99.00 && $valor <= 100.00){ ///OTIMO
                    $array_cor1[$ind] = '"#28a745"';
                    $array_border1[$ind] = '"#198754"';
                }

            }
        }
    }

    $Console[] = $array_codigo1;
    $Console[] = $array_valores1;
    $Console[] = $array_cor1;
    $Console[] = $array_border1;

    $Console = json_encode($Console);

    arsort($array_valores1);

    foreach($array_valores1 as $ind => $val){
        $array_valores[] = $array_valores1[$ind];
        $array_codigo[] =  $array_codigo1[$ind];
        $array_cor[] = $array_cor1[$ind];
        $array_border[] = $array_border1[$ind];
    }

?>


<canvas can id="chart_geral"></canvas>

<script>
    console.log('<?=$Console?>');
    var ctx10 = document.getElementById('chart_geral');
    var chart_ano = new Chart(ctx10, {
        type: 'bar',
        data: {
            labels: [
                <?=@implode(",", $array_codigo)?>
            ],
            datasets: [{
                label: ["GERAL (Q&D)"],
                backgroundColor: [<?=@implode(",", $array_cor)?>],
                borderColor: [<?=@implode(",", $array_border)?>],
                borderWidth: 1,
                data: [<?=@implode(",", $array_valores)?>],
                type: 'bar'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 110,
                },
                x: {
                    display: true,
                    offset: true,
                }
            }
        }
    });
</script>
