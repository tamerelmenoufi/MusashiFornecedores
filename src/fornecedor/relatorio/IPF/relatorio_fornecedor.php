<?php
#require_once "../../../../lib/config.php";
// error_reporting(E_ALL);
global $pdo;

if (isset($_POST['ano'])) {
    $Y = $_POST['ano'];
} else {
    $Y = date("Y");
}

if (isset($_POST['mes'])) {
    $M = str_pad($_POST['mes'], 2, "0", STR_PAD_LEFT);
} else {
    $M = date("m");
}
if (isset($_POST['tipo_relatorio'])) {
    $tipo_relatorio = $_POST['tipo_relatorio'];
} else {
    $tipo_relatorio = "IPF";
}

$query = $pdo->prepare("SELECT * FROM fornecedores WHERE codigo = :c");
$query->bindValue(':c', $_POST['codigo_fornecedor']);
$query->execute();

$fornecedor = $query->fetch();

function mesExtenso($mes)
{
    switch ($mes) {
        case '1':
            echo 'Jan';
            break;
        case '2':
            echo 'Fev';
            break;
        case '3':
            echo 'Mar';
            break;
        case '4':
            echo 'Abr';
            break;
        case '5':
            echo 'Mai';
            break;
        case '6':
            echo 'Jun';
            break;
        case '7':
            echo 'Jul';
            break;
        case '8':
            echo 'Ago';
            break;
        case '9':
            echo 'Set';
            break;
        case '10':
            echo 'Out';
            break;
        case '11':
            echo 'Nov';
            break;
        case '12':
            echo 'Dez';
            break;
    }
}

?>

<style>
    #tabela-assinaturas tbody tr {
        vertical-align: middle !important;
    }

    div[barras], div[linhas] {
        height: 800px;
    }

    @media all {
        .page-break {
            display: none;
        }
    }

    @media print {
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

        .assinaturas-item {
            width: 100%;
        }

        div[barras], div[linhas] {
            height: auto;
            margin-bottom: 100px;
        }

        .page-break {
            display: block !important;
            page-break-before: always !important;
        }
    }
</style>

<div class="container-fluid">
    <div painel class="row justify-content-center align-items-center g-3 m-3">

        <div rs class="col-4">
            <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> Relatório de Desempenho</h3>
        </div>

        <div class="col-1 noprint">
            <select ano class="form-select">
                <option value="<?= $Y ?>" selected><?= $Y ?></option>
                <?php
                $query = $pdo->prepare("SELECT ano FROM avaliacao_anual WHERE codigo_fornecedor = {$_POST['codigo_fornecedor']}");
                $query->execute();

                while ($options = $query->fetch()) {
                    ?>
                    <option value="<?= $options['ano'] ?>"><?= $options['ano'] ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="col-1 noprint">
            <select mes class="form-select">
                <option value="<?= $M ?>" selected><?= $M ?></option>
                <?php
                for ($i = 1; $i <= 12; $i++) {
                    ?>
                    <option value="<?= str_pad($i, 2, "0", STR_PAD_LEFT) ?>"><?= str_pad($i, 2, "0", STR_PAD_LEFT) ?></option>
                    <?php
                }
                ?>
            </select>

        </div>

        <div class="col-2 noprint">
            <select tipo_relatorio class="form-select">
                <option value="IPF" <?= $tipo_relatorio == 'IPF' || $tipo_relatorio == '' ? 'selected' : '' ?>>IPF
                </option>
                <option value="IQF" <?= $tipo_relatorio == 'IQF' ? 'selected' : '' ?> >IQF</option>
                <option value="IAF" <?= $tipo_relatorio == 'IAF' ? 'selected' : '' ?> >IAF</option>

            </select>

        </div>

        <div class="col-2 noprint">
            <button imprimir type="button" class="btn btn-primary " title="Imprimir">
                <i class="fa fa-print" aria-hidden="true"></i>
            </button>
        </div>

        <div class="col-2 noprint">
            <button voltar type="button" class="btn btn-light fs-6 pull-right noprint"><i class="fa fa-angle-left"
                                                                                          aria-hidden="true"></i> voltar
            </button>
        </div>

        <div class="col-5">
            <span class="fw-light">Fornecedor:</span><h5><?= utf8_encode($fornecedor['nome']) ?> <i
                        class="fa fa-handshake-o" aria-hidden="true"></i></h5>
        </div>

        <input type="hidden" fornecedor="<?= $_POST['codigo_fornecedor'] ?>">

        <div class="col-3 ">
            <span class="fw-light">CNPJ:</span>
            <p><?= $fornecedor['cnpj'] ?></p>
        </div>

        <div class="col-2 ">
            <span class="fw-light">Data de inicio:</span>
            <p><?= date('d/m/Y', strtotime($fornecedor['data_inicio'])) ?></p>
            <input type="hidden" inicio="<?= $fornecedor['data_inicio'] ?>">
        </div>

        <div class="col-2 ">
            <span class="fw-light">Data de Conclusão:</span>
            <p><?= date('d/m/Y', strtotime($fornecedor['data_fim'])) ?></p>
            <input type="hidden" fim="<?= $fornecedor['data_fim'] ?>">
        </div>

    </div>

    <div class="container-fluid">
        <div class="row m-0 p-2 ">
            <!-- GRAFICOS -->
            <div barras class="col-12 p-0 mb-3"></div>

            <div class="col-md-12 d-flex justify-content-center p-0 mb-3 quadros">
                <div class="col-3 p-3 text-white bg-danger d-flex flex-column align-items-center justify-content-between">
                    0.00 - 84.99
                    <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                    <footer>
                        DEFICIENTE
                    </footer>
                </div>
                <div class="col-3 p-3 text-white bg-warning d-flex flex-column align-items-center justify-content-between">
                    85.00 - 93.99
                    <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                    <footer>
                        REGULAR
                    </footer>
                </div>
                <div class="col-3 p-3 text-white bg-primary d-flex flex-column align-items-center justify-content-between">
                    94.00 - 98.99
                    <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                    <footer>
                        BOM
                    </footer>
                </div>
                <div class="col-3 p-3 text-white bg-success d-flex flex-column align-items-center justify-content-between">
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

<div class="page-break"></div>

<div class="container-fluid">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center g-3 m-3">
            <div rs="" class="col-12 text-center">
                <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> HISTÓRICO DO PERÍODO AVALIADO:</h3>
            </div>
        </div>
    </div>

    <div tabela class="col-md-12 mb-3 p-0 ">
        <table class="table table-striped table">
            <thead tfonts>
            <tr>
                <th scope="col">MÊS</th>
                <th scope="col">QUALITY</th>
                <th scope="col">DELIVERY</th>
                <!-- <th scope="col">GERAL(Q&D)</th> -->
                <th scope="col">GERAL(IPF)</th>
                <th scope="col">POSIÇÃO</th>
            </tr>
            </thead>
            <tbody tfonts>
            <?php
            // faz comparação da data selecionada com os 12 meses anteriores
            $query = "SELECT *,
                                        (am.quality+am.delivery)/2 as qd,

                                        (
                                            SELECT AVG((t2.quality+t2.delivery)/2)
                                            FROM avaliacao_mensal t2
                                            WHERE t2.codigo_fornecedor = am.codigo_fornecedor
                                                AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) >= -11
                                                AND TIMESTAMPDIFF(MONTH, am.anoMes, t2.anoMes) <= 0
                                        ) AS IPF
                                        FROM avaliacao_mensal am
                                        WHERE codigo_fornecedor = :cf
                                            AND DATE(concat(am.ano, '-', am.mes, '-01')) <= DATE(LAST_DAY(DATE(concat(:y2, '-', :m2, '-01'))))
                                            AND DATE(concat(am.ano, '-', am.mes, '-01')) >= DATE_SUB(concat(:y3, '-', :m3, '-01'), INTERVAL 11 MONTH)
                                        ORDER BY am.ano, am.mes";
            $sql = $pdo->prepare($query);
            $sql->bindValue(":cf", $_POST['codigo_fornecedor']);
            $sql->bindValue(":y2", $Y);
            $sql->bindValue(":y3", $Y);
            $sql->bindValue(":m2", $M);
            $sql->bindValue(":m3", $M);
            $sql->execute();
            while ($d = $sql->fetch()) {
                $posicao[$d['codigo']] = number_format($d['qd'], 2);
                ?>
                <tr>
                    <td><?= mesExtenso($d['mes']) ?>-<?= $d['ano'] ?></td>
                    <td><?= number_format($d['quality'], 2) ?></td>
                    <td><?= number_format($d['delivery'], 2) ?></td>
                    <td><?= number_format(($d['qd']), 2) ?></td>
                    <!-- <td><?= number_format($d['IPF'], 2) ?></td> -->
                    <td posicao<?=$d['codigo']?>></td>
                </tr>
                <?php
            }
            arsort($posicao);
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="page-break"></div>

<div class="container-fluid">

    <div class="container-fluid">
        <div class="row justify-content-center align-items-center g-3 m-3">
            <div rs="" class="col-12 text-center">
                <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> DESEMPENHO QUALITY E DELIVERY</h3>
            </div>
        </div>
    </div>

    <div linhas class="col-12 p-0 mb-3"></div>

    <div class="row m-0 p-0 justify-content-center">
        <?php
        $sql = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = :cf AND ano = :y  AND mes = :m AND status = 1");
        $sql->bindValue(":cf", $_POST['codigo_fornecedor']);
        $sql->bindValue(":y", $Y);
        $sql->bindValue(":m", $M);
        $sql->execute();

        if ($sql->rowCount()) {
            $pontuacao = $sql->fetch();

            $query = $pdo->prepare("SELECT count(codigo) as quantidade FROM avaliacao_mensal WHERE ano = :y  AND mes = :m AND status = 1");
            $query->bindValue(":y", $Y);
            $query->bindValue(":m", $M);
            $query->execute();

            $qnt = $query->fetch();

            ?>
            <input type="hidden" cod_mensal value="<?= $pontuacao['codigo'] ?>">

            <div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>FORNECEDORES AVALIADOS</h6>
                    <p><?= $qnt['quantidade'] ?></p>
                </div>
            </div>

            <div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>RESULTADO DA PERFORMANCE</h6>
                    <p><?= $pontuacao['classificacao'] ?></p>
                </div>
            </div>

            <div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>CLASSIFICAÇÃO Q&D</h6>
                    <p><?= $pontuacao['qualificacao_ipf'] ?></p>
                </div>
            </div>

            <div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>POSIÇÃO NO RANKING</h6>
                    <p><?= $pontuacao['posicao'] ?>º</p>
                </div>
            </div>

            <div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>DATA QAV-1</h6>
                    <?php
                    if ($pontuacao['qav_data'] == NULL) {
                        ?>
                        <p>0000-00-00</p>
                        <?php
                    } else {
                        ?>
                        <p><?= date('d/m/Y', strtotime($pontuacao['qav_data'])) ?></p>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100 ">
                    <h6>NOTA QAV-1</h6>
                    <div class="input-group">
                        <?php
                        if (($pontuacao['qav'] == NULL || $pontuacao['qav'] == 0) and !$_SESSION['musashi_cod_forn']){
                            ?>
                            <input type="number" qav class="form-control">
                            <div class="input-group-text p-0">
                                <button qav_av class="btn btn-success btn-sm h-100 w-100"
                                        style="border-radius: 0px 3px 3px 0px;">Avaliar
                                </button>
                            </div>
                            <?php
                        }else{
                        ?>
                    </div>
                    <p><?= $pontuacao['qav'] ?></p>
                    <?php if ($ConfUsu['tipo'] == '1') { ?>
                        <div class="d-grid gap-2 noprint">
                            <button qav_limpar type="button" class="btn btn-danger btn-sm">LIMPAR NOTA</button>
                        </div>
                    <?php } ?>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<div class="page-break"></div>

<div class="container-fluid">
    <div class="justify-content-center align-items-center g-3 m-3">
        <!-- Card assinaturas-->
        <div class="row my-4 p-0">
            <?php
            $assinaturas_data = @json_decode($pontuacao['assinaturas_ipf'], true) ?: [];
            $search = array_search($_SESSION['musashi_cod_usu'], array_column($assinaturas_data, 'codigo'));
            $is_assinado = ($search >= 0 and $search !== false);
            ?>
            <div class="position-relative mb-4">
                <h3 class="text-center">
                    <i class="fa fa-check-square-o" aria-hidden="true"></i> ASSINATURAS
                </h3>

                <?php if ($ConfUsu['assinante_documento'] === 'S' and $pontuacao['codigo'] and !$_SESSION['musashi_cod_forn']) { ?>
                    <button
                            assinar
                            type="button"
                            class="btn btn-success noprint"
                            style="position:absolute; right: 20px; top:0 "
                        <?= $is_assinado ? 'disabled' : '' ?>
                    >
                        <i
                                class="fa fa-pencil-square-o"
                                aria-hidden="true"
                        ></i> <span text><?= $is_assinado ? 'ASSINADO' : 'ASSINAR' ?></span>
                    </button>
                <?php } ?>
            </div>

            <?php

            if ($pontuacao['codigo']) { ?>
                <?php if ($assinaturas_data) { ?>
                    <?php foreach ($assinaturas_data as $ass) {
                        $url_qrcode = "http://musashi.mohatron.com/validacao-de-assinatura.php?v={$ass['chave']}"
                        ?>
                        <div class="col-4 mb-2 assinaturas-item">
                            <div class="rounded border h-100 px-3 py-2 position-relative">
                                <?php
                                if(!$_SESSION['musashi_cod_forn']){
                                ?>
                                <a
                                        href="#"
                                        class="position-absolute text-danger noprint"
                                        remover_assinatura
                                        cod="<?= $ass['codigo']; ?>"
                                        cod_mensal="<?= $pontuacao['codigo']; ?>"
                                        style="top: 0; right: 5px"
                                >
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </a>
                                <?php
                                }
                                ?>
                                <div class="d-flex flex-row justify-content-between">
                                    <div style="flex:1">
                                        <div title="Usuário">
                                            <i class="fa fa-user" aria-hidden="true"></i> <?= $ass['usuario']; ?>
                                        </div>
                                        <div title="Cargo">
                                            <i class="fa fa-briefcase" aria-hidden="true"></i> <?= $ass['cargo']; ?>
                                        </div>
                                        <div title="Data e Hora da assinatura">
                                            <i class="fa fa-calendar"
                                               aria-hidden="true"></i> <?= date("d/m/Y H:i", strtotime($ass['data_hora'])) ?>
                                        </div>
                                        <div title="Chave">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                            <small><?= $ass['chave']; ?></small>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center px-1">
                                        <img
                                                src="src/fornecedor/barcode.php?f=png&s=qr&d=<?= $url_qrcode ?>"
                                                style="width: 80px"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
            } else { ?>
                <p class="text-center text-muted"><?= "{$Y}/{$M} não possui registro para este fornecedor"; ?></p>
            <?php } ?>
        </div>
        <!-- End Card assinaturas-->

    </div>
</div>


<script>

    $(function () {

        <?php
        $p = 0;
        $pos = 0;
        $g = 0;
        foreach($posicao as $ind => $val){
            $g++;
            if($pos == 0) $pos = $val;
            if($pos == $val and $p == 0) {$p = 1;}
            if($pos != $val) {$pos = $val; $p = ($g-1);}
            if($p > 2) $p = $g;
        ?>
        $("td[posicao<?=$ind?>]").html('<?=(($val == '0.00')?'-':$p)?>');
        <?php
        }
        ?>


        $('button[imprimir]').click(function () {
            window.print();
        })

        $('select[ano],select[mes], select[tipo_relatorio]').change(function () {
            let ano = $('select[ano]').val();
            let mes = $('select[mes]').val();
            let tipo_relatorio = $('select[tipo_relatorio]').val();
            let codigo_fornecedor = $('input[fornecedor]').attr('fornecedor');
            //alert(mes);
            $.ajax({
                url: 'src/fornecedor/relatorio_fornecedor.php',
                method: 'POST',
                data: {
                    codigo_fornecedor,
                    ano,
                    mes,
                    tipo_relatorio
                }, success: function (retorno) {
                    $('div#home').html(retorno)

                    // $.ajax({
                    //     url: 'src/fornecedor/barras.php',
                    //     method: 'POST',
                    //     data: {
                    //         codigo: codigo_fornecedor,
                    //         ano
                    //     },success: function(chart){
                    //         $('div[barras]').html(chart)

                    //     }
                    // })

                    // $.ajax({
                    //     url: 'src/fornecedor/linhas.php',
                    //     method: 'POST',
                    //     data: {
                    //         codigo: codigo_fornecedor,
                    //         ano
                    //     },success: function(chart){
                    //         $('div[linhas]').html(chart)

                    //     }
                    // })

                }
            })
        })

        $('button[voltar]').click(function () {
            $.ajax({
                url: '<?=(($_SESSION['musashi_cod_forn'])?'src/consultar/listagem_mes.php':'src/fornecedor/fornecedor_lista.php')?>',
                success: function (retorno) {
                    $('div#home').html(retorno)
                }
            })
        })

        $('button[qav_av]').click(function () {
            let codigo_fornecedor = $('input[fornecedor]').attr('fornecedor')
            let qav = $('input[qav]').val();
            let ano = '<?=$Y?>';
            let mes = '<?=$M?>';


            $.ajax({
                url: 'src/fornecedor/actions/qav_action.php',
                method: 'POST',
                data: {
                    codigo_fornecedor,
                    qav,
                    ano,
                    mes
                },
                success: function () {
                    $.ajax({
                        url: 'src/fornecedor/relatorio_fornecedor.php',
                        method: 'POST',
                        data: {
                            codigo_fornecedor,
                            ano,
                            mes
                        }, success: function (retorno) {
                            $('div#home').html(retorno);

                            // $.ajax({
                            //     url: 'src/fornecedor/barras.php',
                            //     method: 'POST',
                            //     data: {
                            //         codigo: codigo_fornecedor,
                            //         ano
                            //     },success: function(chart){
                            //         $('div[barras]').html(chart)

                            //     }
                            // })

                            // $.ajax({
                            //     url: 'src/fornecedor/linhas.php',
                            //     method: 'POST',
                            //     data: {
                            //         codigo: codigo_fornecedor,
                            //         ano
                            //     },success: function(chart){
                            //         $('div[linhas]').html(chart)

                            //     }
                            // })


                        }
                    })
                }
            })


        })

        $('button[qav_limpar]').click(function () {
            let codigo_fornecedor = $('input[fornecedor]').attr('fornecedor')
            let ano = '<?=$Y?>';
            let mes = '<?=$M?>';

            $.alert({
                title: false,
                content: 'Tem certeza que deseja limpar a nota qav?',
                buttons: {
                    Sim: function () {
                        $.ajax({
                            url: 'src/fornecedor/actions/qav_action.php',
                            method: 'POST',
                            data: {
                                codigo_fornecedor,
                                ano,
                                mes,
                                acao: 'qav_limpar',
                            }, success: function () {

                                $.ajax({
                                    url: 'src/fornecedor/relatorio_fornecedor.php',
                                    method: 'POST',
                                    data: {
                                        codigo_fornecedor,
                                        ano,
                                        mes
                                    }, success: function (retorno) {
                                        $('div#home').html(retorno);
                                    }
                                });

                            }
                        });
                    },
                    Não: function () {

                    }
                }
            })

        })

        $('button[assinar]').click(function () {
            let cod_mensal = $('input[cod_mensal]').val();
            let codigo_fornecedor = $('input[fornecedor]').attr('fornecedor')
            let ano = '<?=$Y?>';
            let mes = '<?=$M?>';
            let tipo_relatorio = '<?=$tipo_relatorio?>';

            $.dialog({
                title: 'ASSINATURA',
                content: function () {
                    var self = this;

                    return $.ajax({
                        url: 'src/fornecedor/assinatura.php',
                        method: 'POST',
                        data: {
                            cod_mensal,
                            codigo_fornecedor,
                            ano,
                            mes,
                            tipo_relatorio,
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

            var codigo = $(this).attr('cod');
            var codigo_mensal = $(this).attr('cod_mensal');
            let tipo_relatorio = '<?=$tipo_relatorio?>';

            var obj = $(this).parent();

            $.alert({
                title: false,
                content: 'Tem certeza que deseja remover assinatura?',
                buttons: {
                    sim: {
                        text: 'Sim',
                        action: function () {
                            $.ajax({
                                url: 'src/fornecedor/actions/assinatura.php',
                                type: 'POST',
                                dataType: 'JSON',
                                data: {
                                    codigo,
                                    codigo_mensal,
                                    tipo_relatorio,
                                    acao: 'remover_assinatura',
                                },
                                success: function (retorno) {
                                    if (retorno.status) {
                                        $.alert(retorno.msg);

                                        obj.remove();

                                        if (retorno.desabilita_btn === true) {
                                            $('button[assinar]')
                                                .removeAttr('disabled')
                                                .find('span[text]')
                                                .text('ASSINAR');
                                        }
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

        let codigo_fornecedor = $('input[fornecedor]').attr('fornecedor');
        let ano = '<?=$Y?>';
        let mes = '<?=$M?>';
        let tipo_relatorio = '<?=$tipo_relatorio?>';

        $.ajax({
            url: 'src/fornecedor/relatorio/<?=$tipo_relatorio?>/barras.php',
            method: 'POST',
            data: {
                codigo: codigo_fornecedor,
                ano,
                mes,
                tipo_relatorio
            }, success: function (chart) {
                $('div[barras]').html(chart)

            }
        })

        $.ajax({
            url: 'src/fornecedor/relatorio/<?=$tipo_relatorio?>/linhas.php',
            method: 'POST',
            data: {
                codigo: codigo_fornecedor,
                ano,
                mes,
                tipo_relatorio
            }, success: function (chart) {
                $('div[linhas]').html(chart)

            }
        })
    })
</script>