create table enderecos (
	uf varchar(2),
	municipio varchar(100),
	endereco varchar(200), 
	bairro varchar(200)
)

create table pessoas (
	nome varchar(250),
	telefone varchar(20),
	email varchar(100)
)

create table imoveis (
	area int,
	urbano bit,
	quartos int,
	banheiros int,
	vaga_estacionamento int,
	andar int,
	aceita_animais bit,
	mobiliada bit,
	condominio int,
	aluguel int,
	iptu int,
	seguro int
)
