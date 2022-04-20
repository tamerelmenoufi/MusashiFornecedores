<?php
    require_once "../../../lib/config.php";
    global $pdo;

    function Rand_color() {
        return '#' . str_pad(dechex(mt_Rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

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

    $fornecedores = [];

    for($i=11; $i>=0; $i--){

        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

        $query_quality = $pdo->prepare("SELECT  f.nome, codigo_fornecedor, quality, mes FROM avaliacao_mensal avm LEFT JOIN fornecedores f ON f.codigo = avm.codigo_fornecedor WHERE ano = '{$Ano}'
        AND mes = '{$Mes}' ORDER BY nome, mes");
        $query_quality->execute();

        if($query_quality->rowCount() > 0){
            while ($fornecedor = $query_quality->fetch()) {
                for($i=0;$i<count($meses);$i++){
                    $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$meses[$i]] =  false;
                }
            }

            $query_quality->execute();

            while ($fornecedor = $query_quality->fetch()) {
                $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$fornecedor['mes']] =  '"'.$fornecedor['quality'].'"';

                $fornecedores[$fornecedor['codigo_fornecedor']]['codigo'] = '00'.$fornecedor['codigo_fornecedor'].'';
                $fornecedores[$fornecedor['codigo_fornecedor']]['cor'] = Rand_color();
            }
        }
    }
?>


<canvas can id="line_quality" ></canvas>

<script>
    var ctx10 = document.getElementById('line_quality');
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
