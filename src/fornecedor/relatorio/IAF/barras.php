<?php
    require_once "../../../../lib/config.php";
    global $pdo;

    if(isset($_POST['tipo_relatorio'])){
        $tipo_relatorio = $_POST['tipo_relatorio'];
    }else{
        $tipo_relatorio = "";
    }
    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }

    if(isset($_POST['mes'])){
        $M = str_pad($_POST['mes'], 2, "0", STR_PAD_LEFT);
    }else{
        $M = date("m");
    }

    function mesExtenso($mes){
        switch ($mes) {
            case '1':
                return 'Jan';
                break;
            case '2':
                return 'Fev';
                break;
            case '3':
                return 'Mar';
                break;
            case '4':
                return 'Abr';
                break;
            case '5':
                return 'Mai';
                break;
            case '6':
                return 'Jun';
                break;
            case '7':
                return 'Jul';
                break;
            case '8':
                return 'Ago';
                break;
            case '9':
                return 'Set';
                break;
            case '10':
                return 'Out';
                break;
            case '11':
                return 'Nov';
                break;
            case '12':
                return 'Dez';
                break;
        }
    }


    // $mes_atual = date("m", mktime(0, 0, 0, ($M-12), date('d'), $Y));
    // $ano_atual = date("Y", mktime(0, 0, 0, ($M-12), date('d'), $Y));

    // //$mes_atual = date("m");
    // $total_dias_mes = date("t");

    // function diasDoMes(){
    //     $data_inicio = mktime(0, 0, 0, date('m'), 1, date('Y'));
    //     $data_fim = mktime(23, 59, 59, date('m'), date("t"), date('Y'));
    //     $dias = [];
    //     while ($data_inicio <= $data_fim) {
    //         $dias[] = date('d', $data_inicio);
    //         $data_inicio = strtotime("+1 day", $data_inicio);
    //     }
    //     return $dias;
    // }

    // function getPercentual($valor, $total){
    //     if ($total > 0) {
    //         return round(((int)$valor / (int)$total) * 100, 1) ?: 0;
    //     } else {
    //         return 0;
    //     }
    // }


    $array_valores = [];
    $array_quality = [];
    $array_delivery = [];
    $array_meses = [];


    for($i=11; $i>=0; $i--){

        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

        echo "SELECT f.nome,
        am.*
        FROM `avaliacao_mensal` am
        LEFT JOIN fornecedores f ON am.codigo_fornecedor = f.codigo
        where am.mes = '".($Mes*1)."' AND am.ano = '{$Ano}' and am.codigo_fornecedor = '{$_POST['codigo']}'";
        $query = $pdo->prepare("SELECT f.nome,
        am.*
        FROM `avaliacao_mensal` am
        LEFT JOIN fornecedores f ON am.codigo_fornecedor = f.codigo
        where am.mes = '".($Mes*1)."' AND am.ano = '{$Ano}' and am.codigo_fornecedor = '{$_POST['codigo']}'");
        $query->execute();
        $d = $query->fetch();

        $ind = ($Mes*1);
        $array_meses[$ind] =  '"'.mesExtenso($ind).'/'.substr($Ano,-2).'"';
        $array_valores[$ind] = (($d['classificacao'])?:'');
        $array_quality[$ind] = (($d['quality'])?:'');
        $array_delivery[$ind] = (($d['delivery'])?:'');

    }


    // while ($d = $query->fetch()) {
    //     $array_meses[] =  '"'.mesExtenso($d['mes']).'"';
    //     $array_valores[] = $d['classificacao'];
    //     $array_quality[] = $d['quality'];
    //     $array_delivery[] = $d['delivery'];
    // }

    // if($query->rowCount() > 0){
    //     $min = min($array_valores);
    //     $min2 =min($array_quality);
    //     $min3 = min($array_delivery);
    //     $minfinal = min($min, $min2, $min3);

    //     if($minfinal != 100){
    //         $minfinal = $minfinal-10;
    //     }else{
    //         $minfinal = 0;
    //     }
    // }else{
    //     $minfinal = 0;
    // }
    // $obj = (object)[];


?>


<canvas can id="chart_barras<?=md5(date("YmdHis"))?>" style="height: 100%; width: 100%"></canvas>

<script>
    var ctx10 = document.getElementById('chart_barras<?=md5(date("YmdHis"))?>');
    var chart_ano = new Chart(ctx10, {
        type: 'line',
        data: {
            labels: [
                <?=@implode(",", $array_meses)?>
            ],

            datasets: [
                {
                label: 'DELIVERY',
                backgroundColor: 'rgb(51,153,102,.5)',
                borderColor: 'rgb(58,195,113)',
                borderWidth: 1,
                data: [<?=@implode(",", $array_delivery)?>],
                stack: 'combined',
                barThickness: 50,
                type: 'bar'
            },

            // {
            //     label: 'QUALITY',
            //     backgroundColor: 'rgb(73,116,165)',
            //     borderColor: 'rgb(73,116,165)',
            //     borderWidth: 1,
            //     data: [<?=@implode(",", $array_quality)?>],
            //     stack: 'combined',
            //     borderWidth: 2
            // },
            // {
            //     label: 'DELIVERY',
            //     backgroundColor: 'rgb(113,195,58)',
            //     borderColor: 'rgb(113,195,58)',
            //     borderWidth: 1,
            //     data: [<?=@implode(",", $array_delivery)?>],
            //     stack: 'combined',
            //     borderWidth: 2
            // },
            {
                label: 'DEFICIENTE',
                backgroundColor: '#d11527',
                borderColor: '#d11527',
                borderWidth: 1,
                data: [85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85],
                stack: 'combined',
                borderDash: [5,5],
                borderWidth: 2
            },
            {
                label: 'META',
                backgroundColor: 'rgb(33,214,33)',
                borderColor: 'rgb(33,214,33)',
                borderWidth: 1,
                data: [94, 94, 94, 94, 94, 94, 94, 94, 94, 94, 94, 94],
                stack: 'combined',
                borderDash: [5,5],
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                },
                x: {
                    display: true,
                    offset: true,
                }
            }
        }
    });
</script>
