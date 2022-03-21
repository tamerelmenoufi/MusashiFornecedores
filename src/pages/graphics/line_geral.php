<?php
    require_once "../../../lib/config.php";
    global $pdo;

    if(!isset($_POST['ano'])){
        $Y = date("Y");
    }else{
        $Y = $_POST['ano'];
    }

    function mesExtenso($mes){
        switch ($mes) {
            case '1':
                return 'Janeiro';
                break;
            case '2':
                return 'Fevereiro';
                break;
            case '3':
                return 'Março';
                break;
            case '4':
                return 'Abril';
                break;
            case '5':
                return 'Maio';
                break;
            case '6':
                return 'Junho';
                break;
            case '7':
                return 'Julho';
                break;
            case '8':
                return 'Agosto';
                break;
            case '9':
                return 'Setembro';
                break;
            case '10':
                return 'Outubro';
                break;
            case '11':
                return 'Novembro';
                break;
            case '12':
                return 'Dezembro';
                break;
        }
    }

    function Rand_color() {
        return '#' . str_pad(dechex(mt_Rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }


    $meses = [];
    $fornecedores = [];
    for($i = 1; $i <= 12; $i++){
        $meses[] =  $i;
    }

    $query_classificacao = $pdo->prepare("SELECT  f.nome, codigo_fornecedor, classificacao, mes FROM avaliacao_mensal avm LEFT JOIN fornecedores f ON f.codigo = avm.codigo_fornecedor WHERE ano = '{$Y}'
    AND mes in ('".implode("','", $meses)."') ORDER BY nome, mes");
    $query_classificacao->execute();

    if($query_classificacao->rowCount() > 0){
        while ($fornecedor = $query_classificacao->fetch()) {
            for($i=0;$i<count($meses);$i++){
                $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$meses[$i]] =  false;
            }       
        }

        $query_classificacao->execute();
        
        while ($fornecedor = $query_classificacao->fetch()) {
            $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$fornecedor['mes']] =  '"'.$fornecedor['classificacao'].'"';
                
            $fornecedores[$fornecedor['codigo_fornecedor']]['codigo'] = '00'.$fornecedor['codigo_fornecedor'].'';
            
            $fornecedores[$fornecedor['codigo_fornecedor']]['cor'] = Rand_color();            
        }
    }
?>


<canvas can id="line_geral" ></canvas>  
    
<script>
    var ctx10 = document.getElementById('line_geral');
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
