CREATE TABLE ofertas(
    id INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    data_fim DATE,
    descricao TEXT NOT NULL,
    link VARCHAR(100),
    categorias_id INT NOT NULL,
    PRIMARY KEY(id),
    CONSTRAINT fk_ofertas_categorias
        FOREIGN KEY (categorias_id)
        REFERENCES categorias(id)
) ENGINE=InnoDB;


CREATE TABLE cidades(
    id INT NOT NULL AUTO_INCREMENT,
    cidade  VARCHAR(70) NOT NULL,
    link VARCHAR(100),
    PRIMARY KEY(id)
) 
	ENGINE = InnoDB;

CREATE TABLE atores(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    genero ENUM('M', 'F') NOT NULL,
    idade INT NOT NULL,
    altura DECIMAL(3, 2) NOT NULl,
    cache DECIMAL(10, 2),
    linguas VARCHAR(255),
    biografia TEXT(600) NOT NULL,
    link VARCHAR(100),
    cidades_id INT NOT NULL,
    PRIMARY KEY(id),
    CONSTRAINT fk_atores_cidades
        FOREIGN KEY (cidades_id)
        REFERENCES cidades (id)
)
	ENGINE = InnoDB;

CREATE TABLE produtoras(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    biografia TEXT(600) NOT NULL,
    link VARCHAR(100),
    cidades_id INT NOT NULL,
    PRIMARY KEY(id),
    constraint fk_produtoras_cidades
		foreign key (cidades_id)
        references cidades (id)
)
	ENGINE = InnoDB;

CREATE TABLE agencias(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    biografia TEXT(600) NOT NULL,
    link VARCHAR(100),
    cidades_id INT NOT NULL,
    PRIMARY KEY(id),
    constraint fk_agencias_cidades
		foreign key (cidades_id)
        references cidades (id)
) 
	ENGINE = InnoDB;

CREATE TABLE categorias(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    link VARCHAR(100),
    PRIMARY KEY(id)
)
	ENGINE = InnoDB;

CREATE TABLE usuarios(
id INT NOT NULL AUTO_INCREMENT,
 nome VARCHAR(70) NOT NULL,
 login VARCHAR(70) NOT NULL,
 email VARCHAR(30) NOT NULL,
 senha VARCHAR(60) NOT NULL,
 salt VARCHAR(20),
 nivel VARCHAR(1),
  PRIMARY KEY (id)
)	
	ENGINE = InnoDB;

CREATE TABLE imagens(
    id INT NOT NULL AUTO_INCREMENT,
    caminho VARCHAR(100) NOT NULL,
    link VARCHAR(100),
    ofertas_id INT NOT NULL,
    produtoras_id INT NOT NULL,
    agencias_id INT NOT NULL,
    atores_id INT NOT NULL,
    PRIMARY KEY (id),
    constraint fk_imagens_ofertas
		foreign key (ofertas_id)
        references ofertas (id),
	constraint fk_imagens_produtoras
		foreign key (produtoras_id)
        references produtoras (id),
	constraint fk_imagens_agencias
		foreign key (agencias_id)
        references agencias (id),
	constraint fk_imagens_atores
		foreign key (atores_id)
        references atores (id)
)
	ENGINE = InnoDB;

CREATE TABLE ofertas_produtoras(
	produtoras_id INT NOT NULL,
    ofertas_id INT NOT NULL,
    primary key (ofertas_id, produtoras_id),
    constraint fk_ofertas_produtoras_ofertas
		foreign key ( ofertas_id)
        references ofertas (id),
	constraint fk_ofertas_produtoras_produtoras
		foreign key (produtoras_id)
        references produtoras (id)
)	ENGINE = InnoDB;

CREATE TABLE ofertas_agencias(
	ofertas_id INT NOT NULL,
    agencias_id INT NOT NULL,
    primary key (ofertas_id, agencias_id),
    constraint fk_ofertas_agencias_ofertas
		foreign key ( ofertas_id)
        references ofertas (id),
	constraint fk_ofertas_agencias_agencias
		foreign key (agencias_id)
        references agencias (id)
)	ENGINE = InnoDB;

CREATE TABLE ofertas_atores(
	ofertas_id INT NOT NULL,
    atores_id INT NOT NULL,
    primary key (ofertas_id, atores_id),
    constraint fk_ofertas_atores_ofertas
		foreign key ( ofertas_id)
        references ofertas (id),
	constraint fk_ofertas_atores_atores
		foreign key (atores_id)
        references atores (id)
)	ENGINE = InnoDB;

CREATE TABLE ofertas_categorias(
	ofertas_id INT NOT NULL,
    categorias_id INT NOT NULL,
    primary key (ofertas_id, categorias_id),
    constraint fk_ofertas_categorias_ofertas
		foreign key ( ofertas_id)
        references ofertas (id),
	constraint fk_ofertas_categorias_categorias
		foreign key (categorias_id)
        references categorias (id)
)	ENGINE = InnoDB;

CREATE TABLE markers (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL,
    address VARCHAR(80) NOT NULL,
    lat FLOAT(10, 6) NOT NULL,
    lng FLOAT(10, 6) NOT NULL,
    type VARCHAR(30) NOT NULL
) ENGINE = InnoDB;

ALTER TABLE atores
ADD COLUMN markers_id INT,
ADD CONSTRAINT fk_atores_markers
FOREIGN KEY (markers_id)
REFERENCES markers(id);

ALTER TABLE produtoras
ADD COLUMN markers_id INT,
ADD CONSTRAINT fk_produtoras_markers
FOREIGN KEY (markers_id)
REFERENCES markers(id);

ALTER TABLE agencias
ADD COLUMN markers_id INT,
ADD CONSTRAINT fk_agencias_markers
FOREIGN KEY (markers_id)
REFERENCES markers(id);


