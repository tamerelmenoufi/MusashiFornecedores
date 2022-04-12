<?php
    // require_once "../../../../lib/config.php";

    global $pdo;

    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }

    if(isset($_POST['mes'])){
        $M = str_pad($_POST['mes'], 2, "0", STR_PAD_LEFT);
    }else{
        $M = date("m");
    }
    if(isset($_POST['tipo_relatorio'])){
        $tipo_relatorio = $_POST['tipo_relatorio'];
    }else{
        $tipo_relatorio = "IPF";
    }


    function deliver_iaf($m, $a, $f){
        global $pdo;

        $deliver_iaf = 0;;
        $p =  0;
        for($i=11; $i>=0; $i--){

            $Mes = date("m", mktime(0, 0, 0, ($m - $i), 1, $a));
            $Ano = date("Y", mktime(0, 0, 0, ($m - $i), 1, $a));

            $query = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE
                                    codigo_fornecedor = '{$f}' AND
                                    mes = '{$Mes}' AND
                                    ano = '{$Ano}'
                                ");
            $query->execute();
            $d = $query->fetch();

            if($query->rowCount()){
                $p++;
                $deliver_iaf = $deliver_iaf + $d['delivery'];
            }
        }

        return (($deliver_iaf > 0) ? ($deliver_iaf/(($p>0)?:1)) : false);

    }


    $query = $pdo->prepare("SELECT * FROM fornecedores WHERE codigo = :c");
    $query->bindValue(':c',  $_POST['codigo_fornecedor']);
    $query->execute();

    $fornecedor = $query->fetch();

    function mesExtenso($mes){
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

<div class="container-fluid">
    <div painel class="row justify-content-center align-items-center g-3 m-3">
        <div rs class="col-4">
            <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> Relatório de Desempenho</h3>
        </div>

        <div class="col-1 noprint">
            <select ano class="form-select">
                <option value="<?=$Y?>" selected><?=$Y?></option>
                <?php
                    $query = $pdo->prepare("SELECT ano FROM avaliacao_anual WHERE codigo_fornecedor = {$_POST['codigo_fornecedor']}");
                    $query->execute();

                    while($options = $query->fetch()){
                ?>
                    <option value="<?=$options['ano']?>"><?=$options['ano']?></option>
                <?php
                    }
                ?>
            </select>
        </div>
        <div class="col-1 noprint">
            <select mes class="form-select">
                <option value="<?=$M?>" selected><?=$M?></option>
                <?php
                    for($i=1;$i<=12;$i++){
                ?>
                    <option value="<?=str_pad($i, 2, "0", STR_PAD_LEFT)?>"><?=str_pad($i, 2, "0", STR_PAD_LEFT)?></option>
                <?php
                    }
                ?>
            </select>

        </div>

        <div class="col-2 noprint">
            <select tipo_relatorio class="form-select">
                <option value="IPF" <?= $tipo_relatorio == 'IPF' || $tipo_relatorio == '' ? 'selected':''?>>IPF</option>
                <option value="IQF" <?= $tipo_relatorio == 'IQF'? 'selected':''?> >IQF</option>
                <option value="IAF" <?= $tipo_relatorio == 'IAF'? 'selected':''?> >IAF</option>

            </select>

        </div>

        <div class="col-2 noprint">
            <button imprimir type="button" class="btn btn-primary " title="Imprimir"><i class="fa fa-print" aria-hidden="true"></i></button>
        </div>

        <div class="col-2 noprint">
            <button voltar type="button" class="btn btn-light fs-6 pull-right noprint"><i class="fa fa-angle-left" aria-hidden="true"></i> voltar</button>
        </div>


        <div class="col-5">
            <span class="fw-light">Fornecedor:</span><h5><?=utf8_encode($fornecedor['nome'])?> <i class="fa fa-handshake-o" aria-hidden="true"></i></h5>
        </div>
        <input type="hidden" fornecedor="<?=$_POST['codigo_fornecedor']?>">
        <div class="col-3 ">
            <span class="fw-light">CNPJ:</span><p><?=$fornecedor['cnpj']?></p>
        </div>
        <div class="col-2 ">
            <span class="fw-light">Data de inicio:</span><p><?=date('d/m/Y', strtotime($fornecedor['data_inicio']))?></p>
            <input type="hidden" inicio="<?=$fornecedor['data_inicio']?>">
        </div>

        <div class="col-2 ">
            <span class="fw-light">Data de Conclusão:</span><p><?=date('d/m/Y', strtotime($fornecedor['data_fim']))?></p>
            <input type="hidden" fim="<?=$fornecedor['data_fim']?>">
        </div>
        <div  class="row m-0 p-2 ">
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

                            for($i=11; $i>=0; $i--){

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
                            where am.mes = '".($Mes*1)."' AND am.ano = '{$Ano}' and am.codigo_fornecedor = '{$_POST['codigo_fornecedor']}'");
                            $query->execute();
                            $d = $query->fetch();

                        ?>
                        <tr>
                            <td><?=mesExtenso($d['mes'])?>-<?=$d['ano']?></td>
                            <td><?=number_format($d['delivery'], 2)?></td>
                            <td><?=number_format(deliver_iaf($Mes, $Ano, $_POST['codigo_fornecedor']), 2)?></td>
                            <td><?=$d['posicao_quality']?></td>
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
                    <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> ACOMPANHAMENTO DA ENTREGA X DIAS DE ATRASO</h3>
                </div>
            </div>
        </div>
        <div linhas class="col-12 p-0 mb-3" style="height: 800px"></div>


        <div class="row m-0 p-0 justify-content-center ">
            <?php
                $sql = $pdo->prepare("SELECT * FROM avaliacao_mensal WHERE codigo_fornecedor = '{$_POST['codigo_fornecedor']}' AND ano = '{$Ano}'  AND mes = '{$Mes}' AND status = 1");
                $sql->execute();

                if($sql->rowCount()){
                    $pontuacao = $sql->fetch();
                    $query = $pdo->prepare("SELECT count(codigo) as quantidade FROM avaliacao_mensal WHERE ano = '{$Ano}'  AND mes = '{$Mes}' AND status = 1");
                    $query->execute();
                    $qnt = $query->fetch();
                }
                ?>
                    <div class="col-md-2 col-4">
                        <div class="rounded p-2 text-center border h-100">
                            <h6>FORNECEDORES AVALIADOS</h6>
                            <p><?=$qnt['quantidade']?></p>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="rounded p-2 text-center border h-100">
                            <h6>RESULTADO DA PERFORMANCE</h6>
                            <p><?=$pontuacao['classificacao']?></p>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="rounded p-2 text-center border h-100">
                            <h6>CLASSIFICAÇÃO Q&D</h6>
                            <p><?=$pontuacao['qualificacao_ipf']?></p>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="rounded p-2 text-center border h-100">
                            <h6>POSIÇÃO NO RANKING</h6>
                            <p><?=(($pontuacao['posicao'])?"{$pontuacao['posicao']}º":false)?></p>
                        </div>
                    </div>
                    <div class="col-md-2 col-4">
                        <div class="rounded p-2 text-center border h-100">
                            <h6>DATA QAV-1</h6>
                            <?php
                                if($pontuacao['qav_data'] == NULL){
                            ?>
                                <p></p>
                            <?php
                                }else{
                            ?>
                                <p><?=date('d/m/Y', strtotime($pontuacao['qav_data']))?></p>
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
                                    if($pontuacao['qav'] == NULL || $pontuacao['qav'] == 0){
                                ?>
                                <input type="number" qav class="form-control">
                                <div class="input-group-text p-0">
                                    <button qav_av class="btn btn-success btn-sm h-100 w-100" style="border-radius: 0px 3px 3px 0px;">Avaliar</button>
                                </div>
                                <?php
                                    }else{
                                ?>
                            </div>
                                <p><?=$pontuacao['qav']?></p>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
        </div>
    </div>
</div>

<script>

$(function(){


    $('button[imprimir]').click(function(){
        window.print();
    })

    $('select[ano],select[mes], select[tipo_relatorio]').change(function(){
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
            },success: function(retorno){
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

    $('button[voltar]').click(function(){
        $.ajax({
            url: 'src/fornecedor/fornecedor_lista.php',
            success: function(retorno){
                $('div#home').html(retorno)
            }
        })
    })

    $('button[qav_av]').click(function(){
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
            url: 'src/fornecedor/relatorio_fornecedor.php',
            method: 'POST',
            data: {
                codigo_fornecedor,
                ano,
                mes
            },success: function(retorno){
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
        url: 'src/fornecedor/relatorio/<?=$tipo_relatorio?>/barras.php',
        method: 'POST',
        data: {
            codigo: codigo_fornecedor,
            ano,
            mes,
            tipo_relatorio
        },success: function(chart){
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
        },success: function(chart){
            $('div[linhas]').html(chart)

        }
    })
})
</script>