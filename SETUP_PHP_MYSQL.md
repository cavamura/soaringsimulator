SETUP - Migração para PHP + MySQL
═════════════════════════════════════════════════════════════════

PASSO 1: Configurar Banco de Dados
──────────────────────────────────
1. Acesse sua ferramenta MySQL (phpMyAdmin, MySQL Workbench, ou CLI)
2. Execute o conteúdo do arquivo: database_setup.sql
   
   Isso criará:
   - Banco de dados: flight_simulator
   - Tabela: acessos (com campos para todos os dados coletados)

PASSO 2: Configurar o arquivo log.php
──────────────────────────────────────
1. Abra o arquivo: log.php
2. Altere as credenciais de conexão (no topo do arquivo):
   - $db_host = 'localhost'    (ou seu host MySQL)
   - $db_user = 'root'         (seu usuário MySQL)
   - $db_pass = ''             (sua senha MySQL)
   - $db_name = 'flight_simulator'  (nome do banco)

3. Salve o arquivo

PASSO 3: Configurar o simulador
────────────────────────────────
1. Abra index.html no navegador
2. Vá para "Configuracao da simulacao"
3. No campo "URL de log (PHP ou Apps Script)" insira:
   https://seu-servidor.com/log.php
   (substitua seu-servidor.com pela URL do seu servidor)

4. Clique em "Aplicar"

PASSO 4: Testar
────────────────
1. Coloque a opção "Mostrar termicas" como habilitada
2. Clique em "Reiniciar" para iniciar uma simulação
3. Deixe rodar alguns segundos
4. Na pausa ou ao encerrar, os dados serão enviados

Verifique os logs no banco de dados:
   SELECT * FROM flight_simulator.acessos ORDER BY data_hora DESC LIMIT 5;

CARACTERÍSTICAS
═════════════════════════════════════════════════════════════════

✓ Erros silenciosos - Nenhuma mensagem de erro é exibida ao usuário
✓ Compatível com Google Apps Script - Você pode voltar a usar Google Apps Script
  alterando apenas a URL de log
✓ Preparadas contra SQL Injection - Usa prepared statements
✓ UTF-8 completo - Suporta caracteres especiais
✓ CORS habilitado - Funciona com requisições cross-origin

DASHBOARD SQL (exemplos de queries)
═════════════════════════════════════════════════════════════════

-- Estatísticas gerais
SELECT 
  COUNT(*) as total_acessos,
  COUNT(DISTINCT DATE(data_hora)) as dias_unicos,
  AVG(tempo_voo_seg) as tempo_medio_voo_seg,
  MAX(altitude_max) as altitude_maxima_geral
FROM acessos;

-- Acessos por navegador
SELECT navegador, COUNT(*) as total
FROM acessos
WHERE evento = 'acesso'
GROUP BY navegador
ORDER BY total DESC;

-- Sessões por dia
SELECT 
  DATE(data_hora) as data,
  COUNT(*) as total_eventos,
  AVG(tempo_voo_seg) as tempo_voo_medio
FROM acessos
GROUP BY DATE(data_hora)
ORDER BY data DESC;

-- Altitudes máximas
SELECT evento, AVG(altitude_max) as altitude_media, MAX(altitude_max) as max
FROM acessos
WHERE evento = 'fim_jogo'
GROUP BY evento;

SOLUÇÃO DE PROBLEMAS
═════════════════════════════════════════════════════════════════

P: Os dados não estão sendo salvos
R: Verifique:
   - As credenciais do banco em log.php estão corretas
   - A tabela 'acessos' foi criada (execute database_setup.sql)
   - O arquivo log.php está no servidor e acessível pela URL configurada
   - O usuário MySQL tem permissão INSERT na tabela

P: Conexão recusada ao banco de dados
R: O usuário verá simples não exibição de erro (conforme desejado).
   Verifique no servidor:
   - Se MySQL está rodando
   - Se as credenciais (user/pass) estão corretas
   - Se o host é acessível (localhost vs. 127.0.0.1)

P: Posso voltar ao Google Apps Script?
R: Sim! Apenas altere a URL de log no simulador para a URL do Google Apps Script.
   O código JavaScript suporta ambos.

CONTATO & SUPORTE
═════════════════════════════════════════════════════════════════
Autor: L. Cavamura Jr.
Email: lcavamura@gmail.com
