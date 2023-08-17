<?php
    require_once "../../../lib/config.php";
    
    global $pdo;

    $sql = $pdo->prepare("SELECT * FROM registros_diarios WHERE codigo_fornecedor = :c AND data_registro = :dr");
    $sql->bindValue(":c", $_POST['codigo_fornecedor']);
    $sql->bindValue(":dr", $_POST['data']);
    $sql->execute();
    
    if($sql->rowCount() == 0){
        $insert = $pdo->prepare("INSERT INTO registros_diarios SET codigo_fornecedor = :cf, data_registro = :dr");
        $insert->bindValue(":cf", $_POST['codigo_fornecedor']);
        $insert->bindValue(":dr", $_POST['data']);
        $insert->execute();
        $cod = $pdo->lastInsertId();

        //Aqui a entrada das assinaturas com a nova data de registro
        
        // codigo_avaliacao_mensal	usuario	doc	ordem	status	chave
        $sql1 = $pdo->prepare("SELECT * FROM assinatura_nivel ORDER BY documento ASC, codigo ASC");
        $sql->execute();
        $count = 1;
        while($d = $sql->fetch()){
            $insert = $pdo->prepare("REPLACE INTO assinaturas SET 
                                                    codigo_avaliacao_mensal = '{$cod}',
                                                    usuario = '{$d['assinantes']}',
                                                    doc = '{$d['documento']}',
                                                    ordem = '{$count}',
                                                    status = '0',
                                                    chave = '".md5($cod.$d['assinantes'].$d['documento'])."'");
            $insert->execute();
            $count++;
        }

    ?>
        <div class="container-fluid p-0">
            <h4 class="text-success mb-3">Sucesso  <i class="fa fa-check-square-o" aria-hidden="true"></i></h4>
            <p>Data registrata, aguardando avaliação.</p>
            <div class="col-md-12 p-0">
                <button concluir local="src/fornecedor/fornecedor_registros.php" cod="<?=$_POST['codigo_fornecedor']?>" class="btn btn-success btn-sm pull-right">Concluir</button>
            </div>
        </div>
    <?php
    }else{
        ?>
        <div class="container-fluid p-0">
            <h4 class="text-primary mb-3">Data já Registrada  <i class="fa fa-check-square-o" aria-hidden="true"></i></h4>
            <p>O registro referênte a <?=date('d/m/Y', strtotime($_POST['data']))?> já consta registrada no sistema.</p>
            <div class="col-md-12 p-0">
                <button concluir local="src/fornecedor/fornecedor_registros.php" cod="<?=$_POST['codigo_fornecedor']?>" class="btn btn-success btn-sm pull-right">Concluir</button>
            </div>
        </div>
    <?php
    }
?>
<script>
    $("button[concluir]").click(function(){
        let local = $(this).attr('local')
        let codigo_fornecedor = $(this).attr('cod')

        $.ajax({
            url: local,
            method: "POST",
            data: {
                codigo_fornecedor
            },success: function(retorno){
                $('div#home').html(retorno)
                popup.close()
            }
        })
    })
</script>