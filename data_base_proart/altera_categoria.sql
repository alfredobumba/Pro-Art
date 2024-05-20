CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_altera_categoria`(
    id_categoria INT, 
    nome_categoria VARCHAR(70), 
    OUT saida VARCHAR(80),
    out saida_rotulo VARCHAR(15)
)
BEGIN
	declare
     link_categoria  VARCHAR(100);
     SET  link_categoria = nome_categoria;
     SET link_categoria = CONCAT(link_categoria, '-', NOW());
     SET link_categoria = (SELECT gera_link(link_categoria));
    IF NOT EXISTS (SELECT * FROM categorias 
        WHERE nome = nome_categoria) THEN
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
END