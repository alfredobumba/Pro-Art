DELIMITER $$
CREATE PROCEDURE sp_cadastra_categoria(IN nome_categoria VARCHAR(70), link_categoria VARCHAR(100), OUT saída VARCHAR(80))
BEGIN
	IF NOT EXISTS (SELECT * FROM categorias WHERE nome = nome_categoria OR link =  link_categoria) THEN
    BEGIN
		INSERT INTO categorias (nome, link)
        VALUES (nome_categoria, link_categoria);
        
        IF ROW_COUNT() = 0 THEN
			SET saída = "ERRO! A categoria não foi cadastrada";
		ELSE
			SET saída = "Tudo certo! categoria cadastrada com sucesso";
		END IF ;
    END;
	ELSE
		SET saída = "OPS! Essa categoria já existe.";
	END IF ;
    SELECT saída;
END $$

DELIMITER ;

CALL sp_cadastra_categoria("Teatro" ,  "teatro" ,@saida);
CALL sp_cadastra_categoria("Cinema" ,  "cinema" ,@saida);

select * from categorias;