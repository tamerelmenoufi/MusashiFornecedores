<?php
    require_once "../../lib/config.php";

    global $pdo;
?>    
<table class="table table-striped table">
    <thead tfonts>
        <tr>
            <th scope="col">CÓDIGO</th>
            <th scope="col">FORNECEDOR</th>
            <th scope="col">QUALITY</th>
            <th scope="col">DELIVERY</th>
            <th scope="col">GERAL(Q&D)</th>
            <th scope="col">POSIÇÃO</th>
        </tr>
    </thead>
    <tbody tfonts>
        <?php
            $sql = $pdo->prepare("SELECT b.codigo, b.nome as fornecedor, a.quality as quality, a.delivery as delivery, 
            a.classificacao as classificacao, a.ano as ano, a.posicao as posicao FROM avaliacao_anual as a
            JOIN fornecedores as b ON  b.codigo = a.codigo_fornecedor
            WHERE a.ano = :y ORDER BY posicao");
            $sql->bindValue(":y", $_POST['ano']);

            $sql->execute();
            while($d = $sql->fetch()){
        ?>
        <tr>
            <td ><?=str_pad($d['codigo'], 4, "0", STR_PAD_LEFT)?></td>
            <td><?=utf8_encode($d['fornecedor'])?></td>
            <td><?=$d['quality']?></td>
            <td><?=$d['delivery']?></td>
            <td><?=$d['classificacao']?></td>
            <td><?=$d['posicao']?></td>
        </tr>
        <?php
            }
        ?>
    </tbody>
</table>