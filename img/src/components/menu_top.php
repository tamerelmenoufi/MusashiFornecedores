<nav class="navbar navbar-expand-lg navbar-light bg-light" style="height: 20%">
  <div class="container-fluid">
    <a class="navbar-brand text-danger fw-bold" href="#">MUSASHI</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a home class="nav-link " aria-current="page" href="#">Principal</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Fornecedores
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a abrir local="src/fornecedor/fornecedor_form.php" class="dropdown-item" href="#">Cadastrar</a></li>
            <li><a abrir local="src/fornecedor/fornecedor_lista.php" class="dropdown-item" href="#">Lista de Fornecedores</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?=$ConfUsu['nome']?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a sair local="src/pages/actions/home_action.php" class="dropdown-item" href="#">Sair</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Transparência
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a abrir local="src/pages/criterios.php" class="dropdown-item" aria-current="page" href="#">Critérios</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
    $('a[abrir]').click(function(){
        let local = $(this).attr('local')
        $.ajax({
            url: local,
            success: function(retorno){
                $('div#home').html(retorno)
            }
        })
    })

    $('a[sair]').click(function(){
        let local = $(this).attr('local')

        $.ajax({
            url: local,
            method: 'POST',
            data: {
                acao: 'sair'
            },success: function(sair){
                if(sair == 'ok'){
                    $.ajax({
                        url: "src/pages/login.php",
                        success: function(login){
                            $('div#body').html(login)
                        }
                    })
                }
            }
        })
    })

    $('a[home]').click(function(){
        location.reload()
    })
</script>