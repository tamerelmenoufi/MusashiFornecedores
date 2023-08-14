<?php
require "../../../lib/config.php";

global $pdo;

$rotulo = [
    'doc_ipf' => 'IPF',
    'doc_iqf' => 'IQF',
    'doc_iaf' => 'IAF',
    'doc_geral' => 'GERAL',
];

?>
<h6>Reratório novo <?=$rotulo[$_POST['doc']]?></h6>