<?php
// ═══════════════════════════════════════════════════════════
//  Flight Simulator - Diagnóstico de conexão e tabela
//  ATENÇÃO: Remova ou restrinja este arquivo após o teste!
// ═══════════════════════════════════════════════════════════

$db_host = 'localhost';
$db_port = '5432';
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
<h2>1. Extensão PostgreSQL (pgsql)</h2>
<?php
if (extension_loaded('pgsql')) {
    ok('Extensão pgsql está disponível.');
} else {
    fail('ERRO: extensão pgsql NÃO está carregada. Habilite pgsql no php.ini.');
}
?>
</div>

<div class="box">
<h2>2. Conexão com o Banco de Dados</h2>
<?php
$connStr = 'host=' . $db_host
  . ' port=' . $db_port
  . ' dbname=' . $db_name
  . ' user=' . $db_user
  . ' password=' . $db_pass;

$conn = @pg_connect($connStr);
if (!$conn) {
    fail('Falha na conexão com PostgreSQL.');
    info("Host: $db_host | Porta: $db_port | Usuário: $db_user | Banco: $db_name");
} else {
    ok("Conexão bem-sucedida com <strong>$db_name</strong> em <strong>$db_host:$db_port</strong>");

    // 3. Verificar se a tabela existe
    echo '<h2 style="color:#7fc9ff;border-bottom:1px solid #333;padding-bottom:6px;margin-top:16px">3. Tabela <code>acessos</code></h2>';
    $res = @pg_query_params($conn, 'SELECT to_regclass($1) as tbl', ['public.acessos']);
    $tbl = $res ? pg_fetch_assoc($res) : null;
    if ($tbl && $tbl['tbl']) {
        ok('Tabela <strong>acessos</strong> encontrada.');

        // 4. Verificar estrutura
        echo '<h2 style="color:#7fc9ff;border-bottom:1px solid #333;padding-bottom:6px;margin-top:16px">4. Estrutura da Tabela</h2>';
        $cols = @pg_query($conn, "SELECT column_name, data_type, is_nullable FROM information_schema.columns WHERE table_schema='public' AND table_name='acessos' ORDER BY ordinal_position");
        echo '<pre>';
        printf("%-20s %-20s %-5s\n", 'Campo', 'Tipo', 'Null');
        echo str_repeat('-', 48) . "\n";
        while ($row = pg_fetch_assoc($cols)) {
            printf("%-20s %-20s %-5s\n", $row['column_name'], $row['data_type'], $row['is_nullable']);
        }
        echo '</pre>';

        // 5. Total de registros
        echo '<h2 style="color:#7fc9ff;border-bottom:1px solid #333;padding-bottom:6px;margin-top:16px">5. Registros</h2>';
        $cnt = @pg_query($conn, 'SELECT COUNT(*)::bigint as total FROM acessos');
        $row = $cnt ? pg_fetch_assoc($cnt) : ['total' => '0'];
        ok('Total de registros na tabela: <strong>' . $row['total'] . '</strong>');

        // Últimos 5 registros
        $recent = @pg_query($conn, 'SELECT * FROM acessos ORDER BY data_hora DESC LIMIT 5');
        if ($recent && pg_num_rows($recent) > 0) {
            info('Últimos registros:');
            echo '<pre>';
            while ($r = pg_fetch_assoc($recent)) {
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
        $ins = @pg_query_params(
            $conn,
            'INSERT INTO acessos (data_hora, evento, navegador, so, resolucao, duracao_seg, tempo_voo_seg, altitude_max, ip_anon, consentimento) VALUES (NOW(), $1, $2, $3, $4, $5, $6, $7, $8, $9) RETURNING id',
            ['debug', 'debug', 'debug', '0x0', 1, 1, 100, '0.0.0.xxx', 'sim']
        );
        if ($ins) {
            $rIns = pg_fetch_assoc($ins);
            ok('INSERT de teste executado com sucesso (id=' . $rIns['id'] . ').');
            // Remove o registro de teste
            @pg_query_params($conn, 'DELETE FROM acessos WHERE evento=$1 AND navegador=$2', ['debug', 'debug']);
            info('Registro de teste removido.');
        } else {
            fail('Falha ao executar INSERT de teste.');
        }

    } else {
        fail('Tabela <strong>acessos</strong> NÃO encontrada no banco <strong>' . $db_name . '</strong>.');
        info('Execute o script <code>database_setup.sql</code> no seu banco de dados PostgreSQL.');
    }

    @pg_close($conn);
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
