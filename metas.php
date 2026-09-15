<?php
require_once "../config/conexao.php";
require_once "../includes/auth.php";
$titulo = "Metas Financeiras";
$id = $_SESSION["usuario_id"];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acao = $_POST["acao"] ?? "";
    if ($acao === "adicionar") {
        $nome = trim($_POST["nome"]);
        $valor = (float)str_replace(",", ".", $_POST["valor"]);
        $atual = (float)str_replace(",", ".", $_POST["atual"]);
        $prazo = $_POST["prazo"];
        $stmt = $pdo->prepare("INSERT INTO metas (usuario_id,nome,valor_objetivo,valor_atual,prazo) VALUES (?,?,?,?,?)");
        $stmt->execute([$id,$nome,$valor,$atual,$prazo]);
        $msg = "Meta criada com sucesso!";
    } elseif ($acao === "atualizar") {
        $atual = (float)str_replace(",", ".", $_POST["atual"]);
        $stmt = $pdo->prepare("UPDATE metas SET valor_atual=? WHERE id=? AND usuario_id=?");
        $stmt->execute([$atual,(int)$_POST["id"],$id]);
        $msg = "Progresso atualizado!";
    } elseif ($acao === "excluir") {
        $stmt = $pdo->prepare("DELETE FROM metas WHERE id=? AND usuario_id=?");
        $stmt->execute([(int)$_POST["id"],$id]);
        $msg = "Meta excluída.";
    }
}
$stmt = $pdo->prepare("SELECT * FROM metas WHERE usuario_id=? ORDER BY id DESC");
$stmt->execute([$id]);
$metas = $stmt->fetchAll();

include "../includes/header.php";
?>
<div class="page-title d-flex justify-content-between align-items-end flex-wrap gap-3 fade-in">
    <div><h2>Metas financeiras</h2><p>Transforme seus objetivos em planos e acompanhe seu progresso.</p></div>
    <button class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#goalModal"><i class="fa-solid fa-plus me-2"></i>Nova meta</button>
</div>
<?php if ($msg): ?><div class="alert-finapp auto-dismiss mb-3"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<div class="row g-3">
<?php if (!$metas): ?>
<div class="col-12"><div class="card-dark empty-state"><i class="fa-solid fa-bullseye d-block"></i>Você ainda não criou nenhuma meta.</div></div>
<?php endif; ?>
<?php foreach($metas as $meta):
    $pct = $meta["valor_objetivo"] > 0 ? min(100, ($meta["valor_atual"]/$meta["valor_objetivo"])*100) : 0;
?>
<div class="col-lg-6">
<div class="card-dark goal-card fade-in">
    <div class="d-flex justify-content-between align-items-start">
        <div><h4><?= htmlspecialchars($meta["nome"]) ?></h4><small class="text-secondary">Prazo: <?= date("d/m/Y", strtotime($meta["prazo"])) ?></small></div>
        <form method="POST" onsubmit="return confirm('Excluir esta meta?')"><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= $meta["id"] ?>"><button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button></form>
    </div>
    <div class="goal-meta"><span>R$ <?= number_format($meta["valor_atual"],2,",",".") ?> acumulados</span><span><?= number_format($pct,0) ?>%</span></div>
    <div class="progress"><div class="progress-bar" style="width:<?= $pct ?>%"></div></div>
    <div class="d-flex justify-content-between align-items-center mt-3"><small class="text-secondary">Objetivo: R$ <?= number_format($meta["valor_objetivo"],2,",",".") ?></small>
    <button class="btn btn-outline-purple btn-sm" data-bs-toggle="modal" data-bs-target="#update<?= $meta["id"] ?>">Atualizar</button></div>
</div>
</div>

<div class="modal fade" id="update<?= $meta["id"] ?>" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form method="POST">
<input type="hidden" name="acao" value="atualizar"><input type="hidden" name="id" value="<?= $meta["id"] ?>">
<div class="modal-header"><h5 class="modal-title">Atualizar <?= htmlspecialchars($meta["nome"]) ?></h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><label class="form-label">Valor atual</label><input name="atual" class="form-control" value="<?= number_format($meta["valor_atual"],2,",",".") ?>" required></div>
<div class="modal-footer"><button class="btn btn-outline-purple" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-purple">Salvar</button></div>
</form></div></div></div>
<?php endforeach; ?>
</div>

<div class="modal fade" id="goalModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content"><form method="POST">
<input type="hidden" name="acao" value="adicionar">
<div class="modal-header"><h5 class="modal-title">Criar nova meta</h5><button class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="mb-3"><label class="form-label">Nome da meta</label><input name="nome" class="form-control" placeholder="Ex.: Viagem, notebook, reserva..." required></div>
<div class="row g-3"><div class="col-6"><label class="form-label">Valor objetivo</label><input name="valor" class="form-control" placeholder="0,00" required></div><div class="col-6"><label class="form-label">Já tenho</label><input name="atual" class="form-control" value="0" required></div>
<div class="col-12"><label class="form-label">Prazo</label><input type="date" name="prazo" class="form-control" required></div></div></div>
<div class="modal-footer"><button class="btn btn-outline-purple" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-purple">Criar meta</button></div>
</form></div></div></div>
<?php include "../includes/footer.php"; ?>
