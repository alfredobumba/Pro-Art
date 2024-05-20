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