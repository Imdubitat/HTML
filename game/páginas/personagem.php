<?php
	$tempoDeVidaDaSessao = 3600;  // Tempo de vida da sessão em segundos
	$caminho = '/';             // Caminho onde o cookie de sessão estará disponível
	$dominio = '';              // Domínio onde o cookie de sessão estará disponível
	$seguro = false;            // O cookie não é transmitido apenas por conexões seguras (HTTP)
	$httpApenas = true;         // O cookie de sessão só é acessível por scripts do lado do servidor

	session_set_cookie_params(
		$tempoDeVidaDaSessao,
		$caminho,
		$dominio,
		$seguro,
		$httpApenas
	);

	session_start(); // Inicia a sessão

	// Verifica se o usuário não está logado
	if (!isset($_SESSION['usuario_logado'])) {
		header('Location: ../index.php'); // Redireciona para a página inicial
		exit();
	}

	// Obtém o tempo restante de sessão
	$tempoRestante = $tempoDeVidaDaSessao - (time() - $_SESSION['last_activity']);

	// Verifica se a sessão expirou
	if ($tempoRestante <= 0) {
		session_unset();
		session_destroy();
		header('Location: ../index.php');
		exit();
	}
	
	if ($_GET["tipo"] === "heroi") {
		$arquivo = "../arquivostxt/heroi.txt";
		$imagem = "../imagens/heroi.png";
		$personagem = "Starfox";
	} elseif ($_GET["tipo"] === "vilao") {
		$arquivo = "../arquivostxt/vilao.txt";
		$imagem = "../imagens/vilao.png";
		$personagem = "Thanos";
	} else {
		session_unset(); // Limpa os dados da sessão
		session_destroy(); // Destrói a sessão
		header('Location: ../index.php');
		exit();
	}
	
	if (isset($_GET['logout'])) {
		session_unset();
		session_destroy();
		header('Location: ../index.php');
		exit();
	}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $personagem; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		
        body {
            background-color: black;
        }
        h1{
            color: white;
        }
        a{
            text-decoration: none;
            color:white;
            padding-left: 30px;
            padding-right: 10px;
        }
        a:hover{
            color: #ff00ff;
            background-color: transparent;
        }
        
    </style>
</head>
<body>
	<header>
        <img style="padding-left: 100px;" src="../imagens/Infinitystyles.png">
        <div style="float:right; padding-right: 200px;">
            <a href="personagem.php?logout">Logout</a>
            <a href="jogo.php" >Início</a>
            <a href="adm.php">Minha conta</a>
            <img src="../imagens/barras.png">
        </div>
    </header>
    
	<div class="container mt-5">
        <h1 class="text-center" style="color:white;"><?php echo $personagem; ?></h1>
        <div class="text-center mt-3">
            <img src="<?php echo $imagem; ?>" alt="<?php echo $personagem; ?>" class="img-fluid">
        </div>
        <div class="container mt-3">
            <p class="text-center" style="color:white;"><?php echo file_get_contents($arquivo); ?></p>
        </div>
        <div class="text-center mt-3">
            <a href="jogo.php" class="btn btn-primary">Escolher Outro Personagem</a>
            <a href="jogofuncionando.php" class="btn btn-primary">Jogar</a>
        </div>
    </div>
</body>
</html>
