select* FROM categorias;

CALL sp_cadastra_categoria("Televisão", "televisao" ,@saida);

CALL sp_altera_categoria(5, "Teatro", "teatro" ,@saida);

CALL sp_deleta_categoria(5,@saida);

SELECT @saida;
