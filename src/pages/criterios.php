<?php
    require "../../lib/config.php";

    global $pdo;
?>

<div class="container-fluid">
    <div class="row justify-content-center m-4" style="gap: 12px">
        <div class="card col-md-10 p-0">

            <div class="card-header fw-bolder" >
                Informe de Problema e Informativo de Divergencia Material - (IP  & IDM)
            </div>
            <div class="card-body row justify-content-center m-0" style="gap: 12px">  
                <!------------------------------------------------- QUALITY ------------------------------------------------->
                <div class="col-md-5 p-0">

                    <div class="card h-100">
                        <div class="card-header fw-bolder" >
                            QUALITY
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">IP Oficial Emitido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_ip_oficial_emissao");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['ip_emissao']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">IP Reincidente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_ip_reincidente");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['ip_reincidente']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">Atraso de Resposta de IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_ip_atraso_resposta");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['atraso']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-muted" style="text-align: justify;">
                            Nota: A avaliação de Quality reflete o desempenho na entrega de materias e na qualidade
                            do mesmo.
                        </div>
                    </div>
                </div>


                <!-------------------------------------------- DELIVERY ------------------------------------------------->
                <div class="col-md-5 p-0 h-100">

                    <div class="card">
                        <div class="card-header fw-bolder" >
                            DELIVERY 
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">IDM Emitidos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_idm_emitidos");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['idm_emitidos']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                            
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">IDM Reincidente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_idm_reincidente");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['idm_reincidente']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">Atraso de Resposta de IDM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_idm_atraso_resposta");
                                        $sql->execute();
                                        
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['idm_resp_atraso']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-muted" style="text-align: justify;">
                            Nota: A avaliação de Delivery reflete a comunicação e transparência do fornecedor para com a Musashi Ltda.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-md-10 p-0">

            <div class="card-header fw-bolder" >
                Atentimento - DELIVERY
            </div>
            <div class="card-body row justify-content-center m-0" style="gap: 12px">  

                <div class="col-md-5 p-0 ">
        
                    <div class="card">
                        <div class="card-header fw-bolder" >
                            Entrega
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">Dias de atraso na Entrega</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_atraso_entrega");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['dias_atraso']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 p-0 ">
                    
                    <div class="card">
                        <div class="card-header fw-bolder" >
                            % Atendimento
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">Porcentagem de Atendimento (Mês)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_atendimento");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=$d['atendimento']?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 p-0">
                    
                    <div class="card">
                        <div class="card-header fw-bolder" >
                            Parada de Linha 
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">Situação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_parada_linha");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=utf8decode($d['parada_de_linha'])?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 p-0">
                    
                    <div class="card">
                        <div class="card-header fw-bolder" >
                            Comunicação 
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Demérito</th>
                                        <th scope="col">Situação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = $pdo->prepare("SELECT * FROM aux_comunicacao");
                                        $sql->execute();
                                        while($d = $sql->fetch()){
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$d['demerito']?></th>
                                        <td><?=utf8decode($d['comunicacao'])?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
                                    
            
        <!---------------------------------------------------------- PPM ------------------------------------------------------------>
        <div class="col-md-10 p-0 ">
            <div class="card">
                <div class="card-header fw-bolder" >
                    Parte Por Milhão - PPM
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Demérito</th>
                                <th scope="col">Devolução em PPM</th>
                                <th scope="col">Tipo de Fornecedor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = $pdo->prepare("SELECT * FROM aux_ppm");
                                $sql->execute();
                                while($d = $sql->fetch()){
                                    if($d['tipo'] == "PD"){
                                        $tipo = 'Padrão';
                                    }else{
                                        $tipo = 'Peças Fundidas';
                                    }
                            ?>
                            <tr>
                                <th scope="row"><?=$d['demerito']?></th>
                                <td><?=utf8decode($d['limite_ppm'])?></td>
                                <td><?=$tipo?></td>
                            </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-muted" style="text-align: justify;">
                    Nota: Fornecedoras de <strong>peças fundidas</strong> com até 22.000 PPM conforme indica a tabela acima não terá Demérito,
                    PPM acima de 22.000, perderá 20 pontos neste quesito da avaliação, desde que a quantidade entregue no mês seja igual ou superior a 5.000
                    peças (Abaixo de 5.000 peças o fornecedor de <strong>peças fundidas</strong> não será demeritado em PPM).<br><br>

                    Obs: PPM = (peças devolvidas / quantidade recebida) x 1000000
                </div>
            </div>
        </div>
        </div>
    </div>
</div>