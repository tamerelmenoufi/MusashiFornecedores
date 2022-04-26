<?php
require "../../lib/config.php";

?>

<div class="container-fluid">
    <div class="row justify-content-center align-items-center g-3 m-3">
        <h3>
            <i class="fa fa-list-alt" aria-hidden="true"></i> Lista de Meses
        </h3>
        <div class="card p-0 h-100">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fornecedor</th>
                        <th scope="col">Mês</th>
                        <th scope="col">Ano</th>
                        <th scope="col">Eficiência</th>
                        <th scope="col">Quality</th>
                        <th scope="col">Delivery</th>
                        <th scope="col" style="text-align: center;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $data_atual = date("Y-m-d");

                    $query = "SELECT * FROM avaliacao_mensal am "
                        . "LEFT JOIN fornecedores f ON f.codigo = am.codigo_fornecedor "
                        . "WHERE am.codigo_fornecedor = '{$_SESSION['musashi_cod_forn']}' AND am.status = '1'";

                    $sql = $pdo->prepare($query);
                    $sql->execute();
                    $count = 1;
                    while ($d = $sql->fetch()) {
                        // if(date("Y-m-d") > date('d/m/Y', strtotime($d['data_fim']))){
                        ?>
                        <tr>
                            <th scope="row"><?= $count ?></th>
                            <td><?= $d['nome'] ?></td>
                            <td><?= $d['mes'] ?></td>
                            <td><?= utf8_encode($d['ano']) ?></td>
                            <td><?= utf8_encode($d['eficiencia']) ?></td>
                            <td><?= utf8_encode($d['quality']) ?></td>
                            <td><?= utf8_encode($d['delivery']) ?></td>
                            <td class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                                <button
                                        relatorio
                                        cod="<?= $d['codigo'] ?>"
                                        type="button"
                                        class="btn btn-success btn-sm"
                                        title="Relatório"
                                >
                                    Relatorio
                                </button>

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
    $(function () {
        $('button[relatorio]').click(function () {
            let codigo_fornecedor = $(this).attr('cod')

            $.ajax({
                url: 'src/consultar/relatorio_fornecedor.php',
                method: 'POST',
                data: {
                    codigo_fornecedor
                }, success: function (retorno) {
                    $('div#home').html(retorno)
                }
            })
        })
    });
</script>
