<?php 

// Deleta
if (array_key_exists("remover", $_GET)) {
  $id = intval($_GET["remover"]);
  $removeAviso = $pdo->prepare("DELETE FROM avisos WHERE id = :id");
  $removeAviso->execute(array(":id" => $id));
  
  if ($removeAviso->rowCount() > 0) {
    include("Location: ?pagina=home");
  } else {
    die("Não foi possível remover o aviso!!!");
  }
}


// Aviso padrão para cadastro
$aviso = array("id" => 0, "conteudo" => "", "data_inicio" => date("Y-m-d"), "data_fim" => date("Y-m-d"));
if (array_key_exists("editar", $_GET)) {
  $id = intval($_GET["editar"]);
  $buscaAviso = $pdo->prepare("SELECT * FROM avisos WHERE id = :id");
  $buscaAviso->execute(array(":id" => $id));
  $verificaAviso = $buscaAviso->fetch();

  if (!is_array($verificaAviso)) {
    die("Aviso não encontrado!!!");
  } else {
    $aviso = $verificaAviso;
  }
}

if (array_key_exists("conteudo", $_POST)) {
  $id = intval($_GET["id"]);
  if ($id > 0) {
    // Edita
    $atualiza = $pdo->prepare("UPDATE avisos SET conteudo = :conteudo, data_inicio = :data_inicio, data_fim = :data_fim WHERE id = :id");
    $atualiza->execute(array(
      ":id" => $id, 
      ":conteudo" => $_POST["conteudo"], 
      ":data_inicio" => $_POST["data_inicio"], 
      ":data_fim" => $_POST["data_fim"]));

    if ($atualiza->rowCount() > 0) {
      header("Location: ?pagina=home");
    } else {
      die("Não foi possível atualizar o aviso!!!");
    }
  } else {
    // Cadastra
    $cadastra = $pdo->prepare("INSERT INTO avisos (conteudo, data_cadastro, idusuario, data_inicio, data_fim) 
    VALUES (:conteudo, NOW(), :idusuario, :data_inicio, :data_fim)");
    $cadastra->execute(array(
      ":conteudo" => $_POST["conteudo"], 
      ":idusuario" => $_SESSION["usuario"]["id"], 
      ":data_inicio" => $_POST["data_inicio"], 
      ":data_fim" => $_POST["data_fim"]));

    if ($cadastra->rowCount() > 0) {
      header("Location: ?pagina=home");
    } else {
      die("Não foi possível cadastrar o aviso!!!");
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
  <title>Avisos</title>
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
          <h2><i>Gerenciar Avisos</i></h2>
        </div>
      </div>
    </div>
    <br>

    <div class="row cadastro">
      <div class="col-md-12">
        <form class="form-group needs-validation justify-center" method="POST" action="?pagina=gerenciaAviso&id=<?php echo $aviso['id'];?>">
          <div class="form-row">
            <div class="col-md-1"></div>
            <div class="col-md-4">
              <label for="titulo">Aviso:</label>
              <input type="text" name="conteudo" class="form-control" value="<?php echo $aviso['conteudo'];?>" required="">
            </div>


            <div class="form-group col-md-3">
              <label for="">Disponível de:</label>
              <input type="datetime-local" class="form-control" name="data_inicio" required value="<?php echo $aviso["data_inicio"]; ?>">
            </div>

            <div class="form-group col-md-3">
              <label for="">Até:</label>
              <input type="datetime-local" class="form-control" name="data_fim" required value="<?php echo $aviso["data_fim"]; ?>">
            </div>
          </div>

          <div class="form-row">
              <button type="submit" class="btn btn-danger col-md-1">Salvar</button>
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