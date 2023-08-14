<?php
require "../../../lib/config.php";

global $pdo;


if($_POST['acao'] == 'novo_nivel'){

    $sql = $pdo->prepare("INSERT INTO assinatura_nivel SET documento = '{$_POST['documento']}', nivel = '{$_POST['nivel']}'");
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

    <script>
        $(function(){
            $("#novo_grupo").click(function(){
                documento = $("#doc").val();
                nivel = $("#nivel").val();
                $.ajax({
                    url:"src/pages/assinaturas/doc.php",
                    type:"POST",
                    data:{
                        documento,
                        nivel,
                        acao:"novo_nivel"
                    },
                    success:funciton(dados){
                        $('div#home').html(retorno)
                    }
                });
            })
        })
    </script>