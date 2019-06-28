<!DOCTYPE html>
<html lang="pt-br">

  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
      integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="CSS/style.css">
    <title>Home</title>
  </head>

  <body>

    <div class="jumbotron jumbotron-fluid py-3">
      <div class="container">
          <div class="row navbar">
            <a href="?pagina=home"><img id="logo_menu" src="<?php echo $empresa["logo"];?>" class="rounded-circle"></a>

            <pre class="text-left"><code>
                    <i><?php echo $empresa["nome"];?></i>
                    Contato: <?php echo $empresa["telefone"];?> 
                    E-mail: <?php echo $empresa["email"];?>
                  </code></pre>

              <?php 
              if (array_key_exists("usuario", $_SESSION) && is_array($_SESSION["usuario"])) {
                $nivel = intval($_SESSION["usuario"]["tipo"]);
                if ($nivel == 1) {
                  // Se for nível 1, pode editar empresa e usuários
                  ?>
                  <a class="btn btn-danger px-3 py-1" href="?pagina=gerenciaSite">Empresa</a>
                  <a class="btn btn-danger px-3 py-1" href="?pagina=gerenciaUsuario">Usuários</a>
                  <?php
                }
              ?>

                <a class="btn btn-danger px-3 py-1" href="?pagina=sair">Sair</a>
              
              <?php
              } else {
              ?>
                <a class="btn btn-danger px-3 py-1" href="?pagina=login">Logar-se</a>
              
              <?php
              }
              ?>     


            <div class="col-md-1">
            </div>
          </div>
      </div>
    </div>

    <div class="container">
      <div class="col-md-12">

        <div class="form-row">
          <div class="card col-md-6 p-3"><h1>Notícias</h1>

          <?php 
            $buscaNoticias = $pdo->prepare("SELECT * FROM noticia 
            WHERE NOW() >= data_inicio AND data_fim > NOW() ORDER BY data_inicio DESC");
            $buscaNoticias->execute();
            $noticias = $buscaNoticias->fetchAll();
            foreach($noticias as $noticia) {
          ?>

            <div class="card">
              <img class="card-img-top" alt="Imagem de capa do card" src="<?php echo $noticia["imagem"];?>">
              <div class="card-body">
                <h5 class="card-title"><?php echo $noticia["titulo"];?></h5>
                <p class="card-text font-weight-light"><?php echo $noticia["resumo"];?></p>
                <a href="?pagina=noticia&id=<?php echo $noticia["id"];?>" class="btn btn-danger">Ler mais</a>
              </div>
            </div>

            <br>

         <?php
            }
            if (array_key_exists("usuario", $_SESSION) && array_key_exists("tipo", $_SESSION["usuario"])) {
              $nivel = intval($_SESSION["usuario"]["tipo"]);
              if ($nivel == 1 || $nivel == 2) {
          ?>
            <a href="?pagina=gerenciaNoticia" class="btn btn-danger mt-3">Nova Notícia</a>
          
          <?php
              }
            }
          ?>

          </div>

          <div class="col-md-1"></div>
          <div class="col-md-5 p-3 card"><h1>Avisos</h1>

          <?php 
            $buscaAvisos = $pdo->prepare("SELECT * FROM avisos WHERE NOW() >= data_inicio AND data_fim > NOW() ORDER BY data_inicio DESC");
            $buscaAvisos->execute();
            $avisos = $buscaAvisos->fetchAll();
            foreach ($avisos as $aviso) {
          ?>

            <div class="card mt-3">
              <div class="card-body">
                <h5 class="card-title"><?php echo $aviso["conteudo"];?></h5>

                <?php 
                  if (array_key_exists("usuario", $_SESSION) && array_key_exists("tipo", $_SESSION["usuario"])) {
                    $nivel = intval($_SESSION["usuario"]["tipo"]);
                    if ($nivel == 1 || $nivel == 3) {
                      ?>
                      <a href="?pagina=gerenciaAviso&editar=<?php echo $aviso["id"];?>" class="btn btn-danger">Editar</a>
                      <a href="?pagina=gerenciaAviso&remover=<?php echo $aviso["id"];?>" class="btn btn-danger">Remover</a>
                      <?php
                    }
                  }
                ?>

              </div>
            </div>

        <?php
          }
          if (array_key_exists("usuario", $_SESSION) && array_key_exists("tipo", $_SESSION["usuario"])) {
            $nivel = intval($_SESSION["usuario"]["tipo"]);
            if ($nivel == 1 || $nivel == 3) {
              ?>
              <a href="?pagina=gerenciaAviso" class="btn btn-danger mt-3">Novo Aviso</a>
              <?php
            }
          }
        ?>

          </div>
        </div>
      </div>
    </div>
    </div>
    <br><br>



    <div>
      <footer>
        <div class="footer-copyright text-center py-2"><b>@ 2019, Luíza Crippa</b>
        </div>
      </footer>
    </div>


    <!-- JavaScript (Opcional) -->
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
      integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
      crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>
  </body>

  </html>