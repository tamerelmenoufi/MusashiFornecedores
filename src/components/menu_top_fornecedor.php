<style>
    li.dropdown:last-child .confis {
        right: 0 !important;
        left: auto !important;
    }

    nav[menu] li {
        cursor: pointer;
    }

    div.img {
        height: 50px;
        weight: 60px;
        padding: 0 8px;

    }

    div.img img {
        height: 100%;
        /* weight: 100%; */
    }

    @media (max-width: 991px) {
        div[ponta] {
            display: none;
        }
    }
</style>

<div class="container-fluid p-0" style="position: fixed; top: 0; z-index: 9999;">
    <nav menu class="navbar navbar-expand-lg navbar-light bg-light noprint nav-tabs" style="height: 20%">
        <div class="container-fluid">
            <a class="navbar-brand text-danger fw-bold">
                <div class="img">
                    <img src="img/logotopo1_sem_bg.png" alt="">
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav col-md-6  ">
                    <li class="nav-item">
                        <a home class="nav-link " aria-current="page">Principal</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown ">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <?= $ConfForn['nome'] ?>
                        </a>
                        <ul class="dropdown-menu confis" aria-labelledby="navbarDropdown">
                            <li><a sair local="src/pages/actions/home_action.php" class="dropdown-item">Sair</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div ponta class="img">
            <img src="img/logotopo2_sem_bg.png" alt="">
        </div>
    </nav>
</div>

<script>
    $('a[abrir]').click(function () {
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function (retorno) {
                $('div#home').html(retorno)
            }
        })
    })

    $('a[senha]').click(function () {
        let local = $(this).attr('local')

        let codigo = $(this).attr('cod_usu')

        $.ajax({
            url: local,
            method: 'POST',
            data: {
                codigo,
                acao: 'alterar_senha'
            }, success: function (retorno) {
                popup = $.dialog({
                    content: retorno,
                    title: false,
                    backgroundDismiss: true,
                    closeIcon: false,
                })
            }
        })
    })

    $('a[sair]').click(function () {
        let local = $(this).attr('local')

        $.ajax({
            url: local,
            method: 'POST',
            data: {
                acao: 'sair'
            }, success: function (sair) {
                if (sair == 'ok') {
                    $.ajax({
                        url: "src/pages/login.php",
                        success: function (login) {
                            $('div#body').html(login)
                        }
                    })
                }
            }
        })
    })

    $('a[home]').click(function () {
        location.reload()
    })
</script>