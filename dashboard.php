<?php
require_once "../config/conexao.php";
require_once "../includes/auth.php";
$titulo = "Dashboard";
$id = $_SESSION["usuario_id"];

$stmt = $pdo->prepare("SELECT
    COALESCE(SUM(CASE WHEN tipo='receita' THEN valor ELSE 0 END),0) receitas,
    COALESCE(SUM(CASE WHEN tipo='despesa' THEN valor ELSE 0 END),0) despesas
    FROM movimentacoes WHERE usuario_id=?");
$stmt->execute([$id]);
$totais = $stmt->fetch();
$saldo = $totais["receitas"] - $totais["despesas"];

$stmt = $pdo->prepare("SELECT * FROM movimentacoes WHERE usuario_id=? ORDER BY data_movimentacao DESC, id DESC LIMIT 6");
$stmt->execute([$id]);
$movimentacoes = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM metas WHERE usuario_id=? ORDER BY id DESC LIMIT 3");
$stmt->execute([$id]);
$metas = $stmt->fetchAll();

include "../includes/header.php";
?>
<div class="page-title fade-in">
    <h2>Visão geral</h2>
    <p>Tenha uma visão rápida da sua vida financeira.</p>
</div>

<div class="stat-grid fade-in">
    <div class="card-dark stat-card"><div class="stat-icon"><i class="fa-solid fa-wallet"></i></div><span class="label">Saldo atual</span><div class="value">R$ <?= number_format($saldo,2,",",".") ?></div></div>
    <div class="card-dark stat-card"><div class="stat-icon"><i class="fa-solid fa-arrow-trend-up"></i></div><span class="label">Total de receitas</span><div class="value positive">R$ <?= number_format($totais["receitas"],2,",",".") ?></div></div>
    <div class="card-dark stat-card"><div class="stat-icon"><i class="fa-solid fa-arrow-trend-down"></i></div><span class="label">Total de despesas</span><div class="value negative">R$ <?= number_format($totais["despesas"],2,",",".") ?></div></div>
    <div class="card-dark stat-card"><div class="stat-icon"><i class="fa-solid fa-bullseye"></i></div><span class="label">Metas cadastradas</span><div class="value"><?= count($metas) ?></div></div>
</div>

<div class="grid-2">
    <div class="card-dark panel fade-in">
        <div class="panel-head"><h3>Fluxo financeiro</h3><span>Resumo geral</span></div>
        <div class="chart-box"><canvas id="financeChart"></canvas></div>
    </div>
    <div class="card-dark panel fade-in">
        <div class="panel-head"><h3>Acesso rápido</h3><span>Atalhos</span></div>
        <div class="quick-grid">
            <a class="quick" href="movimentacoes.php"><i class="fa-solid fa-plus"></i><strong>Nova movimentação</strong><small>Registrar entrada ou saída</small></a>
            <a class="quick" href="metas.php"><i class="fa-solid fa-bullseye"></i><strong>Criar meta</strong><small>Planejar um objetivo</small></a>
            <a class="quick" href="investidor.php"><i class="fa-solid fa-seedling"></i><strong>Meu perfil</strong><small>Descobrir seu perfil</small></a>
            <a class="quick" href="conteudos.php"><i class="fa-solid fa-book-open"></i><strong>Aprender</strong><small>Conteúdos financeiros</small></a>
        </div>
    </div>
</div>

<div class="card-dark panel mt-4 fade-in">
    <div class="panel-head"><h3>Movimentações recentes</h3><a href="movimentacoes.php" class="btn btn-outline-purple btn-sm">Ver todas</a></div>
    <?php if (!$movimentacoes): ?>
        <div class="empty-state"><i class="fa-solid fa-receipt d-block"></i>Nenhuma movimentação cadastrada ainda.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-dark-custom">
            <thead><tr><th>Descrição</th><th>Categoria</th><th>Data</th><th>Tipo</th><th>Valor</th></tr></thead>
            <tbody>
            <?php foreach($movimentacoes as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m["descricao"]) ?></td>
                    <td><?= htmlspecialchars($m["categoria"]) ?></td>
                    <td><?= date("d/m/Y", strtotime($m["data_movimentacao"])) ?></td>
                    <td><span class="badge-soft"><?= ucfirst($m["tipo"]) ?></span></td>
                    <td class="<?= $m["tipo"] === "receita" ? "positive" : "negative" ?>">R$ <?= number_format($m["valor"],2,",",".") ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<script>
const ctx = document.getElementById('financeChart');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Receitas', 'Despesas'],
        datasets: [{data: [<?= (float)$totais["receitas"] ?>, <?= (float)$totais["despesas"] ?>],
        backgroundColor: ['#7c3aed','#ff6b81'], borderColor:'#17131f', borderWidth:4}]
    },
    options: {responsive:true, maintainAspectRatio:false, plugins:{legend:{labels:{color:'#ddd6e5'}}}}
});
</script>
<?php include "../includes/footer.php"; ?>
