<?php 

require "banco.php";

session_start();

$buscaEmpresa = $pdo->prepare("SELECT * FROM empresa WHERE id = 1");
$buscaEmpresa->execute();
$empresa = $buscaEmpresa->fetch();

// Página padrão
$pagina = "home";
// Verifica se existe o parâmetro 'pagina' no get
if (array_key_exists("pagina", $_GET)) {
    $pagina = $_GET["pagina"];
}

// Valida Login
switch($pagina) {
    case "login":
        if (array_key_exists("usuario", $_SESSION)) {
            // Redireciona se usuário estiver logado
            header("Location: ?pagina=home");
        } else {
            if (array_key_exists("login", $_POST)) {
                // Tenta fazer login
                $buscaUsuario = $pdo->prepare("SELECT * FROM usuario WHERE login = :login AND senha = :senha LIMIT 1");
                $buscaUsuario->execute(array(":login" => $_POST["login"], ":senha" => $_POST["senha"]));
                $usuario = $buscaUsuario->fetch();
                if (!empty($usuario)) {
                    $_SESSION["usuario"] = $usuario;
                    header("Location: ?pagina=home");
                } else {
                    header("Location: ?pagina=login");
                }
            } else {
                include("login.php");
            }
        }
        break;

    
    case "sair":
        //Logoff
        unset($_SESSION["usuario"]); 
        include("login.php");
        break;
    case "home":
        include("home.php");
        break;
    case "noticia":
        include("noticiaCompleta.php");
        break;
        
    case "gerenciaNoticia":
        if (array_key_exists("usuario", $_SESSION) && array_key_exists("tipo", $_SESSION["usuario"])) {
            $nivel = intval($_SESSION["usuario"]["tipo"]);
            if ($nivel == 1 || $nivel == 2) {
                include("gerenciarNoticia.php");
            } else {
                // Se usuário não tiver permissão volta para a home
                header("Location: ?pagina=home");
            }
        } else {
            // Se usuário não estiver logado, vai para página de login
            header("Location: ?pagina=login");
        }
        break;

    case "gerenciaAviso":
        if (array_key_exists("usuario", $_SESSION) && array_key_exists("tipo", $_SESSION["usuario"])) {
            $nivel = intval($_SESSION["usuario"]["tipo"]);
            if ($nivel == 1 || $nivel == 3) {
                include("gerenciarAviso.php");
            } else {
                // Se usuário não tiver permissão volta para a home
                header("Location: ?pagina=home");
            }
        } else {
            // Se usuário não estiver logado, vai para página de login
            header("Location: ?pagina=login");
        }
        break;

    case "gerenciaUsuario":
        if (array_key_exists("usuario", $_SESSION) && array_key_exists("tipo", $_SESSION["usuario"])) {
            $nivel = intval($_SESSION["usuario"]["tipo"]);
            if ($nivel == 1) {
                include("gerenciarUsuario.php");
            } else {
                // Se usuário não tiver permissão volta para a home
                header("Location: ?pagina=home");
            }
        } else {
            // Se usuário não estiver logado, vai para página de login
            header("Location: ?pagina=login");
        }
        break;

    case "gerenciaSite":
        if (array_key_exists("usuario", $_SESSION) && array_key_exists("tipo", $_SESSION["usuario"])) {
            $nivel = intval($_SESSION["usuario"]["tipo"]);
            if ($nivel == 1) {
                include("gerenciarSite.php");
            } else {
                // Se usuário não tiver permissão volta para a home
                header("Location: ?pagina=home");
            }
        } else {
            // Se usuário não estiver logado, vai para página de login
            header("Location: ?pagina=login");
        }
    break;
}

