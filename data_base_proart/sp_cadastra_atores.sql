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