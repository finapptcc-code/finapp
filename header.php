<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$pagina = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? htmlspecialchars($titulo) . " | FINAPP" : "FINAPP" ?></title>
    <link rel="icon" href="../assets/img/logo-finapp.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body class="app-body">
<div class="app-layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand-area">
            <img src="../assets/img/logo-finapp.jpeg" alt="Logo FINAPP" class="brand-logo">
            <div>
                <strong>FINAPP</strong>
                <small>Finanças inteligentes</small>
            </div>
        </div>

        <nav class="side-nav">
            <a href="dashboard.php" class="<?= $pagina === 'dashboard.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i><span>Dashboard</span>
            </a>
            <a href="movimentacoes.php" class="<?= $pagina === 'movimentacoes.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-arrow-right-arrow-left"></i><span>Receitas e Despesas</span>
            </a>
            <a href="metas.php" class="<?= $pagina === 'metas.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-bullseye"></i><span>Metas</span>
            </a>
            <a href="investidor.php" class="<?= $pagina === 'investidor.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-seedling"></i><span>Perfil do Investidor</span>
            </a>
            <a href="conteudos.php" class="<?= $pagina === 'conteudos.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-book-open"></i><span>Conteúdos</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="sobre.php"><i class="fa-solid fa-circle-info"></i><span>Sobre o FINAPP</span></a>
            <a href="../logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i><span>Sair</span></a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button class="mobile-menu" id="mobileMenu"><i class="fa-solid fa-bars"></i></button>
            <div>
                <span class="eyebrow">Olá,</span>
                <h1><?= htmlspecialchars($_SESSION["usuario_nome"] ?? "Usuário") ?> 👋</h1>
            </div>
            <div class="top-actions">
                <div class="avatar"><?= strtoupper(substr($_SESSION["usuario_nome"] ?? "U", 0, 1)) ?></div>
            </div>
        </header>
        <section class="page-content">
