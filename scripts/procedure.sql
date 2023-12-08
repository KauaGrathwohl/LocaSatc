DROP PROCEDURE IF EXISTS pr_renova_contrato;

CREATE PROCEDURE pr_renova_contrato(IN contrato_id INT)
BEGIN
  DECLARE contrato_status VARCHAR(50);
  DECLARE contrato_dt_fim DATE;
  
  SELECT status, dt_fim INTO contrato_status, contrato_dt_fim
  FROM contratos
  WHERE id = contrato_id;
  
  IF contrato_status = 'ENCERRADO' AND contrato_dt_fim < CURDATE() THEN
    UPDATE contratos
    SET status = 'RENOVADO', dt_inicio = CURDATE(), dt_fim = DATE_ADD(CURDATE(), INTERVAL 1 YEAR)
    WHERE id = contrato_id;

    SELECT 'Contrato renovado com sucesso.' AS mensagem;
  ELSE
    SELECT 'O contrato não pode ser renovado.' AS mensagem;
  END IF;
  
END

call pr_renova_contrato(3)
