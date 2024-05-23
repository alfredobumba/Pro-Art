CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_altera_cidade`(
    id_cidade INT, 
    nome_cidade VARCHAR(70), 
    OUT saida VARCHAR(80),
    out saida_rotulo VARCHAR(15)
)
BEGIN
	declare
     link_cidade  VARCHAR(100);
     SET  link_cidade = nome_cidade;
     SET link_cidade = CONCAT(link_cidade, '-', NOW());
     SET link_cidade = (SELECT gera_link(link_cidade));
    IF NOT EXISTS (SELECT * FROM cidades 
        WHERE cidade = nome_cidade) THEN
        UPDATE cidades
        SET cidade = nome_cidade, link = link_cidade
        WHERE id = id_cidade;
        
        IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = "ERRO!";
            SET saida = "Nenhuma cidade foi alterada!";
        ELSE
			SET saida_rotulo = "Tudo certo!";
            SET saida = "Cidade alterada com sucesso!";
        END IF;
    ELSE
		SET saida_rotulo = "OPS!";
        SET saida = "Essa cidade já existe!";
    END IF;
    
    SELECT saida_rotulo, saida;
END