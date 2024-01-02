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

	$email = "";
	$mensagemErro = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$email = $_POST["email"];
		$senha = $_POST["senha"];

		// Conexão com o banco de dados
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "projeto_programacaoweb";

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Consulta SQL para obter a senha armazenada no banco de dados
			$stmt = $conn->prepare("SELECT nome, senha FROM cadastrousuarios WHERE email = :email");
			$stmt->bindParam(':email', $email);
			$stmt->execute();

			if ($stmt->rowCount() > 0) {
				$row = $stmt->fetch();
				$nome = $row['nome'];
				$senhaArmazenada = $row['senha'];
				$_SESSION['nomecadastro'] = $nome;
				
				// Consulta SQL para obter a imagem do banco de dados
				$stmtImagem = $conn->prepare("SELECT imagem_perfil FROM cadastrousuarios WHERE email = :email");
				$stmtImagem->bindParam(':email', $email);
				$stmtImagem->execute();
				
				if ($stmtImagem->rowCount() > 0) {
					$rowImagem = $stmtImagem->fetch();
					$_SESSION['imagem_dados'] = $rowImagem['imagem_perfil'];
				}
				
				if (password_verify($senha, $senhaArmazenada)) {
					// A senha está correta
					$_SESSION['usuario_logado'] = true;
					$_SESSION['last_activity'] = time();
					$_SESSION['email'] = $email;

					header("Location: páginas/jogo.php?email=" . urlencode($email));
					exit();
				} else {
					$mensagemErro = "E-mail ou senha incorretos.";
				}
			} else {
				$mensagemErro = "E-mail não encontrado no banco de dados. Se não possui uma conta, por favor, faça o cadastro.";
			}
		} catch (PDOException $e) {
			echo "Erro: " . $e->getMessage();
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seja Bem-vindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
            color: white;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #333;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .popup-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            color: white;
            border: none;
        }

        .form-container {
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
        }

        .form-container img {
            max-width: 100%;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container text-center">
    <img src="imagens/harrystylesinfinity.jpg" class="img-fluid mx-auto" alt="Papel de Parede">
    <div class="mt-4">
        <img src="imagens/Infinitystyles.png">
    </div>

    <!-- Formulário de acesso ao jogo -->
    <form method="post" class="mt-4">
        <div class="form-group">
            <label style="color:white;" for="email">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
        </div>
        <div class="form-group">
            <label style="color:white;" for="senha">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Entrar</button>
            <?php if ($mensagemErro): ?>
                <p style="color: red;"><?php echo $mensagemErro; ?></p>
            <?php endif; ?>
        </div>
    </form>

    <!-- Pop-up de Cadastro -->
    <div class="container text-center mt-3">
        <button id="btnAbrirCadastro" class="btn btn-primary">Abrir Cadastro</button>
    </div>
    <div id="popupCadastro" class="popup">
		<button type="button" class="btn-close popup-close-btn" aria-label="Close" onclick="fecharPopup()"></button>
        <h2>Cadastro</h2>
        <form method="post" action="páginas/processarcadastro.php" enctype="multipart/form-data" class="mt-4">
			<div class="form-group">
				<input type="file" name="fileToUpload" id="fileToUpload">
			</div>
			<div class="form-group">
				<label for="text">Nome de Usuário:</label>
				<input type="text" id="nomecadastro" name="nomecadastro" required>
			</div>
			<div class="form-group">
				<label for="email">E-mail:</label>
				<input type="email" id="emailcadastro" name="emailcadastro" required>
			</div>
            <div class="form-group">
				<label for="senha">Senha:</label>
				<input type="password" id="senhacadastro" name="senhacadastro" required>
            </div>
            <div class="text-center mt-3">
				<button type="submit" class="btn btn-primary">Cadastrar</button>
			</div>
        </form>
    </div>

    <!-- Configuração do comportamento do poup-up -->
    <script>
        document.getElementById('btnAbrirCadastro').addEventListener('click', function () {
            document.getElementById('popupCadastro').style.display = 'block';
        });

        function fecharPopup() {
            document.getElementById('popupCadastro').style.display = 'none';
        }
    </script>
</div>
</body>
</html>
