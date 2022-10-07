<?php
    require_once "../../../../lib/config.php";
    global $pdo;

    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }

    if(isset($_POST['mes'])){
        $M = $_POST['mes'];
    }else{
        $M = date("M");
    }

    function mesExtenso($mes){
        switch ($mes) {
            case '1':
                return 'Jano';
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



    function dias_atrasos_tabela($m, $a, $f){

        global $pdo;

        $quality_ip_emitido = 0;
        $quality_ip_reincidente = 0;
        $quality_atraso_resposta = 0;
        $quality_ppm = 0;

        $p =  0;

        // for($i=11; $i>=0; $i--){

            $Mes = $m; //date("m", mktime(0, 0, 0, ($m - $i), 1, $a));
            $Ano = $a; //date("Y", mktime(0, 0, 0, ($m - $i), 1, $a));


            $q = "SELECT

                    (b.idm_emitidos) as quality_ip_emitido,
                    (c.idm_reincidente) as quality_ip_reincidente,
                    (d.demerito) as quality_atraso_resposta,
                    (e.demerito) as quality_ppm

                FROM registros_diarios a
                    left join aux_idm_emitidos b on a.quality_ip_emitido = b.codigo
                    left join aux_idm_reincidente c on a.quality_ip_reincidente = c.codigo
                    left join aux_idm_atraso_resposta d on a.quality_atraso_resposta = d.codigo
                    left join aux_ppm e on a.quality_ppm = e.codigo

                WHERE

                    a.codigo_fornecedor = '{$f}' AND
                    month(a.data_registro) = '{$Mes}' AND
                    year(a.data_registro) = '{$Ano}'";

            $query = $pdo->prepare($q);
            $query->execute();
            $d = $query->fetch();
            $n = $query->rowCount();
            if($n){

                $quality_ip_emitido = $quality_ip_emitido + $d['quality_ip_emitido'];
                $quality_ip_reincidente = $quality_ip_reincidente + $d['quality_ip_reincidente'];
                $quality_atraso_resposta = $quality_atraso_resposta + $d['quality_atraso_resposta'];
                $quality_ppm = $quality_ppm + $d['quality_ppm'];

            }
        // }

        return [
            'quality_ip_emitido' => $quality_ip_emitido,
            'quality_ip_reincidente' => $quality_ip_reincidente,
            'quality_atraso_resposta' => $quality_atraso_resposta,
            'quality_ppm' => $quality_ppm,
        ];

    }

    // $mes_atual = date("m", mktime(1, 0, 0, date('m'), date('d'), $Y));
    // $mes_atual = date("m");
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
    // $query = $pdo->prepare("SELECT f.nome,
    // am.mes,
    // am.ano,
    // am.eficiencia,
    // am.quality,
    // am.delivery,
    // am.classificacao,
    // am.posicao,
    // am.*
    // FROM `avaliacao_mensal` am
    // LEFT JOIN fornecedores f ON am.codigo_fornecedor = f.codigo
    // where f.codigo = {$_POST['codigo']}
    // AND DATE(concat(ano, '-', mes, '-01')) <= DATE(LAST_DAY(DATE(concat({$Y}, '-', {$M}, '-01'))))
    // AND DATE(concat(ano, '-', mes, '-01')) >= DATE_SUB(concat({$Y}, '-', {$M}, '-01'), INTERVAL 11 MONTH)
    // ORDER BY ano, mes");
    // $query->execute();

    // $array_valores = [];
    // $array_quality = [];
    // $array_delivery = [];
    // $array_meses = [];

    // while ($d = $query->fetch()) {
    //     $array_meses[] =  '"'.mesExtenso($d['mes']).'"';
    //     $array_valores[] = $d['classificacao'];
    //     $array_quality[] = $d['quality'];
    //     $array_delivery[] = $d['delivery'];
    // }
    // // se os 12 meses não estiverem preenchidos, preenche os meses com dados vazios
    // if (count($array_meses) != 12) {
    //     $count = 12 - count($array_meses);
    //     for ($i=$count; $i < 12; $i++) {
    //         # code...
    //     }
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




//Nova Versão


$array_valores = [];
$array_quality = [];
$array_delivery = [];
$array_meses = [];

for($i=11; $i>=0; $i--){

    $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
    $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));


    $query = $pdo->prepare("SELECT f.nome,
    am.mes,
    am.ano,
    am.eficiencia,
    am.quality,
    am.delivery,
    am.classificacao,
    am.posicao,
    am.*
    FROM `avaliacao_mensal` am
    LEFT JOIN fornecedores f ON am.codigo_fornecedor = f.codigo
    where f.codigo = {$_POST['codigo']} AND am.mes = '".($Mes*1)."' AND am.ano = '{$Ano}'");



    $query->execute();
    $d = $query->fetch();

    $ind = ($Mes*1);
    $array_meses[$ind] =  '"'.mesExtenso($ind).'/'.substr($Ano,-2).'"';
    $entrega[$ind] = (($d['eficiencia'])?:'');
    $retorno = dias_atrasos_tabela($Mes, $Ano, $_POST['codigo']);

    // $quality_ip_emitido = 0;
    // $quality_ip_reincidente = 0;
    // $quality_atraso_resposta = 0;

    $emitido[$ind] = (($retorno['quality_ip_emitido'])?:'0');
    $reincidente[$ind] = (($retorno['quality_ip_reincidente'])?:'0');
    $atraso[$ind] = (($retorno['quality_atraso_resposta'])?:'0');
    $quality_ppm[$ind] = (($retorno['quality_ppm']?:'0'));
}



?>


<canvas can id="chart_linhas<?=md5(date("YmdHis"))?>" style="height: 100%; width: 100%"></canvas>

<script>
    var ctx10 = document.getElementById('chart_linhas<?=md5(date("YmdHis"))?>');
    var chart_ano = new Chart(ctx10, {
        type: 'line',
        data: {
            labels: [
                <?=@implode(",", $array_meses)?>
            ],
            datasets: [
            {
                label: 'PPM',
                backgroundColor: 'rgb(255,255,255,.5)',
                borderColor: 'red',
                borderWidth: 2,
                data: [<?=@implode(",", $quality_ppm)?>],
                stack: 'combined',
                // barThickness: 50,
                // type: 'bar'
            },
            {
                label: 'IPEmitido',
                backgroundColor: 'rgb(255,191,1)',
                borderColor: 'rgb(255,191,1)',
                data: [<?=@implode(",", $emitido)?>],
                stack: 'combined',
                borderWidth: 2
            },
            {
                label: 'IPOficial"R"',
                backgroundColor: 'rgb(1,67,255)',
                borderColor: 'rgb(1,67,255)',
                data: [<?=@implode(",", $reincidente)?>],
                stack: 'combined',
                borderWidth: 2
            },
            {
                label: 'Atraso Resp.',
                backgroundColor: 'rgb(7,146,0)',
                borderColor: 'rgb(7,146,0)',
                data: [<?=@implode(",", $atraso)?>],
                stack: 'combined',
                borderWidth: 2
            }
            /*,
            {
                label: 'DELIVERY',
                backgroundColor: 'rgb(113,195,58)',
                borderColor: 'rgb(113,195,58)',
                borderWidth: 1,
                data: [<?=@implode(",", $array_delivery)?>],
                stack: 'combined',
                borderWidth: 2
            },
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
            }*/]
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
