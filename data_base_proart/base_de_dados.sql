CREATE TABLE categorias(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    link VARCHAR(100),
    PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE cidades(
    id INT NOT NULL AUTO_INCREMENT,
    cidade VARCHAR(70) NOT NULL,
    link VARCHAR(100),
    PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE ofertas(
    id INT NOT NULL AUTO_INCREMENT,
    titulo VARCHAR(100) NOT NULL,
    data_fim DATE,
    descricao TEXT NOT NULL,
    link VARCHAR(100),
    categorias_id INT NOT NULL,
    PRIMARY KEY(id),
    CONSTRAINT fk_ofertas_categorias FOREIGN KEY (categorias_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

CREATE TABLE atores(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    genero ENUM('M', 'F') NOT NULL,
    idade INT NOT NULL,
    altura DECIMAL(3, 2) NOT NULL,
    cache DECIMAL(10, 2),
    linguas VARCHAR(255),
    biografia TEXT NOT NULL,
    link VARCHAR(100),
    cidades_id INT NOT NULL,
    PRIMARY KEY(id),
    CONSTRAINT fk_atores_cidades FOREIGN KEY (cidades_id) REFERENCES cidades(id)
) ENGINE=InnoDB;

CREATE TABLE produtoras(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    biografia TEXT NOT NULL,
    link VARCHAR(100),
    cidades_id INT NOT NULL,
    PRIMARY KEY(id),
    CONSTRAINT fk_produtoras_cidades FOREIGN KEY (cidades_id) REFERENCES cidades(id)
) ENGINE=InnoDB;

CREATE TABLE agencias(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    biografia TEXT NOT NULL,
    link VARCHAR(100),
    cidades_id INT NOT NULL,
    PRIMARY KEY(id),
    CONSTRAINT fk_agencias_cidades FOREIGN KEY (cidades_id) REFERENCES cidades(id)
) ENGINE=InnoDB;

CREATE TABLE usuarios(
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(70) NOT NULL,
    login VARCHAR(70) NOT NULL,
    email VARCHAR(30) NOT NULL,
    senha VARCHAR(60) NOT NULL,
    salt VARCHAR(20),
    nivel VARCHAR(1),
    PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE imagens(
    id INT NOT NULL AUTO_INCREMENT,
    caminho VARCHAR(100) NOT NULL,
    link VARCHAR(100),
    ofertas_id INT,
    produtoras_id INT,
    agencias_id INT,
    atores_id INT,
    PRIMARY KEY (id),
    CONSTRAINT fk_imagens_ofertas FOREIGN KEY (ofertas_id) REFERENCES ofertas(id),
    CONSTRAINT fk_imagens_produtoras FOREIGN KEY (produtoras_id) REFERENCES produtoras(id),
    CONSTRAINT fk_imagens_agencias FOREIGN KEY (agencias_id) REFERENCES agencias(id),
    CONSTRAINT fk_imagens_atores FOREIGN KEY (atores_id) REFERENCES atores(id)
) ENGINE=InnoDB;

CREATE TABLE ofertas_produtoras(
    produtoras_id INT NOT NULL,
    ofertas_id INT NOT NULL,
    PRIMARY KEY (ofertas_id, produtoras_id),
    CONSTRAINT fk_ofertas_produtoras_ofertas FOREIGN KEY (ofertas_id) REFERENCES ofertas(id),
    CONSTRAINT fk_ofertas_produtoras_produtoras FOREIGN KEY (produtoras_id) REFERENCES produtoras(id)
) ENGINE=InnoDB;

CREATE TABLE ofertas_agencias(
    ofertas_id INT NOT NULL,
    agencias_id INT NOT NULL,
    PRIMARY KEY (ofertas_id, agencias_id),
    CONSTRAINT fk_ofertas_agencias_ofertas FOREIGN KEY (ofertas_id) REFERENCES ofertas(id),
    CONSTRAINT fk_ofertas_agencias_agencias FOREIGN KEY (agencias_id) REFERENCES agencias(id)
) ENGINE=InnoDB;

CREATE TABLE ofertas_atores(
    ofertas_id INT NOT NULL,
    atores_id INT NOT NULL,
    PRIMARY KEY (ofertas_id, atores_id),
    CONSTRAINT fk_ofertas_atores_ofertas FOREIGN KEY (ofertas_id) REFERENCES ofertas(id),
    CONSTRAINT fk_ofertas_atores_atores FOREIGN KEY (atores_id) REFERENCES atores(id)
) ENGINE=InnoDB;

CREATE TABLE ofertas_categorias(
    ofertas_id INT NOT NULL,
    categorias_id INT NOT NULL,
    PRIMARY KEY (ofertas_id, categorias_id),
    CONSTRAINT fk_ofertas_categorias_ofertas FOREIGN KEY (ofertas_id) REFERENCES ofertas(id),
    CONSTRAINT fk_ofertas_categorias_categorias FOREIGN KEY (categorias_id) REFERENCES categorias(id)
) ENGINE=InnoDB;

CREATE TABLE markers (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL,
    address VARCHAR(80) NOT NULL,
    lat FLOAT(10, 6) NOT NULL,
    lng FLOAT(10, 6) NOT NULL,
    type VARCHAR(30) NOT NULL
) ENGINE=InnoDB;

ALTER TABLE atores
ADD COLUMN markers_id INT,
ADD CONSTRAINT fk_atores_markers FOREIGN KEY (markers_id) REFERENCES markers(id);

ALTER TABLE produtoras
ADD COLUMN markers_id INT,
ADD CONSTRAINT fk_produtoras_markers FOREIGN KEY (markers_id) REFERENCES markers(id);

ALTER TABLE agencias
ADD COLUMN markers_id INT,
ADD CONSTRAINT fk_agencias_markers FOREIGN KEY (markers_id) REFERENCES markers(id);




CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_altera_categoria`(
    id_categoria INT, 
    nome_categoria VARCHAR(70), 
    OUT saida VARCHAR(80),
    out saida_rotulo VARCHAR(15)
)
BEGIN
	declare
     link_categoria  VARCHAR(100);
     SET  link_categoria = nome_categoria;
     SET link_categoria = CONCAT(link_categoria, '-', NOW());
     SET link_categoria = (SELECT gera_link(link_categoria));
    IF NOT EXISTS (SELECT * FROM categorias 
        WHERE nome = nome_categoria) THEN
        UPDATE categorias
        SET nome = nome_categoria, link = link_categoria
        WHERE id = id_categoria;
        
        IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
            SET saida = "Nenhuma categoria foi alterada!";
        ELSE
			SET saida_rotulo = "Tudo certo!";
            SET saida = "Categoria alterada com sucesso!";
        END IF;
    ELSE
		SET saida_rotulo = "OPS!";
        SET saida = "Essa categoria já existe!";
    END IF;
    
    SELECT saida_rotulo, saida;
END



CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_categoria`(IN nome_categoria VARCHAR(70), link_categoria VARCHAR(100), OUT saida VARCHAR(80), out saida_rotulo VARCHAR(15))
BEGIN
	SET link_categoria = CONCAT(link_categoria, '-', NOW());
    SET link_categoria = (SELECT gera_link(link_categoria));
	IF NOT EXISTS (SELECT * FROM categorias WHERE nome = nome_categoria) THEN
    BEGIN
		INSERT INTO categorias (nome, link)
        VALUES (nome_categoria, link_categoria);
        
        IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
			SET saida = "A categoria não foi cadastrada";
		ELSE
			SET saida_rotulo = "Tudo certo!";
			SET saida = "Categoria cadastrada com sucesso";
		END IF ;
    END;
	ELSE
		SET saida_rotulo = "OPS!";
		SET saida = "Essa categoria já existe.";
	END IF ;
    SELECT saida_rotulo, saída;
END


DELIMITER $$

CREATE PROCEDURE sp_cadastra_usuario
(
nome_usuario VARCHAR(70), login_usuario VARCHAR(30), email_usuario VARCHAR(50), senha_usuario VARCHAR(60),
salt_usuario VARCHAR(20), nivel_usuario CHAR(1), OUT saida VARCHAR(80),  OUT saida_rotulo VARCHAR(15)
)
BEGIN
	IF EXISTS(SELECT * FROM usuarios WHERE login = login_usuario) THEN
    BEGIN
		SET saida_rotulo ='OPS!';
		SET saida ='Este login já está cadrastrada!';
    END;
    ELSEIF EXISTS(SELECT * FROM usuarios WHERE email = email_usuario) THEN
    BEGIN
		SET saida_rotulo ='OPS!';
		SET saida ='Este E-mail já está cadrastrada!';
    END;
    ELSE
		INSERT INTO usuarios (nome, login, email, senha, salt, nivel)
        VALUES (nome_usuario, login_usuario, email_usuario,  senha_usuario, salt_usuario, nivel_usuario);
        
        IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = 'ERRO!';
            SET saida = 'O usuario não foi cadastrado!';
            
		ELSE
			SET saida_rotulo = 'Tudo certo!';
            SET saida = 'Usuario cadastrado com sucesso!';
		END IF;
	END IF;
            
	SELECT saida_rotulo, saida;
END $$

DELIMITER ;

CALL sp_cadastra_usuario ( 'Davidson delo', 'Davidsondelo', 'Davidsondelo@gmail.com','123', '123', 2, @saida,@rotulo);

/* OS COMANDOS USADOS PARA ALTERAR A TABELA ATORES*/

USE proart;
select * from atores;

ALTER TABLE atores ADD COLUMN idade INT NOT NULL, ADD COLUMN altura DECIMAL(3, 2) NOT NULl, ADD COLUMN caché DOUBLE, ADD COLUMN linguas VARCHAR(255);
ALTER TABLE atores ADD COLUMN genero ENUM('M', 'F') NOT NULL;

ALTER TABLE atores CHANGE caché cache DECIMAL(10, 2);
DESCRIBE atores;


CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_categoria`(id_categoria int, OUT saida VARCHAR(80), out saida_rotulo VARCHAR(15))
BEGIN
	IF NOT EXISTS (SELECT * FROM categorias WHERE id = id_categoria) THEN
    BEGIN
		SET saida_rotulo = "OPS!";
		SET saida = "Categoria não encontrada!.";
    END;
    ELSEIF EXISTS(SELECT * FROM vw_ofertas_categorias WHERE id_categoria = id_categoria) THEN
    BEGIN
		SET saida_rotulo = "OPS!";
		SET saida = "Não foi possível eliminar está categoria pois ela está vínculada a uma oferta.";
    END;
	ELSE
		DELETE from categorias WHERE id = id_categoria;
         IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
			SET saida = "A categoria não foi excluída!";
		ELSE
			SET saida_rotulo = "Tudo certo!";
			SET saida = "Categoria  excluída com sucesso";
		END IF ;
	END IF ;
    SELECT saida_rotulo, saida;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_categoria`(id_categoria int, OUT saida VARCHAR(80), out saida_rotulo VARCHAR(15))
BEGIN
	IF NOT EXISTS (SELECT * FROM categorias WHERE id = id_categoria) THEN
    BEGIN
		SET saida_rotulo = "OPS!";
		SET saida = "Categoria não encontrada!.";
    END;
    ELSEIF EXISTS(SELECT * FROM vw_ofertas_categorias WHERE id_categoria = id_categoria) THEN
    BEGIN
		SET saida_rotulo = "OPS!";
		SET saida = "Não foi possível eliminar está categoria pois ela está vínculada a uma oferta.";
    END;
	ELSE
		DELETE from categorias WHERE id = id_categoria;
         IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
			SET saida = "A categoria não foi excluída!";
		ELSE
			SET saida_rotulo = "Tudo certo!";
			SET saida = "Categoria  excluída com sucesso";
		END IF ;
	END IF ;
    SELECT saida_rotulo, saida;
END


CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_categoria`(id_categoria int, OUT saida VARCHAR(80), out saida_rotulo VARCHAR(15))
BEGIN
	IF NOT EXISTS (SELECT * FROM categorias WHERE id = id_categoria) THEN
    BEGIN
		SET saida_rotulo = "OPS!";
		SET saida = "Categoria não encontrada!.";
    END;
    ELSEIF EXISTS(SELECT * FROM vw_ofertas_categorias WHERE id_categoria = id_categoria) THEN
    BEGIN
		SET saida_rotulo = "OPS!";
		SET saida = "Não foi possível eliminar está categoria pois ela está vínculada a uma oferta.";
    END;
	ELSE
		DELETE from categorias WHERE id = id_categoria;
         IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
			SET saida = "A categoria não foi excluída!";
		ELSE
			SET saida_rotulo = "Tudo certo!";
			SET saida = "Categoria  excluída com sucesso";
		END IF ;
	END IF ;
    SELECT saida_rotulo, saida;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_atores`(
    nome_ator VARCHAR(70),
    cidade_ator INT,
    biografia_ator VARCHAR(500),
    idade_ator INT,
    altura_ator DECIMAL(3, 2),
    cache_ator DECIMAL(10, 2),
    linguas_ator VARCHAR(255),
	genero_ator ENUM('M', 'F'),
    imagem_ator1 VARCHAR(100),
    imagem_ator2 VARCHAR(100),
    imagem_ator3 VARCHAR(100),
    OUT saida VARCHAR(80),
	OUT saida_rotulo VARCHAR(15)
)
BEGIN
	declare link_ator VARCHAR(100);
	declare id_ator INT;
    declare deu_certo INT;
	IF EXISTS (SELECT * FROM atores WHERE nome = nome_ator) THEN
	BEGIN
		SET saida_rotulo = "OPS!";
        SET saida = "Este ator/atriz já está cadastrado";
	END;
	ELSE 
		SET link_ator = CONCAT(nome_ator, '-', NOW());
        SET link_ator = (SELECT gera_link(link_ator));
        
        START TRANSACTION;
         /*ESTE INSERT É PARA A TABELA DE ATORES */
        INSERT INTO atores (nome, biografia, link, cidades_id, idade, altura, cache, linguas, genero)
        VALUES (nome_ator, biografia_ator, link_ator, cidade_ator, idade_ator, altura_ator, cache_ator, linguas_ator, genero_ator);
        
        IF ROW_COUNT() = 0 THEN
			SET deu_certo = 0;
		ELSE 
			SET deu_certo = 1;
		END IF;
        
         /*ESTE INSERT É PARA A TABELA DE IMAGENS */
		SET  id_ator = (SELECT id FROM atores WHERE nome = nome_ator);
        IF imagem_ator1 <> "" THEN
        INSERT INTO imagens (caminho, atores_id) VALUES (imagem_ator1, id_ator);
         IF ROW_COUNT() = 0 THEN
			SET deu_certo = 0;
		ELSE 
			SET deu_certo = deu_certo + 1;
		END IF;
	 END IF;
        
        SET  id_ator = (SELECT id FROM atores WHERE nome = nome_ator);
        IF imagem_ator2 <> "" THEN
        INSERT INTO imagens (caminho, atores_id) VALUES (imagem_ator2, id_ator);
         IF ROW_COUNT() = 0 THEN
			SET deu_certo = 0;
		ELSE 
			SET deu_certo = deu_certo + 1;
		END IF;
	END IF;
        
        SET  id_ator = (SELECT id FROM atores WHERE nome = nome_ator);
        IF imagem_ator3 <> "" THEN
        INSERT INTO imagens (caminho, atores_id) VALUES (imagem_ator3, id_ator);
         IF ROW_COUNT() = 0 THEN
			SET deu_certo = 0;
		ELSE 
			SET deu_certo = deu_certo + 1;
		END IF;
	 END IF;
        
	 IF deu_certo > 0 THEN
		SET saida_rotulo ="Tudo certo!";
        SET saida ="Ator/Atriz cadastrado(a) com sucesso!";
        COMMIT;
	 ELSE 
		 SET saida_rotulo ="Erro!";
		 SET saida ="Ator/Atriz não foi cadastrado(a)!";
		 ROLLBACK;
	   END IF;
	END IF;
    SELECT saida, saida_rotulo;
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_cidade`(IN nome_cidade VARCHAR(70), link_cidade VARCHAR(100), OUT saida VARCHAR(80), out saida_rotulo VARCHAR(15))
BEGIN
	SET link_cidade = CONCAT(link_cidade, '-', NOW());
    SET link_cidade = (SELECT gera_link(link_cidade));
	IF NOT EXISTS (SELECT * FROM cidades WHERE cidade = nome_cidade) THEN
    BEGIN
		INSERT INTO cidades (cidade, link)
        VALUES (nome_cidade, link_cidade);
        
        IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
			SET saida = "A cidade não foi cadastrada";
		ELSE
			SET saida_rotulo = "Tudo certo!";
			SET saida = "Cidade cadastrada com sucesso";
		END IF ;
    END;
	ELSE
		SET saida_rotulo = "OPS!";
		SET saida = "Essa cidade já existe.";
	END IF ;
    SELECT saida_rotulo, saída; 
END

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_imagem`(
    IN p_ofertas_id INT,
    IN p_caminho VARCHAR(100),
    IN p_link VARCHAR(100),
    IN p_produtoras_id INT,
    IN p_agencias_id INT,
    IN p_atores_id INT,
    OUT saida VARCHAR(80),
    OUT rotulo VARCHAR(15)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        SET saida = 'Erro ao cadastrar imagem';
        SET rotulo = 'ERRO!';
        ROLLBACK;
    END;

    START TRANSACTION;

    IF (SELECT COUNT(*) FROM ofertas WHERE id = p_ofertas_id) = 0 THEN
        SET saida = 'ID da oferta não existe';
        SET rotulo = 'ERRO!';
    ELSEIF (SELECT COUNT(*) FROM produtoras WHERE id = p_produtoras_id) = 0 THEN
        SET saida = 'ID da produtora não existe';
        SET rotulo = 'ERRO!';
    ELSEIF (SELECT COUNT(*) FROM agencias WHERE id = p_agencias_id) = 0 THEN
        SET saida = 'ID da agência não existe';
        SET rotulo = 'ERRO!';
    ELSEIF (SELECT COUNT(*) FROM atores WHERE id = p_atores_id) = 0 THEN
        SET saida = 'ID do ator não existe';
        SET rotulo = 'ERRO!';
    ELSE
        INSERT INTO imagens (caminho, link, ofertas_id, produtoras_id, agencias_id, atores_id) 
        VALUES (p_caminho, p_link, p_ofertas_id, p_produtoras_id, p_agencias_id, p_atores_id);

        IF ROW_COUNT() > 0 THEN
            SET saida = 'Imagem cadastrada com sucesso';
            SET rotulo = 'SUCESSO!';
            COMMIT;
        ELSE
            SET saida = 'Erro ao cadastrar imagem';
            SET rotulo = 'ERRO!';
            ROLLBACK;
        END IF;
    END IF;
END

CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_ofertas_categorias` AS
    SELECT 
        `ofertas_categorias`.`ofertas_id` AS `id_oferta`,
        `ofertas_categorias`.`categorias_id` AS `id_categoria`
    FROM
        `ofertas_categorias`


    CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_retorna_categorias` AS
    SELECT 
        `categorias`.`id` AS `Id_Categoria`,
        `categorias`.`nome` AS `Nome_Categoria`,
        `categorias`.`link` AS `Link_Categoria`
    FROM
        `categorias`

        CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_retorna_cidade` AS
    SELECT 
        `cidades`.`id` AS `id_cidade`,
        `cidades`.`cidade` AS `nome_cidade`,
        `cidades`.`link` AS `link_cidade`
    FROM
        `cidades`

        
create or replace view vw_usuarios as
SELECT id as id_usuario, nome as nome_usuario, login login_usuario, email email_usuario, senha senha_usuario, salt salt_usuario, nivel nivel_usuario
FROM usuarios;


CREATE DEFINER=`root`@`localhost` FUNCTION `gera_link`(Texto VARCHAR(150)) RETURNS varchar(150) CHARSET utf8mb4 COLLATE utf8mb4_general_ci
BEGIN
DECLARE Resultado VARCHAR(150);

SET Resultado   = UPPER(Texto); 
SET Resultado = REPLACE(Resultado,' ','-');
SET Resultado = REPLACE(Resultado,'\'','');
SET Resultado = REPLACE(Resultado,'`','');
SET Resultado = REPLACE(Resultado,'.','');

SET Resultado = REPLACE(Resultado,'À','A');
SET Resultado = REPLACE(Resultado,'Á','A');
SET Resultado = REPLACE(Resultado,'Â','A');
SET Resultado = REPLACE(Resultado,'Ã','A');
SET Resultado = REPLACE(Resultado,'Ä','A');
SET Resultado = REPLACE(Resultado,'Å','A');

SET Resultado = REPLACE(Resultado,'È','E');
SET Resultado = REPLACE(Resultado,'É','E');
SET Resultado = REPLACE(Resultado,'Ê','E');
SET Resultado = REPLACE(Resultado,'Ë','E');

SET Resultado = REPLACE(Resultado,'Ì','I');
SET Resultado = REPLACE(Resultado,'Í','I');
SET Resultado = REPLACE(Resultado,'Î','I');
SET Resultado = REPLACE(Resultado,'Ï','I');

SET Resultado = REPLACE(Resultado,'Ò','O');
SET Resultado = REPLACE(Resultado,'Ó','O');
SET Resultado = REPLACE(Resultado,'Ô','O');
SET Resultado = REPLACE(Resultado,'Õ','O');
SET Resultado = REPLACE(Resultado,'Ö','O');

SET Resultado = REPLACE(Resultado,'Ù','U');
SET Resultado = REPLACE(Resultado,'Ú','U');
SET Resultado = REPLACE(Resultado,'Û','U');
SET Resultado = REPLACE(Resultado,'Ü','U');

SET Resultado = REPLACE(Resultado,'Ø','O');

SET Resultado = REPLACE(Resultado,'Æ','A');
SET Resultado = REPLACE(Resultado,'Ð','D');
SET Resultado = REPLACE(Resultado,'Ñ','N');
SET Resultado = REPLACE(Resultado,'Ý','Y');
SET Resultado = REPLACE(Resultado,'Þ','B');
SET Resultado = REPLACE(Resultado,'ß','S');

SET Resultado = REPLACE(Resultado,'Ç','C');

RETURN LOWER(Resultado);
END


