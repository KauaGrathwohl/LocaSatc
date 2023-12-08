
DROP TRIGGER IF EXISTS tr_update_contratos;

CREATE TABLE IF NOT EXISTS `contratos_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `imovel` int(11) NOT NULL,
  `locatario` int(11) NOT NULL,
  `corretor` int(11) NOT NULL,
  `unidade` int(11) NOT NULL,
  `dt_inicio` date NOT NULL,
  `dt_fim` date NOT NULL,
  `fiador` int(11) DEFAULT NULL,
  `acao` varchar(10) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `hora_acao` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TRIGGER tr_update_contratos AFTER UPDATE ON contratos
FOR EACH ROW
BEGIN
    DECLARE acao VARCHAR(10);
    SET acao = 'UPDATE';

    INSERT INTO contratos_log (id, status, imovel, locatario, corretor, unidade, dt_inicio, dt_fim, fiador, acao, usuario, hora_acao)
    VALUES (NEW.id, NEW.status, NEW.imovel, NEW.locatario, NEW.corretor, NEW.unidade, NEW.dt_inicio, NEW.dt_fim, NEW.fiador, acao, CURRENT_USER(), NOW());
END;
