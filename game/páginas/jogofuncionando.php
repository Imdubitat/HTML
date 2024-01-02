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
    <title>Infinity Styles</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
        }
        .personagem1 {
            width: 150px;
            height: 200px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .personagem2 {
            width: 300px;
            height: 300px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .personagem1:hover {
            transform: scale(1.1);
        }
        .personagem2:hover {
            transform: scale(1.1);
        }
        #pontos1, #pontos2 {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
            margin: 20px 0;
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
        }
    </style>
</head>
<body>
    <header>
        <img style="padding-left: 100px;" src="../imagens/Infinitystyles.png">
        <div style="float:right; padding-right: 200px;">
            <a href="jogofuncionando.php?logout">Logout</a>
            <a href="jogo.php" >Início</a>
            <a href="adm.php">Minha conta</a>
            <img src="../imagens/barras.png">
        </div>
    </header>
    
    <div class="text-white text-center"><br>
        <h1>Infinity Styles</h1>
        <p>Salve ou destrua o universo!</p>
        <img src="../imagens/imagem-heroi.png" class="personagem1" id="personagem1">
        <img src="../imagens/imagem-vilao.png" class="personagem2" id="personagem2">
        <p id="pontos1">Starfox: 100</p>
        <p id="pontos2">Thanos: 100</p>
		
		<script>
			let pontosDeVida1 = 100;
			let pontosDeVida2 = 100;
			let personagensClicaveis = true;

			document.getElementById('personagem1').addEventListener('click', function() {
				if (personagensClicaveis) {
					// Trocar temporariamente a imagem e restaurá-la após 200ms (0.2 segundos)
					this.src = '../imagens/heroi-dano.png';
					setTimeout(() => {
						this.src = '../imagens/imagem-heroi.png';
					}, 200);

					const dano = Math.floor(Math.random() * 20) + 1;
					pontosDeVida1 -= dano;

					if (pontosDeVida1 <= 0) {
						pontosDeVida1 = 0;
						document.getElementById('pontos1').style.color = 'red';
						document.getElementById('pontos1').innerHTML = `Starfox perdeu!`;
						personagensClicaveis = false;
					} else {
						document.getElementById('pontos1').innerHTML = `Starfox: ${pontosDeVida1}`;
					}
				}
			});

			document.getElementById('personagem2').addEventListener('click', function() {
				if (personagensClicaveis) {
					// Trocar temporariamente a imagem e restaurá-la após 200ms (0.2 segundos)
					this.src = '../imagens/vilao-dano.png';
					setTimeout(() => {
						this.src = '../imagens/imagem-vilao.png';
					}, 200);

					const dano = Math.floor(Math.random() * 20) + 1;
					pontosDeVida2 -= dano;

					if (pontosDeVida2 <= 0) {
						pontosDeVida2 = 0;
						document.getElementById('pontos2').style.color = 'red';
						document.getElementById('pontos2').innerHTML = `Thanos perdeu!`;
						personagensClicaveis = false;
					} else {
						document.getElementById('pontos2').innerHTML = `Thanos: ${pontosDeVida2}`;
					}
				}
			});
		</script>
    </div>
</body>
</html>
