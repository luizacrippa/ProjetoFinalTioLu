<?php 
  if (array_key_exists("remover", $_GET)) {
    $id = intval($_GET["remover"]);
    $removeNoticia = $pdo->prepare("DELETE FROM noticia WHERE id = :id");
    $removeNoticia->execute(array(":id" => $id));
    if ($removeNoticia->rowCount() > 0) {
      header("Location: ?pagina=home");
    } else {
      die("Não foi possível remover a notícia!!!");
    }
  }
  // Notícia padrão para cadastro
  $noticia = array(
    "id" => 0, 
    "titulo" => "", 
    "resumo" => "", 
    "conteudo" => "", 
    "data_inicio" => date("Y-m-d"), 
    "data_fim" => date("Y-m-d"));

  if (array_key_exists("editar", $_GET)) {
    $id = intval($_GET["editar"]);
    $buscaNoticia = $pdo->prepare("SELECT * FROM noticia WHERE id = :id");
    $buscaNoticia->execute(array(":id" => $id));
    $verificaNoticia = $buscaNoticia->fetch();
    if (!is_array($verificaNoticia)) {
      die("Notícia não encontrada!!!");
    } else {
      $noticia = $verificaNoticia;
    }
  }

  if (array_key_exists("conteudo", $_POST)) {
    $id = intval($_GET["id"]);
    $imagem = "";
    if (!empty($_FILES["imagem"]["tmp_name"])) {
      $imagem = "data:" . $_FILES['imagem']['type'] . ";base64," . base64_encode(file_get_contents($_FILES['imagem']['tmp_name']));
    }
    if ($id > 0) {
      // Edita
      $atualizaDados = array(
        ":id" => $id, 
        ":titulo" => $_POST["titulo"], 
        ":resumo" => substr($_POST["conteudo"], 0, 150) . "...", 
        ":conteudo" => $_POST["conteudo"], 
        ":data_inicio" => $_POST["data_inicio"], 
        ":data_fim" => $_POST["data_fim"]);

      if (!empty($imagem)) {
          $atualiza = $pdo->prepare("UPDATE noticia SET imagem = :imagem, titulo = :titulo, resumo = :resumo, conteudo = :conteudo, data_inicio = :data_inicio, data_fim = :data_fim WHERE id = :id");
          $atualizaDados[":imagem"] = $imagem;
      } else {
          $atualiza = $pdo->prepare("UPDATE noticia SET titulo = :titulo, resumo = :resumo, conteudo = :conteudo, data_inicio = :data_inicio, data_fim = :data_fim WHERE id = :id");
      }
      $atualiza->execute($atualizaDados);
      if ($atualiza->rowCount() > 0) {
        header("Location: ?pagina=home");
      } else {
        die("Não foi possível atualizar a notícia!!!");
      }
    } else {
      // Cadastra
      $cadastra = $pdo->prepare("INSERT INTO noticia (titulo, resumo, imagem, conteudo, data_cadastro, idusuario, data_inicio, data_fim) VALUES (:titulo, :resumo, :imagem, :conteudo, NOW(), :idusuario, :data_inicio, :data_fim)");
      $cadastra->execute(array(":titulo" => $_POST["titulo"], ":resumo" => substr($_POST["conteudo"], 0, 150) . "...", ":imagem" => $imagem, ":conteudo" => $_POST["conteudo"], ":idusuario" => $_SESSION["usuario"]["id"], ":data_inicio" => $_POST["data_inicio"], ":data_fim" => $_POST["data_fim"]));
      if ($cadastra->rowCount() > 0) {
        header("Location: ?pagina=home");
      } else {
        die("Não foi possível cadastrar a notícia!!!");
      }
    }
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
    <title>Notícias</title>
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

                        <a class="btn btn-danger px-3 py-1" href="?pagina=sair">Sair</a>


                    <div class="col-md-1">
                    </div>
                </div>
        </div>
    </div>

    <div class="container">
        <div class="row formulario">
            <div class="col-md-12">
                <div class="display-4" style="text-align: center">
                    <h2><i>Gerenciar Notícias</i></h2>
                </div>
            </div>
        </div>
        <br>

        <div class="row cadastro">
            <div class="col-md-12">
                <form class="form-group needs-validation justify-content-center" method="POST" enctype="multipart/form-data" action="?pagina=gerenciaNoticia&id=<?php echo $noticia["id"];?>"
                    novalidate>

                    <div class="form-row">
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                            <label for="titulo">Título da Notícia:</label>
                            <input type="text" name="titulo" class="form-control" required="" value="<?php echo $noticia["titulo"];?>">
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-8">
                            <label for="descricao" class="mt-2">Notícia Completa:</label>
                            <textarea name="conteudo" type="text" class="form-control" placeholder="Descriçao" required=""><?php echo $noticia["conteudo"];?></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-4">
                            <label for="inputFoto">Foto: </label>
                            <input name="imagem" type="file" class="form-control border-0" id="inputFoto" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-2"></div>
                        <div class="form-group col-md-3">
                            <label for="">Disponível de:</label>
                            <input type="datetime-local" class="form-control" name="data_inicio" required
                                value="<?php echo $noticia["data_inicio"];?>">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="">Até:</label>
                            <input type="datetime-local" class="form-control" name="data_fim" required value="<?php echo $noticia["data_fim"];?>">
                        </div>
                    </div>

                    <div class="form-row">
                            <button type="submit"
                                class="btn btn-danger col-md-1">Salvar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

   
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