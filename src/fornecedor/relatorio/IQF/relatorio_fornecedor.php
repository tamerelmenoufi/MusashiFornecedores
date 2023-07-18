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


function quality_iqf($m, $a, $f)
{
    global $pdo;

    $quality_iqf = 0;;
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
            $quality_iqf = $quality_iqf + $d['quality'];
        }
    }

    return (($n) ? ($quality_iqf / $p) : 0);

}


function dias_atrasos_tabela($m, $a, $f)
{
    global $pdo;

    $quality_ip_emitido = 0;
    $quality_ip_reincidente = 0;
    $quality_atraso_resposta = 0;
    $quality_ppm = 0;

    $p = 0;

    // for ($i = 11; $i >= 0; $i--) {

        $Mes = $m; //date("m", mktime(0, 0, 0, ($m - $i), 1, $a));
        $Ano = $a; //date("Y", mktime(0, 0, 0, ($m - $i), 1, $a));

        $q = "SELECT

        (b.idm_emitidos) as quality_ip_emitido,
        (c.idm_reincidente) as quality_ip_reincidente,
        (d.demerito) as quality_atraso_resposta,
        (e.demerito) as quality_ppm

    FROM registros_diarios a
        left join aux_idm_emitidos b on a.quality_ip_emitido = b.codigo
        left join aux_idm_reincidente c on a.quality_ip_reincidente = c.codigo
        left join aux_idm_atraso_resposta d on a.quality_atraso_resposta = d.codigo
        left join aux_ppm e on a.quality_ppm = e.codigo

    WHERE

        a.codigo_fornecedor = '{$f}' AND
        month(a.data_registro) = '{$Mes}' AND
        year(a.data_registro) = '{$Ano}'";

        $query = $pdo->prepare($q);
        $query->execute();
        $d = $query->fetch();
        $n = $query->rowCount();
        if ($n) {
            // $p++;
            // $dias_atrasos = $dias_atrasos + $d['atrasos'];
            // $entregas = $entregas + $d['entregas'];


            $quality_ip_emitido = $quality_ip_emitido + $d['quality_ip_emitido'];
            $quality_ip_reincidente = $quality_ip_reincidente + $d['quality_ip_reincidente'];
            $quality_atraso_resposta = $quality_atraso_resposta + $d['quality_atraso_resposta'];
            $quality_ppm = $quality_ppm + $d['quality_ppm'];


        }
    // }


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
        'quality_ip_emitido' => $quality_ip_emitido,
        'quality_ip_reincidente' => $quality_ip_reincidente,
        'quality_atraso_resposta' => $quality_atraso_resposta,
        'quality_ppm' => $quality_ppm,
        'quality' => $d['quality'],

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
    #tabela-assinaturas tbody tr {
        vertical-align: middle !important;
    }

    div[barras], div[linhas] {
        height: 800px;
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

        div[barras], div[linhas] {
            height: auto;
            margin-bottom: 100px;
        }

        .page-break {
            display: block !important;
            page-break-before: always !important;
        }

        .assinaturas-item {
            width: 100%;
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
            <button imprimir type="button" class="btn btn-primary " title="Imprimir"><i class="fa fa-print"
                                                                                        aria-hidden="true"></i></button>
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

        <div class="row m-0 p-2 ">
            <!-- GRAFICOS -->
            <div barras class="col-12 p-0 mb-3"></div>

            <div class="col-md-12 d-flex justify-content-center p-0 mb-3 ">
                <div class="col-3 p-3 text-white bg-danger d-flex flex-column align-items-center justify-content-between">
                    0.00 - 77.99
                    <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                    <footer>
                        DEFICIENTE
                    </footer>
                </div>
                <div class="col-3 p-3 text-white bg-warning d-flex flex-column align-items-center justify-content-between">
                    78.00 - 91.99
                    <i class="fa fa-arrow-right fa-3x" aria-hidden="true"></i>
                    <footer>
                        REGULAR
                    </footer>
                </div>
                <div class="col-3 p-3 text-white bg-primary d-flex flex-column align-items-center justify-content-between">
                    92.00 - 98.99
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
                        <th scope="col">QUALITY</th>
                        <th scope="col">DELIVERY</th>
                        <th scope="col">IQF</th>
                        <th scope="col">POSIÇÃO</th>
                    </tr>
                    </thead>
                    <tbody tfonts>
                    <?php

                    for ($i = 11; $i >= 0; $i--) {

                        $Mes = date("m", mktime(0, 0, 0, ($M - $i), 1, $Y));
                        $Ano = date("Y", mktime(0, 0, 0, ($M - $i), 1, $Y));
                        // faz comparação da data selecionada com os 12 meses anteriores


                        $q = "SELECT f.nome,
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
                        where am.mes = '" . ($Mes * 1) . "' AND am.ano = '{$Ano}' and am.codigo_fornecedor = '{$_POST['codigo_fornecedor']}' order by am.eficiencia asc";
                        $query = $pdo->prepare($q);
                        $query->execute();
                        $d = $query->fetch();
                        $posicao[$d['codigo']] = number_format($d['classificacao'], 2);
                        ?>
                        <tr>
                            <td><?= mesExtenso($Mes) ?>-<?= $Ano ?></td>
                            <td><?= number_format($d['quality'], 2) ?></td>
                            <td><?= number_format($d['delivery'], 2) ?></td>
                            <td><?= number_format($d['classificacao'], 2) ?></td>

                            <!-- <td><?= ((number_format(quality_iqf($Mes, $Ano, $_POST['codigo_fornecedor']), 2)) ?: false) ?></td> -->
                            <td posicao<?=$d['codigo']?>></td>
                        </tr>
                        <?php
                    }
                    @arsort($posicao);
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<div class="page-break"></div>

<div class="container-fluid">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center g-3 m-3">
            <div rs="" class="col-12 text-center">
                <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> ACOMPANHAMENTO DE EMISSÃO DE IP</h3>
            </div>
        </div>
    </div>

    <div linhas class="col-12 p-0 mb-3"></div>

    <div tabela class="col-md-12 mb-3 p-0 ">
        <table class="table table-striped table">
            <thead tfonts>
            <tr>
                <th scope="col">CRITÉRIOS Q</th>
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
                            <td scope="col">IPEmitido</td>
                        </tr>
                        <tr>
                            <td scope="col">IPOficial"R"</td>
                        </tr>
                        <tr>
                            <td scope="col">Atraso Resp.</td>
                        </tr>
                        <tr>
                            <td scope="col">PPM</td>
                        </tr>
                        <tr>
                            <td scope="col">QUALITY</td>
                        </tr>
                        <tr>
                            <td scope="col">IQF</td>
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
                                <td scope="col">&nbsp;<?= $retorno['quality_ip_emitido'] ?></td>
                            </tr>
                            <tr>
                                <td scope="col">&nbsp;<?= $retorno['quality_ip_reincidente'] ?></td>
                            </tr>
                            <tr>
                                <td scope="col">&nbsp;<?= $retorno['quality_atraso_resposta'] ?></td>
                            </tr>
                            <tr>
                                <td scope="col">&nbsp;<?= $retorno['quality_ppm'] ?></td>
                            </tr>
                            <tr>
                                <td scope="col">&nbsp;<?= $retorno['quality'] ?></td>
                            </tr>
                            <tr>
                                <td scope="col">
                                    &nbsp;<?= ((number_format(quality_iqf($Mes, $Ano, $_POST['codigo_fornecedor']), 0)) ?: false) ?></td>
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

    <div class="row m-0 p-0 justify-content-center">
        <?php
        $sql = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = '{$_POST['codigo_fornecedor']}' AND ano = '{$Ano}'  AND mes = '{$Mes}' AND status = 1");
        $sql->execute();

        if ($sql->rowCount()) {
            $pontuacao = $sql->fetch();
            $query = $pdo->prepare("SELECT count(codigo) as quantidade FROM avaliacao_mensal WHERE ano = '{$Ano}'  AND mes = '{$Mes}' AND status = 1");
            $query->execute();
            $qnt = $query->fetch();

            ?>

            <input type="hidden" cod_mensal value="<?= $pontuacao['codigo'] ?>">

            <div class="col-md-3 col-6 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>FORNECEDORES AVALIADOS</h6>
                    <p><?= $qnt['quantidade'] ?></p>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>RESULTADO DA PERFORMANCE</h6>
                    <p><?= $pontuacao['classificacao'] ?></p>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>CLASSIFICAÇÃO Q&D</h6>
                    <p><?= $pontuacao['qualificacao_ipf'] ?></p>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>POSIÇÃO NO RANKING</h6>
                    <p><?= (($pontuacao['posicao']) ? "{$pontuacao['posicao']}º" : false) ?></p>
                </div>
            </div>

            <!--<div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100">
                    <h6>DATA QAV-1</h6>
                    <?php
            /*                    if ($pontuacao['qav_data'] == NULL) {
                                    */ ?>
                        <p></p>
                        <?php
            /*                    } else {
                                    */ ?>
                        <p><? /*= date('d/m/Y', strtotime($pontuacao['qav_data'])) */ ?></p>
                        <?php
            /*                    }
                                */ ?>
                </div>
            </div>-->

            <!--<div class="col-md-2 col-4 mb-3">
                <div class="rounded p-2 text-center border h-100 ">
                    <h6>NOTA QAV-1</h6>
                    <div class="input-group">
                        <?php
            /*                        if ($pontuacao['qav'] == NULL || $pontuacao['qav'] == 0){
                                        */ ?>
                            <input type="number" qav class="form-control">
                            <div class="input-group-text p-0">
                                <button qav_av class="btn btn-success btn-sm h-100 w-100"
                                        style="border-radius: 0px 3px 3px 0px;">Avaliar
                                </button>
                            </div>
                            <?php
            /*                        }else{
                                    */ ?>
                    </div>
                    <p><? /*= $pontuacao['qav'] */ ?></p>
                    <?php /*if ($ConfUsu['tipo'] == '1') { */ ?>
                        <div class="d-grid gap-2 noprint">
                            <button qav_limpar type="button" class="btn btn-danger btn-sm">LIMPAR NOTA</button>
                        </div>
                    <?php /*} */ ?>
                    <?php
            /*                    }
                                */ ?>
                </div>
            </div>-->
        <?php } ?>
    </div>

</div>

<div class="page-break"></div>

<div class="container-fluid">
    <div class="justify-content-center align-items-center g-3 m-3">
        <!-- Card assinaturas-->
        <div class="row my-4 p-0">
            <?php
            $assinaturas_data = @json_decode($pontuacao['assinaturas_iqf'], true) ?: [];
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
        if($posicao){
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
            //alert('OPC: ' + codigo_fornecedor);
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
                            tipo_relatorio
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

            let codigo = $(this).attr('cod');
            let codigo_mensal = $(this).attr('cod_mensal');
            let obj = $(this).parent();
            let tipo_relatorio = '<?=$tipo_relatorio?>';

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
                                    acao: 'remover_assinatura',
                                    tipo_relatorio,
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