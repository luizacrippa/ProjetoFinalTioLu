<?php 

// Deleta
if (array_key_exists("remover", $_GET)) {
  $id = intval($_GET["remover"]);
  $removeUsuario = $pdo->prepare("DELETE FROM usuario WHERE id = :id");
  $removeUsuario->execute(array(":id" => $id));
  if ($removeUsuario->rowCount() > 0) {
    header("Location: ?pagina=gerenciaUsuario");
  } else {
    die("Não foi possível remover o usuário!!!");
  }
}


// Usuário padrão para cadastro
$usuario = array("id" => 0, "login" => "", "senha" => "", "nome" => "", "tipo" => 3);
if (array_key_exists("editar", $_GET)) {
  $id = intval($_GET["editar"]);
  $buscaUsuario = $pdo->prepare("SELECT * FROM usuario WHERE id = :id");
  $buscaUsuario->execute(array(":id" => $id));
  $verificaUsuario = $buscaUsuario->fetch();
  if (!is_array($verificaUsuario)) {
    die("Usuário não encontrado!!!");
  } else {
    $usuario = $verificaUsuario;
  }
}

if (array_key_exists("login", $_POST)) {
  $id = intval($_GET["id"]);
  if ($id > 0) {
    // Edita
    $atualizaDados = array(
        ":id" => $id, 
        ":login" => $_POST["login"], 
        ":nome" => $_POST["nome"], 
        ":tipo" => $_POST["tipo"]);
    if (empty($_POST["senha"])) {
        // não atualiza senha se não digitar nada no formulário
        $atualiza = $pdo->prepare("UPDATE usuario SET login = :login, nome = :nome, tipo = :tipo WHERE id = :id");
    } else {
        $atualiza = $pdo->prepare("UPDATE usuario SET 
        login = :login, 
        senha = :senha, 
        nome = :nome, 
        tipo = :tipo WHERE id = :id");
        $atualizaDados["senha"] = $_POST["senha"];
    }
    $atualiza->execute($atualizaDados);
    if ($atualiza->rowCount() > 0) {
      header("Location: ?pagina=home");
    } else {
      die("Não foi possível atualizar o usuário!!!");
    }

  } else {
    // Cadastra
    $cadastra = $pdo->prepare("INSERT INTO usuario (login, senha, nome, tipo) 
    VALUES (:login, :senha, :nome, :tipo)");
    $cadastra->execute(array(
        ":login" => $_POST["login"], 
        ":senha" => $_POST["senha"], 
        ":nome" => $_POST["nome"], 
        ":tipo" => intval($_POST["tipo"])));

    if ($cadastra->rowCount() > 0) {
      header("Location: ?pagina=home");
    } else {
      die("Não foi possível cadastrar o usuário!!!");
    }
  }
}

$buscaUsuarios = $pdo->prepare("SELECT * FROM usuario");
$buscaUsuarios->execute();
$usuarios = $buscaUsuarios->fetchAll();
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
        <title>Gerenciar Usuários</title>
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
                        <h2><i>Gerenciamento de usuários</i></h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <table class="table">
                        <?php 
                        foreach($usuarios as $item) {
                        ?>
                        <tr>
                            <td><?php echo $item["login"];?></td>
                            <td><?php echo $item["nome"];?></td>
                            <td><a href="?pagina=gerenciaUsuario&editar=<?php echo $item["id"];?>" class="btn btn-danger">Editar</a>&nbsp;<a href="?pagina=gerenciaUsuario&remover=<?php echo $item["id"];?>" class="btn btn-danger">Remover</a></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </table>
                </div>
            </div>

            <br>

            <div class="row cadastro">
                <div class="col-md-12">
                    <?php 
                    if ($usuario["id"] > 0) {
                        echo "<h3>Edição de usuário</h3>";
                    } else {
                        echo "<h3>Cadastro de usuário</h3>";
                    }
                    ?>
                    <form class="form-group needs-validation justify-content-center" method="POST" action="?pagina=gerenciaUsuario&id=<?php echo $usuario['id'];?>" novalidate>

                        <p>Usuario</p>
                        <input type="text" class="form-control col-md-12" name="login"
                            placeholder="Insira seu nome de usuario" value="<?php echo $usuario['login']?>" required="">
                        <br>
                        <p>Nome de Perfil</p>
                        <input type="text" class="form-control col-md-12" name="nome" value="<?php echo $usuario['nome']?>"
                            placeholder="Insira seu nome completo" required="">
                        <br>
                        <p>Senha</p>
                        <input type="password" class="form-control col-md-12" name="senha" placeholder="Insira a senha"
                            required="" value="">
                        <br>
                        <p>Tipo de Conta</p>
                        <select type="select" class="form-control col-md-5" name="tipo" placeholder="Tipo de conta"
                            required="">
                            <option value="1" <?php echo $usuario["tipo"] == 1 ? 'selected' : '' ;?>>Administrador</option>
                            <option value="2" <?php echo $usuario["tipo"] == 2 ? 'selected' : '' ;?>>Gerenciador de Noticias</option>
                            <option value="3" <?php echo $usuario["tipo"] == 3 ? 'selected' : '' ;?>>Gerenciador de Avisos</option>
                        </select>
                        <br>
                        <button type="submit" class="btn btn-danger mb-2" id="botao"
                            class="btn">Salvar</button>
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