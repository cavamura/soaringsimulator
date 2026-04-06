<?php
// ═══════════════════════════════════════════════════════════
//  Flight Simulator - Log receiver (PHP + MySQL)
//  Recebe dados do simulador e armazena no banco de dados
// ═══════════════════════════════════════════════════════════

// Configuração - ALTERE CONFORME SEU BANCO DE DADOS
$db_host = 'localhost';
$db_user = 'lcvmcom_simulador';
$db_pass = '#Sim1508#2';
$db_name = 'lcvmcom_simulador';

// Configurar header e retorno
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Responder OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

// Apenas aceitar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false]);
  exit;
}

// Ler dados do POST
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validar dados básicos
if (!$data || !isset($data['evento'])) {
  http_response_code(400);
  echo json_encode(['ok' => false]);
  exit;
}

// Tentar conectar ao banco de dados
try {
  mysqli_report(MYSQLI_REPORT_OFF);
  $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
  
  // Verificar conexão (sem exposição de erro)
  if ($conn->connect_error) {
    http_response_code(200);
    echo json_encode(['ok' => true]);
    exit;
  }
  
  // Definir charset
  $conn->set_charset('utf8mb4');
  
  // Extrair dados com validação
  $evento       = isset($data['evento']) ? substr(strval($data['evento']), 0, 50) : '';
  $navegador    = isset($data['navegador']) ? substr(strval($data['navegador']), 0, 50) : '';
  $so           = isset($data['so']) ? substr(strval($data['so']), 0, 50) : '';
  $resolucao    = isset($data['resolucao']) ? substr(strval($data['resolucao']), 0, 50) : '';
  $duracao_seg  = isset($data['duracao_seg']) ? intval($data['duracao_seg']) : 0;
  $tempo_voo_seg= isset($data['tempo_voo_seg']) ? intval($data['tempo_voo_seg']) : 0;
  $altitude_max = isset($data['altitude_max']) ? intval($data['altitude_max']) : 0;
  $ip_anon      = isset($data['ip_anon']) ? substr(strval($data['ip_anon']), 0, 50) : '';
  $consentimento= isset($data['consentimento']) ? substr(strval($data['consentimento']), 0, 10) : '';
  
  // Preparar e executar query
  $query = $conn->prepare(
    'INSERT INTO acessos (data_hora, evento, navegador, so, resolucao, duracao_seg, tempo_voo_seg, altitude_max, ip_anon, consentimento) ' .
    'VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)'
  );
  
  if (!$query) {
    throw new Exception('Prepare failed');
  }
  
  $query->bind_param(
    'ssssiiiss',
    $evento, $navegador, $so, $resolucao, $duracao_seg, $tempo_voo_seg, $altitude_max, $ip_anon, $consentimento
  );
  
  $query->execute();
  $query->close();
  $conn->close();
  
  // Sucesso silencioso
  http_response_code(200);
  echo json_encode(['ok' => true]);
  
} catch (Exception $e) {
  // Erro silencioso (não expor detalhes)
  http_response_code(200);
  echo json_encode(['ok' => true]);
}
?>
