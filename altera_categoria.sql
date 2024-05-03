DELIMITER $$
CREATE PROCEDURE sp_altera_categoria(
    id_categoria INT, 
    nome_categoria VARCHAR(70), 
    link_categoria VARCHAR(100), 
    OUT saída VARCHAR(80)
)
BEGIN
    IF NOT EXISTS (
        SELECT * FROM categorias 
        WHERE nome = nome_categoria OR link = link_categoria
    ) THEN
        UPDATE categorias
        SET nome = nome_categoria, link = link_categoria
        WHERE id = id_categoria;
        
        IF ROW_COUNT() = 0 THEN
            SET saída = "ERRO! Nenhuma categoria foi alterada!";
        ELSE
            SET saída = "Tudo certo! Categoria alterada com sucesso";
        END IF;
    ELSE
        SET saída = "OPS! Essa categoria já existe!";
    END IF;
    
    SELECT saída;
END$$

DELIMITER ;

CALL sp_altera_categoria(3, 'Romance', 'romance', @saida);



select * from categorias;
