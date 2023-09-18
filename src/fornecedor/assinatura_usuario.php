<?php
require "../../lib/config.php";

global $pdo;

$doc = [
    'doc_geral' => 'Geral',
    'doc_iaf' => 'IAF',
    'doc_ipf' => 'IPF',
    'doc_iqf' => 'IQF',
];

if ($_POST['acao'] === 'assinar') {
    #@formatter:off
    $senha          = md5($_POST['senha']);
    $tipo_relatorio = $_POST['tipo_relatorio'];

    $query = $pdo->prepare("SELECT codigo, nome, cargo FROM login WHERE codigo = :c AND senha = :s");
    $query->bindValue(":s", $senha);
    $query->bindValue(":c", $_SESSION['musashi_cod_usu']);
    $query->execute();

    if ($query->rowCount() > 0) {

        for($i = 0; $i < count($_POST['cod_assinatura']); $i++){

            $campo_assinatura = "";

            if ($_POST['tipo_relatorio'][$i] === 'IPF')     $campo_assinatura = 'assinaturas_ipf';
            elseif ($_POST['tipo_relatorio'][$i] === 'IQF') $campo_assinatura = 'assinaturas_iqf';
            elseif ($_POST['tipo_relatorio'][$i] === 'IAF') $campo_assinatura = 'assinaturas_iaf';

            $usuario = $query->fetch();

            $query2 = $pdo->prepare("SELECT codigo, {$campo_assinatura} FROM avaliacao_mensal WHERE codigo = :c");

            $query2->bindValue(":c", $_POST['cod_mensal'][$i]);
            $query2->execute();

            $assinaturas = [];

            $avaliacao_mes = $query2->fetch();

            $assinaturas    = json_decode($avaliacao_mes[$campo_assinatura]) ?: [];
            $data_hora      = date('Y-m-d H:i:s');
            $chave          = md5(
                        $_SESSION['musashi_cod_usu']
                        . $data_hora
                        . $usuario['nome']
                        . $usuario['cargo']
                        . $tipo_relatorio
            );

            $nova_assinatura = [
                "codigo"    => $_SESSION['musashi_cod_usu'],
                "usuario"   => $usuario['nome'],
                "cargo"     => $usuario['cargo'],
                "data_hora" => $data_hora,
                "chave"     => $chave,
            ];

            array_push($assinaturas, $nova_assinatura);


            $q = "UPDATE avaliacao_mensal SET {$campo_assinatura} = '".json_encode($assinaturas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."' WHERE codigo = '{$avaliacao_mes['codigo']}'";


            // $query3 = $pdo->prepare("UPDATE avaliacao_mensal SET {$campo_assinatura} = :a WHERE codigo = :c");
            // $query3->bindValue(':a', json_encode($assinaturas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            // $query3->bindValue(':c', $avaliacao_mes['codigo']);

            // if ($query3->execute()) {

            //     $query4 = $pdo->prepare("UPDATE assinaturas SET 
            //                                         status = '1'
            //                                     WHERE 
            //                                         codigo = '{$_POST['cod_assinatura'][$i]}'
            //                             ");
            //     $query4->execute();

            // } 
            
        }

        echo json_encode([
            "status" => true,
            "msg" => "Assinatura realizada com sucesso!". $q
        ]);

    } else {
        echo json_encode([
            "status" => false,
            "msg" => "Senha incorreta!"
        ]);
    }

    exit();
}

?>

<div class="container container-assinatura">
    <div class="row">
        <div class="col-md-12">
            <div class="p-3">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Seq.</th>
                            <th>Documento</th>
                            <th>mes/ano</th>
                            <th>Fornecedor</th>
                            <th>CNPJ</th>
                        </tr>
                    </thead>
                    <tbody>
        <?php

        $sql = $pdo->prepare("select 
        a.codigo,
        a.codigo_avaliacao_mensal,
        a.usuario,
        a.doc,
        b.nome,
        b.email,
        /*'tamer.menoufi@gmail.com' as email,*/
        c.mes,
        c.ano,
        d.nome as fornecedor_nome,
        d.cnpj as fornecedor_cnpj
        from assinaturas a 
        left join login b on a.usuario = b.codigo
        left join avaliacao_mensal c on a.codigo_avaliacao_mensal = c.codigo
        left join fornecedores d on c.codigo_fornecedor = d.codigo
        where a.status != '1' and a.usuario = '{$_SESSION['musashi_cod_usu']}'
        group by a.codigo_avaliacao_mensal, a.doc
        order by a.codigo");
        $sql->execute();
        $i = 1;
        while($d = $sql->fetch()){
?>
                        <tr>
                            <td>
                                <input 
                                    opcoes
                                    tipo_relatorio="<?=$doc[$d['doc']]?>"
                                    cod_mensal="<?=$d['codigo_avaliacao_mensal']?>"
                                    cod_assinatura="<?=$d['codigo']?>"
                                    type="checkbox" />
                            </td>
                            <td><?=$i?></td>
                            <td><?=$doc[$d['doc']]?></td>
                            <td><?="{$d['mes']}/{$d['ano']}"?></td>
                            <td><?=$d['fornecedor_nome']?></td>
                            <td><?=$d['fornecedor_cnpj']?></td>
                        </tr>
<?php
        $i++;
        }
?>
                    </tbody>
                </table>

                <button assinar class="btn btn-success m-3">ASSINAR RELATÓRIO(S)</button>

            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("button[assinar]").click(function(){

            
            tipo_relatorio = [];
            cod_mensal = [];
            cod_assinatura = [];             
            $("input[opcoes]").each(function(){
                if($(this).prop("checked") == true){
                    tipo_relatorio.push($(this).attr("tipo_relatorio"));
                    cod_mensal.push($(this).attr("cod_mensal"));
                    cod_assinatura.push($(this).attr("cod_assinatura"));
                }                
            })


            $.confirm({
                title: 'Assinar Relatórios',
                content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Informe sua senha do sistema para efetuar a sua assinatura:</label>' +
                '<input type="password" placeholder="Digite a senha" class="senha form-control" required />' +
                '</div>' +
                '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Assinar',
                        btnClass: 'btn-success',
                        action: function () {
                            var senha = this.$content.find('.senha').val();
                            if(!senha){
                                $.alert('Favor digite sua senha');
                                return false;
                            }

                            $.ajax({
                                url:"src/fornecedor/assinatura_usuario.php",
                                type:"POST",
                                dataType:"JSON",
                                data:{
                                    senha,
                                    tipo_relatorio,
                                    cod_mensal,
                                    cod_assinatura,
                                    acao:'assinar'
                                },
                                success:function(dados){
                                    console.log(dados)
                                }
                            });
                        }
                    },
                    'Cancelar': function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });

        })
    });
</script>
