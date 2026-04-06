<?php
// ═══════════════════════════════════════════════════════════
//  Flight Simulator - Diagnóstico de conexão e tabela
//  ATENÇÃO: Remova ou restrinja este arquivo após o teste!
// ═══════════════════════════════════════════════════════════

$db_host = 'localhost';
$db_user = 'lcvmcom_simulador';
$db_pass = '#Sim1508#2';
$db_name = 'lcvmcom_simulador';

header('Content-Type: text/html; charset=utf-8');

function ok($msg)   { echo "<p style='color:#00cc55'>✔ $msg</p>"; }
function fail($msg) { echo "<p style='color:#ff4444'>✘ $msg</p>"; }
function info($msg) { echo "<p style='color:#aaa'>ℹ $msg</p>"; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Diagnóstico - Flight Simulator Log</title>
  <style>
    body { font-family: monospace; background:#111; color:#ddd; padding:30px; }
    h2   { color:#7fc9ff; border-bottom:1px solid #333; padding-bottom:6px; }
    pre  { background:#1a1a2e; padding:10px; color:#0f0; font-size:12px; overflow:auto; }
    .box { border:1px solid #333; padding:14px; margin-top:16px; }
  </style>
</head>
<body>
<h1>Diagnóstico — Flight Simulator Log</h1>

<div class="box">
<h2>1. Extensão MySQLi</h2>
<?php
if (extension_loaded('mysqli')) {
    ok('MySQLi está disponível.');
} else {
    fail('ERRO: extensão MySQLi NÃO está carregada. Habilite mysqli no php.ini.');
}
?>
</div>

<div class="box">
<h2>2. Conexão com o Banco de Dados</h2>
<?php
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    fail('Falha na conexão: ' . htmlspecialchars($conn->connect_error));
    info("Host: $db_host | Usuário: $db_user | Banco: $db_name");
} else {
    ok("Conexão bem-sucedida com <strong>$db_name</strong> em <strong>$db_host</strong>");
    $conn->set_charset('utf8mb4');

    // 3. Verificar se a tabela existe
    echo '<h2 style="color:#7fc9ff;border-bottom:1px solid #333;padding-bottom:6px;margin-top:16px">3. Tabela <code>acessos</code></h2>';
    $res = $conn->query("SHOW TABLES LIKE 'acessos'");
    if ($res && $res->num_rows > 0) {
        ok('Tabela <strong>acessos</strong> encontrada.');

        // 4. Verificar estrutura
        echo '<h2 style="color:#7fc9ff;border-bottom:1px solid #333;padding-bottom:6px;margin-top:16px">4. Estrutura da Tabela</h2>';
        $cols = $conn->query("DESCRIBE acessos");
        echo '<pre>';
        printf("%-20s %-20s %-5s\n", 'Campo', 'Tipo', 'Null');
        echo str_repeat('-', 48) . "\n";
        while ($row = $cols->fetch_assoc()) {
            printf("%-20s %-20s %-5s\n", $row['Field'], $row['Type'], $row['Null']);
        }
        echo '</pre>';

        // 5. Total de registros
        echo '<h2 style="color:#7fc9ff;border-bottom:1px solid #333;padding-bottom:6px;margin-top:16px">5. Registros</h2>';
        $cnt = $conn->query("SELECT COUNT(*) as total FROM acessos");
        $row = $cnt->fetch_assoc();
        ok('Total de registros na tabela: <strong>' . $row['total'] . '</strong>');

        // Últimos 5 registros
        $recent = $conn->query("SELECT * FROM acessos ORDER BY data_hora DESC LIMIT 5");
        if ($recent && $recent->num_rows > 0) {
            info('Últimos registros:');
            echo '<pre>';
            while ($r = $recent->fetch_assoc()) {
                echo htmlspecialchars(
                    $r['data_hora'] . ' | ' . $r['evento'] . ' | ' . $r['navegador'] . ' | ' . $r['so'] . ' | voo:' . $r['tempo_voo_seg'] . 's'
                ) . "\n";
            }
            echo '</pre>';
        } else {
            info('Nenhum registro ainda na tabela.');
        }

        // 6. Teste de INSERT
        echo '<h2 style="color:#7fc9ff;border-bottom:1px solid #333;padding-bottom:6px;margin-top:16px">6. Teste de INSERT</h2>';
        $q = $conn->prepare(
            'INSERT INTO acessos (data_hora, evento, navegador, so, resolucao, duracao_seg, tempo_voo_seg, altitude_max, ip_anon, consentimento)
             VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        if (!$q) {
            fail('Falha ao preparar INSERT: ' . htmlspecialchars($conn->error));
        } else {
            $ev='debug'; $nav='debug'; $so='debug'; $res='0x0'; $dur=1; $voo=1; $alt=100; $ip='0.0.0.xxx'; $con='sim';
            $q->bind_param('ssssiiiss', $ev, $nav, $so, $res, $dur, $voo, $alt, $ip, $con);
            if ($q->execute()) {
                ok('INSERT de teste executado com sucesso (id=' . $conn->insert_id . ').');
                // Remove o registro de teste
                $conn->query("DELETE FROM acessos WHERE evento='debug' AND navegador='debug'");
                info('Registro de teste removido.');
            } else {
                fail('Falha ao executar INSERT: ' . htmlspecialchars($q->error));
            }
            $q->close();
        }

    } else {
        fail('Tabela <strong>acessos</strong> NÃO encontrada no banco <strong>' . $db_name . '</strong>.');
        info('Execute o script <code>database_setup.sql</code> no seu banco de dados MySQL.');
    }

    $conn->close();
}
?>
</div>

<div class="box">
<h2>7. Conclusão</h2>
<?php
info('Se todos os itens acima estão ✔, configure a URL <code>https://seu-servidor.com/log.php</code> no simulador e clique em <strong>Aplicar</strong>.');
info('Se o usuário clicou em <strong>Recusar</strong> no aviso LGPD, o envio fica bloqueado. Limpe o localStorage do navegador (DevTools → Application → Local Storage → remover glider_lgpd) e recarregue.');
?>
</div>

<p style="margin-top:30px; color:#555; font-size:11px">
  ⚠️ <strong>ATENÇÃO:</strong> Remova ou proteja este arquivo após o diagnóstico para não expor informações do servidor.
</p>
</body>
</html>
