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

            $query4 = $pdo->prepare("UPDATE assinaturas SET 
                                                status = '1'
                                            WHERE 
                                                codigo = '{$_POST['cod_assinatura']}'
                                    ");
            $query4->execute();

        } 


        echo json_encode([
            "status" => true,
            "msg" => "Assinatura realizada com sucesso!"
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

            console.log(tipo_relatorio)
            console.log(cod_mensal)
            console.log(cod_assinatura)

        })
    });
</script>
