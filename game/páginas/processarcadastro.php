<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projeto_programacaoweb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configura o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Processar os campos do formulário
    $email = $_POST['emailcadastro'];
    $nome = $_POST['nomecadastro'];
    $senha = password_hash($_POST['senhacadastro'], PASSWORD_BCRYPT);

    // Verificar se o email é válido
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Processar o upload da imagem
        $imagem_temp = $_FILES['fileToUpload']['tmp_name'];
        $imagem_nome = $_FILES['fileToUpload']['name'];
        $imagem_tamanho = $_FILES['fileToUpload']['size'];

        // Verificar se é uma imagem válida
        if (getimagesize($imagem_temp)) {
            // Ler o conteúdo do arquivo da imagem
            $imagem_dados = file_get_contents($imagem_temp);

            // Inserir os dados no banco de dados
            $sql = "INSERT INTO cadastrousuarios (email, nome, senha, imagem_perfil) VALUES (:email, :nome, :senha, :imagem)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':imagem', $imagem_dados, PDO::PARAM_LOB);
            $stmt->execute();
            
            // Salvar a imagem na sessão
			$_SESSION['imagem_dados'] = $imagem_dados;

            // Redirecionar para a página de sucesso ou realizar outra ação
            header('Location: ../index.php');
            
        } else {
            echo "Imagem inválida.";
        }
    } else {
        echo "Email inválido.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
$conn = null;
?>
