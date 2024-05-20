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