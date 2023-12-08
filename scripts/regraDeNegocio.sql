-- 1) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual imobiliária realizou mais contratos nos últimos dois anos?

SELECT imobiliaria, COUNT(id) AS qtd_contratos
FROM contratos
WHERE YEAR(dt_inicio) >= YEAR(CURDATE()) - 2
GROUP BY imobiliaria
ORDER BY qtd_contratos DESC
LIMIT 1;



-- 2) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual proprietário possui mais imóveis alugados ?

SELECT proprietario, COUNT(id) AS qtd_imoveis_alugados
FROM imoveis
WHERE aluguel IS NOT NULL
GROUP BY proprietario
ORDER BY qtd_imoveis_alugados DESC
LIMIT 1;



-- 3) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual o estado que tem mais imobiliárias residindo?

SELECT uf, COUNT(id) AS qtd_imobiliarias
FROM imobiliaria
GROUP BY uf
ORDER BY qtd_imobiliarias DESC
LIMIT 1;



-- 4) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Quantos contratos serão finalizados no ano de 2023? 

SELECT COUNT(id) AS qtd_contratos_finalizados
FROM contratos
WHERE YEAR(dt_fim) = 2023;



-- 5) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual é a média de renda dos locatários que têm contratos ativos?

SELECT AVG(renda) AS media_renda
FROM locatario
WHERE id IN (SELECT locatario FROM contratos WHERE dt_fim >= CURDATE());



-- 6) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Quais são os contratos que foram assinados pelo corretor com mais contratos realizados?

SELECT *
FROM contratos
WHERE corretor = (
  SELECT corretor
  FROM contratos
  GROUP BY corretor
  ORDER BY COUNT(id) DESC
  LIMIT 1
);


-- 7) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Quais são os imóveis cujo valor do aluguel está acima da média?

SELECT *
FROM imoveis
WHERE aluguel > (SELECT AVG(aluguel) FROM imoveis WHERE aluguel IS NOT NULL);



-- 8) Utilizando a linguagem SQL, execute consultas que possam responder a seguinte questão: Qual locatário possui mais contratos de aluguel vinculado?

SELECT locatario, COUNT(id) AS qtd_contratos
FROM contratos
GROUP BY locatario
ORDER BY qtd_contratos DESC
LIMIT 1;
