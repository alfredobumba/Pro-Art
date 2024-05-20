CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_ofertas_categorias` AS
    SELECT 
        `ofertas_categorias`.`ofertas_id` AS `id_oferta`,
        `ofertas_categorias`.`categorias_id` AS `id_categoria`
    FROM
        `ofertas_categorias`