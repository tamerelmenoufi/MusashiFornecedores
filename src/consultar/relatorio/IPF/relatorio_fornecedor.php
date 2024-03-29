<?php
// require_once "../../../../lib/config.php";
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
        <div class="d-flex justify-content-between align-items-center flex-row">
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
            <span class="fw-light">Fornecedor:</span><h5><?= utf8decode($fornecedor['nome']) ?> <i
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
                        <th scope="col">GERAL(Q&D)</th>
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
                        ?>
                        <tr>
                            <td><?= mesExtenso($d['mes']) ?>-<?= $d['ano'] ?></td>
                            <td><?= number_format($d['quality'], 2) ?></td>
                            <td><?= number_format($d['delivery'], 2) ?></td>
                            <td><?= number_format(($d['qd']), 2) ?></td>
                            <td><?= number_format($d['IPF'], 2) ?></td>
                            <td><?= $d['posicao'] ?></td>
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
                    <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> DESEMPENHO QUALITY E DELIVERY</h3>
                </div>
            </div>
        </div>
        <div linhas class="col-12 p-0 mb-3" style="height: 800px"></div>


        <div class="row m-0 p-0 justify-content-center ">
            <?php
            $sql = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = :cf AND ano = :y  AND mes = :m AND status = 1");
            $sql->bindValue(":cf", $_POST['codigo_fornecedor']);
            $sql->bindValue(":y", $Y);
            $sql->bindValue(":m", $M);
            $sql->execute();

            //if($sql->rowCount()){
            $pontuacao = $sql->fetch();

            $query = $pdo->prepare("SELECT count(codigo) as quantidade FROM avaliacao_mensal WHERE ano = :y  AND mes = :m AND status = 1");
            $query->bindValue(":y", $Y);
            $query->bindValue(":m", $M);
            $query->execute();

            $qnt = $query->fetch();
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
                    <p><?= $pontuacao['posicao'] ?>º</p>
                </div>
            </div>
            <div class="col-md-2 col-4">
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
            <?php
            //}
            ?>
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
            //alert(mes);
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