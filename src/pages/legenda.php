<?php
    require "../../lib/config.php";
    global $pdo;

    if(isset($_POST['ano'])){
        $Y = $_POST['ano'];
    }else{
        $Y = date("Y");
    }
?>

<div class="card p-0 h-100 col-md-12 mb-4">
    <div class="card-header">
        <h5 class="m-0">Legenda</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fornecedor</th>
                    <th scope="col">CNPJ</th>
                    <th scope="col">Nomenclatura</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = $pdo->prepare("SELECT * FROM fornecedores WHERE '{$Y}' BETWEEN year(data_inicio) AND year(data_fim) AND situacao = '1' ORDER BY codigo;");
                    $sql->execute();
                    $count = 1;
                    while($d = $sql->fetch()){

                        // if(date("Y-m-d") > date('d/m/Y', strtotime($d['data_fim']))){
                ?>
                    <tr>
                        <th scope="row"><?=$count?></th>
                        <td ><?=utf8_encode($d['nome'])?></td>
                        <td><?=$d['cnpj']?></td>
                        <td><strong><?=str_pad($d['codigo'], 4, "0", STR_PAD_LEFT)?></strong></td>
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
<div class="col-md-12 p-0 ">
    <button fechar class="btn btn-secondary pull-right">OK</button>
</div>

<script>
    $('button[fechar]').click(function(){
        popup.close();
    })
</script>
