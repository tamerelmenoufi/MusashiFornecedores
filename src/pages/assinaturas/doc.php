<?php
require "../../../lib/config.php";

global $pdo;


if($_POST['acao'] == 'novo_nivel'){

    $query = "INSERT INTO assinatura_nivel SET documento = '{$_POST['doc']}', nivel = '{$_POST['nivel']}'";
    $sql = $pdo->prepare($query);
    $sql->execute();

}

$rotulo = [
    'doc_ipf' => 'IPF',
    'doc_iqf' => 'IQF',
    'doc_iaf' => 'IAF',
    'doc_geral' => 'GERAL',
];

?>
    <h6>Reratório novo <?=$rotulo[$_POST['doc']]?></h6>
    <div class="row">
        <div class="col">
            <div class="input-group mb-3">
                <span class="input-group-text">Inserir Grupo de Assinaturas</span>
                <input type="text"  id="nivel" class="form-control" placeholder="Nome do grupo de assinaturas" aria-label="Nome do grupo de assinaturas" aria-describedby="novo_grupo">
                <button class="btn btn-outline-secondary" type="button" id="novo_grupo">Adicionar</button>
                <input type="hidden" id="doc" value="<?=$_POST['doc']?>" >
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col">
            <?php
                    $sql = $pdo->prepare("SELECT * FROM assinatura_nivel  WHERE documento = '{$_POST['doc']}'");
                    $sql->execute();
                    while($d = $sql->fetch()){
            ?>
            <div class="card">
            <div class="card-header">
                Featured
            </div>
            <div class="card-body">
                <h5 class="card-title">Special title treatment</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
            </div>
            <?php
                    }
            ?>
        </div>
    </div>
    <script>
        $(function(){
            $("#novo_grupo").click(function(){
                doc = $("#doc").val();
                nivel = $("#nivel").val();
                $.ajax({
                    url:"src/pages/assinaturas/doc.php",
                    type:"POST",
                    data:{
                        doc,
                        nivel,
                        acao:"novo_nivel"
                    },
                    success:function(dados){
                        $('div#home').html(dados)
                    }
                });
            })
        })
    </script>