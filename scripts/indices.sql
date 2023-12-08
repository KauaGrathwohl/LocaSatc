ALTER TABLE `imoveis` ADD FOREIGN KEY (`proprietario`) REFERENCES `proprietario` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`imovel`) REFERENCES `imoveis` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`locatario`) REFERENCES `locatario` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`corretor`) REFERENCES `corretor` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`fiador`) REFERENCES `fiador` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`locatario`) REFERENCES `locatario` (`id`);

ALTER TABLE `corretor` ADD FOREIGN KEY (`unidade`) REFERENCES `imobiliaria` (`id`);

ALTER TABLE `imobiliaria` ADD FOREIGN KEY (`franqueado`) REFERENCES `franqueado` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`locatario`) REFERENCES `locatario` (`id`);
