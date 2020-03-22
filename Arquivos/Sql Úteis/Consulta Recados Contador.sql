select COUNT(*) as total from
(
(select 
	 perfil1.ID_USUARIO AS QUEM_CURTIU_ID
	 ,usuario1.NOME AS QUEM_CURTIU_NOME
	 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
	 ,'curtiu uma publicação de' AS MSG
 	 ,perfil2.ID_USUARIO AS DONO_ID
	 ,usuario2.NOME AS DONO_NOME
	 ,foto2.ARQUIVO AS DONO_FOTO
	 ,curtir.DT_CADASTRO AS DATA_HORA_ACAO
	 ,'' AS DETALHES
from tb_socialrecado_curtir as curtir
inner join tb_social_recados as recado on curtir.ID_RECADO = recado.ID_RECADO

#QUEM CURTIU
inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = curtir.QUEM_CURTIU
left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO

#DONO DA ATUALIZAÇÂO
inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_REMETENTE
left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
where 1 = 1
)
UNION ALL
(SELECT 
	
		 perfil1.ID_USUARIO AS QUEM_CURTIU_ID
	 ,usuario1.NOME AS QUEM_CURTIU_NOME
	 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
	 ,'curtiu uma publicação de' AS MSG
 	 ,perfil2.ID_USUARIO AS DONO_ID
	 ,usuario2.NOME AS DONO_NOME
	 ,foto2.ARQUIVO AS DONO_FOTO
	 ,comentarios.DT_CADASTRO AS DATA_HORA_ACAO
	 ,comentarios.COMENTARIO AS DETALHES
	from 
	
	tb_social_comentarios as comentarios
	inner join tb_social_recados as recado on comentarios.ID_RECADO = recado.ID_RECADO
	#QUEM CURTIU
	inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = comentarios.ID_USUARIO
	left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
	inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
	
	#DONO DA ATUALIZAÇÂO
	inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_REMETENTE
	left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
	inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
	
 )
 ) as uniao 
 where 1 = 1
 and NOW() <= DATE_ADD(uniao.DATA_HORA_ACAO,INTERVAL 120 MINUTE)
 order by uniao.DATA_HORA_ACAO DESC;
