--  FOREIGN KEYS contratos
ALTER TABLE contratos
ADD CONSTRAINT fk_contrato_imovel
FOREIGN KEY (imovel) REFERENCES imoveis(id),
ADD CONSTRAINT fk_contrato_proprietario
FOREIGN KEY (proprietario) REFERENCES proprietario(id),
ADD CONSTRAINT fk_contrato_locatario
FOREIGN KEY (locatario) REFERENCES locatario(id),
ADD CONSTRAINT fk_contrato_corretor
FOREIGN KEY (corretor) REFERENCES corretor(id),
ADD CONSTRAINT fk_contrato_fiador
FOREIGN KEY (fiador) REFERENCES fiador(id);

-- FOREIGN KEYS imoveis
ALTER TABLE imoveis
ADD CONSTRAINT fk_imovel_proprietario
FOREIGN KEY (proprietario) REFERENCES proprietario(id);

-- FOREIGN KEYS locatario
ALTER TABLE locatario
ADD CONSTRAINT fk_locatario_fiador
FOREIGN KEY (fiador) REFERENCES fiador(id),
ADD CONSTRAINT fk_locatario_contrato
FOREIGN KEY (contrato) REFERENCES contratos(id);

-- FOREIGN KEYS fiador
ALTER TABLE fiador
ADD CONSTRAINT fk_fiador_contrato
FOREIGN KEY (contrato) REFERENCES contratos(id);

-- FOREIGN KEYS imobiliaria
ALTER TABLE imobiliaria
ADD CONSTRAINT fk_imobiliaria_franqueado
FOREIGN KEY (franqueado) REFERENCES franqueado(id);

-- FOREIGN KEYS corretor
ALTER TABLE corretor
ADD CONSTRAINT fk_corretor_unidade
FOREIGN KEY (unidade) REFERENCES enderecos(id);

-- FOREIGN KEYS franqueado
ALTER TABLE franqueado
ADD CONSTRAINT fk_franqueado_unidade
FOREIGN KEY (id) REFERENCES enderecos(id);
