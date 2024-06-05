CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_edita_ator`(IN `id_ator` INT, IN `nome_ator` VARCHAR(100), IN `cidade_ator` INT, IN `biografia` TEXT, IN `nome_imagem1` VARCHAR(100), IN `id_imagem1` INT, IN `nome_imagem2` VARCHAR(100), IN `id_imagem2` INT, IN `nome_imagem3` VARCHAR(100), IN `id_imagem3` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))
BEGIN
	/* VERIFICA AS IMAGENS QUE FORAM ENVIADAS PARA UPDATE */
    IF EXISTS(SELECT * FROM imagens WHERE id = id_imagem1) THEN
    	IF nome_imagem1 = "" THEN
           DELETE FROM imagens WHERE id = id_imagem1;
        ELSE
        	/*SE FOR O CÓDIGO DA IMAGEM E TAMBÉM O NOME, FAZ UM UPDATE DA IMAGEM */
        	UPDATE imagens SET caminho = nome_imagem1 WHERE id = id_imagem1;
        END IF;
    END IF;
    
    IF EXISTS(SELECT * FROM imagens WHERE id = id_imagem2) THEN
    	IF nome_imagem2 = "" THEN
           DELETE FROM imagens WHERE id = id_imagem2;
        ELSE
        	/*SE FOR O CÓDIGO DA IMAGEM E TAMBÉM O NOME, FAZ UM UPDATE DA IMAGEM */
        	UPDATE imagens SET caminho = nome_imagem2 WHERE id = id_imagem2;
        END IF;
    END IF;
      
    IF EXISTS(SELECT * FROM imagens WHERE id = id_imagem3) THEN
    	IF nome_imagem3 = "" THEN
           DELETE FROM imagens WHERE id = id_imagem3;
        ELSE
        	/*SE FOR O CÓDIGO DA IMAGEM E TAMBÉM O NOME, FAZ UM UPDATE DA IMAGEM */
        	UPDATE imagens SET caminho = nome_imagem3 WHERE id = id_imagem3;
        END IF;
    END IF;
    
    /* ESTA PARTE É PARA INSERIR UMA NOVA IMAGEM */
    IF nome_imagem1 <> "" AND id_imagem1 = "" THEN
       INSERT INTO imagens (caminho, atores_id) VALUES (nome_imagem1, id_ator);
     END IF;
     IF nome_imagem2 <> "" AND id_imagem2 = "" THEN
       INSERT INTO imagens (caminho, atores_id) VALUES (nome_imagem2, id_ator);
     END IF;
     IF nome_imagem3 <> "" AND id_imagem3 = "" THEN
       INSERT INTO imagens (caminho, atores_id) VALUES (nome_imagem3, id_ator);
     END IF;
     
     START TRANSACTION;
     /* ALTERANDO DADOS DO ATOR */
     UPDATE atores SET nome = nome_ator, biografia = biografia_ator, cidades_id = cidade_ator
     WHERE id = id_ator;
     
   IF ROW_COUNT() > 1 THEN
     SET saida_rotulo = "ERRO!";
     SET saida = "Cadastro ator/atriz não foi alterado";
     ROLLBACK;
   ELSE
   	   SET saida_rotulo = "Tudo certo!";
       SET saida = "Cadastro ator/atriz alterado com sucesso!";
       COMMIT;
   END IF;
   SELECT saida_rotulo, saida;
END