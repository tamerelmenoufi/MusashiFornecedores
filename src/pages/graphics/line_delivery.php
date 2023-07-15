<?php
    require_once "../../../lib/config.php";
    global $pdo;

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

        $a = date("y", mktime(0, 0, 0, ($M - $i), 1, $Y));

        $ListaMeses[] = mesExtenso($Mes*1)."/{$a}";


        $query_delivery = $pdo->prepare("SELECT f.nome, codigo_fornecedor, delivery, mes FROM avaliacao_mensal avm LEFT JOIN fornecedores f ON f.codigo = avm.codigo_fornecedor WHERE ano = '{$Ano}'
        AND mes ='{$Mes}' AND f.situacao != '1' AND f.deletado != '1' ORDER BY nome, mes");
        $query_delivery->execute();

        if($query_delivery->rowCount() > 0){
            while ($fornecedor = $query_delivery->fetch()) {
                $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$fornecedor['mes']] =  false;
            }

            $query_delivery->execute();

            while ($fornecedor = $query_delivery->fetch()) {
                $fornecedores[$fornecedor['codigo_fornecedor']]['dados'][$fornecedor['mes']] =  '"'.$fornecedor['delivery'].'"';

                $fornecedores[$fornecedor['codigo_fornecedor']]['codigo'] = $fornecedor['nome']; //'00'.$fornecedor['codigo_fornecedor'].'';
                $fornecedores[$fornecedor['codigo_fornecedor']]['cor'] = Rand_color();
            }
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
                '<?=implode("','", $ListaMeses)?>'
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
