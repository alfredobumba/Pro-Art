CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_cidades`(IN nome_cidade VARCHAR(70), link_cidade VARCHAR(100), OUT saida VARCHAR(80), out saida_rotulo VARCHAR(15))
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