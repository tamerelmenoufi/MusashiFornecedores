<?php
require "./lib/config.php";

global $pdo;

?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validação de assinatura</title>
    <!-- BOOTSTRAP 5.1 -->
    <link rel="stylesheet" href="lib/css/bootstrap.min.css">
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" type="text/css">
</head>
<body>
<style>
    .fw-600 {
        font-weight: 600;
    }
</style>
<div class="container">
    <div class="col-12 mt-4">

        <div class="d-flex justify-content-center flex-row mb-4">
            <img
                    src="img/musashi.png"
                    style="width: 140px;"
                    alt="musashi"
            >
        </div>


        <?php

        #@formatter:off

        $parametros = explode('-', $_GET['v']);
        $chave      = $parametros[0];
        $tipo       = $_GET['tipo'];

        if (!$parametros[1]):

            $sql = "SELECT * FROM avaliacao_mensal "
                . "WHERE status = '1' AND "
                . "(assinaturas_ipf != '' AND assinaturas_ipf IS NOT NULL) OR "
                . "(assinaturas_iqf != '' AND assinaturas_iqf IS NOT NULL) OR "
                . "(assinaturas_iaf != '' AND assinaturas_iaf IS NOT NULL)";

            $query = $pdo->prepare($sql);
            $query->execute();

            $assinaturas = [];
            $dados       = [];

            #@formatter:on

            while ($d = $query->fetch()) {
                if ($d['assinaturas_ipf']) $assinaturas[][$d['codigo']] = json_decode($d['assinaturas_ipf'], true);
                if ($d['assinaturas_iqf']) $assinaturas[][$d['codigo']] = json_decode($d['assinaturas_iqf'], true);
                if ($d['assinaturas_iaf']) $assinaturas[][$d['codigo']] = json_decode($d['assinaturas_iaf'], true);
            }

            foreach ($assinaturas_array as $assinaturas) {
                foreach ($assinaturas as $codigo => $assinatura) {

                    foreach ($assinatura as $ass) {
                        if ($ass['chave'] === $chave) {
                            $dados['codigo_mensal'] = $codigo;
                            $dados = $ass;
                            break;
                        }
                    }

                }
            }

            if (!$dados) : ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Chave de assinatura não encontrada
                </div>

            <?php else : ?>
                <div class="alert alert-success" role="alert">
                    <i class="fa fa-check" aria-hidden="true"></i> Assinatura valida
                </div>

                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <h5>Informações do usuário</h5>
                            <div class="row">
                                <div class="col-md-4 fw-600">Usuário</div>
                                <div class="col-md-8"><?= $dados['usuario']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 fw-600">Cargo</div>
                                <div class="col-md-8"><?= $dados['cargo']; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 fw-600">Data da Assinatura</div>
                                <div class="col-md-8"><?= date('d/m/Y H:i', strtotime($dados['data_hora'])) ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 fw-600">Chave da autenticação</div>
                                <div class="col-md-8"><?= $dados['chave'] ?></div>
                            </div>
                        </div>

                        <?php
                        $sql = "SELECT av.ano, av.mes, f.nome FROM avaliacao_mensal av "
                            . "INNER JOIN fornecedores f ON f.codigo = av.codigo_fornecedor "
                            . "WHERE av.codigo = :codigo";

                        $query_avaliacao = $pdo->prepare($sql);
                        $query_avaliacao->bindValue(':codigo', $dados['codigo_mensal']);
                        $query_avaliacao->execute();

                        $result = $query_avaliacao->fetch();

                        ?>
                        <div class="row mt-4">
                            <h5>Informações do fornecedor</h5>
                            <div class="row">
                                <div class="col-md-4 fw-600">Fornecedor</div>
                                <div class="col-md-8"><?= $result['nome'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 fw-600">Mês/Ano da Avaliação</div>
                                <div class="col-md-8"><?= $result['mes'] . '/' . $result['ano'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>

        <?php endif; ?>
    </div>
</div>
</body>
</html>
