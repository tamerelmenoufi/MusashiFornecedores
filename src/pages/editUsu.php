<?php
require "../../lib/config.php";

global $pdo;

if ($_POST['tipo'] == "editar") {
    $sql = $pdo->prepare("SELECT * FROM login  WHERE codigo = :c");
    $sql->bindValue(":c", $_POST["codigo"]);
    $sql->execute();

    $user = $sql->fetch();


    $pa = json_decode($user["perfil_assinaturas"]);

    ?>
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-md-12 p-0">
                <h4>Dados do Usuário</h4>
            </div>
            <div class="col-md-12 p-0">
                <label for="nome" class="fs-6 fw-bolder">Nome:</label>
                <div class="input-group">
                    <div class="input-group-text" style="width: 40px">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </div>
                    <input nome iid="nome" type="text" class="form-control" value="<?= utf8decode($user["nome"]) ?>">
                </div>
            </div>
            <div class="col-md-12 p-0">
                <label for="email" class="fs-6 fw-bolder">E-mail:</label>
                <div class="input-group">
                    <div class="input-group-text" style="width: 40px">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </div>
                    <input email id="email" type="text" class="form-control" value="<?= utf8decode($user["email"]) ?>">
                </div>
            </div>

            <div class="col-md-12 p-0">
                <label for="email" class="fs-6 fw-bolder">Perfil:</label>
                <div class="input-group">
                    <div class="input-group-text" style="width: 40px">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    <select tipo class="form-control" name="tipo" id="tipo">
                        <option value="2" <?= (($user["tipo"] == '2') ? 'selected' : false) ?>>Usuário</option>
                        <option value="1" <?= (($user["tipo"] == '1') ? 'selected' : false) ?>>Gestor</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 p-0">
                <label for="cargo" class="fs-6 fw-bolder">Cargo:</label>
                <div class="input-group">
                    <div class="input-group-text" style="width: 40px">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </div>
                    <input cargo id="cargo" type="text" class="form-control" value="<?= utf8decode($user["cargo"]) ?>">
                </div>
            </div>

            <div class="col-md-8" style="padding: 0 12px 0 0">
                <label for="usuario" class="fs-6 fw-bolder">Usuário:</label>
                <div class="input-group">
                    <div class="input-group-text" style="width: 40px">
                        <i class="fa fa-key" aria-hidden="true"></i>
                    </div>
                    <input usuario id="usuario" type="text" class="form-control"
                           value="<?= utf8decode($user["usuario"]) ?>">
                </div>
            </div>

            <div class="col-md-4 d-flex align-items-end justify-content-end p-0">
                <button reset class="btn btn-outline-success" codigo_usuario="<?= $_POST['codigo'] ?>">Resetar Senha
                </button>
            </div>

            <div class="col-md-12 form-switch">
                <input
                        class="form-check-input"
                        type="checkbox"
                        id="assinante_documento"
                    <?= ($user["assinante_documento"] === 'S' ? 'checked' : '') ?>
                >
                <label class="form-check-label" for="assinante_documento">Assinante de documento?</label>
            </div>

            <div class="col-md-12">
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="doc_ipf" <?=(($pa[0]->doc_ipf == 'true')?'checked':false)?>>
                    <label class="form-check-label" for="doc_ipf">Assina relatório IPF</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="doc_iqf" <?=(($pa[1]->doc_iqf == 'true')?'checked':false)?>>
                    <label class="form-check-label" for="doc_iqf">Assina relatório IQF</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="doc_iaf" <?=(($pa[2]->doc_iaf == 'true')?'checked':false)?>>
                    <label class="form-check-label" for="doc_iaf">Assina relatório IAF</label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="doc_geral" <?=(($pa[3]->doc_geral == 'true')?'checked':false)?>>
                    <label class="form-check-label" for="doc_geral">Assina Resumo Geral</label>
                </div>
                
            </div>
            
            <div class="col-nd-12 p-0 d-flex justify-content-between">
                <button class="btn btn-danger" fechar>Fechar</button>
                <button
                        class="btn btn-success"
                        confirm
                        codigo_usuario="<?= $_POST['codigo'] ?>"
                        local="src/pages/actions/editUsu_action.php"
                >
                    Confirmar
                </button>
            </div>
        </div>
    </div>
    <?php
}
?>
<script>
    $("button[fechar]").click(function () {
        popup.close()
    })

    $("button[reset]").click(function () {
        let codigo = $(this).attr("codigo_usuario")

        $.ajax({
            url: "src/pages/actions/editUsu_action.php",
            method: "POST",
            data: {
                codigo,
                acao: "resetar-confirm"
            }, success: function (confirm) {
                popup_confirm = $.dialog({
                    content: confirm,
                    closeIcon: false,
                    title: false,
                    columnClass: "col-md-offset-9 col-md-3"
                })
            }
        })
    })

    $("button[confirm]").click(function () {
        let local = $(this).attr("local")
        let codigo = $(this).attr("codigo_usuario")
        let nome = $("input[nome]").val()
        let email = $("input[email]").val()
        let usuario = $("input[usuario]").val()
        let tipo = $("select[tipo]").val()
        let cargo = $("input[cargo]").val()
        let assinante_documento = $("#assinante_documento").is(':checked') ? 'S' : 'N'

        let perfil_assinaturas = [];
        perfil_assinaturas.push({'doc_ipf':$("#doc_ipf").is(':checked') ? true : false})
        perfil_assinaturas.push({'doc_iqf':$("#doc_iqf").is(':checked') ? true : false})
        perfil_assinaturas.push({'doc_iaf':$("#doc_iaf").is(':checked') ? true : false})
        perfil_assinaturas.push({'doc_geral':$("#doc_geral").is(':checked') ? true : false})


        // console.log(perfil_assinaturas)
        $.ajax({
            url: local,
            method: "POST",
            data: {
                codigo,
                nome,
                email,
                usuario,
                tipo,
                cargo,
                assinante_documento,
                perfil_assinaturas,
                acao: "atualizar"
            }, success: function (retorno) {
                popup.close();
                $.ajax({
                    url: "src/pages/usuarios.php",
                    success: function (refresh) {
                        $("div#home").html(refresh)
                    }
                })
            }
        })
    })
</script>