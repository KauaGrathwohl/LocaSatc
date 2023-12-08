-- 1) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual proprietário possui mais imóveis alugados ?

SELECT
    p.nome AS proprietario_nome,
    COUNT(*) AS total_imoveis_alugados
FROM
    proprietario p
WHERE
    p.id IN (SELECT DISTINCT proprietario FROM imoveis WHERE aluguel IS NOT NULL)
GROUP BY
    p.nome
ORDER BY
    total_imoveis_alugados DESC
LIMIT 1;

-- 2) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual o estado que tem mais imobiliárias residindo?

SELECT
    i.uf AS estado,
    COUNT(*) AS total_imobiliarias
FROM
    imobiliaria i
GROUP BY
    i.uf
ORDER BY
    total_imobiliarias DESC
LIMIT 1;

-- 3) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Quantos contratos serão finalizados no ano de 2023? 

SELECT
    COUNT(*) AS total_contratos_finalizados
FROM
    contratos
WHERE
    YEAR(dt_fim) = 2023;

-- 4) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual é a média de renda dos locatários que têm contratos ativos?

SELECT
    AVG(l.renda) AS media_renda_locatarios
FROM
    locatario l
WHERE
    l.id IN (SELECT DISTINCT locatario FROM contratos WHERE status = 'ativo');
