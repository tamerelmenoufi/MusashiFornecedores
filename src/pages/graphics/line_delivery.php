<?php
    require_once "../../../lib/config.php";
    global $pdo;

    if(!isset($_POST['ano'])){
        $Y = date("Y");
    }else{
        $Y = $_POST['ano'];
    }

    function Rand_color() {
        return '#' . str_pad(dechex(mt_Rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    $meses = [];
    $fornecedores = [];
    for($i = 1; $i <= 12; $i++){
        $meses[] =  $i;
    }

    $query_delivery = $pdo->prepare("SELECT f.nome, codigo_fornecedor, delivery, mes FROM avaliacao_mensal avm LEFT JOIN fornecedores f ON f.codigo = avm.codigo_fornecedor WHERE ano = '{$Y}'
    AND mes in ('".implode("','", $meses)."') ORDER BY nome, mes");
    $query_delivery->execute();

    if($query_delivery->rowCount() > 0){
        while ($fornecedor = $query_delivery->fetch()) {
            for($i=0;$i<count($meses);$i++){
                $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$meses[$i]] =  false;
            }       
        }

        $query_delivery->execute();
        
        while ($fornecedor = $query_delivery->fetch()) {
            $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$fornecedor['mes']] =  '"'.$fornecedor['delivery'].'"';
                
            $fornecedores[$fornecedor['codigo_fornecedor']]['codigo'] = '00'.$fornecedor['codigo_fornecedor'].'';
            $fornecedores[$fornecedor['codigo_fornecedor']]['cor'] = Rand_color();            
        }
    }
?>


<canvas can id="line_delivery" ></canvas>  
    
<script>
    var ctx10 = document.getElementById('line_delivery');
    var chart_ano = new Chart(ctx10, {
        type: 'line',
        data: {
            labels: [
                'jan','fev','mar','abr','mai','jun','jul','ago','out','nov','dez'
            ],
            datasets: [
            <?php
                foreach($fornecedores as $indice => $value){
            ?>
                {
                    label: '<?=str_pad($value['codigo'], 4, "0", STR_PAD_LEFT)?>',
                    backgroundColor: '<?=$value['cor']?>',
                    borderColor: '<?=$value['cor']?>',
                    borderWidth: 1,
                    data: [<?=@implode(",", $value['dados'])?>],
                    type: 'line'
                },
            <?php
                }
            ?>
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true
                }
            },
            scales: {
                x: {
                    display: true,
                    offset: true,
                }
            }
        }
    });
</script>
