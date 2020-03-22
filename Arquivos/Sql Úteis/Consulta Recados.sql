select msg.ID_RECADO_PRIMEIRA_ORIGEM,
 (SELECT MENSAGEM FROM tb_social_recados WHERE ID_RECADO = msg.ID_RECADO_PRIMEIRA_ORIGEM LIMIT 1) AS MS2
 ,msg.FL_COMPARTILHAMENTO
 ,msg.FL_PRIVADA
 ,msg.ID_RECADO
 ,msg.ID_RECADO_COMPARTILHADO
 ,msg.MENSAGEM
 ,comparrecado.MENSAGEM as MENSAGEMCOMPART
 ,curtir.QUEM_CURTIU
 ,msg.CURTIDAS
 ,msg.COMPARTILHAMENTOS
 ,msg.ID_RECADO as MENSAGEM_CODIGO
 ,msg.MENSAGEM as MENSAGEM
 ,msg.DT_CADASTRO as DATA
 ,userremet.ID_USUARIO as REMETENTE_ID
 ,userremet.NOME as REMETENTE_NOME
 ,userremet.SLUG as REMETENTE_SLUG
 ,fotoremet.ARQUIVO as REMETENTE_FOTO
 ,userdest.ID_USUARIO as DESTINATARIO_ID
 ,userdest.NOME as DESTINATARIO_NOME
 ,userdest.SLUG as DESTINATARIO_SLUG
 ,fotodest.ARQUIVO as DESTINATARIO_FOTO
  #Compartilhamento 
 ,comparuser.ID_USUARIO as DESTINATARIOCOMPART_ID
 ,comparuser.NOME as DESTINATARIOCOMPART_NOME
 ,comparuser.SLUG as DESTINATARIOCOMPART_SLUG
 ,comparfoto.ARQUIVO as DESTINATARIOCOMPART_FOTO
 ,msg.MENSAGEM_COMPARTILHAMENTO as msgcompart
 ,msg.DT_COMPARTILHAMENTO 
 from 
 	tb_social_recados as msg 
	inner join tb_glove_perfil as perfilremet on perfilremet.ID_USUARIO = msg.ID_USUARIO_REMETENTE 
	inner join tb_sys_usuario as userremet on userremet.ID_USUARIO = perfilremet.ID_USUARIO 
	left join tb_social_foto as fotoremet on fotoremet.ID_FOTO = perfilremet.ID_FOTOPERFIL 
	inner join tb_glove_perfil as perfildest on perfildest.ID_USUARIO = msg.ID_USUARIO_DESTINATARIO 
	inner join tb_sys_usuario as userdest on userdest.ID_USUARIO = perfildest.ID_USUARIO 
	left join tb_social_foto as fotodest on fotodest.ID_FOTO = perfilremet.ID_FOTOPERFIL 
	left join tb_socialrecado_curtir as curtir on msg.ID_RECADO = curtir.ID_RECADO AND curtir.QUEM_CURTIU = '1' 
	left join tb_social_recados as comparrecado on msg.ID_RECADO_COMPARTILHADO = msg.ID_RECADO 
	left join tb_glove_perfil as comparperfil on comparperfil.ID_USUARIO = msg.ID_USUARIO_COMPARTILHOU 
	left join tb_sys_usuario as comparuser on comparuser.ID_USUARIO = comparperfil.ID_USUARIO 
	left join tb_social_foto as comparfoto on comparfoto.ID_FOTO = comparperfil.ID_FOTOPERFIL 
where 1=1 
and userremet.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '1') 
or userremet.ID_USUARIO = '1' 
and msg.FL_PRIVADA = 0 
order by msg.DT_CADASTRO DESC LIMIT 0, 20;