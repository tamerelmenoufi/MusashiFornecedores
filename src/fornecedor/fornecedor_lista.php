<?php
    require "../../lib/config.php";

    global $pdo;

    if($_POST['acao'] == 'excluir'){
        $update = $pdo->prepare("UPDATE fornecedores set situacao = '0' WHERE codigo = {$_POST['codigo_fornecedor']}");
        $update->execute();
    }

?>
<div class="container-fluid" >
    <div class="row justify-content-center align-items-center g-3 m-3">
        <h3><i class="fa fa-list-alt" aria-hidden="true"></i> Lista de Fornecedores</h3>
        <div class="card p-0 h-100">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fornecedor</th>
                            <th scope="col">CNPJ</th>
                            <th scope="col">Inicio</th>
                            <th scope="col">Fim</th>
                            <th scope="col" style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $data_atual = date("Y-m-d");

                            $sql = $pdo->prepare("SELECT * FROM fornecedores WHERE '{$data_atual}' BETWEEN data_inicio AND data_fim AND situacao = '1' ORDER BY nome;");
                            $sql->execute();
                            $count = 1;
                            while($d = $sql->fetch()){

                                // if(date("Y-m-d") > date('d/m/Y', strtotime($d['data_fim']))){
                        ?>
                            <tr>
                                <th scope="row"><?=$count?></th>
                                <td ><?=utf8_encode($d['nome'])?></td>
                                <td><?=$d['cnpj']?></td>
                                <td><?=date('d/m/Y', strtotime($d['data_inicio']))?></td>
                                <td><?=date('d/m/Y', strtotime($d['data_fim']))?></td>
                                <td class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                                    <button semanas cod="<?=$d['codigo']?>" type="button" class="btn btn-success btn-sm" title="Semanas">
                                        <!-- <i class="fa fa-calendar" aria-hidden="true"></i> -->
                                        Registros
                                    </button>
                                    <button editar cod="<?=$d['codigo']?>" type="button" class="btn btn-primary btn-sm" title="Editar">
                                        <!-- <i class="fa fa-pencil-square-o" aria-hidden="true"></i> -->
                                        Editar
                                    </button>
                                    <button relatorio cod="<?=$d['codigo']?>" type="button" class="btn btn-dark btn-sm" title="Relatório">
                                        <!-- <i class="fa fa-pencil-square-o" aria-hidden="true"></i> -->
                                        Relatorio
                                    </button>
                                    <?php
                                    if($ConfUsu['tipo'] == 1){
                                    ?>
                                    <button excluir cod="<?=$d['codigo']?>" type="button" class="btn btn-danger btn-sm" title="Excluir">
                                        <!-- <i class="fa fa-pencil-square-o" aria-hidden="true"></i> -->
                                        Excluir
                                    </button>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                                // }
                                $count++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted" style="text-align: justify;">
                Nota: A ordem de listagem não corresponde a posição no Ranking de Fornecedores.
            </div>
        </div>
    </div>
</div>

<script>
    $('button[semanas]').click(function(){
        let codigo_fornecedor = $(this).attr('cod')

        $.ajax({
            url: 'src/fornecedor/fornecedor_registros.php',
            method: 'POST',
            data: {
                codigo_fornecedor
            },success: function(retorno){
                $('div#home').html(retorno)
            }
        })
    })

    $('button[editar]').click(function(){
        let codigo_fornecedor = $(this).attr('cod')

        $.ajax({
            url: 'src/fornecedor/editar.php',
            method: 'POST',
            data: {
                codigo_fornecedor
            },
            success: function(retorno){
                $('div#home').html(retorno)
            }
        })
    })

    $('button[excluir]').click(function(){
        let codigo_fornecedor = $(this).attr('cod')

        $.confirm({
            content:"Deseja realmente desativar o Fornecedor?",
            title:false,
            buttons:{
                'SIM':function(){
                    $.ajax({
                        url: 'src/fornecedor/fornecedor_lista.php',
                        method: 'POST',
                        data: {
                            codigo_fornecedor,
                            acao:'excluir'
                        },success: function(retorno){
                            $('div#home').html(retorno)
                        }
                    })
                },
                'NÃO':function(){

                }
            }
        });


    })

    $('button[relatorio]').click(function(){
        let codigo_fornecedor = $(this).attr('cod')

        $.ajax({
            url: 'src/fornecedor/relatorio_fornecedor.php',
            method: 'POST',
            data: {
                codigo_fornecedor
            },success: function(retorno){
                $('div#home').html(retorno)

                // $.ajax({
                //     url: 'src/fornecedor/chart.php',
                //     method: 'POST',
                //     data: {
                //         codigo: codigo_fornecedor
                //     },success: function(chart){
                //         $('div[grafico]').html(chart)

                //     }
                // })
            }
        })
    })
</script>