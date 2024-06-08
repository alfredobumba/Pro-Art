-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Jun-2024 às 17:27
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `proart`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_altera_categoria` (IN `id_categoria` INT, IN `nome_categoria` VARCHAR(70), OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
    DECLARE link_categoria VARCHAR(100);
    SET link_categoria = nome_categoria;
    SET link_categoria = CONCAT(link_categoria, '-', NOW());
    SET link_categoria = (SELECT gera_link(link_categoria));
    
    IF NOT EXISTS (SELECT * FROM categorias WHERE nome = nome_categoria) THEN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_altera_cidade` (IN `id` INT, IN `nome_cidade` VARCHAR(70), OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
	DECLARE link_cidade VARCHAR(100);

	IF NOT EXISTS (SELECT * FROM cidades WHERE cidade = nome_cidade AND id <> id) THEN
		SET link_cidade = nome_cidade;
		SET link_cidade = CONCAT(link_cidade, '-', NOW());
		SET link_cidade = (SELECT gera_link(link_cidade));

		UPDATE cidades
		SET cidade = nome_cidade, link = link_cidade
		WHERE id = id;
	    
		IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
			SET saida = "Nenhuma cidade foi alterada!";
		ELSE
			SET saida_rotulo = "Tudo certo!";
			SET saida = "Cidade alterada com sucesso!";
		END IF;
	ELSE
		SET saida_rotulo = "OPS!";
		SET saida = "Já existe uma cidade com esse nome!";
	END IF;
    
	SELECT saida_rotulo, saida;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_agencias` (IN `nome_agencia` VARCHAR(70), IN `cidade_agencia` INT, IN `marker_agencia` INT, IN `biografia_agencia` VARCHAR(500), IN `imagem_agencia1` VARCHAR(100), IN `imagem_agencia2` VARCHAR(100), IN `imagem_agencia3` VARCHAR(100), OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
    DECLARE link_agencia VARCHAR(100);
    DECLARE id_agencia INT;
    DECLARE deu_certo INT;

    IF EXISTS (SELECT * FROM agencias WHERE nome = nome_agencia) THEN
        SET saida_rotulo = "OPS!";
        SET saida = "Esta agência já está cadastrada";
    ELSE 
        SET link_agencia = CONCAT(nome_agencia, '-', NOW());
        SET link_agencia = (SELECT gera_link(link_agencia));
        
        START TRANSACTION;
        -- Este INSERT é para a tabela de agencias
        INSERT INTO agencias (nome, biografia, link, cidades_id, markers_id)
        VALUES (nome_agencia, biografia_agencia, link_agencia, cidade_agencia, marker_agencia);
        
        IF ROW_COUNT() = 0 THEN
            SET deu_certo = 0;
        ELSE 
            SET deu_certo = 1;
        END IF;
        
        -- Este INSERT é para a tabela de imagens
        SET id_agencia = (SELECT id FROM agencias WHERE nome = nome_agencia);
        
        IF imagem_agencia1 <> '' THEN
            INSERT INTO imagens (caminho, agencias_id) VALUES (imagem_agencia1, id_agencia);
            IF ROW_COUNT() = 0 THEN
                SET deu_certo = 0;
            ELSE 
                SET deu_certo = deu_certo + 1;
            END IF;
        END IF;
        
        IF imagem_agencia2 <> '' THEN
            INSERT INTO imagens (caminho, agencias_id) VALUES (imagem_agencia2, id_agencia);
            IF ROW_COUNT() = 0 THEN
                SET deu_certo = 0;
            ELSE 
                SET deu_certo = deu_certo + 1;
            END IF;
        END IF;
        
        IF imagem_agencia3 <> '' THEN
            INSERT INTO imagens (caminho, agencias_id) VALUES (imagem_agencia3, id_agencia);
            IF ROW_COUNT() = 0 THEN
                SET deu_certo = 0;
            ELSE 
                SET deu_certo = deu_certo + 1;
            END IF;
        END IF;
        
        IF deu_certo > 0 THEN
            SET saida_rotulo = "Tudo certo!";
            SET saida = "Agência cadastrada com sucesso!";
            COMMIT;
        ELSE 
            SET saida_rotulo = "Erro!";
            SET saida = "Agência não foi cadastrada!";
            ROLLBACK;
        END IF;
    END IF;

    SELECT saida, saida_rotulo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_atores` (IN `nome_ator` VARCHAR(70), IN `cidade_ator` INT, IN `biografia_ator` VARCHAR(500), IN `idade_ator` INT, IN `altura_ator` DECIMAL(3,2), IN `cache_ator` DECIMAL(10,2), IN `linguas_ator` VARCHAR(255), IN `genero_ator` ENUM('M','F'), IN `imagem_ator1` VARCHAR(100), IN `imagem_ator2` VARCHAR(100), IN `imagem_ator3` VARCHAR(100), OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_categoria` (IN `nome_categoria` VARCHAR(70), IN `link_categoria` VARCHAR(100), OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_cidade` (IN ` nome_cidade` VARCHAR(70), IN `link_cidade` VARCHAR(100), OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_imagem` (IN `p_ofertas_id` INT, IN ` p_caminho` VARCHAR(100), IN `p_link ` VARCHAR(100), IN `p_produtoras_id ` INT, IN `p_agencias_id ` INT, IN `p_atores_id` INT, OUT `saida` VARCHAR(80), OUT `rotulo` VARCHAR(15))   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_usuario` (IN `nome_usuario` VARCHAR(70), IN `login_usuario` VARCHAR(30), IN `email_usuario` VARCHAR(50), IN `senha_usuario` VARCHAR(60), IN `salt_usuario` VARCHAR(20), IN `nivel_usuario` VARCHAR(1), OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_agencias` (IN `id_agencia` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
    -- Verificar se o ator está cadastrado
    IF NOT EXISTS (SELECT 1 FROM agencias WHERE id = id_agencia) THEN
        SET saida_rotulo = 'OPS!';
        SET saida = 'Agencia não encontrada!';
    ELSE
		 -- remover agencia da tabela ofertas_agencias
         DELETE FROM ofertas_agencias WHERE agencias_id = id_agencia;
        -- Primeiro, remover as imagens da agencia
        DELETE FROM imagens WHERE agencias_id = id_agencia;
        
        -- Depois deletar a agencia
        DELETE FROM agencias WHERE id = id_agencia;
        
        IF ROW_COUNT() = 0 THEN
            SET saida_rotulo = 'ERRO!';
            SET saida = 'Agencia não foi excluida!';
        ELSE
            SET saida_rotulo = 'Tudo certo!';
            SET saida = 'Agencia excluida com sucesso!';
        END IF;
    END IF;
    
    -- Selecionar os resultados para saída
    SELECT saida_rotulo, saida;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_atores` (IN `id_ator` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
    -- Verificar se o ator está cadastrado
    IF NOT EXISTS (SELECT 1 FROM atores WHERE id = id_ator) THEN
        SET saida_rotulo = 'OPS!';
        SET saida = 'Ator/Atriz não encontrado(a)!';
    ELSE
		 -- remover ator da tabela ofertas_atores
         DELETE FROM ofertas_atores WHERE atores_id = id_ator;
        -- Primeiro, remover as imagens do ator
        DELETE FROM imagens WHERE atores_id = id_ator;
        
        -- Depois deletar o ator
        DELETE FROM atores WHERE id = id_ator;
        
        IF ROW_COUNT() = 0 THEN
            SET saida_rotulo = 'ERRO!';
            SET saida = 'Ator/Atriz não foi excluido(a)!';
        ELSE
            SET saida_rotulo = 'Tudo certo!';
            SET saida = 'Ator/Atriz excluido(a) com sucesso!';
        END IF;
    END IF;
    
    -- Selecionar os resultados para saída
    SELECT saida_rotulo, saida;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_categoria` (IN `id_categoria` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_cidade` (IN `id_cidade` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
	IF NOT EXISTS (SELECT * FROM cidades WHERE id = id_cidade) THEN
    BEGIN
		SET saida_rotulo = "OPS!";
		SET saida = "Cidade não encontrada!.";
    END;
	ELSE
		DELETE from cidades WHERE id = id_cidade;
         IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
			SET saida = "A cidade não foi excluída!";
		ELSE
			SET saida_rotulo = "Tudo certo!";
			SET saida = "Cidade  excluída com sucesso";
		END IF ;
	END IF ;
    SELECT saida_rotulo, saida;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_edita_agencia` (IN `id_agencia` INT, IN `nome_agencia` VARCHAR(100), IN `cidade_agencia` INT, IN `biografia` TEXT, IN `nome_imagem1` VARCHAR(100), IN `id_imagem1` INT, IN `nome_imagem2` VARCHAR(100), IN `id_imagem2` INT, IN `nome_imagem3` VARCHAR(100), IN `id_imagem3` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
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
       INSERT INTO imagens (caminho, agencias_id) VALUES (nome_imagem1, id_agencia);
     END IF;
     IF nome_imagem2 <> "" AND id_imagem2 = "" THEN
       INSERT INTO imagens (caminho, agencias_id) VALUES (nome_imagem2, id_agencia);
     END IF;
     IF nome_imagem3 <> "" AND id_imagem3 = "" THEN
       INSERT INTO imagens (caminho, agencias_id) VALUES (nome_imagem3, id_agencia);
     END IF;
     
     START TRANSACTION;
     /* ALTERANDO DADOS Da agencia*/
     UPDATE agencias SET nome = nome_agencia, biografia = biografia_agencia, cidades_id = cidade_agencia, markers = marker_agencia
     WHERE id = id_agencia;
     
   IF ROW_COUNT() > 1 THEN
     SET saida_rotulo = "ERRO!";
     SET saida = "Cadastro agencia não foi alterada";
     ROLLBACK;
   ELSE
   	   SET saida_rotulo = "Tudo certo!";
       SET saida = "Cadastro agencia alterada com sucesso!";
       COMMIT;
   END IF;
   SELECT saida_rotulo, saida;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_edita_ator` (IN `id_ator` INT, IN `nome_ator` VARCHAR(100), IN `cidade_ator` INT, IN `biografia` TEXT, IN `nome_imagem1` VARCHAR(100), IN `id_imagem1` INT, IN `nome_imagem2` VARCHAR(100), IN `id_imagem2` INT, IN `nome_imagem3` VARCHAR(100), IN `id_imagem3` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))   BEGIN
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
END$$

--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `gera_link` (`Texto` VARCHAR(150)) RETURNS VARCHAR(150) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
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
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agencias`
--

CREATE TABLE `agencias` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `biografia` text NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `cidades_id` int(11) NOT NULL,
  `markers_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atores`
--

CREATE TABLE `atores` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `biografia` text NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `cidades_id` int(11) NOT NULL,
  `idade` int(11) NOT NULL,
  `altura` decimal(3,2) NOT NULL,
  `cache` decimal(10,2) NOT NULL,
  `linguas` varchar(255) DEFAULT NULL,
  `genero` enum('M','F') NOT NULL,
  `markers_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `atores`
--

INSERT INTO `atores` (`id`, `nome`, `biografia`, `link`, `cidades_id`, `idade`, `altura`, `cache`, `linguas`, `genero`, `markers_id`) VALUES
(92, 'kelvin Bumba', 'Ator teatro', 'kelvin-bumba-2024-05-28-16:05:35', 1, 24, 1.79, 2500.00, 'Inglês', 'M', NULL),
(93, 'Lina Mendes', 'Atriz foto', 'lina-mendes-2024-05-28-16:10:40', 3, 21, 1.70, 100.00, 'Português', 'F', NULL),
(142, 'Marcinha', 'foto', 'marcinha-2024-06-05-05:03:15', 1, 35, 1.71, 3.00, 'Português', 'M', NULL),
(143, 'Marcinho', 'ator', 'marcinho-2024-06-05-06:41:37', 1, 35, 1.71, 3.00, 'Português', 'M', NULL),
(144, 'andré sabino', 'ator imagem', 'andre-sabino-2024-06-05-18:04:58', 9, 37, 1.60, 9999.00, 'Inglês e Português', 'M', NULL),
(145, '9 FILMES', 'Casting', '9-filmes-2024-06-08-13:33:11', 10, 0, 0.00, 0.00, '', '', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `link` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `link`) VALUES
(41, 'Teatro', 'teatro-2024-05-22-05:32:00'),
(42, 'Cinema', 'cinema-2024-05-24-13:48:06'),
(46, 'Casting', 'casting-2024-06-04-20:27:52'),
(47, 'MODELO 3', 'modelo-3-2024-06-05-00:01:46'),
(54, 'dança', 'danca-2024-06-05-18:06:11'),
(57, 'MODELO 7', 'modelo-7-2024-06-08-02:28:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cidades`
--

CREATE TABLE `cidades` (
  `id` int(11) NOT NULL,
  `cidade` varchar(70) NOT NULL,
  `link` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cidades`
--

INSERT INTO `cidades` (`id`, `cidade`, `link`) VALUES
(10, 'Amadora', 'amadora-10-2024-06-08-03:43:12'),
(15, 'Amadora', 'amadora-10-2024-06-08-03:43:12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagens`
--

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL,
  `caminho` varchar(100) NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `ofertas_id` int(11) NOT NULL,
  `produtoras_id` int(11) NOT NULL,
  `agencias_id` int(11) NOT NULL,
  `atores_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `markers`
--

CREATE TABLE `markers` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `markers`
--

INSERT INTO `markers` (`id`, `name`, `address`, `lat`, `lng`, `type`) VALUES
(170, 'Agência de Publicidade X', 'Avenida da Liberdade, Lisboa', 38.716236, -9.141810, 'agencia'),
(171, 'Agência de Publicidade Y', 'Rua Augusta, Lisboa', 38.712456, -9.138975, 'agencia'),
(172, 'Produtora de Filmes Z', 'Praça do Comércio, Lisboa', 38.707085, -9.136230, 'produtora'),
(173, 'Produtora de Filmes W', 'Rua Garrett, Lisboa', 38.710320, -9.141678, 'produtora'),
(174, 'Teatro A', 'Rua de São Bento, Lisboa', 38.712936, -9.153191, 'teatro'),
(175, 'Teatro B', 'Rua do Alecrim, Lisboa', 38.707977, -9.147322, 'teatro'),
(176, 'Agência de Publicidade Z', 'Avenida da República, Lisboa', 38.743561, -9.147056, 'agencia'),
(177, 'Produtora de Filmes X', 'Rua de São José, Lisboa', 38.715328, -9.144933, 'produtora'),
(178, 'Agência de Publicidade X', 'Avenida da Liberdade, Lisboa', 38.716236, -9.141810, 'agencia'),
(179, 'Agência de Publicidade Y', 'Rua Augusta, Lisboa', 38.712456, -9.138975, 'agencia'),
(180, 'Produtora de Filmes Z', 'Praça do Comércio, Lisboa', 38.707085, -9.136230, 'produtora'),
(181, 'Produtora de Filmes W', 'Rua Garrett, Lisboa', 38.710320, -9.141678, 'produtora'),
(182, 'Teatro A', 'Rua de São Bento, Lisboa', 38.712936, -9.153191, 'teatro'),
(183, 'Teatro B', 'Rua do Alecrim, Lisboa', 38.707977, -9.147322, 'teatro'),
(184, 'Agência de Publicidade Z', 'Avenida da República, Lisboa', 38.743561, -9.147056, 'agencia'),
(185, 'Produtora de Filmes X', 'Rua de São José, Lisboa', 38.715328, -9.144933, 'produtora');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ofertas`
--

CREATE TABLE `ofertas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `data_fim` date DEFAULT NULL,
  `descricao` text NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `categorias_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ofertas_agencias`
--

CREATE TABLE `ofertas_agencias` (
  `ofertas_id` int(11) NOT NULL,
  `agencias_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ofertas_atores`
--

CREATE TABLE `ofertas_atores` (
  `ofertas_id` int(11) NOT NULL,
  `atores_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ofertas_categorias`
--

CREATE TABLE `ofertas_categorias` (
  `ofertas_id` int(11) NOT NULL,
  `categorias_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ofertas_produtoras`
--

CREATE TABLE `ofertas_produtoras` (
  `produtoras_id` int(11) NOT NULL,
  `ofertas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtoras`
--

CREATE TABLE `produtoras` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `biografia` text NOT NULL,
  `link` varchar(100) DEFAULT NULL,
  `cidades_id` int(11) NOT NULL,
  `markers_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `login` varchar(70) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `salt` varchar(20) DEFAULT NULL,
  `nivel` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `login`, `email`, `senha`, `salt`, `nivel`) VALUES
(1, 'Alfredo', 'alfredo', 'alfredbuma3@gmail.com', '123', '123', '1'),
(2, 'Davidson delo', 'Davidsondelo', 'Davidsondelo@gmail.com', '123', '123', '2'),
(3, ' davidson delo', 'adm', 'davidsondelo2@gmail.com', '123', '123', '1'),
(4, ' Alfredo Simba Lembe Bumba', 'bumba', 'Alfredobumba3@gmail.com', '123', '123', '2'),
(5, ' Alexandre Barão', 'Barao', 'alexandrebarao@universidadeeuropeia.pt', '123', '123', '1'),
(6, ' MARCIOSARAIVA', 'marciooscar', 'ms_holihood@oscar.com', '123', '123', '2'),
(7, ' Simba Lembe Bumba', 'SIMBA', 'Alfredobumba4@gmail.com', '123', '123', '1');

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_ofertas_categorias`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_ofertas_categorias` (
`id_oferta` int(11)
,`id_categoria` int(11)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_retorna_agencias`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_retorna_agencias` (
`id_agencia` int(11)
,`nome_agencia` varchar(70)
,`biografia_agencia` text
,`link_agencia` varchar(100)
,`cidade_agencia` int(11)
,`marker_agencia` int(11)
,`caminho_imagem` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_retorna_atores`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_retorna_atores` (
`id_ator` int(11)
,`nome_ator` varchar(70)
,`biografia_ator` text
,`link_ator` varchar(100)
,`cidade_ator` int(11)
,`idade_ator` int(11)
,`altura_ator` decimal(3,2)
,`cache_ator` decimal(10,2)
,`lingua_ator` varchar(255)
,`genero_ator` enum('M','F')
,`marker_ator` int(11)
,`caminho_imagem` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_retorna_categorias`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_retorna_categorias` (
`Id_Categoria` int(11)
,`Nome_Categoria` varchar(70)
,`Link_Categoria` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_retorna_cidade`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_retorna_cidade` (
`id_cidade` int(11)
,`nome_cidade` varchar(70)
,`link_cidade` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_retorna_cidades`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_retorna_cidades` (
`id_cidade` int(11)
,`nome_cidade` varchar(70)
,`link_cidade` varchar(100)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_retorna_markers`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_retorna_markers` (
`id_marker` int(11)
,`name_marker` varchar(60)
,`address_marker` varchar(80)
,`latitude` float(10,6)
,`longitude` float(10,6)
,`type_marker` varchar(30)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `vw_usuarios`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `vw_usuarios` (
`id_usuario` int(11)
,`nome_usuario` varchar(70)
,`login_usuario` varchar(70)
,`email_usuario` varchar(50)
,`senha_usuario` varchar(60)
,`salt_usuario` varchar(20)
,`nivel_usuario` varchar(1)
);

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_ofertas_categorias`
--
DROP TABLE IF EXISTS `vw_ofertas_categorias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_ofertas_categorias`  AS SELECT `ofertas_categorias`.`ofertas_id` AS `id_oferta`, `ofertas_categorias`.`categorias_id` AS `id_categoria` FROM `ofertas_categorias` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_retorna_agencias`
--
DROP TABLE IF EXISTS `vw_retorna_agencias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_retorna_agencias`  AS SELECT `a`.`id` AS `id_agencia`, `a`.`nome` AS `nome_agencia`, `a`.`biografia` AS `biografia_agencia`, `a`.`link` AS `link_agencia`, `a`.`cidades_id` AS `cidade_agencia`, `a`.`markers_id` AS `marker_agencia`, `b`.`caminho` AS `caminho_imagem` FROM (`agencias` `a` left join `imagens` `b` on(`a`.`id` = `b`.`agencias_id`)) GROUP BY `a`.`id` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_retorna_atores`
--
DROP TABLE IF EXISTS `vw_retorna_atores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_retorna_atores`  AS SELECT `a`.`id` AS `id_ator`, `a`.`nome` AS `nome_ator`, `a`.`biografia` AS `biografia_ator`, `a`.`link` AS `link_ator`, `a`.`cidades_id` AS `cidade_ator`, `a`.`idade` AS `idade_ator`, `a`.`altura` AS `altura_ator`, `a`.`cache` AS `cache_ator`, `a`.`linguas` AS `lingua_ator`, `a`.`genero` AS `genero_ator`, `a`.`markers_id` AS `marker_ator`, `b`.`caminho` AS `caminho_imagem` FROM (`atores` `a` left join `imagens` `b` on(`a`.`id` = `b`.`atores_id`)) GROUP BY `a`.`id` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_retorna_categorias`
--
DROP TABLE IF EXISTS `vw_retorna_categorias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_retorna_categorias`  AS SELECT `categorias`.`id` AS `Id_Categoria`, `categorias`.`nome` AS `Nome_Categoria`, `categorias`.`link` AS `Link_Categoria` FROM `categorias` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_retorna_cidade`
--
DROP TABLE IF EXISTS `vw_retorna_cidade`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_retorna_cidade`  AS SELECT `cidades`.`id` AS `id_cidade`, `cidades`.`cidade` AS `nome_cidade`, `cidades`.`link` AS `link_cidade` FROM `cidades` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_retorna_cidades`
--
DROP TABLE IF EXISTS `vw_retorna_cidades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_retorna_cidades`  AS SELECT `cidades`.`id` AS `id_cidade`, `cidades`.`cidade` AS `nome_cidade`, `cidades`.`link` AS `link_cidade` FROM `cidades` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_retorna_markers`
--
DROP TABLE IF EXISTS `vw_retorna_markers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_retorna_markers`  AS SELECT `markers`.`id` AS `id_marker`, `markers`.`name` AS `name_marker`, `markers`.`address` AS `address_marker`, `markers`.`lat` AS `latitude`, `markers`.`lng` AS `longitude`, `markers`.`type` AS `type_marker` FROM `markers` ;

-- --------------------------------------------------------

--
-- Estrutura para vista `vw_usuarios`
--
DROP TABLE IF EXISTS `vw_usuarios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_usuarios`  AS SELECT `usuarios`.`id` AS `id_usuario`, `usuarios`.`nome` AS `nome_usuario`, `usuarios`.`login` AS `login_usuario`, `usuarios`.`email` AS `email_usuario`, `usuarios`.`senha` AS `senha_usuario`, `usuarios`.`salt` AS `salt_usuario`, `usuarios`.`nivel` AS `nivel_usuario` FROM `usuarios` ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `agencias`
--
ALTER TABLE `agencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_agencias_cidades` (`cidades_id`),
  ADD KEY `fk_agencias_markers` (`markers_id`);

--
-- Índices para tabela `atores`
--
ALTER TABLE `atores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_atores_cidades` (`cidades_id`),
  ADD KEY `fk_atores_markers` (`markers_id`);

--
-- Índices para tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cidades`
--
ALTER TABLE `cidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_imagens_ofertas` (`ofertas_id`),
  ADD KEY `fk_imagens_produtoras` (`produtoras_id`),
  ADD KEY `fk_imagens_agencias` (`agencias_id`),
  ADD KEY `fk_imagens_atores` (`atores_id`);

--
-- Índices para tabela `markers`
--
ALTER TABLE `markers`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ofertas`
--
ALTER TABLE `ofertas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ofertas_categorias` (`categorias_id`);

--
-- Índices para tabela `ofertas_agencias`
--
ALTER TABLE `ofertas_agencias`
  ADD PRIMARY KEY (`ofertas_id`,`agencias_id`),
  ADD KEY `fk_ofertas_agencias_agencias` (`agencias_id`);

--
-- Índices para tabela `ofertas_atores`
--
ALTER TABLE `ofertas_atores`
  ADD PRIMARY KEY (`ofertas_id`,`atores_id`),
  ADD KEY `fk_ofertas_atores_atores` (`atores_id`);

--
-- Índices para tabela `ofertas_categorias`
--
ALTER TABLE `ofertas_categorias`
  ADD PRIMARY KEY (`ofertas_id`,`categorias_id`),
  ADD KEY `fk_ofertas_categorias_categorias` (`categorias_id`);

--
-- Índices para tabela `ofertas_produtoras`
--
ALTER TABLE `ofertas_produtoras`
  ADD PRIMARY KEY (`ofertas_id`,`produtoras_id`),
  ADD KEY `fk_ofertas_produtoras_produtoras` (`produtoras_id`);

--
-- Índices para tabela `produtoras`
--
ALTER TABLE `produtoras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produtoras_cidades` (`cidades_id`),
  ADD KEY `fk_produtoras_markers` (`markers_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agencias`
--
ALTER TABLE `agencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `atores`
--
ALTER TABLE `atores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `cidades`
--
ALTER TABLE `cidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `markers`
--
ALTER TABLE `markers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT de tabela `ofertas`
--
ALTER TABLE `ofertas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `produtoras`
--
ALTER TABLE `produtoras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agencias`
--
ALTER TABLE `agencias`
  ADD CONSTRAINT `fk_agencias_cidades` FOREIGN KEY (`cidades_id`) REFERENCES `cidades` (`id`),
  ADD CONSTRAINT `fk_agencias_markers` FOREIGN KEY (`markers_id`) REFERENCES `markers` (`id`);

--
-- Limitadores para a tabela `atores`
--
ALTER TABLE `atores`
  ADD CONSTRAINT `fk_atores_markers` FOREIGN KEY (`markers_id`) REFERENCES `markers` (`id`);

--
-- Limitadores para a tabela `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `fk_imagens_agencias` FOREIGN KEY (`agencias_id`) REFERENCES `agencias` (`id`),
  ADD CONSTRAINT `fk_imagens_atores` FOREIGN KEY (`atores_id`) REFERENCES `atores` (`id`),
  ADD CONSTRAINT `fk_imagens_ofertas` FOREIGN KEY (`ofertas_id`) REFERENCES `ofertas` (`id`),
  ADD CONSTRAINT `fk_imagens_produtoras` FOREIGN KEY (`produtoras_id`) REFERENCES `produtoras` (`id`);

--
-- Limitadores para a tabela `ofertas`
--
ALTER TABLE `ofertas`
  ADD CONSTRAINT `fk_ofertas_categorias` FOREIGN KEY (`categorias_id`) REFERENCES `categorias` (`id`);

--
-- Limitadores para a tabela `ofertas_agencias`
--
ALTER TABLE `ofertas_agencias`
  ADD CONSTRAINT `fk_ofertas_agencias_agencias` FOREIGN KEY (`agencias_id`) REFERENCES `agencias` (`id`),
  ADD CONSTRAINT `fk_ofertas_agencias_ofertas` FOREIGN KEY (`ofertas_id`) REFERENCES `ofertas` (`id`);

--
-- Limitadores para a tabela `ofertas_atores`
--
ALTER TABLE `ofertas_atores`
  ADD CONSTRAINT `fk_ofertas_atores_atores` FOREIGN KEY (`atores_id`) REFERENCES `atores` (`id`),
  ADD CONSTRAINT `fk_ofertas_atores_ofertas` FOREIGN KEY (`ofertas_id`) REFERENCES `ofertas` (`id`);

--
-- Limitadores para a tabela `ofertas_categorias`
--
ALTER TABLE `ofertas_categorias`
  ADD CONSTRAINT `fk_ofertas_categorias_categorias` FOREIGN KEY (`categorias_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `fk_ofertas_categorias_ofertas` FOREIGN KEY (`ofertas_id`) REFERENCES `ofertas` (`id`);

--
-- Limitadores para a tabela `ofertas_produtoras`
--
ALTER TABLE `ofertas_produtoras`
  ADD CONSTRAINT `fk_ofertas_produtoras_ofertas` FOREIGN KEY (`ofertas_id`) REFERENCES `ofertas` (`id`),
  ADD CONSTRAINT `fk_ofertas_produtoras_produtoras` FOREIGN KEY (`produtoras_id`) REFERENCES `produtoras` (`id`);

--
-- Limitadores para a tabela `produtoras`
--
ALTER TABLE `produtoras`
  ADD CONSTRAINT `fk_produtoras_cidades` FOREIGN KEY (`cidades_id`) REFERENCES `cidades` (`id`),
  ADD CONSTRAINT `fk_produtoras_markers` FOREIGN KEY (`markers_id`) REFERENCES `markers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
