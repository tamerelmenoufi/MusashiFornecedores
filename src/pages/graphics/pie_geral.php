<?php
    require_once "../../../lib/config.php";
    global $pdo;

    if(!isset($_POST['ano'])){
        $Y = date("Y");
    }else{
        $Y = $_POST['ano'];
    }

    $query = $pdo->prepare("SELECT 
    count(CASE WHEN ava.qualificacao_ipf = 'OTIMO' THEN 1 ELSE NULL END) as otimo,  
    count(CASE WHEN ava.qualificacao_ipf = 'BOM' THEN 1 ELSE NULL END) as bom, 
    count(CASE WHEN ava.qualificacao_ipf = 'REGULAR' THEN 1 ELSE NULL END) as regular, 
    count(CASE WHEN ava.qualificacao_ipf = 'DEFICIENTE' THEN 1 ELSE NULL END) as deficiente
    FROM avaliacao_anual ava
    WHERE ava.ano = '{$Y}' ORDER BY ava.classificacao DESC");
    $query->execute();
    
    $array_valores = [];
    if($query->rowCount() > 0){
        $d = $query->fetch();

        $array_valores[0] = $d['otimo'];
        $array_valores[1] = $d['bom'];
        $array_valores[2] = $d['regular'];
        $array_valores[3] = $d['deficiente'];
    }
?>


<canvas can style="" id="pie_geral"></canvas>  
    
<script>
    var ctx10 = document.getElementById('pie_geral');
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
