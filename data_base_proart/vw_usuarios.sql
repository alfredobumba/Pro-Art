
create or replace view vw_usuarios as
SELECT id as id_usuario, nome as nome_usuario, login login_usuario, email email_usuario, senha senha_usuario, salt salt_usuario, nivel nivel_usuario
FROM usuarios;