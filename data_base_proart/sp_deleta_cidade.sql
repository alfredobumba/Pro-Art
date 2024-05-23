CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_cidade`(id_cidade int, OUT saida VARCHAR(80), out saida_rotulo VARCHAR(15))
BEGIN
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
END