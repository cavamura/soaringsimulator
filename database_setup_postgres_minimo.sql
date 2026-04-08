-- Flight Simulator - Schema mínimo (PostgreSQL)
-- Use este arquivo quando a ferramenta SQL tiver comportamento de "embrulhar" consultas.

CREATE TABLE IF NOT EXISTS acessos (
  id BIGSERIAL PRIMARY KEY,
  data_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  evento VARCHAR(50),
  navegador VARCHAR(50),
  so VARCHAR(50),
  resolucao VARCHAR(50),
  duracao_seg INTEGER DEFAULT 0,
  tempo_voo_seg INTEGER DEFAULT 0,
  altitude_max INTEGER DEFAULT 0,
  ip_anon VARCHAR(50),
  consentimento VARCHAR(10)
);

CREATE INDEX IF NOT EXISTS idx_data_hora ON acessos (data_hora);
CREATE INDEX IF NOT EXISTS idx_evento ON acessos (evento);
