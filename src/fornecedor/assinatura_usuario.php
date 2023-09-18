<?php
require "../../lib/config.php";

global $pdo;

if ($_POST['acao'] === 'assinar') {
    #@formatter:off
    $senha          = md5($_POST['senha']);
    $tipo_relatorio = $_POST['tipo_relatorio'];

    $query = $pdo->prepare("SELECT codigo, nome, cargo FROM login WHERE codigo = :c AND senha = :s");
    $query->bindValue(":s", $senha);
    $query->bindValue(":c", $_SESSION['musashi_cod_usu']);
    $query->execute();

    if ($query->rowCount() > 0) {
        $campo_assinatura = "";

        if ($tipo_relatorio === 'IPF')     $campo_assinatura = 'assinaturas_ipf';
        elseif ($tipo_relatorio === 'IQF') $campo_assinatura = 'assinaturas_iqf';
        elseif ($tipo_relatorio === 'IAF') $campo_assinatura = 'assinaturas_iaf';

        $usuario = $query->fetch();

        $query2 = $pdo->prepare("SELECT codigo, {$campo_assinatura} FROM avaliacao_mensal WHERE codigo = :c");

        $query2->bindValue(":c", $_SESSION['cod_mensal']);
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

        $query3 = $pdo->prepare("UPDATE avaliacao_mensal SET {$campo_assinatura} = :a WHERE codigo = :c");
        $query3->bindValue(':a', json_encode($assinaturas, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $query3->bindValue(':c', $avaliacao_mes['codigo']);

        if ($query3->execute()) {

            $TipoDoc = [
                'assinaturas_ipf' => 'doc_ipf',
                'assinaturas_iqf' => 'doc_iqf',
                'assinaturas_iaf' => 'doc_iaf',
            ];

            $query4 = $pdo->prepare("UPDATE assinaturas SET 
                                                status = '1'
                                            WHERE 
                                                doc = '{$TipoDoc[$campo_assinatura]}' and 
                                                usuario = '{$_SESSION['musashi_cod_usu']}' and 
                                                codigo_avaliacao_mensal = '{$avaliacao_mes['codigo']}'
                                    ");
            $query4->execute();

            echo json_encode([
                "status"     => true,
                "msg"        => "Assinado com sucesso",
                "codigo"     => $avaliacao_mes['codigo'],
                "dados"      => $nova_assinatura,
                "cod_mensal" => $_SESSION['cod_mensal'],
                "tipo"       => $ConfUsu['tipo']
            ]);

            unset($_SESSION['cod_mensal']);
        } else {
            echo json_encode([
                "status" => false,
                "msg"    => "Erro ao inserir assinatura",
                "error"  => $query3->errorInfo()
            ]);
        }
        #@formatter:on
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
        <?php

        $sql = $pdo->prepare("select 
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
        while($d = $sql->fetch()){

            echo $d['doc']."<br>";


        }

        ?>
        </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        
    });
</script>
