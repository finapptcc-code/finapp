<?php
require_once "../config/conexao.php";
require_once "../includes/auth.php";
$titulo = "Perfil do Investidor";
$id = $_SESSION["usuario_id"];
$msg = "";

$stmt = $pdo->prepare("SELECT perfil_investidor FROM usuarios WHERE id=?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $respostas = [
        (int)$_POST["q1"], (int)$_POST["q2"], (int)$_POST["q3"],
        (int)$_POST["q4"], (int)$_POST["q5"]
    ];
    $pontos = array_sum($respostas);
    $perfil = $pontos <= 7 ? "Conservador" : ($pontos <= 11 ? "Moderado" : "Arrojado");
    $stmt = $pdo->prepare("UPDATE usuarios SET perfil_investidor=? WHERE id=?");
    $stmt->execute([$perfil,$id]);
    $usuario["perfil_investidor"] = $perfil;
    $msg = "Perfil calculado com sucesso!";
}

$descricoes = [
    "Conservador" => "Prioriza segurança e previsibilidade. O FINAPP apresenta conteúdos educativos relacionados a alternativas de menor risco.",
    "Moderado" => "Busca equilíbrio entre segurança e rentabilidade, aceitando um nível intermediário de risco.",
    "Arrojado" => "Aceita maior oscilação em busca de retornos maiores no longo prazo. Conhecimento e planejamento continuam sendo essenciais."
];
include "../includes/header.php";
?>
<div class="page-title fade-in"><h2>Perfil do investidor</h2><p>Responda ao questionário e conheça seu perfil de forma educativa.</p></div>
<?php if ($msg): ?><div class="alert-finapp auto-dismiss mb-3"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<?php if ($usuario["perfil_investidor"]): ?>
<div class="investor-hero fade-in">
    <span class="profile-pill"><i class="fa-solid fa-sparkles me-1"></i> Seu resultado</span>
    <h2 class="mt-3 mb-2"><?= htmlspecialchars($usuario["perfil_investidor"]) ?></h2>
    <p class="text-secondary mb-0"><?= $descricoes[$usuario["perfil_investidor"]] ?></p>
</div>
<?php endif; ?>

<div class="card-dark panel fade-in">
<div class="panel-head"><h3>Questionário</h3><span>5 perguntas</span></div>
<form method="POST">
<?php
$perguntas = [
["q1","Como você se sentiria se um investimento apresentasse oscilações no curto prazo?",["Prefiro evitar qualquer oscilação.","Aceito pequenas oscilações.","Aceito oscilações maiores pensando no longo prazo."]],
["q2","Qual é seu principal objetivo ao investir?",["Preservar meu dinheiro.","Equilibrar segurança e crescimento.","Buscar maior crescimento no longo prazo."]],
["q3","Por quanto tempo você pretende manter seus investimentos?",["Curto prazo.","Médio prazo.","Longo prazo."]],
["q4","Como você avalia seu conhecimento sobre investimentos?",["Ainda estou começando.","Tenho conhecimento básico/intermediário.","Tenho bastante interesse e busco aprender sobre diferentes opções."]],
["q5","Se seu investimento caísse temporariamente, o que faria?",["Preferiria sair para evitar perdas maiores.","Avaliaria a situação antes de decidir.","Manteria o plano, considerando meu horizonte de longo prazo."]]
];
foreach($perguntas as $i=>$p):
?>
<div class="mb-4">
<label class="form-label mb-2"><?= $i+1 ?>. <?= $p[1] ?></label>
<?php foreach($p[2] as $idx=>$op): ?>
<div class="form-check mb-2"><input class="form-check-input" type="radio" name="<?= $p[0] ?>" value="<?= $idx+1 ?>" <?= $idx===0?'required':'' ?>><label class="form-check-label small text-secondary"><?= $op ?></label></div>
<?php endforeach; ?>
</div>
<?php endforeach; ?>
<button class="btn btn-purple">Calcular meu perfil</button>
</form>
<div class="alert-finapp mt-4"><i class="fa-solid fa-circle-info me-2"></i>O resultado é educativo e não representa consultoria ou recomendação financeira individual.</div>
</div>
<?php include "../includes/footer.php"; ?>
