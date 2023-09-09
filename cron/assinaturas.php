<?php
    require "../lib/config.php";

    global $pdo;

    $sql = $pdo->prepare("select *, min(ordem) as maximo from assinaturas where status != '1' group by codigo_avaliacao_mensal, doc order by codigo asc");
    $sql->execute();
    while($d = $sql->fetch()){
        echo "{$d->codigo}<br>";
    }

    $html = file_get_contents("./alert.html");


    $dados = [
        'from_name' => 'Musashi Fornecedores',
        'from_email' => 'fornecedores@musashi.com.br',
        'subject' => 'Nusashi Fornecedores - Alerta de Assinaturas',
        'html' => $html,
        // 'attachment' => [
        //         './img_bk.png',
        //         './cliente-mohatron.xls',
        //         './formulario_prato_cheio.pdf',
        // ],
        'inline' => [
                'http://musashi.mohatron.com/img/banner_notificacao.png',
        ],
        'to' => [
                ['to_name' => 'Tamer Elmenoufi', 'to_email' => 'tamer.menoufi@gmail.com'],
                // ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer@mohatron.com.br'],
        ]
    ];

    $url = "http://email.mohatron.com/send.php";
    // Make a POST request
    $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($dados)
        ]
    ]);

    // Send a request
    $result = file_get_contents($url, false, $options);
    $result = json_decode($result);

    // echo "<pre>";   
    // print_r($result);
    // echo "</pre>";

    return $result->status;