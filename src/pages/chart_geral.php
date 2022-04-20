<?php
    require_once "../../lib/config.php";
    global $pdo;

    if(!isset($_POST['ano'])){
        $Y = date("Y");
    }else{
        $Y = $_POST['ano'];
    }

    function Limites($vetor, $val){
        $r = [];
        for($i=0;$i<count($vetor);$i++){
            $r[] = $val;
        }
        return implode(', ', $r);
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

    $query = $pdo->prepare("SELECT f.nome,
    f.codigo,
    ava.ano,
    ava.classificacao,
    ava.quality,
    ava.delivery,
    ava.posicao
    FROM avaliacao_anual ava
    LEFT JOIN fornecedores f ON ava.codigo_fornecedor = f.codigo
    where ava.ano = '".$Y."' ORDER BY classificacao DESC");
    $query->execute();

    $array_valores = [];
    $array_quality = [];
    $array_delivery = [];
    $array_codigo = [];

    if($query->rowCount() > 0){
        while ($d = $query->fetch()) {
            $array_codigo[] =  '"'.str_pad($d['codigo'], 4, "0", STR_PAD_LEFT).'"';
            $array_valores[] = $d['classificacao'];
            $array_quality[] = $d['quality'];
            $array_delivery[] = $d['delivery'];
        }


        $min = min($array_valores);
        $min2 =min($array_quality);
        $min3 = min($array_delivery);
        $minfinal = min($min, $min2, $min3);

        if($minfinal != 100){
            $minfinal = $minfinal-10;
        }else{
            $minfinal = 0;
        }
    }else{
        $minfinal = 0;
    }


?>


<canvas can id="chart_empresas" ></canvas>

<script>
    var ctx10 = document.getElementById('chart_empresas');
    var chart_ano = new Chart(ctx10, {
        type: 'line',
        data: {
            labels: [
                <?=@implode(",", $array_codigo)?>
            ],
            datasets: [
            {
                label: 'GERAL (Q&D)',
                backgroundColor: 'rgb(58,113,195,.5)',
                borderColor: 'rgb(58,113,195)',
                borderWidth: 1,
                data: [<?=@implode(",", $array_valores)?>],
                barThickness: 50,
                type: 'bar'
            },
            {
                label: 'DEFICIENTE',
                backgroundColor: '#d11527',
                borderColor: '#d11527',
                borderWidth: 1,
                data: [<?=Limites($array_valores, 85)?>],
                stack: 'combined',
                borderDash: [5,5],
                borderWidth: 2
            },
            {
                label: 'META',
                backgroundColor: 'rgb(33,214,33)',
                borderColor: 'rgb(33,214,33)',
                borderWidth: 1,
                data: [<?=Limites($array_valores, 94)?>],
                stack: 'combined',
                borderDash: [5,5],
                borderWidth: 2
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
