<?php
    require '../../lib/config.php';

    include("../components/menu_top.php");

    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }

    function mesExtenso($mes){
        switch ($mes) {
            case '1':
                echo 'Janeiro';
                break;
            case '2':
                echo 'Fevereiro';
                break;
            case '3':
                echo 'Março';
                break;
            case '4':
                echo 'Abril';
                break;
            case '5':
                echo 'Maio';
                break;
            case '6':
                echo 'Junho';
                break;
            case '7':
                echo 'Julho';
                break;
            case '8':
                echo 'Agosto';
                break;
            case '9':
                echo 'Setembro';
                break;
            case '10':
                echo 'Outubro';
                break;
            case '11':
                echo 'Novembro';
                break;
            case '12':
                echo 'Dezembro';
                break;
        }
    }
?>
<style>
    @media print {
        div[grafico]{
            height: 300px !important;
        }
        div[rs]{
            width: 100% !important;
            margin: 0 !important;
        }
        .noprint{
            display: none !important;
        }
        canvas[can]{
            width:  100% !important;
            height: 300px !important;
        }
        div.tfonts{
            font-size: 14px;
        }
    }
</style>

<!-- <button legenda class="btn btn-warning position-fixed" style="left: 30px; top: 90px; width: 40px; color: #fff; z-index: 999; font-size: 20px; font-weight: 800">?</button> -->

<div id="home" style="margin-top: 70px ">
    <div class="container p-0">
        <div painel class="row justify-content-center align-items-center g-3 mt-3">
            <div rs class="col-md-10 col-6">
                <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> Gráfico de Desempenho Anual </h3>
            </div>

            <div class="col-md-2 col-6 noprint d-flex">
                <div class="input-group">
                    <select ano class="form-select">
                        <option disabled value="<?=$Y?>" selected><?=$Y?></option>
                        <?php
                            $query = $pdo->prepare("SELECT ano FROM avaliacao_anual GROUP BY ano");
                            $query->execute();

                            while($options = $query->fetch()){
                        ?>
                            <option value="<?=$options['ano']?>"><?=$options['ano']?></option>
                        <?php
                            }
                        ?>
                    </select>
                    <div class="input-group-text p-0">
                        <button imprimir type="button" class="btn btn-primary " title="Imprimir" style="border-radius: 0px 3px 3px 0;"><i class="fa fa-print" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>

            <!-- GRAFICOS -->
            <div grafico class="col-12 p-0 mb-3"></div>
            <div tabela class="col-md-12 mb-3 p-0 ">

            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $.ajax({
            url: 'src/pages/chart_geral.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(chart){
                $('div[grafico]').html(chart)

            }
        })

        $.ajax({
            url: 'src/pages/table.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(tabela){
                $('div[tabela]').html(tabela)

            }
        })
    })

    $('button[imprimir]').click(function(){
        window.print();
    })

    $('select[ano]').change(function(){
        let ano = $("select[ano]").val()

        $.ajax({
            url: "src/pages/chart.php",
            method: "POST",
            data:{
                ano
            },success: function(grafico){
                $("div[grafico]").html(grafico)

                $.ajax({
                    url: "src/pages/table.php",
                    method: "POST",
                    data:{
                        ano,
                    },success: function(tabela){
                        $("div[tabela]").html(tabela)
                    }
                })
            }
        })
    })

    $('button[legenda]').click(function(){
        $.ajax({
            url: "src/pages/legenda.php",
            method: "POST",
            data: { ano: <?=$Y?>, acao: "principal" },
            success: function(legenda){
                popup = $.dialog({
                    content: legenda,
                    backgroundDismiss: true,
                    closeIcon: false,
                    title: false,
                    columnClass: 'col-md-offset-1 col-md-10'
                })
            }
        })
    })
</script>
