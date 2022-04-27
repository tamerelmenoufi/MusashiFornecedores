<?php
// require_once "../../../../lib/config.php";

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


function deliver_iaf($m, $a, $f)
{
    global $pdo;

    $deliver_iaf = 0;;
    $p = 0;
    for ($i = 11; $i >= 0; $i--) {

        $Mes = date("m", mktime(0, 0, 0, ($m - $i), 1, $a));
        $Ano = date("Y", mktime(0, 0, 0, ($m - $i), 1, $a));

        $query = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE
                                    codigo_fornecedor = '{$f}' AND
                                    mes = '{$Mes}' AND
                                    ano = '{$Ano}'
                                ");
        $query->execute();
        $d = $query->fetch();
        $n = $query->rowCount();
        if ($n) {
            $p++;
            $deliver_iaf = $deliver_iaf + $d['delivery'];
        }
    }

    return (($n) ? ($deliver_iaf / $p) : 0);

}


function dias_atrasos_tabela($m, $a, $f)
{
    global $pdo;

    $delivery_idm_emitidos = 0;
    $delivery_idm_reincidente = 0;
    $delivery_atraso_resposta = 0;
    $delivery_comunicacao = 0;
    $delivery_parada_linha = 0;

    $p = 0;

    for ($i = 11; $i >= 0; $i--) {

        $Mes = date("m", mktime(0, 0, 0, ($m - $i), 1, $a));
        $Ano = date("Y", mktime(0, 0, 0, ($m - $i), 1, $a));

        $query = $pdo->prepare("SELECT
                                        sum(delivery_idm_emitidos) as delivery_idm_emitidos,
                                        sum(delivery_idm_reincidente) as delivery_idm_reincidente,
                                        sum(delivery_atraso_resposta) as delivery_atraso_resposta,
                                        sum(delivery_comunicacao) as delivery_comunicacao,
                                        sum(delivery_parada_linha) as delivery_parada_linha

                                    FROM registros_diarios WHERE

                                        codigo_fornecedor = '{$f}' AND
                                        month(data_registro) = '{$Mes}' AND
                                        year(data_registro) = '{$Ano}'
                                ");
        $query->execute();
        $d = $query->fetch();
        $n = $query->rowCount();
        if ($n) {
            // $p++;
            // $dias_atrasos = $dias_atrasos + $d['atrasos'];
            // $entregas = $entregas + $d['entregas'];


            $delivery_idm_emitidos = $delivery_idm_emitidos + $d['delivery_idm_emitidos'];
            $delivery_idm_reincidente = $delivery_idm_reincidente + $d['delivery_idm_reincidente'];
            $delivery_atraso_resposta = $delivery_atraso_resposta + $d['delivery_atraso_resposta'];
            $delivery_comunicacao = $delivery_comunicacao + $d['delivery_comunicacao'];
            $delivery_parada_linha = $delivery_parada_linha + $d['delivery_parada_linha'];


        }
    }


    $query = $pdo->prepare("SELECT f.nome,
        am.mes,
        am.ano,
        am.eficiencia,
        am.quality,
        am.delivery,
        am.classificacao,
        am.posicao,
        am.*
        FROM `avaliacao_mensal` am
        LEFT JOIN fornecedores f ON am.codigo_fornecedor = f.codigo
        where f.codigo = {$f} AND am.mes = '" . ($Mes * 1) . "' AND am.ano = '{$Ano}'");

    $query->execute();
    $n = $query->rowCount();
    $d = $query->fetch();


    return [
        'delivery_idm_emitidos' => (($n) ? ($delivery_idm_emitidos) : '-'),
        'delivery_idm_reincidente' => (($n) ? ($delivery_idm_reincidente) : '-'),
        'delivery_atraso_resposta' => (($n) ? ($delivery_atraso_resposta) : '-'),
        'delivery_comunicacao' => (($n) ? ($delivery_comunicacao) : '-'),
        'delivery_parada_linha' => (($n) ? ($delivery_parada_linha) : '-'),
        'pct_atendimento' => (($n) ? ($d['eficiencia']) : '-'),
        'delivery' => (($n) ? ($d['delivery']) : '-'),
        'delivery_entrega' => (($n) ? ($d['delivery_entrega']) : '-'),
    ];

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
    }

</style>

<div class="container-fluid">
    <div painel class="row justify-content-center align-items-center g-3 m-3">

        <div class="d-flex justify-content-between align-items-center">
            <div rs>
                <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> Relatório de Desempenho</h3>
            </div>


            <div class="d-flex flex-row">
                <div class="noprint">
                    <button
                            imprimir
                            type="button"
                            class="btn btn-primary me-2"
                            title="Imprimir"
                    >
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="noprint">
                    <button
                            voltar
                            type="button"
                            class="btn btn-light fs-6 pull-right noprint"
                    >
                        <i class="fa fa-angle-left" aria-hidden="true"></i> voltar
                    </button>
                </div>
            </div>
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
        <div class="row m-0 p-2 ">
            <!-- GRAFICOS -->
            <div barras class="col-12 p-0 mb-3" style="height: 800px"></div>

            <div class="col-md-12 d-flex justify-content-center p-0 mb-3 ">
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
            <div tabela class="col-md-12 mb-3 p-0 ">
                <table class="table table-striped table">
                    <thead tfonts>
                    <tr>
                        <th scope="col">MÊS</th>
                        <th scope="col">DELIVERY</th>
                        <th scope="col">IAF</th>
                        <th scope="col">POSIÇÃO</th>
                    </tr>
                    </thead>
                    <tbody tfonts>
                    <?php

                    for ($i = 11; $i >= 0; $i--) {

                        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
                        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

                        // faz comparação da data selecionada com os 12 meses anteriores

                        $query = $pdo->prepare("SELECT f.nome,
                            am.mes,
                            am.ano,
                            am.eficiencia,
                            am.quality,
                            am.delivery,
                            am.classificacao,
                            am.posicao,
                            am.*
                            FROM `avaliacao_mensal` am
                            LEFT JOIN fornecedores f ON am.codigo_fornecedor = f.codigo
                            where am.mes = '" . ($Mes * 1) . "' AND am.ano = '{$Ano}' and am.codigo_fornecedor = '{$_POST['codigo_fornecedor']}'");
                        $query->execute();
                        $d = $query->fetch();

                        ?>
                        <tr>
                            <td><?= mesExtenso($Mes) ?>-<?= $Ano ?></td>
                            <td><?= number_format($d['delivery'], 2) ?></td>
                            <td><?= ((number_format(deliver_iaf($Mes, $Ano, $_POST['codigo_fornecedor']), 2)) ?: false) ?></td>
                            <td><?= $d['posicao_delivery'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center g-3 m-3">
                <div rs="" class="col-12 text-center">
                    <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> ACOMPANHAMENTO DA ENTREGA X DIAS DE ATRASO
                    </h3>
                </div>
            </div>
        </div>
        <div linhas class="col-12 p-0 mb-3" style="height: 800px"></div>


        <div tabela class="col-md-12 mb-3 p-0 ">
            <table class="table table-striped table">
                <thead tfonts>
                <tr>
                    <th scope="col">CRITÉRIOS D</th>
                    <?php
                    for ($i = 11; $i >= 0; $i--) {

                        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
                        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));
                        ?>
                        <th scope="col"><?= mesExtenso($Mes) ?>-<?= $Ano ?></th>
                        <?php
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <tr>

                    <td>
                        <table class="table">
                            <tr>
                                <td scope="col">IDMemitido</td>
                            </tr>
                            <tr>
                                <td scope="col">IDM"R"</td>
                            </tr>
                            <tr>
                                <td scope="col">Atraso Resp.</td>
                            </tr>
                            <tr>
                                <td scope="col">%Atendimento</td>
                            </tr>
                            <tr>
                                <td scope="col">Delivery Entrega</td>
                            </tr>
                            <tr>
                                <td scope="col">Comunicação</td>
                            </tr>
                            <tr>
                                <td scope="col">Parada de Linha</td>
                            </tr>
                            <tr>
                                <td scope="col">DELIVERY</td>
                            </tr>
                            <tr>
                                <td scope="col">IAF</td>
                            </tr>

                        </table>
                    </td>

                    <?php
                    for ($i = 11; $i >= 0; $i--) {

                        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
                        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));

                        $retorno = dias_atrasos_tabela($Mes, $Ano, $_POST['codigo_fornecedor']);

                        ?>
                        <td>
                            <table class="table">
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['delivery_idm_emitidos'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['delivery_idm_reincidente'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['delivery_atraso_resposta'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['pct_atendimento'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['delivery_entrega'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['delivery_comunicacao'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['delivery_parada_linha'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">&nbsp;<?= $retorno['delivery'] ?></td>
                                </tr>
                                <tr>
                                    <td scope="col">
                                        &nbsp;<?= ((number_format(deliver_iaf($Mes, $Ano, $_POST['codigo_fornecedor']), 0)) ?: false) ?></td>
                                </tr>
                            </table>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="row m-0 p-0 justify-content-center ">
            <?php
            $sql = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = '{$_POST['codigo_fornecedor']}' AND ano = '{$Ano}'  AND mes = '{$Mes}' AND status = 1");
            $sql->execute();

            if ($sql->rowCount()) {
                $pontuacao = $sql->fetch();
                $query = $pdo->prepare("SELECT count(codigo) as quantidade FROM avaliacao_mensal WHERE ano = '{$Ano}'  AND mes = '{$Mes}' AND status = 1");
                $query->execute();
                $qnt = $query->fetch();
            }
            ?>
            <div class="col-md-2 col-4">
                <div class="rounded p-2 text-center border h-100">
                    <h6>FORNECEDORES AVALIADOS</h6>
                    <p><?= $qnt['quantidade'] ?></p>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="rounded p-2 text-center border h-100">
                    <h6>RESULTADO DA PERFORMANCE</h6>
                    <p><?= $pontuacao['classificacao'] ?></p>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="rounded p-2 text-center border h-100">
                    <h6>CLASSIFICAÇÃO Q&D</h6>
                    <p><?= $pontuacao['qualificacao_ipf'] ?></p>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="rounded p-2 text-center border h-100">
                    <h6>POSIÇÃO NO RANKING</h6>
                    <p><?= (($pontuacao['posicao']) ? "{$pontuacao['posicao']}º" : false) ?></p>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="rounded p-2 text-center border h-100">
                    <h6>DATA QAV-1</h6>
                    <?php
                    if ($pontuacao['qav_data'] == NULL) {
                        ?>
                        <p></p>
                        <?php
                    } else {
                        ?>
                        <p><?= date('d/m/Y', strtotime($pontuacao['qav_data'])) ?></p>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-2 col-4">
                <div class="rounded p-2 text-center border h-100 ">
                    <h6>NOTA QAV-1</h6>
                    <div class="input-group">
                        <?php
                        if ($pontuacao['qav'] == NULL || $pontuacao['qav'] == 0){
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
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(function () {


        $('button[imprimir]').click(function () {
            window.print();
        })

        $('select[ano],select[mes], select[tipo_relatorio]').change(function () {
            let ano = $('select[ano]').val();
            let mes = $('select[mes]').val();
            let tipo_relatorio = $('select[tipo_relatorio]').val();
            let codigo_fornecedor = $('input[fornecedor]').attr('fornecedor');
            //alert('OPC: ' + codigo_fornecedor);
            $.ajax({
                url: 'src/consultar/relatorio_fornecedor.php',
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
                url: 'src/consultar/listagem_mes.php',
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
                }
            })

            $.ajax({
                url: 'src/consultar/relatorio_fornecedor.php',
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
        })


        let codigo_fornecedor = $('input[fornecedor]').attr('fornecedor');
        let ano = '<?=$Y?>';
        let mes = '<?=$M?>';
        let tipo_relatorio = '<?=$tipo_relatorio?>';

        $.ajax({
            url: 'src/consultar/relatorio/<?=$tipo_relatorio?>/barras.php',
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
            url: 'src/consultar/relatorio/<?=$tipo_relatorio?>/linhas.php',
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