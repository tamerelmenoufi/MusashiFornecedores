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

    for($i=11; $i>=0; $i--){

        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

        $query = $pdo->prepare("SELECT f.nome,
        f.codigo as fornecedor_codigo,
        ava.ano,
        /*ava.classificacao,*/
        ava.quality,
        ava.delivery,
        ((ava.quality+ava.delivery)/2) as classificacao,
        ava.posicao
        FROM avaliacao_mensal ava
        LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
        WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' ORDER BY ava.classificacao DESC");
        $query->execute();

        if($query->rowCount() > 0){
            while($d = $query->fetch()) {

                $fornecedor[$d['fornecedor_codigo']] =  $fornecedor[$d['fornecedor_codigo']] + $d['classificacao'];
                $nome[$d['fornecedor_codigo']] = $d['nome'];
            }

            usort($fornecedor);
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

                $valor = number_format($valor/12,2);
                $array_codigo[$ind] =  "'".$nome[$ind]/*str_pad($d['fornecedor_codigo'], 4, "0", STR_PAD_LEFT)*/."'";
                $array_valores[$ind] = "'".$valor."'";

                if($valor < 84.99){
                    $array_cor[$ind] = '"#dc3545"'; /// DEFICIENTE
                    $array_border[$ind] = '"#dc3545"';
                }elseif($valor > 84.99 && $valor < 93.99){///// REGULAR
                    $array_cor[$ind] = '"#ffc107"';
                    $array_border[$ind] = '"#ffc107"';
                }elseif($valor > 93.99 && $valor < 98.99){ //// BOM
                    $array_cor[$ind] = '"#007bff"';
                    $array_border[$ind] = '"#6610f2"';
                }elseif($valor > 98.99 && $valor <= 100.00){ ///OTIMO
                    $array_cor[$ind] = '"#28a745"';
                    $array_border[$ind] = '"#198754"';
                }

            }
        }
    }
?>


<canvas can id="chart_geral"></canvas>

<script>
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
