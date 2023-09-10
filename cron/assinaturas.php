<?php
    require "../lib/config.php";

    global $pdo;

    $lib = [
        '[doc]',
        '[nome]',
        '[mes]',
        '[ano]',
        '[fornecedor_nome]',
        '[fornecedor_cnpj]'
    ];

    $doc = [
        'doc_geral' => 'Geral',
        'doc_iaf' => 'IAF',
        'doc_ipf' => 'IPF',
        'doc_iqf' => 'IQF',
    ];

    $html = file_get_contents("./alert.html");

    $sql = $pdo->prepare("select 
                                a.doc,
                                b.nome,
                                /*b.email,*/
                                'tamer@mohatron.com.br' as email,
                                c.mes,
                                c.ano,
                                d.nome as fornecedor_nome,
                                d.cnpj as fornecedor_cnpj
                        from assinaturas a 
                        left join login b on a.usuario = b.codigo
                        left join avaliacao_mensal c on a.codigo_avaliacao_mensal = c.codigo
                        left join fornecedores d on c.codigo_fornecedor = d.codigo
                        where a.status != '1'
                        group by a.codigo_avaliacao_mensal, a.doc
                        order by a.codigo asc limit 3");
    $sql->execute();
    while($d = $sql->fetch()){

        set_time_limit(90);

        $data = [
            $doc[$d['doc']],
            $d['nome'],
            $d['mes'],
            $d['ano'],
            $d['fornecedor_nome'],
            $d['fornecedor_cnpj']
        ];

        $html = str_replace($lib, $data, $html);

        $url = "http://email.mohatron.com/send.php";

        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($dados)
            ]
        ]);

        $result = file_get_contents($url, false, $options);
        $result = json_decode($result);

        echo $result->status;

    }

    exit();


    // $dados = [
    //     'from_name' => 'Musashi Fornecedores',
    //     'from_email' => 'mailgun@moh1.com.br',
    //     'subject' => 'Nusashi Fornecedores - Alerta de Assinaturas',
    //     'html' => $html,
    //     // 'attachment' => [
    //     //         './img_bk.png',
    //     //         './cliente-mohatron.xls',
    //     //         './formulario_prato_cheio.pdf',
    //     // ],
    //     'inline' => [
    //             'http://musashi.mohatron.com/img/banner_notificacao.png',
    //     ],
    //     'to' => [
    //             // ['to_name' => 'Tamer Elmenoufi', 'to_email' => 'tamer.menoufi@gmail.com'],
    //             ['to_name' => $d['nome'], 'to_email' => trim($d['email'])],
    //     ]
    // ];

    // $url = "http://email.mohatron.com/send.php";
    // // Make a POST request
    // $options = stream_context_create(['http' => [
    //         'method'  => 'POST',
    //         'header' => 'Content-Type: application/x-www-form-urlencoded',
    //         'content' => http_build_query($dados)
    //     ]
    // ]);

    // // Send a request
    // $result = file_get_contents($url, false, $options);
    // $result = json_decode($result);

    // // echo "<pre>";   
    // // print_r($result);
    // // echo "</pre>";

    // echo $result->status;