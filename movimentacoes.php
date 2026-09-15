<?php
require_once "../config/conexao.php";
require_once "../includes/auth.php";
$titulo = "Receitas e Despesas";
$id = $_SESSION["usuario_id"];
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $acao = $_POST["acao"] ?? "";
    if ($acao === "adicionar") {
        $tipo = $_POST["tipo"];
        $descricao = trim($_POST["descricao"]);
        $categoria = trim($_POST["categoria"]);
        $valor = (float)str_replace(",", ".", $_POST["valor"]);
        $data = $_POST["data_movimentacao"];

        $stmt = $pdo->prepare("INSERT INTO movimentacoes (usuario_id,tipo,descricao,categoria,valor,data_movimentacao) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$id,$tipo,$descricao,$categoria,$valor,$data]);
        $mensagem = "Movimentação adicionada com sucesso!";
    }
    if ($acao === "excluir") {
        $stmt = $pdo->prepare("DELETE FROM movimentacoes WHERE id=? AND usuario_id=?");
        $stmt->execute([(int)$_POST["id"],$id]);
        $mensagem = "Movimentação excluída.";
    }
}
$stmt = $pdo->prepare("SELECT * FROM movimentacoes WHERE usuario_id=? ORDER BY data_movimentacao DESC,id DESC");
$stmt->execute([$id]);
$lista = $stmt->fetchAll();

include "../includes/header.php";
?>
<div class="page-title d-flex justify-content-between align-items-end flex-wrap gap-3 fade-in">
    <div><h2>Receitas e despesas</h2><p>Registre suas entradas e saídas para entender para onde seu dinheiro está indo.</p></div>
    <button class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#movModal"><i class="fa-solid fa-plus me-2"></i>Nova movimentação</button>
</div>
<?php if ($mensagem): ?><div class="alert-finapp auto-dismiss mb-3"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>

<div class="card-dark panel fade-in">
    <?php if (!$lista): ?>
        <div class="empty-state"><i class="fa-solid fa-arrow-right-arrow-left d-block"></i>Você ainda não possui movimentações.</div>
    <?php else: ?>
    <div class="table-responsive">
    <table class="table table-dark-custom">
        <thead><tr><th>Descrição</th><th>Categoria</th><th>Data</th><th>Tipo</th><th>Valor</th><th></th></tr></thead>
        <tbody>
        <?php foreach($lista as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m["descricao"]) ?></td>
            <td><?= htmlspecialchars($m["categoria"]) ?></td>
            <td><?= date("d/m/Y", strtotime($m["data_movimentacao"])) ?></td>
            <td><span class="badge-soft"><?= ucfirst($m["tipo"]) ?></span></td>
            <td class="<?= $m["tipo"] === "receita" ? "positive" : "negative" ?>">R$ <?= number_format($m["valor"],2,",",".") ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('Excluir esta movimentação?')">
                    <input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= $m["id"] ?>">
                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="movModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered"><div class="modal-content">
<form method="POST">
<input type="hidden" name="acao" value="adicionar">
<div class="modal-header"><h5 class="modal-title">Nova movimentação</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
<div class="modal-body">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Tipo</label><select name="tipo" class="form-select"><option value="receita">Receita</option><option value="despesa">Despesa</option></select></div>
<div class="col-md-6"><label class="form-label">Valor</label><input name="valor" class="form-control" placeholder="0,00" required></div>
<div class="col-md-7"><label class="form-label">Descrição</label><input name="descricao" class="form-control" placeholder="Ex.: Bolsa, salário, mercado..." required></div>
<div class="col-md-5"><label class="form-label">Categoria</label><select name="categoria" class="form-select"><option>Alimentação</option><option>Transporte</option><option>Lazer</option><option>Educação</option><option>Moradia</option><option>Salário</option><option>Bolsa</option><option>Outros</option></select></div>
<div class="col-12"><label class="form-label">Data</label><input type="date" name="data_movimentacao" class="form-control" value="<?= date('Y-m-d') ?>" required></div>
</div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-outline-purple" data-bs-dismiss="modal">Cancelar</button><button class="btn btn-purple">Salvar</button></div>
</form>
</div></div></div>
<?php include "../includes/footer.php"; ?>
