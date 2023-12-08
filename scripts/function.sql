DROP FUNCTION IF EXISTS fc_calcula_valor_contrato;

CREATE FUNCTION fc_calcula_valor_contrato(contrato_id INT) RETURNS float
BEGIN
    DECLARE total_contrato float;
    
 	SELECT SUM(aluguel + condominio + iptu + seguro) INTO total_contrato
    FROM imoveis i
    LEFT JOIN contratos c ON c.imovel = i.id 
    WHERE c.id = contrato_id;
    
    RETURN total_contrato;
END;

SELECT 
fc_calcula_valor_contrato(id) as valor_total_contrato,
c.*
FROM contratos c 
WHERE id = 1;
