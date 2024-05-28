CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cadastra_imagem`(
    IN p_ofertas_id INT,
    IN p_caminho VARCHAR(100),
    IN p_link VARCHAR(100),
    IN p_produtoras_id INT,
    IN p_agencias_id INT,
    IN p_atores_id INT,
    OUT saida VARCHAR(80),
    OUT rotulo VARCHAR(15)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION 
    BEGIN
        SET saida = 'Erro ao cadastrar imagem';
        SET rotulo = 'ERRO!';
        ROLLBACK;
    END;

    START TRANSACTION;

    IF (SELECT COUNT(*) FROM ofertas WHERE id = p_ofertas_id) = 0 THEN
        SET saida = 'ID da oferta não existe';
        SET rotulo = 'ERRO!';
    ELSEIF (SELECT COUNT(*) FROM produtoras WHERE id = p_produtoras_id) = 0 THEN
        SET saida = 'ID da produtora não existe';
        SET rotulo = 'ERRO!';
    ELSEIF (SELECT COUNT(*) FROM agencias WHERE id = p_agencias_id) = 0 THEN
        SET saida = 'ID da agência não existe';
        SET rotulo = 'ERRO!';
    ELSEIF (SELECT COUNT(*) FROM atores WHERE id = p_atores_id) = 0 THEN
        SET saida = 'ID do ator não existe';
        SET rotulo = 'ERRO!';
    ELSE
        INSERT INTO imagens (caminho, link, ofertas_id, produtoras_id, agencias_id, atores_id) 
        VALUES (p_caminho, p_link, p_ofertas_id, p_produtoras_id, p_agencias_id, p_atores_id);

        IF ROW_COUNT() > 0 THEN
            SET saida = 'Imagem cadastrada com sucesso';
            SET rotulo = 'SUCESSO!';
            COMMIT;
        ELSE
            SET saida = 'Erro ao cadastrar imagem';
            SET rotulo = 'ERRO!';
            ROLLBACK;
        END IF;
    END IF;
END