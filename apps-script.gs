// ═══════════════════════════════════════════════════════════
//  Google Apps Script — Glider Simulator Log
//  Autor: L. Cavamura Jr. (lcavamura@gmail.com)
//  Instruções de deploy ao final deste arquivo.
// ═══════════════════════════════════════════════════════════

var SHEET_NAME = 'Acessos'; // nome da aba na planilha

// ─── Recebe dados enviados pelo jogo (POST) ────────────────
function doPost(e) {
  try {
    var data   = JSON.parse(e.postData.contents);
    var sheet  = getOrCreateSheet();
    var now    = new Date();

    sheet.appendRow([
      Utilities.formatDate(now, Session.getScriptTimeZone(), 'dd/MM/yyyy HH:mm:ss'),
      sanitize(data.evento)         || '',
      sanitize(data.navegador)      || '',
      sanitize(data.so)             || '',
      sanitize(data.resolucao)      || '',
      Number(data.duracao_seg)      || 0,
      Number(data.tempo_voo_seg)    || 0,
      Number(data.altitude_max)     || 0,
      sanitize(data.ip_anon)        || '',
      sanitize(data.consentimento)  || '',
    ]);

    return respond({ ok: true });
  } catch (err) {
    return respond({ ok: false, erro: err.message });
  }
}

// ─── Permite teste simples via navegador (GET) ─────────────
function doGet() {
  return ContentService
    .createTextOutput('Glider Simulator Log — online.')
    .setMimeType(ContentService.MimeType.TEXT);
}

// ─── Cria a aba com cabeçalho se não existir ───────────────
function getOrCreateSheet() {
  var ss    = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName(SHEET_NAME);

  if (!sheet) {
    sheet = ss.insertSheet(SHEET_NAME);
    sheet.appendRow([
      'Data/Hora',
      'Evento',
      'Navegador',
      'Sistema Operacional',
      'Resolução',
      'Duração Sessão (s)',
      'Tempo de Voo (s)',
      'Altitude Máx (m)',
      'IP Anonimizado',
      'Consentimento',
    ]);
    // Formata cabeçalho
    var header = sheet.getRange(1, 1, 1, 10);
    header.setFontWeight('bold');
    header.setBackground('#1a3a5c');
    header.setFontColor('#ffffff');
    sheet.setFrozenRows(1);
    sheet.setColumnWidth(1, 140);
    sheet.setColumnWidth(2, 90);
    sheet.setColumnWidth(3, 160);
    sheet.setColumnWidth(4, 120);
    sheet.setColumnWidth(5, 100);
  }

  return sheet;
}

// ─── Utilitários ───────────────────────────────────────────
function respond(obj) {
  return ContentService
    .createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}

function sanitize(val) {
  if (val === null || val === undefined) return '';
  // Remove caracteres de controle, limita a 200 chars
  return String(val).replace(/[\x00-\x1F\x7F]/g, '').substring(0, 200);
}

// ═══════════════════════════════════════════════════════════
//  INSTRUÇÕES DE CONFIGURAÇÃO (leia com atenção)
// ═══════════════════════════════════════════════════════════
//
//  1. Abra o Google Drive: https://drive.google.com
//  2. Crie uma nova Planilha Google (New > Google Sheets)
//     e dê o nome: "Glider Simulator Log"
//  3. Dentro da planilha, acesse o menu:
//     Extensões > Apps Script
//  4. Apague o código padrão e cole TODO o conteúdo deste
//     arquivo (.gs) no editor.
//  5. Salve (Ctrl+S).
//  6. Clique em "Implantar" > "Nova implantação".
//  7. Tipo: Aplicativo da Web
//     - Executar como: EU (sua conta Google)
//     - Quem pode acessar: QUALQUER PESSOA
//  8. Clique em "Implantar" e autorize as permissões.
//  9. Copie a URL gerada (começa com
//     https://script.google.com/macros/s/...)
// 10. Cole essa URL no campo "URL Apps Script" dentro do
//     painel de Configuração do jogo.
//
//  Pronto! Os acessos serão gravados na aba "Acessos"
//  da planilha automaticamente.
// ═══════════════════════════════════════════════════════════
