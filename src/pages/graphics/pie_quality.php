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

        // echo "SELECT
        // count(CASE WHEN ava.qualificacao_iqf = 'OTIMO' THEN 1 ELSE NULL END) as otimo,
        // count(CASE WHEN ava.qualificacao_iqf = 'BOM' THEN 1 ELSE NULL END) as bom,
        // count(CASE WHEN ava.qualificacao_iqf = 'REGULAR' THEN 1 ELSE NULL END) as regular,
        // count(CASE WHEN ava.qualificacao_iqf = 'DEFICIENTE' THEN 1 ELSE NULL END) as deficiente
        // FROM avaliacao_mensal ava
        // WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' ORDER BY ava.classificacao DESC<br>";

        $query = $pdo->prepare("SELECT
        count(CASE WHEN ava.qualificacao_iqf = 'OTIMO' THEN 1 ELSE NULL END) as otimo,
        count(CASE WHEN ava.qualificacao_iqf = 'BOM' THEN 1 ELSE NULL END) as bom,
        count(CASE WHEN ava.qualificacao_iqf = 'REGULAR' THEN 1 ELSE NULL END) as regular,
        count(CASE WHEN ava.qualificacao_iqf = 'DEFICIENTE' THEN 1 ELSE NULL END) as deficiente
        FROM avaliacao_mensal ava
        WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' ORDER BY ava.classificacao DESC");
    $query = $pdo->prepare("SELECT f.nome,
    f.codigo as fornecedor_codigo,
    ava.ano,
    /*ava.classificacao,*/
    ava.quality,
    ava.delivery,
    ((ava.quality+ava.delivery)/2) as classificacao,
    ava.posicao,
    (SELECT TIMESTAMPDIFF( MONTH, concat ( YEAR(min(data_registro)) , '-' , MONTH(min(data_registro)), '-01' ), concat ( YEAR(NOW()) , '-' , MONTH(NOW()), '-01' )) from registros_diarios where codigo_fornecedor = ava.codigo_fornecedor) as qt_meses
    FROM avaliacao_mensal ava
    LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
    WHERE ava.ano = '{$Ano}' AND ava.mes = '{$Mes}' AND f.situacao = '1' AND f.deletado != '1' group by ava.codigo_fornecedor");
    $query->execute();

    while($d = $query->fetch()) {

        $fornecedor[$d['fornecedor_codigo']] =  $fornecedor[$d['fornecedor_codigo']] + $d['quality'];
        $nome[$d['fornecedor_codigo']] = $d['nome'];
        // $qt_meses[$d['fornecedor_codigo']] = ($d['qt_meses']);
        $qt_meses[$d['fornecedor_codigo']]++;
        // echo "<hr>";
        // $d = $query->fetch();
        // $array_valores[0] = $array_valores[0] + $d['otimo'];
        // $array_valores[1] = $array_valores[1] + $d['bom'];
        // $array_valores[2] = $array_valores[2] + $d['regular'];
        // $array_valores[3] = $array_valores[3] + $d['deficiente'];

    }
}

$array_valores = [
    0 => 0,
    1 => 0,
    2 => 0,
    3 => 0,
];
foreach($fornecedor as $ind => $valor){

    $n =  $nome[$ind];
    $classificacao = number_format($valor/(($qt_meses[$ind] >= 12)?12:(($qt_meses[$ind])?:1)),2);
    if($classificacao > 100) $classificacao = 100.00;

    if($classificacao <= 77.99){
        $array_valores[3]++;
    }elseif($classificacao >= 78.00 && $classificacao <= 91.99){ ///// REGULAR
        $array_valores[2]++;
    }elseif($classificacao >= 92.00 && $classificacao <= 98.99){ //// BOM
        $array_valores[1]++;
    }elseif($classificacao >= 99.00 && $classificacao <= 100.00){ /// OTIMO
        $array_valores[0]++;
    }


}

?>

<canvas can style="" id="pie_quality"></canvas>

<script>
    var ctx10 = document.getElementById('pie_quality');
    var chart_ano = new Chart(ctx10, {
        type: 'pie',
        data: {
            labels: [
                'OTIMO',
                'BOM',
                'REGULAR',
                'DEFICIENTE'
            ],
            datasets: [{
                label: [
                    'OTIMO',
                    'BOM',
                    'REGULAR',
                    'DEFICIENTE'
                ],
                backgroundColor: ['#198754', '#007bff', '#ffc107', '#dc3545'],
                borderColor: ['#198754', '#007bff', '#ffc107', '#dc3545'],
                borderWidth: 1,
                data: [<?=@implode(",", $array_valores)?>],
                type: 'pie'
            }]
        },
        options: {
            responsive: true,
        },
    });
</script>
