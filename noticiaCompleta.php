<?php 
$id = intval($_GET["id"]);
$buscaNoticia = $pdo->prepare("SELECT * FROM noticia WHERE id = :id");
$buscaNoticia->execute(array(":id" => $id));
$noticia = $buscaNoticia->fetch();
if (empty($noticia)) {
    header("Location: ?pagina=home");
    die();
}
?>
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
    <title>Notícia Completa</title>
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
        <div style="text-align: center"><h1><b><i><?php echo $noticia["titulo"];?></i></b></h1></div>
        <br>
        <div class="col-md-12">
            <div class="form-row">
                <div class="col-md-2"></div>
                <div class="card col-md-8">
                    <img class="card-img-top" src="<?php echo $noticia["imagem"];?>" alt="Imagem de capa do card">
                    <div class="card-body">
                        <p class="card-text"><?php echo $noticia["conteudo"];?></p>
                        <?php 
                        if (array_key_exists("usuario", $_SESSION) && is_array($_SESSION["usuario"])) {
                            $nivel = intval($_SESSION["usuario"]["tipo"]);
                            if ($nivel == 1 || $nivel == 2) {
                                ?>
                                <a class="btn btn-danger px-3 py-1" href="?pagina=gerenciaNoticia&editar=<?php echo $noticia["id"];?>">Editar</a>
                                <a class="btn btn-danger px-3 py-1" href="?pagina=gerenciaNoticia&remover=<?php echo $noticia["id"];?>">Remover</a>
                                <br/>
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