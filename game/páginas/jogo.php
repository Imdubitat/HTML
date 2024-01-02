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

	session_start(); //Inicia a sessão

	// Verifica se o usuário não está logado
	if (!isset($_SESSION['usuario_logado'])) {
		header('Location: ../index.php'); // Redireciona para a página inicial
		exit();
	}

	// Obtém o tempo restante de sessão
	$tempoRestante = $tempoDeVidaDaSessao - (time() - $_SESSION['last_activity']);

	// Verifica se a sessão expirou
	if ($tempoRestante <= 0) {
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

	// Obtém o nome do usuário logado
	$nomecadastro = $_SESSION['nomecadastro'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Escolha de personagem</title>
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
        
        .rounded-image {
            border-radius: 50%;
            width: 480px;
            height: 410px;
        }
    </style>
</head>
<body>
    <header>
        <img style="padding-left: 100px;" src="../imagens/Infinitystyles.png">
        <div style="float:right; padding-right: 200px;">
            <a href="jogo.php?logout">Logout</a>
            <a href="jogo.php" >Início</a>
            <a href="adm.php">Minha conta</a>
            <img src="../imagens/barras.png">
        </div>
    </header>
    
    <div class="container mt-5">
        <div class="text-center">
            <p class="text-white">Olá, <?php echo $nomecadastro; ?></p>
        </div>
        <div class="text-center">
            <?php
				if (isset($_SESSION['imagem_dados'])) {
					// Exiba a imagem
					echo '<img src="data:image/jpeg;base64,' . base64_encode($_SESSION['imagem_dados']) . '" alt="Imagem de Perfil" class="img-fluid mx-auto d-block rounded-image">';
				} else {
					// Exiba uma mensagem se a imagem não estiver presente
					echo 'Imagem de perfil não encontrada.';
				}

			?>
        </div>
        <div class="text-center mt-3">
            <a href="personagem.php?tipo=heroi" class="btn btn-primary mr-3">Herói</a>
            <a href="personagem.php?tipo=vilao" class="btn btn-danger">Vilão</a>
        </div>
    </div>
</body>
</html>
