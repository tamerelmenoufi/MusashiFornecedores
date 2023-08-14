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
$indice = [
    'doc_ipf' => '0',
    'doc_iqf' => '1',
    'doc_iaf' => '2',
    'doc_geral' => '3',
];

$sql = $pdo->prepare("SELECT * FROM login WHERE perfil_assinaturas->>'$[{$indice[$_POST['doc']]}].{$_POST['doc']}' = 'true'");
$sql->execute();
$ass = [];
while($d = $sql->fetch()){
    $ass[$_POST['doc']][] = $d;
}

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

                        $assinantes = @implode(",", $d['assinantes']);


            ?>
            <div class="card mb-2">
            <div class="card-header">
                <?=$d['nivel']?>
            </div>
            <div class="card-body">
                <?php
                if($ass){
                foreach($ass[$_POST['doc']] as $i => $a){
                ?>
                <div class="col-md-12 form-switch">
                    <input
                            acao="<?=$d['codigo']?>"
                            usuario="<?=$a['codigo']?>"
                            class="form-check-input"
                            type="checkbox"
                            id="assinatura_nivel<?=$d['codigo']?>"
                        <?= (@in_array($a['codigo'], $assinantes) ? 'checked' : '') ?>
                    >
                    <label class="form-check-label" for="assinatura_nivel<?=$d['codigo']?>"><?=$a['nome']?></label>
                </div>
                <?php
                }
                }
                ?>
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
                if(!doc || !nivel){
                    $.alert('Favor informe a descrição do grupo de assinaturas!');
                    return false;
                }
                $.ajax({
                    url:"src/pages/assinaturas/doc.php",
                    type:"POST",
                    data:{
                        doc,
                        nivel,
                        acao:"novo_nivel"
                    },
                    success:function(dados){
                        $(".assinaturas").html(dados);
                    }
                });
            })


            $("input[acao]").click(function(){
                cod = $(this).attr("acao");
                usuario = $(this).attr("usuario");
                ass = [];
                $(`#assinatura_nivel${cod}`).each(function(){
                    if($(this).prop("checked") == true){
                        ass.push(usuario);
                    }
                })
                console.log(ass)
            });
        })
    </script>