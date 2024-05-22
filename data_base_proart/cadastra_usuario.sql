DELIMITER $$

CREATE PROCEDURE sp_cadastra_usuario
(
nome_usuario VARCHAR(70), login_usuario VARCHAR(30), email_usuario VARCHAR(50), senha_usuario VARCHAR(60),
salt_usuario VARCHAR(20), nivel_usuario CHAR(1), OUT saida VARCHAR(80),  OUT saida_rotulo VARCHAR(15)
)
BEGIN
	IF EXISTS(SELECT * FROM usuarios WHERE login = login_usuario) THEN
    BEGIN
		SET saida_rotulo ='OPS!';
		SET saida ='Este login já está cadrastrada!';
    END;
    ELSEIF EXISTS(SELECT * FROM usuarios WHERE email = email_usuario) THEN
    BEGIN
		SET saida_rotulo ='OPS!';
		SET saida ='Este E-mail já está cadrastrada!';
    END;
    ELSE
		INSERT INTO usuarios (nome, login, email, senha, salt, nivel)
        VALUES (nome_usuario, login_usuario, email_usuario,  senha_usuario, salt_usuario, nivel_usuario);
        
        IF ROW_COUNT() = 0 THEN
			SET saida_rotulo = 'ERRO!';
            SET saida = 'O usuario não foi cadastrado!';
            
		ELSE
			SET saida_rotulo = 'Tudo certo!';
            SET saida = 'Usuario cadastrado com sucesso!';
		END IF;
	END IF;
            
	SELECT saida_rotulo, saida;
END $$

DELIMITER ;

CALL sp_cadastra_usuario ( 'Davidson delo', 'Davidsondelo', 'Davidsondelo@gmail.com','123', '123', 2, @saida,@rotulo);