<?php
session_start();
if (isset($_SESSION["usuario_id"])) {
    header("Location: pages/dashboard.php");
    exit;
}
require_once "config/conexao.php";
$erro = "";
$sucesso = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($email === "" || $senha === "") {
        $erro = "Preencha todos os campos.";
    } else {
        $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario["senha"])) {
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            header("Location: pages/dashboard.php");
            exit;
        } else {
            $erro = "E-mail ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | FINAPP</title>
<link rel="icon" href="assets/img/logo-finapp.jpeg">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-body">
<div class="auth-wrapper">
    <div class="auth-brand">
        <img src="assets/img/logo-finapp.jpeg" alt="Logo FINAPP">
        <h1>FIN<span>APP</span></h1>
        <p>Organize seu dinheiro, acompanhe seus objetivos e aprenda sobre finanças em um só lugar.</p>
        <div class="feature-list">
            <div><i class="fa-solid fa-circle-check"></i> Controle de receitas e despesas</div>
            <div><i class="fa-solid fa-circle-check"></i> Metas financeiras e progresso</div>
            <div><i class="fa-solid fa-circle-check"></i> Conteúdos educativos</div>
            <div><i class="fa-solid fa-circle-check"></i> Perfil do investidor</div>
        </div>
    </div>
    <div class="auth-box">
        <div class="auth-tabs">
            <a class="active" href="index.php">Entrar</a>
            <a href="cadastro.php">Criar conta</a>
        </div>
        <h2>Bem-vindo de volta!</h2>
        <p class="sub">Entre para continuar acompanhando sua vida financeira.</p>

        <?php if ($erro): ?><div class="alert-finapp auto-dismiss mb-3"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
        <?php if (isset($_GET["cadastro"]) && $_GET["cadastro"] === "ok"): ?><div class="alert-finapp auto-dismiss mb-3">Conta criada! Agora faça seu login.</div><?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="seuemail@email.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <div class="position-relative">
                    <input type="password" name="senha" id="senha" class="form-control pe-5" placeholder="Digite sua senha" required>
                    <i class="fa-solid fa-eye password-toggle position-absolute end-0 top-50 translate-middle-y me-3" data-target="senha"></i>
                </div>
            </div>
            <button class="btn btn-purple w-100 mt-2">Entrar na minha conta</button>
        </form>
        <div class="footer-note">FINAPP • TCC Desenvolvimento de Sistemas • 2026</div>
    </div>
</div>
</body>
</html>
