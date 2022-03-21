<?php
    require_once "../../lib/config.php";

    global $pdo;

    $sql = $pdo->prepare("SELECT count(codigo) AS qnt, count(CASE WHEN situacao = 1 THEN 1 ELSE NULL END) AS ativos, count(CASE WHEN situacao = 1 THEN NULL ELSE 1 END) AS inativos FROM login;");
    $sql->execute();

    $d = $sql->fetch();
?>
<div class="container p-0">
    <div class="row justify-content-center mb-3">
        <div class="col-md-4 col-4 p-2">
            <div class="card text-center fw-bolder">
                <div class="card-header">
                    Quantidade de Cadastros
                </div>
                <div class="card-body text-center fs-4 fw-bolder">
                    <i class="fa fa-users fa-2x" aria-hidden="true"></i>
                    <p class="m-0"><?=$d["qnt"]?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-4 p-2">
            <div class="card text-center fw-bolder">
                <div class="card-header">
                    Ativos
                </div>
                <div class="card-body text-center fs-4 fw-bolder">
                    <i class="fa fa-user fa-2x" aria-hidden="true"></i> 
                    <p class="m-0"><?=$d["ativos"]?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-4 p-2">
            <div class="card text-center fw-bolder">
                <div class="card-header">
                    Inativos
                </div>
                <div class="card-body text-center fs-4 fw-bolder">
                    <i class="fa fa-user-times fa-2x" aria-hidden="true"></i> 
                    <p class="m-0"><?=$d["inativos"]?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-0 card mb-4">
         <div class="card-header fw-bolder text-success fs-5">
            Usuários Ativos
         </div>
         <div class="card-body p-1">
            <?php
                $qativos = $pdo->prepare("SELECT * FROM login WHERE situacao = 1 AND tipo != 3");
                $qativos->execute();

                while($ativo = $qativos->fetch()){
            ?>
                <div class="row border rounded m-3 p-2 align-items-center fs-6" style="border-left: 8px solid green !important">
                    <div class="col-md-4 p-0">
                    <span class="text-secondary" style="font-size: 13px">Nome:</span>
                        <p class="m-0"><?=$ativo["nome"]?></p>
                    </div>

                    <div class="col-md-4 p-0">
                        <span class="text-secondary" style="font-size: 13px">Email:</span>
                        <p class="m-0"><?=$ativo["email"]?></p>
                    </div>

                    <div class="col-md-3 p-0">
                        <span class="text-secondary" style="font-size: 13px">Usuário:</span>
                        <p class="m-0"><?=$ativo["usuario"]?></p>
                    </div>

                    <div class="col-md-1 p-0 text-center fw-bolder">
                        <span class="text-secondary" style="font-size: 13px">Ações:</span><br>

                        <button acao local="src/pages/editUsu.php" class="btn btn-primary btn-sm" tipo="editar" codigo="<?=$ativo["codigo"]?>">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                        <button acao local="src/pages/actions/usuarios_action.php" class="btn btn-danger btn-sm" tipo="desativar" codigo="<?=$ativo["codigo"]?>">
                            <i class="fa fa-user-times" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
        <div class="card-footer"></div>
     </div>

     <div class="container-fluid p-0 card mb-4">
         <div class="card-header fw-bolder text-danger fs-5">
            Usuários Inativos
         </div>
         <div class="card-body p-1">
            <?php
                $qinativo = $pdo->prepare("SELECT * FROM login WHERE situacao = '0' AND tipo != 3");
                $qinativo->execute();

                while($inativo = $qinativo->fetch()){
            ?>
                <div class="row border rounded m-3 p-2 align-items-center fs-6" style="border-left: 8px solid red !important">
                    <div class="col-md-4 p-0">
                    <span class="text-secondary" style="font-size: 13px">Nome:</span>
                        <p class="m-0"><?=$inativo["nome"]?></p>
                    </div>

                    <div class="col-md-4 p-0">
                        <span class="text-secondary" style="font-size: 13px">Email:</span>
                        <p class="m-0"><?=$inativo["email"]?></p>
                    </div>

                    <div class="col-md-3 p-0">
                        <span class="text-secondary" style="font-size: 13px">Usuário:</span>
                        <p class="m-0"><?=$inativo["usuario"]?></p>
                    </div>

                    <div class="col-md-1 p-0 text-center fw-bolder">
                        <span class="text-secondary" style="font-size: 13px">Ações:</span><br>

                        <button acao local="src/pages/editUsu.php" class="btn btn-primary btn-sm" tipo="editar" codigo="<?=$inativo["codigo"]?>">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                        </button>
                        <button acao local="src/pages/actions/usuarios_action.php" class="btn btn-success btn-sm" tipo="ativar" codigo="<?=$inativo["codigo"]?>">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            <?php
                }
            ?>
        </div>
        <div class="card-footer"></div>
     </div>
</div>
<script>
    $("button[acao]").click(function(){
        let local = $(this).attr("local")
        let tipo = $(this).attr("tipo")
        let codigo = $(this).attr("codigo")

        if(tipo == "editar"){
            columnClass = "col-md-offset-7 col-md-5"
        }else{
            columnClass = "col-md-offset-8 col-md-4"
        }

        $.ajax({
            url: local,
            method: "POST",
            data: {
                local,
                tipo,
                codigo
            },success: function(retorno){
                popup = $.dialog({
                    content: retorno,
                    title: false,
                    closeIcon: false,
                    columnClass: columnClass
                })
            }
        })
    })
</script>