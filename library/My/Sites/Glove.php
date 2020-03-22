<?php
class My_Sites_Glove extends Zend_Controller_Plugin_Abstract
{
 	
 
	public static function urldinamica($url, $trocar=array(), $limitador='-') {
		if( !empty($trocar) ) {
			$url = str_replace((array)$trocar, ' ', $url);
			$url = ltrim($url);
			$url = rtrim($url);
		}
	 	setlocale(LC_ALL, 'en_US.UTF8');
		$url_limpa = iconv('UTF-8', 'ASCII//TRANSLIT', $url);
		$url_limpa = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $url_limpa);
		$url_limpa = strtolower(trim($url_limpa, '-'));
		$url_limpa = preg_replace("/[\/_|+ -]+/", $limitador, $url_limpa);
	 
		return $url_limpa;
	}
	
	
	
	public static function perfil($id_usuario)
	{
		$obj = new Tb_glove_perfil();
		$idade = new Tb_glove_idade();
		$altura = new Tb_glove_altura();
		$peso = new Tb_glove_peso();
		$tipo = new Tb_glove_tipo();
		$situacao = new Tb_glove_situacao();
		$fumante = new Tb_glove_fumante();
		$interesse = new Tb_glove_insteresse();
		$disponibilidade = new Tb_glove_disponibilidade();
		$bebidas = new Tb_glove_bebidas();
		$usuario = new Tb_sys_usuario();
		$foto_perfil = new Tb_social_foto();
		
		$estado = new Tb_sys_estado();
		$cidade = new Tb_sys_cidade();

		$cidade->join($estado);
		
		$usuario->join($cidade, "LEFT");
		$obj->join($idade, "LEFT");
		$obj->join($altura, "LEFT");
		$obj->join($peso, "LEFT");
		$obj->join($tipo, "LEFT");
		$obj->join($situacao, "LEFT");
		$obj->join($fumante, "LEFT");
		$obj->join($interesse, "LEFT");
		$obj->join($disponibilidade, "LEFT");
		$obj->join($bebidas, "LEFT");
		$obj->join($usuario, "LEFT");
		$obj->join($foto_perfil, "LEFT");
		
		
		$obj->selectAs();	
		$obj->selectAs($idade, "%s_idade");
		$obj->selectAs($altura, "%s_altura");
		$obj->selectAs($peso, "%s_peso");
		$obj->selectAs($tipo, "%s_tipo");
		$obj->selectAs($situacao, "%s_situacao");
		$obj->selectAs($fumante, "%s_fumante");
		$obj->selectAs($interesse, "%s_interesse");
		$obj->selectAs($disponibilidade, "%s_disponibilidade");
		$obj->selectAs($bebidas, "%s_bebidas");
		$obj->selectAs($usuario, "%s_usuario");
		$obj->selectAs($estado, "%s_estado");
		$obj->selectAs($cidade, "%s_cidade");
		$obj->selectAs($foto_perfil, "%s_fotoperfil");
		
		
		$obj->where("{tb_sys_usuario} = $id_usuario");
		
		$obj->find(true);
		
		$dados = $obj->toObject();
		
		return $dados;
		
		
	}
	
	
	
	public static function albumPadrao($sessao_usuario)
	{
		$obj = new Tb_social_albumfotos();
		$obj->where("ID_USUARIO = '".$sessao_usuario."' AND FL_PADRAO = 1");
		$obj->find(true);
		return $obj->toObject();
	}
	
	public static function fotoAlbum($id_album)
	{
		$obj = new Tb_social_foto();
		$obj->select("ARQUIVO");
		$obj->where("ID_ALBUM = $id_album");
		$obj->orderBy("DT_CADASTRO DESC");
		$obj->limit(1);
		$obj->find(true);
		return $obj->ARQUIVO;
		
	}
	
	public static function albuns($sessao_usuario)
	{
		$obj = new Tb_social_albumfotos();
		$obj->where("ID_USUARIO = '".$sessao_usuario."'");
		$obj->orderBy("ID_ALBUM DESC");
		$obj->find(true);
		return $obj->allToObject();
		
		
	}
	
	
	
	public static function contasolicitacoes($sessao_usuario)
	{
		$obj = new Tb_glove_perfil();
    	$sql = "SELECT COUNT(*) as total FROM tb_social_amigos 
    			INNER JOIN tb_sys_usuario ON tb_social_amigos.ID_USUARIO = tb_sys_usuario.ID_USUARIO
    			INNER JOIN tb_glove_perfil ON tb_glove_perfil.ID_USUARIO = tb_social_amigos.ID_USUARIO
    			LEFT JOIN tb_social_foto ON tb_social_foto.ID_FOTO = tb_glove_perfil.ID_FOTOPERFIL
    			WHERE 
    			tb_social_amigos.ID_AMIGO = '".$sessao_usuario."' 
    			AND FL_ACEITOU = 0 
    			ORDER BY tb_social_amigos.DT_CADASTRO DESC";
    	$obj->query($sql);
    	$obj->fetch();
    	$total = $obj->total; 
    	
    	$obj->_getConnection()->close();
    	$obj->destroy();
    	
    	return $total;
	}
	
	public static function configuracoes($usuario_logado)
	{
		$obj = new Tb_glove_perfil();
		$obj->get($usuario_logado);
		return $obj->toObject();
		
	}
	
	public static function contaatualizacoes($usuario_logado)
	{
		$obj = new Tb_glove_perfil();
    	$sql = "select COUNT(*) as total, DATA_HORA_ACAO from
				(
				
				(select 
				     recado.ID_RECADO AS ID,
					 'curtir_publicacao' AS TIPO
					 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
					 ,usuario1.NOME AS QUEM_CURTIU_NOME
					 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'curtiu sua própria publicação', 'curtiu uma publicação de') AS MSG
				 	 ,perfil2.ID_USUARIO AS DONO_ID
					 ,usuario2.NOME AS DONO_NOME
					 ,foto2.ARQUIVO AS DONO_FOTO
					 ,curtir.DT_CADASTRO AS DATA_HORA_ACAO
					 ,recado.MENSAGEM AS DETALHES
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
				#AND perfil2.ID_USUARIO = '$usuario_logado' 
				#OR perfil1.ID_USUARIO = '$usuario_logado'
				AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
				)
				
				UNION ALL
				
				(select 
					 recado.ID_RECADO AS ID,
					 'curtir_comentario' AS TIPO
					 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
					 ,usuario1.NOME AS QUEM_CURTIU_NOME
					 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'curtiu seu próprio comentário', 'curtiu um comentário de') AS MSG
				 	 ,perfil2.ID_USUARIO AS DONO_ID
					 ,usuario2.NOME AS DONO_NOME
					 ,foto2.ARQUIVO AS DONO_FOTO
					 ,curtir.DT_CADASTRO AS DATA_HORA_ACAO
					 ,comentario.COMENTARIO AS DETALHES
				from tb_socialcomentario_curtir as curtir
				inner join tb_social_comentarios as comentario on curtir.ID_COMENTARIO = comentario.ID_COMENTARIO
				inner join tb_social_recados as recado on recado.ID_RECADO = comentario.ID_RECADO
				
				#QUEM CURTIU
				inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = curtir.QUEM_CURTIU
				left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
				inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
				
				#DONO DA ATUALIZAÇÂO
				inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = comentario.ID_USUARIO
				left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
				inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
				where 1 = 1
				#AND perfil2.ID_USUARIO = $usuario_logado
				#OR perfil1.ID_USUARIO = $usuario_logado
				AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
				)
		
				
				UNION ALL
				(SELECT 
					recado.ID_RECADO AS ID,
					'comentario' AS TIPO
					 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
					 ,usuario1.NOME AS QUEM_CURTIU_NOME
					 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
				 	 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'comentou sua própria publicação', 'comentou uma publicação de') AS MSG
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
					where 1 = 1
					#AND perfil2.ID_USUARIO = $usuario_logado
  					#OR perfil1.ID_USUARIO = $usuario_logado	
  					AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)			
				 )
				 
				 	UNION ALL
					(SELECT 
						recado.ID_RECADO AS ID,
						'status' AS TIPO
						 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
						 ,usuario1.NOME AS QUEM_CURTIU_NOME
						 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 	 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'postou em seu mural', 'postou no mural de') AS MSG
					 	 ,perfil2.ID_USUARIO AS DONO_ID
						 ,usuario2.NOME AS DONO_NOME
						 ,foto2.ARQUIVO AS DONO_FOTO
						 ,recado.DT_CADASTRO AS DATA_HORA_ACAO
						 ,recado.MENSAGEM AS DETALHES
	
						 from tb_social_recados  as recado
						inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = recado.ID_USUARIO_REMETENTE
						left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
						inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
						
						inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_DESTINATARIO
						left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
						inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
						
						where id_usuario_destinatario = $usuario_logado
						and id_usuario_remetente != $usuario_logado
						and FL_COMPARTILHAMENTO  = 0
						#WHERE id_usuario_destinatario in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)			
					 )
					 
					 
					 UNION ALL
					(SELECT 
						recado.ID_RECADO AS ID,
						'compartilhamento' AS TIPO
						 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
						 ,usuario1.NOME AS QUEM_CURTIU_NOME
						 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 	 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'compartilhou sua publicação', 'compartilhou uma publicação de') AS MSG
					 	 ,perfil2.ID_USUARIO AS DONO_ID
						 ,usuario2.NOME AS DONO_NOME
						 ,foto2.ARQUIVO AS DONO_FOTO
						 ,recado.DT_CADASTRO AS DATA_HORA_ACAO
						 ,recado.MENSAGEM_COMPARTILHAMENTO AS DETALHES
	
						 from tb_social_recados  as recado
						inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = recado.ID_USUARIO_REMETENTE
						left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
						inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
						
						inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_DESTINATARIO
						left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
						inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
						
						where id_usuario_destinatario = $usuario_logado
						and id_usuario_remetente != $usuario_logado
						and FL_COMPARTILHAMENTO  = 1
						#WHERE id_usuario_destinatario in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)			
					 )
				 
				 ) 
				 as uniao 
				 where TIMESTAMPDIFF(MINUTE,DATA_HORA_ACAO,NOW()) <= 40
				 order by uniao.DATA_HORA_ACAO DESC;
				    	";
    	
    	
    	$obj->query($sql);
    	$obj->fetch();
    	$total = $obj->total; 
    	
    	$obj->_getConnection()->close();
    	$obj->destroy();
    	
    	return $total;
	}
	
	
	
	public static function contaatualizacoesPaginacao($usuario_logado)
	{
		$obj = new Tb_glove_perfil();
		$sql = "select COUNT(*) as total, DATA_HORA_ACAO from
		(
	
		(select
		recado.ID_RECADO AS ID,
		'curtir_publicacao' AS TIPO
		,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
		,usuario1.NOME AS QUEM_CURTIU_NOME
		,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
		,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'curtiu sua própria publicação', 'curtiu uma publicação de') AS MSG
		,perfil2.ID_USUARIO AS DONO_ID
		,usuario2.NOME AS DONO_NOME
		,foto2.ARQUIVO AS DONO_FOTO
		,curtir.DT_CADASTRO AS DATA_HORA_ACAO
		,recado.MENSAGEM AS DETALHES
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
		#AND perfil2.ID_USUARIO = '$usuario_logado'
		#OR perfil1.ID_USUARIO = '$usuario_logado'
		AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
		)
	
		UNION ALL
	
		(select
		recado.ID_RECADO AS ID,
		'curtir_comentario' AS TIPO
		,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
		,usuario1.NOME AS QUEM_CURTIU_NOME
		,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
		,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'curtiu seu próprio comentário', 'curtiu um comentário de') AS MSG
		,perfil2.ID_USUARIO AS DONO_ID
		,usuario2.NOME AS DONO_NOME
		,foto2.ARQUIVO AS DONO_FOTO
		,curtir.DT_CADASTRO AS DATA_HORA_ACAO
		,comentario.COMENTARIO AS DETALHES
		from tb_socialcomentario_curtir as curtir
		inner join tb_social_comentarios as comentario on curtir.ID_COMENTARIO = comentario.ID_COMENTARIO
		inner join tb_social_recados as recado on recado.ID_RECADO = comentario.ID_RECADO
	
		#QUEM CURTIU
		inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = curtir.QUEM_CURTIU
		left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
		inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
	
		#DONO DA ATUALIZAÇÂO
		inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = comentario.ID_USUARIO
		left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
		inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
		where 1 = 1
		#AND perfil2.ID_USUARIO = $usuario_logado
		#OR perfil1.ID_USUARIO = $usuario_logado
		AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
		)
	
	
		UNION ALL
		(SELECT
		recado.ID_RECADO AS ID,
		'comentario' AS TIPO
		,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
		,usuario1.NOME AS QUEM_CURTIU_NOME
		,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
		,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'comentou sua própria publicação', 'comentou uma publicação de') AS MSG
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
		where 1 = 1
		#AND perfil2.ID_USUARIO = $usuario_logado
		#OR perfil1.ID_USUARIO = $usuario_logado
		AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
		)
			
		UNION ALL
		(SELECT
		recado.ID_RECADO AS ID,
		'status' AS TIPO
		,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
		,usuario1.NOME AS QUEM_CURTIU_NOME
		,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
		,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'postou em seu mural', 'postou no mural de') AS MSG
		,perfil2.ID_USUARIO AS DONO_ID
		,usuario2.NOME AS DONO_NOME
		,foto2.ARQUIVO AS DONO_FOTO
		,recado.DT_CADASTRO AS DATA_HORA_ACAO
		,recado.MENSAGEM AS DETALHES
	
		from tb_social_recados  as recado
		inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = recado.ID_USUARIO_REMETENTE
		left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
		inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
	
		inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_DESTINATARIO
		left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
		inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
	
		where id_usuario_destinatario = $usuario_logado
		and id_usuario_remetente != $usuario_logado
		and FL_COMPARTILHAMENTO  = 0
		#WHERE id_usuario_destinatario in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
		)
	
	
		UNION ALL
		(SELECT
		recado.ID_RECADO AS ID,
		'compartilhamento' AS TIPO
		,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
		,usuario1.NOME AS QUEM_CURTIU_NOME
		,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
		,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'compartilhou sua publicação', 'compartilhou uma publicação de') AS MSG
		,perfil2.ID_USUARIO AS DONO_ID
		,usuario2.NOME AS DONO_NOME
		,foto2.ARQUIVO AS DONO_FOTO
		,recado.DT_CADASTRO AS DATA_HORA_ACAO
		,recado.MENSAGEM_COMPARTILHAMENTO AS DETALHES
	
		from tb_social_recados  as recado
		inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = recado.ID_USUARIO_REMETENTE
		left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
		inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
	
		inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_DESTINATARIO
		left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
		inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
	
		where id_usuario_destinatario = $usuario_logado
		and id_usuario_remetente != $usuario_logado
		and FL_COMPARTILHAMENTO  = 1
		#WHERE id_usuario_destinatario in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
		)
			
		)
		as uniao
		#where TIMESTAMPDIFF(MINUTE,DATA_HORA_ACAO,NOW()) <= 40
		order by uniao.DATA_HORA_ACAO DESC;
		";
		 
		 
		$obj->query($sql);
				$obj->fetch();
				$total = $obj->total;
				 
				$obj->_getConnection()->close();
				$obj->destroy();
				 
				return $total;
	}
	
	
	public static function amigos($id_usuario, $pagina, $tamanhoPagina)
	{
	    
	    $pagina = isset($pagina) ? $pagina : 1;
	    
	  
	    
	    //$tamanhoPagina = 63;
	     
	    $TAMANHO_PAGINA = $tamanhoPagina;
	    	
	    if($pagina == null)
	    {
	    	$pagina = 1;
	    	$inicio = 0;
	    }else
	    {
	    	$pagina = $pagina;
	    	$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
	    }
	    
	    
	 
	    
	    $obj = new Tb_glove_perfil();
	    $sql = "SELECT count(*) as total
		FROM tb_social_amigos
	    INNER JOIN tb_sys_usuario ON tb_social_amigos.ID_USUARIO = tb_sys_usuario.ID_USUARIO
	    INNER JOIN tb_glove_perfil ON tb_sys_usuario.ID_USUARIO = tb_glove_perfil.ID_USUARIO
	    INNER JOIN tb_sys_cidade ON tb_sys_cidade.ID_CIDADE = tb_sys_usuario.ID_CIDADE
	    INNER JOIN tb_sys_estado ON tb_sys_cidade.ID_ESTADO = tb_sys_estado.ID_ESTADO
	    LEFT JOIN tb_social_foto ON tb_glove_perfil.ID_FOTOPERFIL = tb_social_foto.ID_FOTO
	    WHERE tb_social_amigos.ID_AMIGO = '".$id_usuario."'
	    AND FL_ACEITOU = 1";
	    $obj->query($sql);
	    $obj->fetch();
	    $total = $obj->total;
	    
	  
	    $total_paginas = ceil($total / $TAMANHO_PAGINA);
	    
	    $obj->destroy();
	    
	    
		$obj = new Tb_glove_perfil();
		$sql = "SELECT SLUG, tb_sys_estado.SGL_ESTADO, tb_sys_cidade.NOME_CIDADE, tb_sys_usuario.ID_USUARIO, tb_sys_usuario.NOME, tb_sys_usuario.SOBRENOME,  tb_sys_usuario.SLUG, tb_social_foto.ARQUIVO
		FROM tb_social_amigos
		INNER JOIN tb_sys_usuario ON tb_social_amigos.ID_USUARIO = tb_sys_usuario.ID_USUARIO
		INNER JOIN tb_glove_perfil ON tb_sys_usuario.ID_USUARIO = tb_glove_perfil.ID_USUARIO
		INNER JOIN tb_sys_cidade ON tb_sys_cidade.ID_CIDADE = tb_sys_usuario.ID_CIDADE
		INNER JOIN tb_sys_estado ON tb_sys_cidade.ID_ESTADO = tb_sys_estado.ID_ESTADO
		LEFT JOIN tb_social_foto ON tb_glove_perfil.ID_FOTOPERFIL = tb_social_foto.ID_FOTO
		WHERE tb_social_amigos.ID_AMIGO = '".$id_usuario."' 
		AND FL_ACEITOU = 1
		ORDER BY tb_sys_usuario.NOME ASC LIMIT $inicio, $TAMANHO_PAGINA";
    	
		
		
		
			
    	$obj->query($sql);
    	$obj->fetch();
    	
    	
    
    	
    	
    	/*echo "<pre>";
    	print_r($obj->allToObject());
    	echo "</pre>";
    	
    	die();*/
    	
    	return array("dados" =>$obj->allToObject(), "total" => $total, "total_paginas" => $total_paginas, "TAMANHO_PAGINA" => $TAMANHO_PAGINA);
	}
	
	
	
	public static function fotosgaleria($id_album)
	{
		$obj = new Tb_glove_perfil();
		$sql = "select * from tb_social_foto where ID_ALBUM = '$id_album' order by DT_CADASTRO DESC";
		$obj->query($sql);
		$obj->fetch();
				 
		return $obj->allToObject();
	}
	
	
	public static function foto($id_foto)
	{
		$obj = new Tb_glove_perfil();
		$sql = "select * from tb_social_foto where ID_FOTO = '$id_foto'";
		$obj->query($sql);
		$obj->fetch();
			
		return $obj->toObject();
	}
	
	
	public static function MensagemCurtidaUnica($id, $usuario_logado)
	{
		$obj = new Tb_glove_perfil();
		$sql = "select 
				curtir.QUEM_CURTIU,
				msg.CURTIDAS,
				msg.COMPARTILHAMENTOS,
				msg.ID_RECADO as MENSAGEM_CODIGO,
				msg.MENSAGEM as MENSAGEM,
				msg.DT_CADASTRO as DATA,
				userremet.ID_USUARIO as REMETENTE_ID,
				userremet.NOME as REMETENTE_NOME,
				userremet.SLUG as REMETENTE_SLUG,
				fotoremet.ARQUIVO as REMETENTE_FOTO,
				userdest.ID_USUARIO as DESTINATARIO_ID,
				userdest.NOME as DESTINATARIO_NOME,
				userdest.SLUG as DESTINATARIO_SLUG,
				fotodest.ARQUIVO as DESTINATARIO_FOTO 
				from tb_socialrecado_curtir as curtir 
				inner join tb_social_recados as msg on curtir.ID_RECADO = msg.ID_RECADO 
				inner join tb_glove_perfil as perfilremet on perfilremet.ID_USUARIO = msg.ID_USUARIO_REMETENTE 
				inner join tb_sys_usuario as userremet on userremet.ID_USUARIO = perfilremet.ID_USUARIO 
				left join tb_social_foto as fotoremet on fotoremet.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				inner join tb_glove_perfil as perfildest on perfildest.ID_USUARIO = curtir.QUEM_CURTIU 
				inner join tb_sys_usuario as userdest on userdest.ID_USUARIO = perfildest.ID_USUARIO 
				left join tb_social_foto as fotodest on fotodest.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				where curtir.ID_RECADO = '".$id."' and  curtir.QUEM_CURTIU = '".$usuario_logado."'";
				$obj->query($sql);
				$obj->fetch();
				return $obj->toObject();
	}
	
	public static function mensagens($id_perfil, $page, $tamanho_pagina, $id_mensagem = null)
	{
		
	
    	
    	$pagina = $page;
    	
    	$pagina = isset($pagina) ? $pagina : null;
    	$tamanhoPagina = $tamanho_pagina;
    	
    	$TAMANHO_PAGINA = $tamanhoPagina;
			
		if($pagina == null)
		{
			$pagina = 1;
			$inicio = 0;
		}else
		{
			$pagina = $pagina;
			$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
		}
		
		
		$obj = new Tb_glove_perfil();
		$sql = "select 
				count(*) as total,
				msg.FL_PRIVADA
				from tb_social_recados as msg 
				inner join tb_glove_perfil as perfilremet on perfilremet.ID_USUARIO = msg.ID_USUARIO_REMETENTE 
				inner join tb_sys_usuario as userremet on userremet.ID_USUARIO = perfilremet.ID_USUARIO 
				left join tb_social_foto as fotoremet on fotoremet.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				inner join tb_glove_perfil as perfildest on perfildest.ID_USUARIO = msg.ID_USUARIO_DESTINATARIO 
				inner join tb_sys_usuario as userdest on userdest.ID_USUARIO = perfildest.ID_USUARIO 
				left join tb_social_foto as fotodest on fotodest.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				left join tb_socialrecado_curtir as curtir on msg.ID_RECADO = curtir.ID_RECADO AND curtir.QUEM_CURTIU = '".$id_perfil."'
				left join tb_social_recados as comparrecado on msg.ID_RECADO_COMPARTILHADO = msg.ID_RECADO
				left join tb_glove_perfil as comparperfil on comparperfil.ID_USUARIO = msg.ID_USUARIO_COMPARTILHOU 
				left join tb_sys_usuario as comparuser on comparuser.ID_USUARIO = comparperfil.ID_USUARIO 
				left join tb_social_foto as comparfoto on comparfoto.ID_FOTO = comparperfil.ID_FOTOPERFIL 
				 where 
				 1=1
				 and userremet.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '".$id_perfil."' and fl_aceitou = '1')
				 #and exists ( select 1  from tb_social_amigos where ID_USUARIO = userremet.ID_USUARIO  and fl_aceitou = '1')
				 or userremet.ID_USUARIO = '".$id_perfil."' 
				 having msg.FL_PRIVADA = 0 
				";
				$obj->query($sql);
				$obj->fetch();

			
						
		$total = $obj->total;
		
		
		
		$obj->_getConnection()->close();
		$obj->destroy();
		
		$obj = new Tb_glove_perfil();
		
	
		
		$sql = "select 
				msg.ID_RECADO_PRIMEIRA_ORIGEM,
				(SELECT MENSAGEM FROM tb_social_recados  WHERE ID_RECADO = msg.ID_RECADO_PRIMEIRA_ORIGEM LIMIT 1) AS MS2,
				msg.FL_COMPARTILHAMENTO,
				msg.FL_PRIVADA,
				msg.ID_RECADO,
				msg.ID_RECADO_COMPARTILHADO,
				msg.ID_ALBUM,
				msg.ID_FOTO,
				msg.MENSAGEM,
				comparrecado.MENSAGEM as MENSAGEMCOMPART,
				curtir.QUEM_CURTIU,
				msg.CURTIDAS,
				msg.COMPARTILHAMENTOS,
				msg.ID_RECADO as MENSAGEM_CODIGO,
				msg.MENSAGEM as MENSAGEM,
				msg.DT_CADASTRO as DATA,
				msg.ID_TIPOPUBLICACAO,
				userremet.ID_USUARIO as REMETENTE_ID,
				userremet.NOME as REMETENTE_NOME,
				userremet.SOBRENOME as REMETENTE_SOBRENOME,
				userremet.SLUG as REMETENTE_SLUG,
				fotoremet.ARQUIVO as REMETENTE_FOTO,
				userdest.ID_USUARIO as DESTINATARIO_ID,
				userdest.NOME as DESTINATARIO_NOME,
				userdest.SLUG as DESTINATARIO_SLUG,
				fotodest.ARQUIVO as DESTINATARIO_FOTO,
				#Compartilhamento
				comparuser.ID_USUARIO as DESTINATARIOCOMPART_ID,
				comparuser.NOME as DESTINATARIOCOMPART_NOME,
				comparuser.SLUG as DESTINATARIOCOMPART_SLUG,
				comparfoto.ARQUIVO as DESTINATARIOCOMPART_FOTO,
				msg.MENSAGEM_COMPARTILHAMENTO as msgcompart,
				msg.DT_COMPARTILHAMENTO
				from tb_social_recados as msg 
				left join tb_glove_perfil as perfilremet on perfilremet.ID_USUARIO = msg.ID_USUARIO_REMETENTE 
				left join tb_sys_usuario as userremet on userremet.ID_USUARIO = perfilremet.ID_USUARIO 
				left join tb_social_foto as fotoremet on fotoremet.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				left join tb_glove_perfil as perfildest on perfildest.ID_USUARIO = msg.ID_USUARIO_DESTINATARIO 
				left join tb_sys_usuario as userdest on userdest.ID_USUARIO = perfildest.ID_USUARIO 
				left join tb_social_foto as fotodest on fotodest.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				left join tb_socialrecado_curtir as curtir on msg.ID_RECADO = curtir.ID_RECADO AND curtir.QUEM_CURTIU = '".$id_perfil."'
				left join tb_social_recados as comparrecado on msg.ID_RECADO_COMPARTILHADO = msg.ID_RECADO
				left join tb_glove_perfil as comparperfil on comparperfil.ID_USUARIO = msg.ID_USUARIO_COMPARTILHOU 
				left join tb_sys_usuario as comparuser on comparuser.ID_USUARIO = comparperfil.ID_USUARIO 
				left join tb_social_foto as comparfoto on comparfoto.ID_FOTO = comparperfil.ID_FOTOPERFIL 
				where 
				1=1
				"; 

		
						
		
				if($id_mensagem != null)
				{
				    $sql = $sql . " and msg.ID_RECADO = '".$id_mensagem."'";
				}else
				{
				    
				    $sql = $sql . " and msg.FL_PRIVADA = 0";
				    $sql = $sql . " and (userremet.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '".$id_perfil."' and fl_aceitou = '1')";
				    //$sql = $sql . " and exists ( select 1  from tb_social_amigos where ID_USUARIO = userremet.ID_USUARIO  and fl_aceitou = '1')";
				    $sql = $sql . " or userremet.ID_USUARIO = '".$id_perfil."')";
				}
				
				$orderLimit = " order by msg.ID_RECADO  DESC LIMIT ".$inicio.", ".$TAMANHO_PAGINA.";";
				$sql = $sql . $orderLimit;
		
				
		    /*echo "<pre>";
		    echo $sql;
		    echo "</pre>";
		    
		    die();*/
	
		  
			$obj->query($sql);
			
		
			$total_paginas = ceil($total / $TAMANHO_PAGINA);
   
    		
			return array($obj, $total_paginas, $total, $pagina);
    	
	}
	
	public static function hyperlink($text) {
		$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_+.~#?&//=]+)',
		 '<a href="\1" rel="normal_request" target="_blank">\1</a>', $text);
		 $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_+.~#?&//=]+)',
		 '\1<a href="http://\2" rel=\"normal_request\" target=\"_blank\">\2</a>', $text);
		 $text = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})',
		 '<a href="mailto:\1" rel="normal_request" target="_blank">\1</a>', $text);
		 
		return $text;
	}
	
	
	public static function comentarios($id_post, $page, $tamanho_pagina, $usuario_logado)
	{
		
	
    	
    	$pagina = $page;
    	
    	
    	
    	
    	$pagina = isset($pagina) ? $pagina : null;
    	$tamanhoPagina = $tamanho_pagina;
    	
    	$TAMANHO_PAGINA = $tamanhoPagina;
			
		if($pagina == null)
		{
			$pagina = 1;
			$inicio = 0;
		}else
		{
			$pagina = $pagina;
			$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
		}
		
		
		$comentarios = new Tb_social_comentarios();
		$comentarios->select("COUNT(*) as total");
  		$comentarios->where("ID_RECADO = '$id_post'");
  		$comentarios->orderby("DT_CADASTRO DESC");
  		$comentarios->find(true);  
		$total = $comentarios->total;
		
		
	
		
		
		$comentarios->_getConnection()->close();
		$comentarios->destroy();
		
		$comentarios = new Tb_social_albumfotos();
	
			
	    $sql = "select 
		coments.ID_COMENTARIO,
		users.NOME,
		users.SLUG,
		users.ID_USUARIO,
		foto.ARQUIVO,
		coments.DT_CADASTRO,
		coments.COMENTARIO,
		coments.ID_RECADO,
		curtir.QUEM_CURTIU,
		coments.CURTIDAS
		from tb_social_comentarios as coments
		inner join tb_glove_perfil as perfil on coments.ID_USUARIO = perfil.ID_USUARIO
		inner join tb_sys_usuario as users on users.ID_USUARIO = perfil.ID_USUARIO
		left join tb_social_foto as foto on perfil.ID_FOTOPERFIL = foto.ID_FOTO
		left join tb_socialcomentario_curtir as curtir on curtir.ID_COMENTARIO = coments.ID_COMENTARIO AND curtir.QUEM_CURTIU = '".$usuario_logado."'
		where coments.ID_RECADO = $id_post
		order by coments.DT_CADASTRO DESC LIMIT ".$inicio.", ".$TAMANHO_PAGINA.";";
	
		
	    $comentarios->query($sql);
		$coments = array_reverse($comentarios->allToArray());
		$total_paginas = ceil($total / $TAMANHO_PAGINA);
      
		return array($coments, $total_paginas, $total, $pagina);
    	
	}
	
	
	public static function mensagensOutros($id_perfil, $page, $tamanho_pagina)
	{
		
	
    	
    	$pagina = $page;
    	
    	$pagina = isset($pagina) ? $pagina : null;
    	$tamanhoPagina = $tamanho_pagina;
    	
    	$TAMANHO_PAGINA = $tamanhoPagina;
			
		if($pagina == null)
		{
			$pagina = 1;
			$inicio = 0;
		}else
		{
			$pagina = $pagina;
			$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
		}
		
		
		$obj = new Tb_glove_perfil();
		$sql = "select 
				count(*) as total
				from  tb_social_recados as msg
				inner join tb_glove_perfil as perfilremet on perfilremet.ID_USUARIO = msg.ID_USUARIO_REMETENTE
				inner join tb_sys_usuario as userremet on userremet.ID_USUARIO = perfilremet.ID_USUARIO
				left join tb_social_foto as fotoremet on fotoremet.ID_FOTO = perfilremet.ID_FOTOPERFIL
				
				inner join tb_glove_perfil as perfildest on perfildest.ID_USUARIO = msg.ID_USUARIO_DESTINATARIO
				inner join tb_sys_usuario as userdest on userdest.ID_USUARIO = perfildest.ID_USUARIO
				left join tb_social_foto as fotodest on fotodest.ID_FOTO = perfilremet.ID_FOTOPERFIL
				where userdest.ID_USUARIO = '".$id_perfil."'
				and msg.FL_PRIVADA = 1
				";
				$obj->query($sql);
				$obj->fetch();
		
		$total = $obj->total;
		
		
	$auth = Zend_Auth::getInstance();
	$usuario_logado = $auth->getStorage()->read();

		$obj = new Tb_glove_perfil();
		/*$sql = "select 
			 	curtir.QUEM_CURTIU,
				msg.CURTIDAS,
 				msg.COMPARTILHAMENTOS,
				msg.ID_RECADO as MENSAGEM_CODIGO,
				msg.MENSAGEM as MENSAGEM,
				msg.DT_CADASTRO as DATA,
				userremet.ID_USUARIO as REMETENTE_ID,
				userremet.NOME as REMETENTE_NOME,
				userremet.SLUG as REMETENTE_SLUG,
				fotoremet.ARQUIVO as REMETENTE_FOTO,
				userdest.ID_USUARIO as DESTINATARIO_ID,
				userdest.NOME as DESTINATARIO_NOME,
				userdest.SLUG as DESTINATARIO_SLUG,
				fotodest.ARQUIVO as DESTINATARIO_FOTO
				 
				from  tb_social_recados as msg
				inner join tb_glove_perfil as perfilremet on perfilremet.ID_USUARIO = msg.ID_USUARIO_REMETENTE
				inner join tb_sys_usuario as userremet on userremet.ID_USUARIO = perfilremet.ID_USUARIO
				left join tb_social_foto as fotoremet on fotoremet.ID_FOTO = perfilremet.ID_FOTOPERFIL
				
				inner join tb_glove_perfil as perfildest on perfildest.ID_USUARIO = msg.ID_USUARIO_DESTINATARIO
				inner join tb_sys_usuario as userdest on userdest.ID_USUARIO = perfildest.ID_USUARIO
				left join tb_social_foto as fotodest on fotodest.ID_FOTO = perfilremet.ID_FOTOPERFIL
				left join tb_socialrecado_curtir as curtir on msg.ID_RECADO = curtir.ID_RECADO AND curtir.QUEM_CURTIU = '".$usuario_logado->ID_USUARIO."'
				where userdest.ID_USUARIO = '".$id_perfil."'
				and msg.FL_PRIVADA = 1
				order by msg.DT_CADASTRO DESC LIMIT ".$inicio.", ".$TAMANHO_PAGINA.";";*/
			
				
				$sql = "select 
				msg.ID_RECADO_PRIMEIRA_ORIGEM,
				(SELECT MENSAGEM FROM tb_social_recados  WHERE ID_RECADO = msg.ID_RECADO_PRIMEIRA_ORIGEM LIMIT 1) AS MS2,
				msg.FL_COMPARTILHAMENTO,
				msg.FL_PRIVADA,
				msg.ID_RECADO,
				msg.ID_RECADO_COMPARTILHADO,
				msg.MENSAGEM,
				msg.ID_ALBUM,
				msg.ID_FOTO,
				comparrecado.MENSAGEM as MENSAGEMCOMPART,
				curtir.QUEM_CURTIU,
				msg.CURTIDAS,
				msg.COMPARTILHAMENTOS,
				msg.ID_RECADO as MENSAGEM_CODIGO,
				msg.MENSAGEM as MENSAGEM,
				msg.DT_CADASTRO as DATA,
				msg.ID_TIPOPUBLICACAO,
				userremet.ID_USUARIO as REMETENTE_ID,
				userremet.NOME as REMETENTE_NOME,
				userremet.SOBRENOME as REMETENTE_SOBRENOME,
				userremet.SLUG as REMETENTE_SLUG,
				fotoremet.ARQUIVO as REMETENTE_FOTO,
				userdest.ID_USUARIO as DESTINATARIO_ID,
				userdest.NOME as DESTINATARIO_NOME,
				userdest.SLUG as DESTINATARIO_SLUG,
				fotodest.ARQUIVO as DESTINATARIO_FOTO,
				#Compartilhamento
				comparuser.ID_USUARIO as DESTINATARIOCOMPART_ID,
				comparuser.NOME as DESTINATARIOCOMPART_NOME,
				comparuser.SLUG as DESTINATARIOCOMPART_SLUG,
				comparfoto.ARQUIVO as DESTINATARIOCOMPART_FOTO,
				msg.MENSAGEM_COMPARTILHAMENTO as msgcompart,
				msg.DT_COMPARTILHAMENTO
				from tb_social_recados as msg 
				inner join tb_glove_perfil as perfilremet on perfilremet.ID_USUARIO = msg.ID_USUARIO_REMETENTE 
				inner join tb_sys_usuario as userremet on userremet.ID_USUARIO = perfilremet.ID_USUARIO 
				left join tb_social_foto as fotoremet on fotoremet.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				inner join tb_glove_perfil as perfildest on perfildest.ID_USUARIO = msg.ID_USUARIO_DESTINATARIO 
				inner join tb_sys_usuario as userdest on userdest.ID_USUARIO = perfildest.ID_USUARIO 
				left join tb_social_foto as fotodest on fotodest.ID_FOTO = perfilremet.ID_FOTOPERFIL 
				left join tb_socialrecado_curtir as curtir on msg.ID_RECADO = curtir.ID_RECADO
				left join tb_social_recados as comparrecado on msg.ID_RECADO_COMPARTILHADO = msg.ID_RECADO
				left join tb_glove_perfil as comparperfil on comparperfil.ID_USUARIO = msg.ID_USUARIO_COMPARTILHOU 
				left join tb_sys_usuario as comparuser on comparuser.ID_USUARIO = comparperfil.ID_USUARIO 
				left join tb_social_foto as comparfoto on comparfoto.ID_FOTO = comparperfil.ID_FOTOPERFIL 
		 where 
		 1=1
		 and userdest.ID_USUARIO = '".$id_perfil."'
		 or userremet.ID_USUARIO = '".$id_perfil."'
		 having msg.FL_PRIVADA = 1
		 order by msg.DT_CADASTRO  DESC LIMIT ".$inicio.", ".$TAMANHO_PAGINA.";";		
		
			
			$obj->query($sql);
			
			
			$total_paginas = ceil($total / $TAMANHO_PAGINA);
   
    		
			return array($obj, $total_paginas, $total, $pagina);
    	
	}
	
		/*Pega HTML de um NodeElement*/	
		public static function getInnerHTML($Node)
		{
			 $Body = $Node->ownerDocument->documentElement->firstChild->firstChild;
			 $Document = new DOMDocument();     
			 $Document->appendChild($Document->importNode($Body,true));
			 return $Document->saveHTML();
		}
		
		public static function troca_caracteres_editor($s) {
				
				
			
				return html_entity_decode($s, ENT_NOQUOTES, 'UTF-8');
				
				//return $s;
		
		}


		public static function geraKeywords($string)
		{
			$keywordPadrao = "diversao, rede, social";
			
			
			$string = str_replace(",", "", $string);
			$string = str_replace("-", "", $string);
			$string = str_replace(" ", ", ", $string);
	
			//$string = substr($string, 0,strlen($string)-1);
				
			$string = $string . ", " . $keywordPadrao;
			
			$string = strtolower($string);
			
			/*if(mb_detect_encoding($string) != "UTF-8")
			{
				return utf8_encode($string);	
			}*/
			
			return $string;
			
			
			
		}
	
		
		
		
		
	public static function atualizacoes($page, $tamanho_pagina, $usuario_logado)
	{
		
		
    	$pagina = $page;
    	
    	$pagina = isset($pagina) ? $pagina : null;
    	$tamanhoPagina = $tamanho_pagina;
    	
    	$TAMANHO_PAGINA = $tamanhoPagina;
			
		if($pagina == null)
		{
			$pagina = 1;
			$inicio = 0;
		}else
		{
			$pagina = $pagina;
			$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
		}
    	
		$total = My_Sites_Glove::contaatualizacoesPaginacao($usuario_logado);
		
    	
		$comentarios = new Tb_social_albumfotos();
		$sql = "
			
			select * from
				(
				
				(select 
				     recado.ID_RECADO AS ID,
					 'curtir_publicacao' AS TIPO
					 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
					 ,usuario1.NOME AS QUEM_CURTIU_NOME
					 ,usuario1.SOBRENOME AS QUEM_CURTIU_SOBRENOME
					 ,usuario1.SLUG
					 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'curtiu sua própria publicação', 'curtiu uma publicação de') AS MSG
				 	 ,perfil2.ID_USUARIO AS DONO_ID
					 ,usuario2.NOME AS DONO_NOME
					 ,usuario2.SOBRENOME AS DONO_SOBRENOME
					 ,usuario2.SLUG AS SLUG_DONO
					 ,foto2.ARQUIVO AS DONO_FOTO
					 ,curtir.DT_CADASTRO AS DATA_HORA_ACAO
					 ,recado.MENSAGEM AS DETALHES
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
				#AND perfil2.ID_USUARIO = '$usuario_logado' 
				#OR perfil1.ID_USUARIO = '$usuario_logado'
				AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado' and fl_aceitou = 1)
				)
				
				UNION ALL
				
				(select 
					 recado.ID_RECADO AS ID,
					 'curtir_comentario' AS TIPO
					 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
					 ,usuario1.NOME AS QUEM_CURTIU_NOME
					 ,usuario1.SOBRENOME AS QUEM_CURTIU_SOBRENOME
					 ,usuario1.SLUG
					 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'curtiu seu próprio comentário', 'curtiu um comentário de') AS MSG
				 	 ,perfil2.ID_USUARIO AS DONO_ID
					 ,usuario2.NOME AS DONO_NOME
					 ,usuario2.SOBRENOME AS DONO_SOBRENOME
					 ,usuario2.SLUG AS SLUG_DONO
					 ,foto2.ARQUIVO AS DONO_FOTO
					 ,curtir.DT_CADASTRO AS DATA_HORA_ACAO
					 ,comentario.COMENTARIO AS DETALHES
				from tb_socialcomentario_curtir as curtir
				inner join tb_social_comentarios as comentario on curtir.ID_COMENTARIO = comentario.ID_COMENTARIO
				inner join tb_social_recados as recado on recado.ID_RECADO = comentario.ID_RECADO
				
				#QUEM CURTIU
				inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = curtir.QUEM_CURTIU
				left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
				inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
				
				#DONO DA ATUALIZAÇÂO
				inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = comentario.ID_USUARIO
				left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
				inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
				where 1 = 1
				#AND perfil2.ID_USUARIO = $usuario_logado
				#OR perfil1.ID_USUARIO = $usuario_logado
				AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)
				)
		
				
				UNION ALL
				(SELECT 
					recado.ID_RECADO AS ID,
					'comentario' AS TIPO
					 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
					 ,usuario1.NOME AS QUEM_CURTIU_NOME
					 ,usuario1.SOBRENOME AS QUEM_CURTIU_SOBRENOME
					 ,usuario1.SLUG
					 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
				 	 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'comentou sua própria publicação', 'comentou uma publicação de') AS MSG
				 	 ,perfil2.ID_USUARIO AS DONO_ID
					 ,usuario2.NOME AS DONO_NOME
					 ,usuario2.SOBRENOME AS DONO_SOBRENOME
					 ,usuario2.SLUG AS SLUG_DONO
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
					where 1 = 1
					#AND perfil2.ID_USUARIO = $usuario_logado
  					#OR perfil1.ID_USUARIO = $usuario_logado	
  					AND perfil1.ID_USUARIO in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado'  and fl_aceitou = 1)			
				 )
				 
				 	UNION ALL
					(SELECT 
						recado.ID_RECADO AS ID,
						'status' AS TIPO
						 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
						 ,usuario1.NOME AS QUEM_CURTIU_NOME
						 ,usuario1.SOBRENOME AS QUEM_CURTIU_SOBRENOME
					 	 ,usuario1.SLUG
						 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 	 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'postou em seu mural', 'postou no mural de') AS MSG
					 	 ,perfil2.ID_USUARIO AS DONO_ID
						 ,usuario2.NOME AS DONO_NOME
						 ,usuario2.SOBRENOME AS DONO_SOBRENOME
					 	 ,usuario2.SLUG AS SLUG_DONO
						 ,foto2.ARQUIVO AS DONO_FOTO
						 ,recado.DT_CADASTRO AS DATA_HORA_ACAO
						 ,recado.MENSAGEM AS DETALHES
	
						 from tb_social_recados  as recado
						inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = recado.ID_USUARIO_REMETENTE
						left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
						inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
						
						inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_DESTINATARIO
						left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
						inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
						
						where id_usuario_destinatario = $usuario_logado
						and id_usuario_remetente != $usuario_logado
						and FL_COMPARTILHAMENTO  = 0
						#WHERE id_usuario_destinatario in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado' and fl_aceitou = 1)			
					 )
					 
					 
					 UNION ALL
					(SELECT 
						recado.ID_RECADO AS ID,
						'compartilhamento' AS TIPO
						 ,perfil1.ID_USUARIO AS QUEM_CURTIU_ID
						 ,usuario1.NOME AS QUEM_CURTIU_NOME
						 ,usuario1.SOBRENOME AS QUEM_CURTIU_SOBRENOME
					 	 ,usuario1.SLUG
						 ,foto1.ARQUIVO AS QUEM_CURTIU_FOTO
					 	 ,IF(perfil1.ID_USUARIO = perfil2.ID_USUARIO, 'compartilhou sua publicação', 'compartilhou uma publicação de') AS MSG
					 	 ,perfil2.ID_USUARIO AS DONO_ID
						 ,usuario2.NOME AS DONO_NOME
						 ,usuario2.SOBRENOME AS DONO_SOBRENOME
					 	 ,usuario2.SLUG AS SLUG_DONO
						 ,foto2.ARQUIVO AS DONO_FOTO
						 ,recado.DT_CADASTRO AS DATA_HORA_ACAO
						 ,recado.MENSAGEM_COMPARTILHAMENTO AS DETALHES
	
						 from tb_social_recados  as recado
						inner join tb_glove_perfil as perfil1 on perfil1.ID_USUARIO = recado.ID_USUARIO_REMETENTE
						left join tb_social_foto as foto1 on perfil1.ID_FOTOPERFIL = foto1.ID_FOTO
						inner join tb_sys_usuario as usuario1 on usuario1.ID_USUARIO = perfil1.ID_USUARIO
						
						inner join tb_glove_perfil as perfil2 on perfil2.ID_USUARIO = recado.ID_USUARIO_DESTINATARIO
						left join tb_social_foto as foto2 on perfil2.ID_FOTOPERFIL = foto2.ID_FOTO
						inner join tb_sys_usuario as usuario2 on usuario2.ID_USUARIO = perfil2.ID_USUARIO
						
						where id_usuario_destinatario = $usuario_logado
						and id_usuario_remetente != $usuario_logado
						and FL_COMPARTILHAMENTO  = 1
						#WHERE id_usuario_destinatario in (select ID_AMIGO from tb_social_amigos where ID_USUARIO = '$usuario_logado' and fl_aceitou = 1)			
					 )
				 
				 ) 
				 as uniao 
				 order by uniao.DATA_HORA_ACAO DESC
				 LIMIT ".$inicio.", ".$TAMANHO_PAGINA.";";
						
			
		 		/*echo "<pre>".$sql."</pre>";
		 		die();*/
		  
			$comentarios->query($sql);
			
			
			$total_paginas = ceil($total / $TAMANHO_PAGINA);
   
    		
			return array($comentarios, $total_paginas, $total, $pagina);
   
	    	
	}
	
	public static function convert_datetime($str) { 

	    list($date, $time) = explode(' ', $str); 
	    list($year, $month, $day) = explode('-', $date); 
	    list($hour, $minute, $second) = explode(':', $time); 
	     
	    $timestamp = mktime($hour, $minute, $second, $month, $day, $year); 
	     
	    return $timestamp; 
	}  
	
	
	public static function formatar_tempo($timeBD) {
	
		$timeNow = time();
		$timeRes = $timeNow - $timeBD;
		$nar = 0;
		
		// variável de retorno
		$r = "";
	
		// Agora
		if ($timeRes == 0){
			$r = "agora";
		} else
		// Segundos
		if ($timeRes > 0 and $timeRes < 60){
			$r = $timeRes. " segundo(s) atr&aacute;s";
		} else
		// Minutos
		if (($timeRes > 59) and ($timeRes < 3599)){
			$timeRes = $timeRes / 60;	
			if (round($timeRes,$nar) >= 1 and round($timeRes,$nar) < 2){
				$r = round($timeRes,$nar). " minuto atr&aacute;s";
			} else {
				$r = round($timeRes,$nar). " minutos atr&aacute;s";
			}
		}
		 else
		// Horas
		// Usar expressao regular para fazer hora e MEIA
		if ($timeRes > 3559 and $timeRes < 85399){
			$timeRes = $timeRes / 3600;
			
			if (round($timeRes,$nar) >= 1 and round($timeRes,$nar) < 2){
				$r = round($timeRes,$nar). " hora atr&aacute;s";
			}
			else {
				$r = round($timeRes,$nar). " horas atr&aacute;s";		
			}
		} else
		// Dias
		// Usar expressao regular para fazer dia e MEIO
		if ($timeRes > 86400 and $timeRes < 2591999){
			
			$timeRes = $timeRes / 86400;
			if (round($timeRes,$nar) >= 1 and round($timeRes,$nar) < 2){
				$r = round($timeRes,$nar). " dia atr&aacute;s";
			} else {
	
				preg_match('/(\d*)\.(\d)/', $timeRes, $matches);
				
				if ($matches[2] >= 5) {
					$ext = round($timeRes,$nar) - 1;
					
					// Imprime o dia
					$r = $ext;
					
					// Formata o dia, singular ou plural
					if ($ext >= 1 and $ext < 2){ $r.= " dia "; } else { $r.= " dias ";}
					
					// Imprime o final da data
					$r.= "&frac12; atr&aacute;s";
					
					
				} else {
					$r = round($timeRes,0) . " dias atr&aacute;s";
				}
				
			}		
					
		} else
		// Meses
		if ($timeRes > 2592000 and $timeRes < 31103999){
	
			$timeRes = $timeRes / 2592000;
			if (round($timeRes,$nar) >= 1 and round($timeRes,$nar) < 2){
				$r = round($timeRes,$nar). " mes atr&aacute;s";
			} else {
	
				preg_match('/(\d*)\.(\d)/', $timeRes, $matches);
				
				if ($matches[2] >= 5){
					$ext = round($timeRes,$nar) - 1;
					
					// Imprime o mes
					$r.= $ext;
					
					// Formata o mes, singular ou plural
					if ($ext >= 1 and $ext < 2){ $r.= " mes "; } else { $r.= " meses ";}
					
					// Imprime o final da data
					$r.= "&frac12; atr&aacute;s";
				} else {
					$r = round($timeRes,0) . " meses atr&aacute;s";
				}
				
			}
		} else
		// Anos
		if ($timeRes > 31104000 and $timeRes < 155519999){
			
			$timeRes /= 31104000;
			if (round($timeRes,$nar) >= 1 and round($timeRes,$nar) < 2){
				$r = round($timeRes,$nar). " ano atr&aacute;s";
			} else {
				$r = round($timeRes,$nar). " anos atr&aacute;s";
			}
		} else
		// 5 anos, mostra data
		if ($timeRes > 155520000){
			
			$localTimeRes = localtime($timeRes);
			$localTimeNow = localtime(time());
					
			$timeRes /= 31104000;
			$gmt = array();
			$gmt['mes'] = $localTimeRes[4];
			$gmt['ano'] = round($localTimeNow[5] + 1900 - $timeRes,0);				
						
			$mon = array("Jan ","Fev ","Mar ","Abr ","Mai ","Jun ","Jul ","Ago ","Set ","Out ","Nov ","Dez "); 
			
			$r = $mon[$gmt['mes']] . $gmt['ano'];
		}
		
		return $r;
	
	}
	
	

}

