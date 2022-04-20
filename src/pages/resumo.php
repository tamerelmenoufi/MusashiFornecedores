<?php
    require '../../lib/config.php';

    include("../components/menu_top.php");

    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }

    if(isset($_POST['mes'])){
        $M = str_pad($_POST['mes'] , 2 , '0' , STR_PAD_LEFT);
    }else{
        $M = date("m");
    }

?>
<style>
    @media print {
        div.grafico{
            height: 500px !important;
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
<div class="container p-2">
    <div class="row justify-content-center align-items-center mt-3 p-2">
        <button legenda class="btn btn-warning position-fixed" style="left: 30px; top: 90px; width: 40px; color: #fff; z-index: 999; font-size: 20px; font-weight: 800">?</button>
        <div class="col-md-3 noprint d-flex p-0 mb-3">
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
            </div>
        </div>
        <div class="col-md-4 noprint d-flex p-0 mb-3"></div>
        <div class="col-md-3 noprint d-flex p-0 mb-3">
            <div class="input-group">
                <select mes class="form-select">
                    <option disabled value="<?=$M?>" selected><?=$M?></option>
                    <?php
                        for($i=1;$i<=12;$i++){
                    ?>
                        <option value="<?=$i?>"><?=$i?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>

        <!-- GRAFICOS -->
        <div class="card p-0 col-md-10" style="border-radius: 15px !important; overflow: hidden">
            <div class="card-header p-3 ">
                <h5 class="m-0"><i class="fa fa-bar-chart" aria-hidden="true"></i> Ranking Geral </h3>
            </div>

            <div class="row card-body justify-content-center">
                <div grafico_geral class="col-md-8 grafico"></div>

                <div pie_geral class="col-md-4 grafico"></div>

                <div line_geral class="col-md-12 grafico"></div>
            </div>
            <div class="card-footer p-0">
                <div class="row">
                    <div class="col-md-3 p-3 text-white bg-danger d-flex flex-column align-items-center justify-content-between">
                        0.00 - 84.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            DEFICIENTE
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-warning d-flex flex-column align-items-center justify-content-between">
                        85.00 - 93.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            REGULAR
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-primary d-flex flex-column align-items-center justify-content-between">
                        94.00 - 98.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            BOM
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-success d-flex flex-column align-items-center justify-content-between">
                        99.00 - 100.00
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            ÓTIMO
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center align-items-center mt-3">
        <!-- GRAFICOS -->
        <div class="card p-0 col-md-10" style="border-radius: 15px !important; overflow: hidden">
            <div class="card-header p-3 ">
                <h5 class="m-0"><i class="fa fa-bar-chart" aria-hidden="true"></i> Ranking Delivery </h3>
            </div>

            <div class="row card-body justify-content-center">
                <div grafico_delivery class="col-md-8 grafico"></div>

                <div pie_delivery class="col-md-4 grafico"></div>

                <div line_delivery class="col-md-12 grafico"></div>
            </div>
            <div class="card-footer p-0">
                <div class="row">
                    <div class="col-md-3 p-3 text-white bg-danger d-flex flex-column align-items-center justify-content-between">
                        0.00 - 91.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            DEFICIENTE
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-warning d-flex flex-column align-items-center justify-content-between">
                        92.00 - 95.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            REGULAR
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-primary d-flex flex-column align-items-center justify-content-between">
                        96.00 - 98.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            BOM
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-success d-flex flex-column align-items-center justify-content-between">
                        99.00 - 100.00
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            ÓTIMO
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center align-items-center mt-3">
        <!-- GRAFICOS -->
        <div class="card p-0 col-md-10" style="border-radius: 15px !important; overflow: hidden">
            <div class="card-header p-3 ">
                <h5 class="m-0"><i class="fa fa-bar-chart" aria-hidden="true"></i> Ranking Quality </h3>
            </div>

            <div class="row card-body justify-content-center">
                <div grafico_quality class="col-md-8 grafico"></div>

                <div pie_quality class="col-md-4 grafico"></div>

                <div line_quality class="col-md-12 grafico"></div>
            </div>
            <div class="card-footer p-0">
                <div class="row">
                    <div class="col-md-3 p-3 text-white bg-danger d-flex flex-column align-items-center justify-content-between">
                        0.00 - 77.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            DEFICIENTE
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-warning d-flex flex-column align-items-center justify-content-between">
                        78.00 - 91.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            REGULAR
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-primary d-flex flex-column align-items-center justify-content-between">
                        92.00 - 98.99
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            BOM
                        </footer>
                    </div>
                    <div class="col-md-3 p-3 text-white bg-success d-flex flex-column align-items-center justify-content-between">
                        99.00 - 100.00
                        <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                        <footer>
                            ÓTIMO
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){

        /////////////////////////////////////// GERAL

        $.ajax({
            url: 'src/pages/graphics/chart_geral.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(chart_geral){
                $('div[grafico_geral]').html(chart_geral)

            }
        })

        $.ajax({
            url: 'src/pages/graphics/pie_geral.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(pie_geral){
                $('div[pie_geral]').html(pie_geral)
            }
        })

        $.ajax({
            url: 'src/pages/graphics/line_geral.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(line_geral){
                $('div[line_geral]').html(line_geral)
            }
        })

        /////////////////////////////////////////////////// DELIVERY

        $.ajax({
            url: 'src/pages/graphics/chart_delivery.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(chart_delivery){
                $('div[grafico_delivery]').html(chart_delivery)

            }
        })

        $.ajax({
            url: 'src/pages/graphics/pie_delivery.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(pie_delivery){
                $('div[pie_delivery]').html(pie_delivery)
            }
        })

        $.ajax({
            url: 'src/pages/graphics/line_delivery.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(line_delivery){
                $('div[line_delivery]').html(line_delivery)
            }
        })

        ///////////////////////////////////////////////// QUALITY

        $.ajax({
            url: 'src/pages/graphics/chart_quality.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(chart_quality){
                $('div[grafico_quality]').html(chart_quality)

            }
        })

        $.ajax({
            url: 'src/pages/graphics/pie_quality.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(pie_quality){
                $('div[pie_quality]').html(pie_quality)
            }
        })

        $.ajax({
            url: 'src/pages/graphics/line_quality.php',
            method: "POST",
            data: { ano: <?=$Y?> },
            success: function(line_quality){
                $('div[line_quality]').html(line_quality)
            }
        })
    })

    $('select[ano], select[mes]').change(function(){
        let ano = $("select[ano]").val()
        let mes = $("select[mes]").val()

        $.ajax({
            url: "src/pages/resumo.php",
            method: "POST",
            data:{
                ano,
                mes
            },success: function(grafico){
                $('div#home').html(grafico)
            }
        })
    })

    $('button[legenda]').click(function(){
        $.ajax({
            url: "src/pages/legenda.php",
            method: "POST",
            data: { ano: <?=$Y?>, acao: "resumo"},
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
