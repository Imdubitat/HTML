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

	// Obtém o nome do usuário logado
	$nomecadastro = $_SESSION['nomecadastro'];
	$emailcadastro = $_SESSION['email'];

	// Conexão com o banco de dados
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "projeto_programacaoweb";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// Configura o PDO para lançar exceções em caso de erro
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Processar as alterações no formulário
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$novonome = $_POST['novonome'];
			$novoemail = $_POST['novoemail'];
			$novasenha = password_hash($_POST['novasenha'], PASSWORD_BCRYPT);

			// Verificar se o email é válido
			if (filter_var($novoemail, FILTER_VALIDATE_EMAIL)) {
				// Processar o upload da nova imagem
				$nova_imagem_temp = $_FILES['novofileToUpload']['tmp_name'];
				$nova_imagem_nome = $_FILES['novofileToUpload']['name'];
				$nova_imagem_tamanho = $_FILES['novofileToUpload']['size'];

				// Verificar se é uma imagem válida (opcional, dependendo dos requisitos)
				if (getimagesize($nova_imagem_temp)) {
					// Ler o conteúdo do arquivo da nova imagem
					$nova_imagem_dados = file_get_contents($nova_imagem_temp);

					// Atualizar os dados no banco de dados, incluindo a nova imagem
					$sql = "UPDATE cadastrousuarios SET nome = :novonome, email = :novoemail, senha = :novasenha, imagem_perfil = :nova_imagem WHERE email = :emailcadastro";
					$stmt = $conn->prepare($sql);
					$stmt->bindParam(':novonome', $novonome);
					$stmt->bindParam(':novoemail', $novoemail);
					$stmt->bindParam(':novasenha', $novasenha);
					$stmt->bindParam(':nova_imagem', $nova_imagem_dados, PDO::PARAM_LOB);
					$stmt->bindParam(':emailcadastro', $emailcadastro);
					$stmt->execute();
					
					// Atualizar a variável de sessão com o novo e-mail
					$_SESSION['email'] = $novoemail;

					// Redirecionar para a página de sucesso ou realizar outra ação
					header("Location: ../index.php");
					exit();
				} else {
					echo "Nova imagem inválida.";
				}
			} else {
				echo "Novo email inválido.";
			}

			// Processar a exclusão da conta
			if (isset($_POST['excluirConta'])) {
				// Excluir a conta do banco de dados
				$sqlExcluirConta = "DELETE FROM cadastrousuarios WHERE email = :emailcadastro";
				$stmtExcluirConta = $conn->prepare($sqlExcluirConta);
				$stmtExcluirConta->bindParam(':emailcadastro', $emailcadastro);
				$stmtExcluirConta->execute();

				// Limpar dados da sessão e redirecionar para a página inicial
				session_unset(); // Limpa os dados da sessão
				session_destroy(); // Destrói a sessão
				header('Location: ../index.php'); // Redireciona para a página inicial
				exit();
			}
		}
		
		if (isset($_GET['logout'])) {
		session_unset();
		session_destroy();
		header('Location: ../index.php');
		exit();
	}
	} catch (PDOException $e) {
		echo "Erro: " . $e->getMessage();
	}
	$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Perfil</title>
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
            <a href="adm.php?logout">Logout</a>
            <a href="jogo.php" >Início</a>
            <a href="adm.php" >Minha conta</a>
            <img src="../imagens/barras.png">
        </div>
    </header>
    
    <div class="container text-center mt-5">
        <!-- Exibir dados do usuário -->
        <h1>Perfil do Usuário</h1>
        <?php
				if (isset($_SESSION['imagem_dados'])) {
					// Exiba a imagem
					echo '<img src="data:image/jpeg;base64,' . base64_encode($_SESSION['imagem_dados']) . '" alt="Imagem de Perfil" class="img-fluid mx-auto d-block rounded-image">';
				} else {
					// Exiba uma mensagem se a imagem não estiver presente
					echo 'Imagem de perfil não encontrada.';
				}

			?>
		<div class="container mt-3">
			<p>Nome: <?php echo $nomecadastro; ?></p>
			<p>Email: <?php echo $emailcadastro; ?></p>
		</div>
	</div>
	
	<div class="container text-center mt-3">
        <button id="btnEditarDados" class="btn btn-primary">Editar dados</button>
        <button id="btnExcluirConta" class="btn btn-danger">Excluir conta</button>
    </div>
    <div id="popupAlterarDados" class="popup">
		<button type="button" class="btn-close popup-close-btn" aria-label="Close" onclick="fecharPopup()"></button>
        <h2>Cadastro</h2>
        <form method="post" enctype="multipart/form-data" class="mt-4">
			<div class="form-group">
                <label for="novonome">Novo Nome de Usuário:</label>
                <input type="text" class="form-control" id="novonome" name="novonome" value="<?php echo $nomecadastro; ?>" required>
            </div>
            <div class="form-group">
                <label for="novoemail">Novo E-mail:</label>
                <input type="email" class="form-control" id="novoemail" name="novoemail" value="<?php echo $emailcadastro; ?>" required>
            </div>
            <div class="form-group">
                <label for="novasenha">Nova Senha:</label>
                <input type="password" class="form-control" id="novasenha" name="novasenha" required>
            </div>
            <div class="form-group">
                <label for="novofileToUpload">Nova Imagem de Perfil:</label>
                <input type="file" name="novofileToUpload" id="novofileToUpload">
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>

    <!-- Popup para confirmar a exclusão de conta -->
    <div id="popupExcluirConta" class="popup">
		<button type="button" class="btn-close popup-close-btn" aria-label="Close" onclick="fecharPopupExcluirConta()"></button>
        <h2>Excluir Conta</h2>
        <p>Tem certeza de que deseja excluir sua conta?</p>
        <form method="post">
            <button type="submit" class="btn btn-danger" name="excluirConta">Sim, excluir conta</button>
            <button type="button" class="btn btn-secondary" onclick="fecharPopupExcluirConta()">Cancelar</button>
        </form>
    </div>

    <!-- Configuração do comportamento do poup-up -->
    <script>
        document.getElementById('btnEditarDados').addEventListener('click', function () {
            document.getElementById('popupAlterarDados').style.display = 'block';
        });

        document.getElementById('btnExcluirConta').addEventListener('click', function () {
            document.getElementById('popupExcluirConta').style.display = 'block';
        });

        function fecharPopup() {
            document.getElementById('popupAlterarDados').style.display = 'none';
        }

        function fecharPopupExcluirConta() {
            document.getElementById('popupExcluirConta').style.display = 'none';
        }
    </script>
</body>
</html>
