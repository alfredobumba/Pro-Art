
delimiter //
 
 CREATE FUNCTION gera_link(Texto VARCHAR(150))
 RETURNS VARCHAR(150)
 NOT DETERMINISTIC
 BEGIN
 DECLARE Resultado VARCHAR(150);


 SET Resultado   = UPPER(Texto); 
 SET Resultado = REPLACE(Resultado,' ','-');
 SET Resultado = REPLACE(Resultado,'\'','');
 SET Resultado = REPLACE(Resultado,'`','');
 SET Resultado = REPLACE(Resultado,'.','');
 
 SET Resultado = REPLACE(Resultado,'À','A');
 SET Resultado = REPLACE(Resultado,'Á','A');
 SET Resultado = REPLACE(Resultado,'Â','A');
 SET Resultado = REPLACE(Resultado,'Ã','A');
 SET Resultado = REPLACE(Resultado,'Ä','A');
 SET Resultado = REPLACE(Resultado,'Å','A');
 
 SET Resultado = REPLACE(Resultado,'È','E');
 SET Resultado = REPLACE(Resultado,'É','E');
 SET Resultado = REPLACE(Resultado,'Ê','E');
 SET Resultado = REPLACE(Resultado,'Ë','E');
 
 SET Resultado = REPLACE(Resultado,'Ì','I');
 SET Resultado = REPLACE(Resultado,'Í','I');
 SET Resultado = REPLACE(Resultado,'Î','I');
 SET Resultado = REPLACE(Resultado,'Ï','I');
 
 SET Resultado = REPLACE(Resultado,'Ò','O');
 SET Resultado = REPLACE(Resultado,'Ó','O');
 SET Resultado = REPLACE(Resultado,'Ô','O');
 SET Resultado = REPLACE(Resultado,'Õ','O');
 SET Resultado = REPLACE(Resultado,'Ö','O');
 
 SET Resultado = REPLACE(Resultado,'Ù','U');
 SET Resultado = REPLACE(Resultado,'Ú','U');
 SET Resultado = REPLACE(Resultado,'Û','U');
 SET Resultado = REPLACE(Resultado,'Ü','U');
 
 SET Resultado = REPLACE(Resultado,'Ø','O');
 
 SET Resultado = REPLACE(Resultado,'Æ','A');
 SET Resultado = REPLACE(Resultado,'Ð','D');
 SET Resultado = REPLACE(Resultado,'Ñ','N');
 SET Resultado = REPLACE(Resultado,'Ý','Y');
 SET Resultado = REPLACE(Resultado,'Þ','B');
 SET Resultado = REPLACE(Resultado,'ß','S');
 
 SET Resultado = REPLACE(Resultado,'Ç','C');
 
 RETURN LOWER(Resultado);
  
 END; //
delimiter ;
