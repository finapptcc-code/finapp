<?php
require_once "../config/conexao.php";
require_once "../includes/auth.php";
$titulo = "Conteúdos Educativos";

$stmt = $pdo->query("SELECT * FROM conteudos ORDER BY id");
$conteudos = $stmt->fetchAll();
include "../includes/header.php";
?>
<div class="page-title fade-in"><h2>Aprenda sobre seu dinheiro</h2><p>Conteúdos simples para desenvolver hábitos financeiros mais conscientes.</p></div>
<div class="content-grid">
<?php foreach($conteudos as $c): ?>
<div class="card-dark content-card fade-in">
    <div class="content-img"><i class="<?= htmlspecialchars($c["icone"]) ?>"></i></div>
    <div class="content-body">
        <span class="content-tag"><?= htmlspecialchars($c["categoria"]) ?></span>
        <h3 class="mt-3"><?= htmlspecialchars($c["titulo"]) ?></h3>
        <p><?= htmlspecialchars($c["resumo"]) ?></p>
        <button class="btn btn-outline-purple btn-sm" data-bs-toggle="modal" data-bs-target="#content<?= $c["id"] ?>">Ler conteúdo</button>
    </div>
</div>
<div class="modal fade" id="content<?= $c["id"] ?>" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
<div class="modal-header"><h5><?= htmlspecialchars($c["titulo"]) ?></h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><p class="text-secondary" style="line-height:1.9"><?= nl2br(htmlspecialchars($c["conteudo"])) ?></p></div>
</div></div></div>
<?php endforeach; ?>
</div>
<?php include "../includes/footer.php"; ?>
