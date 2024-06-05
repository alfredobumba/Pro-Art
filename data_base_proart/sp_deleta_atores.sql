CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_deleta_atores`(IN `id_ator` INT, OUT `saida` VARCHAR(80), OUT `saida_rotulo` VARCHAR(15))
BEGIN
    -- Verificar se o ator está cadastrado
    IF NOT EXISTS (SELECT 1 FROM atores WHERE id = id_ator) THEN
        SET saida_rotulo = 'OPS!';
        SET saida = 'Ator/Atriz não encontrado(a)!';
    ELSE
		 -- remover ator da tabela ofertas_atores
         DELETE FROM ofertas_atores WHERE atores_id = id_ator;
        -- Primeiro, remover as imagens do ator
        DELETE FROM imagens WHERE atores_id = id_ator;
        
        -- Depois deletar o ator
        DELETE FROM atores WHERE id = id_ator;
        
        IF ROW_COUNT() = 0 THEN
            SET saida_rotulo = 'ERRO!';
            SET saida = 'Ator/Atriz não foi excluido(a)!';
        ELSE
            SET saida_rotulo = 'Tudo certo!';
            SET saida = 'Ator/Atriz excluido(a) com sucesso!';
        END IF;
    END IF;
    
    -- Selecionar os resultados para saída
    SELECT saida_rotulo, saida;
END