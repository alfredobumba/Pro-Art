DELIMITER $$
CREATE PROCEDURE sp_deleta_categoria(id_categoria int, OUT saída VARCHAR(80))
BEGIN
	IF NOT EXISTS (SELECT * FROM categorias WHERE id = id_categoria) THEN
    BEGIN
		SET saída = "OPS! Categoria não encontrada!.";
    END;
	ELSE
		DELETE from categorias WHERE id = id_categoria;
         IF ROW_COUNT() = 0 THEN
			SET saída = "ERRO! A categoria não foi excluída!";
		ELSE
			SET saída = "Tudo certo! categoria  excluída com sucesso";
		END IF ;
	END IF ;
    SELECT saída;
END $$

DELIMITER ;

CALL sp_deleta_categoria(3,@saida);

select * from categorias;