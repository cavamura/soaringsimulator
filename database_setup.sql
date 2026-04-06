-- ═══════════════════════════════════════════════════════════
--  Flight Simulator - Database Schema
--  Script para criar a tabela de acessos no MySQL
-- ═══════════════════════════════════════════════════════════

-- Criar banco de dados (se ainda não existir)
CREATE DATABASE IF NOT EXISTS lcvmcom_simulador;
USE lcvmcom_simulador;

-- Criar tabela de acessos
CREATE TABLE IF NOT EXISTS acessos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data_hora DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  evento VARCHAR(50),
  navegador VARCHAR(50),
  so VARCHAR(50),
  resolucao VARCHAR(50),
  duracao_seg INT DEFAULT 0,
  tempo_voo_seg INT DEFAULT 0,
  altitude_max INT DEFAULT 0,
  ip_anon VARCHAR(50),
  consentimento VARCHAR(10),
  INDEX idx_data_hora (data_hora),
  INDEX idx_evento (evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Confirmar criação
SHOW TABLES;
SELECT COUNT(*) as total_registros FROM acessos;
