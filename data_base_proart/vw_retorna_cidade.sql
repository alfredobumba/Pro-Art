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



        CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_retorna_markers` AS
    SELECT 
        `markers`.`id` AS `id_marker`,
        `markers`.`name` AS `name_marker`,
        `markers`.`address` AS`address_marker`,
        `markers`.`lat` AS `latitude`,
        `markers`.`lng` AS `longitude`,
        `markers`.`type` AS `type_marker`
    FROM
        `markers`;
