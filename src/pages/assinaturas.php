<?php
require "../../lib/config.php";

global $pdo;
?>
<div class="m-3" style="padding-top:20px;">
    <h4>Gestão de Assinaturas</h4>
    <p>Módulo para ordenar a sequência das assinaturas no sistema.</p>
    <select id="documentos" class="form-select">
        <option value="">:: Selecione o documento::</option>
        <option value="doc_ipf">Relatório IPF</option>
        <option value="doc_iqf">Relatório IQF</option>
        <option value="doc_iaf">Relatório IAF</option>
        <option value="doc_geral">Relatório Geral</option>
    </select>
    <div class="p-3 assinaturas"></div>
</div>

<script>
    $(function(){
        $("#documentos").change(function(){
            doc = $(this).val();
            $(".assinaturas").html('');
            if(doc){
                $.ajax({
                    url:`src/pages/assinaturas/doc.php`,
                    data:{
                        doc
                    },
                    type:"POST",
                    success:function(dados){
                        $(".assinaturas").html(dados);
                    }
                })
            }
        })
    })
</script>