<?php
require '../../lib/config.php';

include("../components/menu_top.php");

if (isset($_POST['ano'])) {
    $Y = $_POST['ano'];
} else {
    $Y = date("Y");
}

if (isset($_POST['mes'])) {
    $M = str_pad($_POST['mes'], 2, '0', STR_PAD_LEFT);
} else {
    $M = date("m");
}

?>

<style>
    @media print {
        div.grafico {
            height: 500px !important;
        }

        div[rs] {
            width: 100% !important;
            margin: 0 !important;
        }

        .noprint {
            display: none !important;
        }

        canvas[can] {
            width: 100% !important;
            height: 300px !important;
        }

        div.tfonts {
            font-size: 14px;
        }
    }
</style>

<div class="container p-2">
    <div class="row justify-content-center align-items-center mt-3 p-2">
        <button legenda class="btn btn-warning position-fixed"
                style="left: 30px; top: 90px; width: 40px; color: #fff; z-index: 999; font-size: 20px; font-weight: 800">
            ?
        </button>
        <div class="col-md-3 noprint d-flex p-0 mb-3">
            <div class="input-group">
                <select ano class="form-select">
                    <option value="<?= $Y ?>" selected><?= $Y ?></option>
                    <?php
                    $query = $pdo->prepare("SELECT ano FROM avaliacao_anual GROUP BY ano");
                    $query->execute();

                    while ($options = $query->fetch()) {
                        ?>
                        <option value="<?= $options['ano'] ?>"><?= $options['ano'] ?></option>
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
                    <option value="<?= $M ?>" selected><?= $M ?></option>
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        ?>
                        <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <!-- GRAFICOS -->
        <div class="card p-0 col-md-10" style="border-radius: 15px !important; overflow: hidden">
            <div class="card-header p-3 ">
                <h5 class="m-0"><i class="fa fa-bar-chart" aria-hidden="true"></i> Ranking Geral </h5>
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
                <h5 class="m-0"><i class="fa fa-bar-chart" aria-hidden="true"></i> Ranking Delivery </h5>
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
                <h5 class="m-0"><i class="fa fa-bar-chart" aria-hidden="true"></i> Ranking Quality </h5>
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

    <!-- Seção assinaturas -->
    <div class="row justify-content-center align-items-center mt-3">
        <?php
        $query1 = $pdo->prepare("SELECT * FROM assinatura_geral WHERE ano = :a AND mes = :m LIMIT 1");
        $query1->bindValue(':a', $Y);
        $query1->bindValue(':m', $M);
        $query1->execute();

        $assinaturas = $query1->fetch();

        $assinaturas_json = @json_decode($assinaturas['assinaturas'], true) ?: [];
        $search = array_search($_SESSION['musashi_cod_usu'], array_column($assinaturas_json, 'codigo'));
        $is_assinado = ($search >= 0 and $search !== false);

        ?>
        <div class="card p-0 col-md-10" style="border-radius: 15px !important; overflow: hidden">
            <div class="card-header p-3 d-flex flex-row justify-content-between">
                <h5 class="m-0">
                    <i class="fa fa-check-square-o" aria-hidden="true"></i> Assinaturas
                </h5>

                <?php if ($ConfUsu['assinante_documento'] === 'S'/* and $ConfUsu['tipo'] == '1'*/) { ?>
                    <button
                            assinar
                            class="btn btn-success btn-sm"
                        <?= $is_assinado ? 'disabled' : '' ?>
                    >
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        <span text><?= $is_assinado ? 'ASSINADO' : 'ASSINAR' ?></span>
                    </button>
                <?php } ?>
            </div>

            <div class="card-body">
                <div class="row p-0">
                    <?php

                    if ($assinaturas_json) {
                        foreach ($assinaturas_json as $assinatura) {
                            #print_r($assinaturas_json);
                            $url_qrcode = "http://musashi.mohatron.com/validacao-de-assinatura.php?v={$assinatura['chave']}-rg"
                            ?>

                            <div class="col-md-6 mb-2 assinaturas-item">
                                <div class="rounded border h-100 px-3 py-2 position-relative">
                                    <a
                                            href="#"
                                            class="position-absolute text-danger noprint"
                                            remover_assinatura
                                            codigo="<?= $assinaturas['codigo']; ?>"
                                            cod_assinatura="<?= $assinatura['codigo']; ?>"
                                            style="top: 0; right: 5px"
                                    >
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>

                                    <div class="d-flex flex-row justify-content-between">
                                        <div style="flex:1">
                                            <div title="Usuário">
                                                <i class="fa fa-user"
                                                   aria-hidden="true"></i> <?= $assinatura['usuario']; ?>
                                            </div>
                                            <div title="Cargo">
                                                <i class="fa fa-briefcase"
                                                   aria-hidden="true"></i> <?= $assinatura['cargo']; ?>
                                            </div>
                                            <div title="Data e Hora da assinatura">
                                                <i class="fa fa-calendar"
                                                   aria-hidden="true"></i> <?= date("d/m/Y H:i", strtotime($assinatura['data_hora'])) ?>
                                            </div>
                                            <div title="Chave">
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <small><?= $assinatura['chave']; ?></small>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center px-1">
                                            <img
                                                    src="src/fornecedor/barcode.php?f=png&s=qr&d=<?= $url_qrcode; ?>"
                                                    style="width: 80px"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php }
                    } else { ?>
                        <p>Não possui assinatura no momento</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Seção assinaturas -->
</div>

<script>
    $(function () {
        /////////////////////////////////////// GERAL

        $.ajax({
            url: 'src/pages/graphics/chart_geral.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (chart_geral) {
                $('div[grafico_geral]').html(chart_geral)

            }
        })

        $.ajax({
            url: 'src/pages/graphics/pie_geral.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (pie_geral) {
                $('div[pie_geral]').html(pie_geral)
            }
        })

        $.ajax({
            url: 'src/pages/graphics/line_geral.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (line_geral) {
                $('div[line_geral]').html(line_geral)
            }
        })

        /////////////////////////////////////////////////// DELIVERY

        $.ajax({
            url: 'src/pages/graphics/chart_delivery.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (chart_delivery) {
                $('div[grafico_delivery]').html(chart_delivery)

            }
        })

        $.ajax({
            url: 'src/pages/graphics/pie_delivery.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (pie_delivery) {
                $('div[pie_delivery]').html(pie_delivery)
            }
        })

        $.ajax({
            url: 'src/pages/graphics/line_delivery.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (line_delivery) {
                $('div[line_delivery]').html(line_delivery)
            }
        })

        ///////////////////////////////////////////////// QUALITY

        $.ajax({
            url: 'src/pages/graphics/chart_quality.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (chart_quality) {
                $('div[grafico_quality]').html(chart_quality)

            }
        })

        $.ajax({
            url: 'src/pages/graphics/pie_quality.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (pie_quality) {
                $('div[pie_quality]').html(pie_quality)
            }
        })

        $.ajax({
            url: 'src/pages/graphics/line_quality.php',
            method: "POST",
            data: {ano: <?=$Y?>, mes: <?=$M?>},
            success: function (line_quality) {
                $('div[line_quality]').html(line_quality)
            }
        })

        $('select[ano], select[mes]').change(function () {
            let ano = $("select[ano]").val();
            let mes = $("select[mes]").val();

            $.ajax({
                url: "src/pages/resumo.php",
                method: "POST",
                data: {
                    ano,
                    mes
                },
                success: function (grafico) {
                    $('div#home').html(grafico)
                }
            })
        })

        $('button[legenda]').click(function () {
            $.ajax({
                url: "src/pages/legenda.php",
                method: "POST",
                data: {ano: <?=$Y?>, acao: "resumo"},
                success: function (legenda) {
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

        $('button[assinar]').click(function () {
            let ano = '<?= $Y;?>';
            let mes = '<?= $M; ?>';

            $.dialog({
                title: 'ASSINATURA',
                content: function () {
                    var self = this;

                    return $.ajax({
                        url: 'src/fornecedor/assinatura_geral.php',
                        method: 'POST',
                        data: {
                            ano,
                            mes,
                        },
                    }).done(function (retorno) {
                        self.setContent(retorno);
                    });
                },
                columnClass: 'medium'
            })
        });

        $('a[remover_assinatura]').click(function (e) {
            e.preventDefault();
            //@formatter:off
            let obj = $(this).parent().parent();
            let codigo = $(this).attr('codigo');
            let cod_assinatura = $(this).attr('cod_assinatura');
            let ano = '<?= $Y;?>';
            let mes = '<?= $M; ?>';
            //@formatter:on

            $.alert({
                title: false,
                content: 'Tem certeza que deseja remover assinatura?',
                buttons: {
                    sim: {
                        text: 'Sim',
                        action: function () {

                            $.ajax({
                                url: 'src/pages/actions/assinatura_geral_action.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    codigo,
                                    cod_assinatura,
                                    acao: 'remover_assinatura',
                                },
                                success: function (retorno) {
                                    if (retorno.status) {
                                        $.alert(retorno.msg);

                                        $.ajax({
                                            url: "src/pages/resumo.php",
                                            method: "POST",
                                            data: {
                                                ano,
                                                mes
                                            },
                                            success: function (retorno) {
                                                $('div#home').html(retorno)
                                            }
                                        })
                                    } else {
                                        $.alert(retorno.msg);
                                    }
                                }
                            })
                        },
                    },
                    nao: {
                        text: 'Não',
                        action: function () {

                        }
                    }
                }
            })
        });
    })
</script>
