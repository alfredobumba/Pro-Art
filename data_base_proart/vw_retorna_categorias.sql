CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_retorna_categorias` AS
    SELECT 
        `categorias`.`id` AS `Id_Categoria`,
        `categorias`.`nome` AS `Nome_Categoria`,
        `categorias`.`link` AS `Link_Categoria`
    FROM
        `categorias`