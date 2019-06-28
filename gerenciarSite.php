<?php 
    $mensagemParaUsuario = "";
    $imagem = "";
    if (array_key_exists("nome", $_POST)) {
        if (!empty($_FILES["imagem"]["tmp_name"])) {
            $imagem = "data:" . $_FILES['imagem']['type'] . ";base64," . base64_encode(file_get_contents($_FILES['imagem']['tmp_name']));
        }

        $alteraEmpresa = array(':id' => 1,
            ':nome' => $_POST['nome'],
            ':telefone' => $_POST['telefone'],
            ':email' => $_POST['email']);

        if (empty($_FILES["imagem"]["tmp_name"])) {
            $stmt = $pdo->prepare("UPDATE empresa SET 
            nome = :nome, 
            telefone = :telefone, 
            email = :email  WHERE id = :id");
        } else {
            $alteraEmpresa[":logo"] = $imagem;
            $stmt = $pdo->prepare("UPDATE empresa SET 
            nome = :nome, 
            telefone = :telefone, 
            email = :email, 
            logo = :logo WHERE id = :id");
        }
        $stmt->execute($alteraEmpresa);

        if ($stmt->rowCount() > 0) {
            $mensagemParaUsuario = "Empresa atualizada!!!";
            // Atualiza variável para mostrar dados no menu
            $empresa = array(
                "logo" => empty($imagem) ? $empresa["logo"] : $imagem,
                "nome" => $_POST['nome'],
                "telefone" => $_POST['telefone'],
                "email" => $_POST['email']
            );
        } else {
            $mensagemParaUsuario = "Não foi possível atualizar a empresa!!!";
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
        <title>Gerenciar Site</title>
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
                    <?php 
                    if (!empty($mensagemParaUsuario)) {
                        echo $mensagemParaUsuario;
                    }
                    ?>
                    <div class="display-4" style="text-align: center">
                        <h2><i>Gerenciamento do Site</i></h2>
                    </div>
                </div>
            </div>
            <br>

        <?php 
            $buscaEmpresa = $pdo->prepare("SELECT * FROM empresa WHERE id = 1");
            $buscaEmpresa->execute();
            $empresa = $buscaEmpresa->fetch();
        ?>

            <div class="row cadastro">
                <div class="col-md-12">
                    <form class="form-group needs-validation justify-content-center" method="POST" action="" enctype="multipart/form-data" novalidate>
                            <br>
                            <h3>Editar Dados da Empresa</h3>
                            <p>Nome da Empresa</p>
                            <input type="text" class="form-control col-md-12" name="nome" value="<?php echo $empresa['nome'];?>"
                                placeholder="Insira o nome da empresa" required="">
                            <br>
                            <p>Telefone</p>
                            <input type="text" class="form-control col-md-12" name="telefone" value="<?php echo $empresa['telefone'];?>"
                                placeholder="Insira o telefone" required="">
                            <br>
                            <p>Email</p>
                            <input type="text" class="form-control col-md-12" name="email" placeholder="Insira o email" value="<?php echo $empresa['email'];?>"
                                required="">
                            <br>
                            <p>Logo</p>
                            <input type="file" name="imagem" required="">
                            <br><br>
                            <button type="submit" class="btn btn-danger mb-2" id="botao"
                                class="btn">Editar Dados</button>
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