CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_retorna_cidade` AS
    SELECT 
        `cidades`.`id` AS `id_cidade`,
        `cidades`.`cidade` AS `nome_cidade`,
        `cidades`.`link` AS `link_cidade`
    FROM
        `cidades`