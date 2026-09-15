<?php
require_once "config/conexao.php";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmar = $_POST["confirmar"] ?? "";

    if ($nome === "" || $email === "" || $senha === "") {
        $erro = "Preencha todos os campos.";
    } elseif ($senha !== $confirmar) {
        $erro = "As senhas não coincidem.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } else {
        $check = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $check->execute([$email]);

        if ($check->fetch()) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $hash]);
            header("Location: index.php?cadastro=ok");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastro | FINAPP</title>
<link rel="icon" href="assets/img/logo-finapp.jpeg">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-body">
<div class="auth-wrapper">
    <div class="auth-brand">
        <img src="assets/img/logo-finapp.jpeg" alt="Logo FINAPP">
        <h1>Seu dinheiro.<br><span>Seu plano.</span></h1>
        <p>Crie sua conta e comece a organizar suas finanças de forma simples, visual e educativa.</p>
        <div class="feature-list">
            <div><i class="fa-solid fa-chart-line"></i> Acompanhe sua evolução</div>
            <div><i class="fa-solid fa-bullseye"></i> Defina metas</div>
            <div><i class="fa-solid fa-book-open"></i> Aprenda no seu ritmo</div>
        </div>
    </div>
    <div class="auth-box">
        <div class="auth-tabs">
            <a href="index.php">Entrar</a>
            <a class="active" href="cadastro.php">Criar conta</a>
        </div>
        <h2>Crie sua conta</h2>
        <p class="sub">Leva menos de um minuto para começar.</p>
        <?php if ($erro): ?><div class="alert-finapp auto-dismiss mb-3"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
        <form method="POST">
            <div class="mb-3"><label class="form-label">Nome</label><input name="nome" class="form-control" placeholder="Como podemos te chamar?" required></div>
            <div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control" placeholder="seuemail@email.com" required></div>
            <div class="mb-3"><label class="form-label">Senha</label><input type="password" name="senha" class="form-control" placeholder="Mínimo de 6 caracteres" required></div>
            <div class="mb-3"><label class="form-label">Confirmar senha</label><input type="password" name="confirmar" class="form-control" placeholder="Repita sua senha" required></div>
            <button class="btn btn-purple w-100 mt-2">Criar minha conta</button>
        </form>
        <div class="footer-note">Ao criar sua conta, você poderá registrar seus dados financeiros no FINAPP.</div>
    </div>
</div>
</body>
</html>
