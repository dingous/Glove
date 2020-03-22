<?php


class ContaController extends Zend_Controller_Action
{
	
	protected $auth;
	protected $usuario;
	protected $array_formerrors = array();
    
	public function init()
	{
		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
    	$this->view->acion = $this->getRequest()->getActionName();
    	
    	
    	
    	 
		if($this->getRequest()->getActionName() != "uploadfoto")
		{
			$auth = Zend_Auth::getInstance();
			/*if($auth->hasIdentity())
			{*/
				$this->auth = $auth;
		    	$this->usuario = $auth->getStorage()->read();
				$this->view->usuario = $this->usuario;
				if($auth->hasIdentity())
				{
					Zend_Layout::getMvcInstance()->assign('contaSolicitacoes', My_Sites_Glove::contasolicitacoes($this->usuario->ID_USUARIO));
					Zend_Layout::getMvcInstance()->assign('contaAtualizacoes', My_Sites_Glove::contaatualizacoes($this->usuario->ID_USUARIO));
					Zend_Layout::getMvcInstance()->assign('verificaOpcoes', My_Sites_Glove::configuracoes($this->usuario->ID_USUARIO));
					
				}
			/*}else{
				
				//throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
				//$this->_redirect("/conta/logoff");
				
			}*/
		}
		
		
		
		
		
	}
	
	public function atualizaamigosgrupoAction()
	{
	    $obj = new Tb_glove_perfil();
	    $obj->find(true);
	    $dados = $obj->allToObject();
	    
	    echo count($dados)."<br><br>";
	    
	    
	    $objuser =new Tb_sys_usuario();
	    //$objuser->where("ID_GRUPO = 1");
	    $objuser->find(true);
	    $lista = $objuser->allToObject();
	    
	    
	    foreach ($dados as $chave => $obj) {
	    	 
   
	    	
	    	
	        echo $obj->tb_sys_usuario."<br>";
	        
	    	foreach($lista as $chave => $objuser)
	    	{
	    
	    
	    
	    		try {
	    			 
	    
	    			$objamigoins = new Tb_glove_perfil();
	    			$sql = "INSERT INTO  `glove`.`tb_social_amigos` (
	    			`ID_USUARIO` ,
	    			`ID_AMIGO` ,
	    			`DT_CADASTRO` ,
	    			`FL_ACEITOU`
	    			)
	    			VALUES (
	    			'".$objuser->ID_USUARIO."',  '".$obj->tb_sys_usuario."', NOW( ) ,  '1'
	    			);";
	    
	    			 
	    
	    			$objamigoins->query($sql);
	    			$objamigoins->_getConnection()->close();
	    			$objamigoins->destroy();
   
	    			
	    			echo "OK<br>";
	    			
	    		} catch (Exception $e) {
	    			 
	    			echo $e->getMessage()." " .$objuser->ID_USUARIO. " "."<br>";
	    		}
	    		
	    		
	    		
	    		try {
	    			 
	    			 
	    			 
	    			$objamigoins2 = new Tb_glove_perfil();
	    			$sql2 = "INSERT INTO  `glove`.`tb_social_amigos` (
	    			`ID_USUARIO` ,
	    			`ID_AMIGO` ,
	    			`DT_CADASTRO` ,
	    			`FL_ACEITOU`
	    			)
	    			VALUES (
	    			'".$obj->tb_sys_usuario."',  '".$objuser->ID_USUARIO."', NOW( ) ,  '1'
	    			);";
	    			 
	    			$objamigoins2->query($sql2);
	    			$objamigoins2->_getConnection()->close();
	    			$objamigoins2->destroy();
	    			 
	    		
	    			echo "OK<br>";
	    		
	    		} catch (Exception $e) {
	    			 
	    			echo $e->getMessage()." " .$objuser->ID_USUARIO. " "."<br>";
	    		}
	    	}
	    
	    
	    
	    }
	    
	    die('aquii');
	}
	
    public function indexAction()
    {
        
        
        
      
        
    	if($this->getRequest()->isXmlHttpRequest())
		{
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    	
    	$idade = new Tb_glove_idade();
    	$idade->orderBy("ORDEM ASC")->find(true);
    	$idades = $idade->allToObject();
    	$idade->_getConnection()->close();
    	$idade->destroy();
    	
    	$altura = new Tb_glove_altura();
    	$altura->orderBy("ORDEM ASC")->find(true);
    	$alturas = $altura->allToObject();
    	$altura->_getConnection()->close();
    	$altura->destroy();
    	
    	$peso = new Tb_glove_peso();
    	$peso->orderBy("ORDEM ASC")->find(true);
    	$pesos = $peso->allToObject();
    	$peso->_getConnection()->close();
    	$peso->destroy();
    	
    	$tipo = new Tb_glove_tipo();
    	$tipo->orderBy("ORDEM ASC")->find(true);
    	$tipos = $tipo->allToObject();
    	$tipo->_getConnection()->close();
    	$tipo->destroy();
    	
    	$situacao = new Tb_glove_situacao();
    	$situacao->orderBy("ORDEM ASC")->find(true);
    	$situacoes = $situacao->allToObject();
    	$situacao->_getConnection()->close();
    	$situacao->destroy();
    	
    	$fumante = new Tb_glove_fumante();
    	$fumante->orderBy("ORDEM ASC")->find(true);
    	$fumantes = $fumante->allToObject();
    	$fumante->_getConnection()->close();
    	$fumante->destroy();
    	
    	$interesse = new Tb_glove_insteresse();
    	$interesse->orderBy("ORDEM ASC")->find(true);
    	$interesses = $interesse->allToObject();
    	$interesse->_getConnection()->close();
    	$interesse->destroy();
    	
    	
    	$disponibilidade = new Tb_glove_disponibilidade();
    	$disponibilidade->orderBy("ORDEM ASC")->find(true);
    	$disponivel = $disponibilidade->allToObject();
    	$disponibilidade->_getConnection()->close();
    	$disponibilidade->destroy();
    	
    	$bebida = new Tb_glove_bebidas();
    	$bebida->orderBy("ORDEM ASC")->find(true);
    	$bebidas = $bebida->allToObject();
    	$bebida->_getConnection()->close();
    	$bebida->destroy();
    	
    	
		$this->view->idade = $idades;
        $this->view->altura = $alturas;
        $this->view->peso = $pesos;
        $this->view->tipo = $tipos;
        $this->view->situacao = $situacoes;
        $this->view->fumante = $fumantes;
        $this->view->interesse = $interesses;
        $this->view->disponivel = $disponivel;
        $this->view->bebida = $bebidas;
        
        $auth = Zend_Auth::getInstance();
        $storage = $auth->getStorage();
        

        if($storage->read() != null)
       	{
        	$this->view->logado = true;
        }else
        {
        	$this->view->logado = false;
        }

		
    }
    
    
    public function editarperfilAction()
    {
    	
    	if($this->getRequest()->isXmlHttpRequest())
		{
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    	
    	$idade = new Tb_glove_idade();
    	$idade->orderBy("ORDEM ASC")->find(true);
    	$idades = $idade->allToObject();
    	$idade->_getConnection()->close();
    	$idade->destroy();
    	
    	$altura = new Tb_glove_altura();
    	$altura->orderBy("ORDEM ASC")->find(true);
    	$alturas = $altura->allToObject();
    	$altura->_getConnection()->close();
    	$altura->destroy();
    	
    	$peso = new Tb_glove_peso();
    	$peso->orderBy("ORDEM ASC")->find(true);
    	$pesos = $peso->allToObject();
    	$peso->_getConnection()->close();
    	$peso->destroy();
    	
    	$tipo = new Tb_glove_tipo();
    	$tipo->orderBy("ORDEM ASC")->find(true);
    	$tipos = $tipo->allToObject();
    	$tipo->_getConnection()->close();
    	$tipo->destroy();
    	
    	$situacao = new Tb_glove_situacao();
    	$situacao->orderBy("ORDEM ASC")->find(true);
    	$situacoes = $situacao->allToObject();
    	$situacao->_getConnection()->close();
    	$situacao->destroy();
    	
    	$fumante = new Tb_glove_fumante();
    	$fumante->orderBy("ORDEM ASC")->find(true);
    	$fumantes = $fumante->allToObject();
    	$fumante->_getConnection()->close();
    	$fumante->destroy();
    	
    	$interesse = new Tb_glove_insteresse();
    	$interesse->orderBy("ORDEM ASC")->find(true);
    	$interesses = $interesse->allToObject();
    	$interesse->_getConnection()->close();
    	$interesse->destroy();
    	
    	
    	$disponibilidade = new Tb_glove_disponibilidade();
    	$disponibilidade->orderBy("ORDEM ASC")->find(true);
    	$disponivel = $disponibilidade->allToObject();
    	$disponibilidade->_getConnection()->close();
    	$disponibilidade->destroy();
    	
    	$bebida = new Tb_glove_bebidas();
    	$bebida->orderBy("ORDEM ASC")->find(true);
    	$bebidas = $bebida->allToObject();
    	$bebida->_getConnection()->close();
    	$bebida->destroy();
    	
    	
    	$id_usuario = $this->usuario->ID_USUARIO;
		
    	
    	    	
		$perfil = My_Sites_Glove::perfil($id_usuario);
		
		Zend_Layout::getMvcInstance()->assign('pattern', $perfil->CSSCLASSPATTERN);
		Zend_Layout::getMvcInstance()->assign('color', $perfil->CSSCLASSCOLOR);
		
		$is_friend = $this->isFriend($perfil->tb_sys_usuario);
		$this->view->perfil = $perfil;
		$this->view->isFriend = $is_friend;
    	
		$this->view->idade = $idades;
        $this->view->altura = $alturas;
        $this->view->peso = $pesos;
        $this->view->tipo = $tipos;
        $this->view->situacao = $situacoes;
        $this->view->fumante = $fumantes;
        $this->view->interesse = $interesses;
        $this->view->disponivel = $disponivel;
        $this->view->bebida = $bebidas;
	
		$this->render("paginas/edit-perfil");
    }
    
    public function faceAction()
    {
    
    }
    
    public function novoAction()
    {
   		if($this->getRequest()->isXmlHttpRequest())
		{
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    }
    
    
    public function updatecolorAction()
    {    	
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    $pars = $this->_request->getParams();
	    $user = $this->usuario->ID_USUARIO;
	    $color = $pars['color'];
	    
	    $obj = new Tb_glove_perfil();
	    $obj->get($user);
	    $obj->CSSCLASSCOLOR = $color;
	    $obj->update();
	 	$obj->_getConnection()->close();
    	$obj->destroy();
    }
    
  	public function updatebackgroundAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    $pars = $this->_request->getParams();
	    $user = $this->usuario->ID_USUARIO;
	    $background = $pars['background'];
	    
	    $obj = new Tb_glove_perfil();
	    $obj->get($user);
	    $obj->CSSCLASSPATTERN = $background;
	    $obj->update();
	   	$obj->_getConnection()->close();
    	$obj->destroy(); 
    }
    
    public function cidadesjsonAction()
    {
    	
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
    		
	    	$pars = $this->_request->getParams();
	    	$limit = $pars['maxRows'];
	    	$like = $pars['name_startsWith'];
	    

    		$obj = new Tb_sys_cidade();
    		$obj2 = new Tb_sys_estado();
    		
    		$obj->select("CONVERT(CAST(tb_sys_cidade.NOME_CIDADE as BINARY) USING latin1) as NOME_CIDADE,tb_sys_cidade.ID_CIDADE, tb_sys_estado.SGL_ESTADO");
    		$obj->join($obj2);
    		$obj->where("CONVERT(CAST(tb_sys_cidade.NOME_CIDADE as BINARY) USING latin1) LIKE '%$like%'");
    		$obj->order("tb_sys_cidade.NOME_CIDADE");
    		$obj->limit($limit);
    		$obj->find(true);
    		$json = $obj->allToJSON(false, true);
    		$obj->_getConnection()->close();
    		$obj->destroy(); 
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($json)
			->sendResponse();
			exit;	
    }
    
    
	public function nomesjsonAction()
    {
    	
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
    		
	    	$pars = $this->_request->getParams();
	    	$limit = $pars['maxRows'];
	    	$like = $pars['name_startsWith'];
	    
			$obj = new Tb_sys_usuario();
			$obj2 = new Tb_glove_perfil();
			$obj3 = new Tb_social_foto();
			$obj->select("NOME, SOBRENOME, tb_sys_usuario.ID_USUARIO, SLUG, ARQUIVO");
			$obj2->join($obj3, "LEFT");
			$obj->join($obj2);
			$obj->where("NOME like '%$like%' || SOBRENOME like '%$like%' || SLUG like '%$like%' || EMAIL like '%$like%'");
    		$obj->order("NOME, SOBRENOME, SLUG ASC");
    		$obj->limit($limit);
    		$obj->find(true);
    		$json = $obj->allToJSON(false, true);
    	   	$obj->_getConnection()->close();
    		$obj->destroy();
    		 
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($json)
			->sendResponse();
			exit;	
    }
    
    
	public function buscaamigosjsonAction()
    {
    	
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
    		
	    	$pars = $this->_request->getParams();
	    	$limit = $pars['maxRows'];
	    	$like = $pars['name_startsWith'];
	    
	    	
	    	
	    	$obj = new Tb_glove_perfil();
		$sql = "SELECT tb_sys_usuario.ID_USUARIO, tb_sys_usuario.NOME, SOBRENOME, tb_sys_usuario.SLUG, tb_social_foto.ARQUIVO
		FROM tb_social_amigos
		INNER JOIN tb_sys_usuario ON tb_social_amigos.ID_USUARIO = tb_sys_usuario.ID_USUARIO
		INNER JOIN tb_glove_perfil ON tb_sys_usuario.ID_USUARIO = tb_glove_perfil.ID_USUARIO
		LEFT JOIN tb_social_foto ON tb_glove_perfil.ID_FOTOPERFIL = tb_social_foto.ID_FOTO
		WHERE tb_social_amigos.ID_AMIGO = '".$this->usuario->ID_USUARIO."'
		AND NOME like '%$like%' || SOBRENOME like '%$like%' || SLUG like '%$like%' || EMAIL like '%$like%'
		AND FL_ACEITOU = 1
		GROUP BY tb_social_amigos.ID_USUARIO
		ORDER BY tb_sys_usuario.NOME ASC";
    	
			
    	$obj->query($sql);
    	$obj->fetch();
    	$json = $obj->allToJSON(false, true);
    	$obj->_getConnection()->close();
		$obj->destroy();
    		 
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($json)
			->sendResponse();
			exit;	
    }
    
   
   
   
	public function salvarAction()
    {	
    	
    	try {
    		
    	
    	$post = $this->_request->getParams();
    	
    
    	$typeChange = isset($post['typeChange']) ? $post['typeChange'] : "novoCadastro";
    	
    	
    
    		
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender();
    	
    	$obj = new Tb_sys_usuario();
    	$id = isset($post['tb_sys_usuario']) ? $post['tb_sys_usuario'] : null;
    	$obj->get($id);
    	
    
    	
    	if($id == null)
		{
	    	$obj->tb_sys_empresa = 2; //empresa do guia cool
	    	$obj->tb_sys_grupos = 2; //empresa do guia cool
	    	$obj->tb_sys_tipousuario = 3;
	    	$obj->FL_ATIVO = 1;
		}    	
   		
	
		
		
    	
	
		
    	if($typeChange == "changePass")
    	{
    		$obj->_setFrom($post);
    	    $senha_atual= $post['SENHA'];
    		$obj->SENHA = $senha_atual;
    		
    	}else
    	{
    		
    		$post["SENHA"] = $obj->SENHA;
    		$post["SENHA1"] = $obj->SENHA;
    		
    		$obj->_setFrom($post);
    		
    	}
    	
    	
    	
    	
    	if($post['tb_sys_cidade'] == null)
    	{
    		throw  new Exception("Informe a sua cidade");
    	}
    	if($post['SENHA'] != $post['SENHA1'])
    	{
    		throw  new Exception("As senhas não conferem");
    	}
    	
    	$result = $obj->validate();
    	
		if(count($result) == 0){
      	 	
			
			
			

			$obj->SLUG = My_Sites_Glove::urldinamica($post['SLUG']);
			
			if($id == null)
			{	
				$obj->DT_CADASTRO = date("Y-m-d H:i:s");
				$obj->insert();
				
				
				
				
				
        		
        		
			}else {
				
				$obj->DT_MODIFICACAO = date("Y-m-d H:i:s");
				$obj->update();
				
			}
			
        		
        	 
        	 $perfil = new Tb_glove_perfil();
        	 if($id != null)
        	 {
        	 	$perfil->get($id);
        	 }
        	 $perfil->tb_sys_usuario = $obj->ID_USUARIO;
       	 	 $perfil->_setFrom($post);
       	 	  if($id == null)
       	 	  {
       	 	      
       	 	    
       	 	      
       	 	 	$perfil->insert();
       	 	 	
       	 	 	
       	 	 	/*$objuser = new Tb_glove_perfil();
       	 	 	$objuser->where("ID_DISPONIBILIDADE = '".$perfil->tb_glove_disponibilidade."'");
       	 	 	$objuser->find(true);
       	 	 	$lista = $objuser->allToObject();
       	 	 	
       	 	 	
       	 	 	foreach($lista as $chave => $objuser)
       	 	 	{
       	 	 		
       	 	 	  
       	 	 	
       	 	 		try {
       	 	 			 
       	 	 	
       	 	 			$objamigoins = new Tb_glove_perfil();
       	 	 			$sql = "INSERT INTO  `glove`.`tb_social_amigos` (
       	 	 			`ID_USUARIO` ,
       	 	 			`ID_AMIGO` ,
       	 	 			`DT_CADASTRO` ,
       	 	 			`FL_ACEITOU`
       	 	 			)
       	 	 			VALUES (
       	 	 			'".$objuser->tb_sys_usuario."',  '".$obj->ID_USUARIO."', NOW( ) ,  '0'
       	 	 			);";
       	 	 			 
       	 	 		} catch (Exception $e) {
       	 	 				
       	 	 				
       	 	 		}
       	 	 		try {
       	 	 	
       	 	 			$objamigoins->query($sql);
       	 	 			$objamigoins->_getConnection()->close();
       	 	 			$objamigoins->destroy();
       	 	 	
       	 	 			$objamigoins = new Tb_glove_perfil();
       	 	 			$sql = "INSERT INTO  `glove`.`tb_social_amigos` (
       	 	 			`ID_USUARIO` ,
       	 	 			`ID_AMIGO` ,
       	 	 			`DT_CADASTRO` ,
       	 	 			`FL_ACEITOU`
       	 	 			)
       	 	 			VALUES (
       	 	 			'".$obj->ID_USUARIO."',  '".$objuser->tb_sys_usuario."', NOW( ) ,  '0'
       	 	 			);";
       	 	 	
       	 	 			$objamigoins->query($sql);
       	 	 			$objamigoins->_getConnection()->close();
       	 	 			$objamigoins->destroy();
       	 	 		} catch (Exception $e) {
       	 	 				
       	 	 				
       	 	 		}
       	 	 	
       	 	 	*/
       	 	 	
       	 	 	
       	 	 	// Utilizado para todos virarem amigos de todos
       	 	 	$objuser = new Tb_sys_usuario();
       	 	 	$objuser->where("ID_GRUPO = 1");
       	 	 	$objuser->find(true);
       	 	 	$lista = $objuser->allToObject();
       	 	 	
       	 	 	
       	 	 	
       	 	 	foreach($lista as $chave => $objuser)
       	 	 	{
       	 	 	
       	 	 	 
       	 	 	    
       	 	 	try {
       	 	 		
       	 	 	
       	 	 		$objamigoins = new Tb_glove_perfil();
       	 	 		$sql = "INSERT INTO  `glove`.`tb_social_amigos` (
       	 	 		`ID_USUARIO` ,
       	 	 		`ID_AMIGO` ,
       	 	 		`DT_CADASTRO` ,
       	 	 		`FL_ACEITOU`
       	 	 		)
       	 	 		VALUES (
       	 	 		'".$objuser->ID_USUARIO."',  '".$obj->ID_USUARIO."', NOW( ) ,  '1'
       	 	 		);";
       	 	 		
       	 	 		} catch (Exception $e) {
       	 	 			 
       	 	 				
       	 	 		}
       	 	 		try {
       	 	 	
       	 	 		$objamigoins->query($sql);
       	 	 		$objamigoins->_getConnection()->close();
       	 	 		$objamigoins->destroy();
       	 	 	
       	 	 		$objamigoins = new Tb_glove_perfil();
       	 	 		$sql = "INSERT INTO  `glove`.`tb_social_amigos` (
       	 	 		`ID_USUARIO` ,
       	 	 		`ID_AMIGO` ,
       	 	 		`DT_CADASTRO` ,
       	 	 		`FL_ACEITOU`
       	 	 		)
       	 	 		VALUES (
       	 	 		'".$obj->ID_USUARIO."',  '".$objuser->ID_USUARIO."', NOW( ) ,  '1'
       	 	 		);";
       	 	 	
       	 	 		$objamigoins->query($sql);
       	 	 		$objamigoins->_getConnection()->close();
       	 	 		$objamigoins->destroy();
       	 	 		} catch (Exception $e) {
       	 	 				
       	 	 				
       	 	 		}
       	 	 	}
       	 	 	
       	 	 	
       	 	  }else {
       	 	  	$perfil->update();
       	 	  }
       	 	 
       	 	 
       	 	 /*echo $perfil->_getSQL(Lumine_Base::SQL_UPDATE);
       	 	 die('aqui');*/
       	 	 
       	 	 if($id == null)
       	 	 {
	       	 	 $album = new Tb_social_albumfotos();
	       	 	 $album->tb_glove_perfil = $obj->ID_USUARIO;
	       	 	 $album->DESCRICAO = "(sem nome)";
	       	 	 $album->DT_CADASTRO = date("Y-m-d H:i:s");
	       	 	 $album->FL_PADRAO = 1;
	       	 	 $album->insert();
	       	 	 $album->_getConnection()->close();
    			 $album->destroy(); 
       	 	 }
       	 	 
        	 if($id == null)
        	 {
        	 	 $msg = "Conta cadastrada com sucesso!";
	       	 	 @$this->enviaEmailParaConfirmacao($obj->ID_USUARIO);
	        	 $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj);
        	 }else 
        	 {
        	 	$msg = "Dados atualizados com sucesso! ;)";
        	 	$content = array('erro' => 'edit', 'html' => '', 'msg' => $msg, 'modelo' => $obj);
        	 }
			
        	 //return $this->_forward('logar', 'conta', 'default', array("modelo" => $obj));
        	 
		}else{
		
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = utf8_encode($erro);
				}
			}
			
			$validacaoToHtml = My_Plugins_Util::validacaoToHtml($array_erros);
			
			
			$html = "";
	    	$content = array('erro' => 'valida', 'html' =>  $html, 'campos_erros' => $array_erros, 'error_html' => $validacaoToHtml, 'msg' => 'Preencha os campos obrigatorios');
	    	
	    	 
	    	
		}
		
    	} catch (Exception $e) {
    		
    		
    		
    		if(strpos($e->getMessage(), "EMAIL_UNIQUE") == true)
    		{
    			$msg = utf8_encode("Este e-mail já está em uso, informe outro.");
    		}else if(strpos($e->getMessage(), "for key 3") == true)
    		{
    		    $msg = utf8_encode("Este e-mail já está em uso, informe outro.");
    		}else if(strpos($e->getMessage(), "SLUG_UNIQUE") == true)
    		{	
    			$msg = utf8_encode("Usuário já está em uso informe outro.");
    		}else if(strpos($e->getMessage(), "fk_TB_SYS_USUARIO_TB_SYS_CIDADE1") == true)
    		{
    			$msg = utf8_encode("Informe a cidade onde<br> reside.");
    		}else {
    			$msg = $e->getMessage();
    		}
    		
    		$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
    		
    	}
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
				
				
    }
    
    
    
    public function salvaredicaoprimeiroacessoAction()
    {
    	try {
	      	$this->_helper->layout()->disableLayout();
		    $this->_helper->viewRenderer->setNoRender();
	    	
    	$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		 	throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
		}
		
		$id_usuario = $this->usuario->ID_USUARIO;
		
		
		$obj = new Tb_glove_perfil();
		$obj->where("{tb_sys_usuario} = $id_usuario");
		$obj->find(true);
    	$post = $this->_request->getParams();
    	$obj->_setFrom($post);
    	$obj->save();
    	$obj->_getConnection()->close();
    	$obj->destroy(); 
    	
    	$obj = new Tb_sys_usuario();
		$obj->get($id_usuario);
		$obj->FL_PRIMEIROACESSO = 0;
		$obj->save();
    	$obj->_getConnection()->close();
    	$obj->destroy(); 		
		
    	
    	
    	$msg = utf8_encode("Perfil atualizado com <br />sucesso!");
        $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj);
		
		    
	    } catch (Exception $e) {
    		$msg = $e->getMessage();
	    	$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
	    }
	    
	    
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
    public function addamigoAction()
    {
    	try {
	      	$this->_helper->layout()->disableLayout();
		    $this->_helper->viewRenderer->setNoRender();
	    	
    	$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		 	throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
		}
		
		$id_usuario = $this->usuario->ID_USUARIO;
		$post = $this->_request->getParams();
    	$id_amigo = $post['amigo'];
    	
    	$is_amigo = $this->isFriend($id_amigo);
    	
    	
    	if($this->usuario->ID_USUARIO == $id_amigo)
    	{
    	    throw new Exception(utf8_encode("Não é possível adicionar a <br>você mesmo."));
    	}
    	
    	if($is_amigo == false)
    	{
    		$obj = new Tb_glove_perfil();
    			$sql = "INSERT INTO  `glove`.`tb_social_amigos` (
							`ID_USUARIO` ,
							`ID_AMIGO` ,
							`DT_CADASTRO` ,
							`FL_ACEITOU`
							)
							VALUES (
							'".$id_usuario."',  '".$id_amigo."', NOW( ) ,  '0'
							);";
    			
    			$obj->query($sql);
    			$obj->_getConnection()->close();
    			$obj->destroy();   
    			  			
    			$msg = utf8_encode("Solicitação enviada com <br />sucesso!");
    		$msg = utf8_encode("Solicitação enviada com <br />sucesso!");
    		
    	}else{
    		if($is_amigo->FL_ACEITOU == 0)
    		{ 
    			$msg = utf8_encode("Uma solicitação de amizade <br /> já foi enviada a esta pessoa!");
    			
    		}else{
    			$msg = utf8_encode("Esta pessoa já <br /> está adicionada em sua lista !");
    		}	
    		
    	}
    	
    	
        $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => '');
		
		    
	    } catch (Exception $e) {
    		$msg = $e->getMessage();
	    	$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
	    }
	    
	    
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    public function getmensagensAction()
    {
    	$this->_helper->layout()->disableLayout();
    	
    	$pars = $this->_request->getParams();
    	
    	
    	//print_r($pars);
    
    	$pagina = isset($pars['pagina']) ? $pars['pagina'] : "";
    	$action = isset($pars['ACTION']) ? $pars['ACTION'] : "";
    	$id_usuario = isset($pars["id_perfil"]) ? $pars["id_perfil"] : "";
    	
    	
    	
    	
    	if(isset($pars['id_mensagem']) && $pars['id_mensagem'] != "null")
    	{
    	    $id_mensagem = $pars['id_mensagem'];
    	    
    	    $msgs = My_Sites_Glove::mensagens( $this->usuario->ID_USUARIO, $pagina, 20, $id_mensagem);
    	    $this->view->showtitulo = false;
    	    
    	    if($pagina == "")
    	    {    	    
    	    	$this->_helper->_layout->setLayout('default');
    	    }
    	    
    	    
    		
    	}else 
    	{
    	
	    	
	    	if((!empty($id_usuario) && $id_usuario == $this->usuario->ID_USUARIO && $action == ""))
	    	{	
	    	
	    		$msgs = My_Sites_Glove::mensagens($id_usuario, $pagina, 20);
	    		
	    	}else
	    	{	
	    		$msgs = My_Sites_Glove::mensagensOutros($id_usuario, $pagina, 20);
	    		
	    	}
	    	
	    	$this->view->showtitulo = true;
    	
    	}
    	
		$msgsdata = $msgs[0]->allToObject();
		$msgs[0]->_getConnection()->close();
		$msgs[0]->destroy();
		$this->view->mensagens = $msgsdata;
		$this->view->total_paginas = $msgs[1];
		$this->view->pagina_atual = $msgs[3];
		$this->view->pagina = $pagina;
    	
    	$this->render("ajax/mensagens");
    }
    
	public function excluirmsgAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$id = $pars['id'];
    	
    	$sql = "SELECT ID_USUARIO_REMETENTE FROM tb_social_recados WHERE ID_RECADO = '$id'";
    	$obj = new Tb_glove_perfil();
    	$obj->query($sql);
    	$obj->fetch();
    	
    	if($obj->ID_USUARIO_REMETENTE != $this->usuario->ID_USUARIO)
    	{
    		throw new Exception(("Esta mensagem não foi <br>postada por você."));
    		
    	}
    	
    	
    	
    	$sql = "DELETE FROM tb_social_recados WHERE ID_RECADO = '$id'";
    	$obj = new Tb_glove_perfil();
    	$obj->query($sql);
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'post' => utf8_encode("Mensagem excluída com sucesso!")
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode($e->getMessage()) 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }

    
    
    public function excluiralbumAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$id = $pars['id'];
    	
    	
    	$album = new Tb_social_albumfotos();
    	$album->get($id);
    	$album->fetch();
    	
    	
    	
    	if($album->FL_PADRAO == 1)
    	{
    		throw  new Exception(("Este álbum não pode ser excluído, ele é padrão das fotos do seu perfil."));
    	}
    	
    	
    	$fotos = new Tb_social_foto();
    	$fotos->where("ID_ALBUM = $id");
    	$fotos->find(true);
    	$dados = $fotos->allToObject();
    	
    
    	
    
    	foreach($dados as $chave => $valor)
    	{
    		
    		$fotos = new Tb_social_foto();
    		$fotos->get($valor->ID_FOTO);
    		$fotos->fetch();
    		@unlink($_SERVER['DOCUMENT_ROOT']."/www/uploads/perfis/" . $fotos->ARQUIVO);
    		$fotos->delete();
    	}
    	
    	
    	$album->delete();
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'msg' => utf8_encode("Álbum exclúido com sucesso!")
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode($e->getMessage()) 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
 	public function buscasenhaAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$SENHAOLD = $pars['SENHAOLD'];
    	
    	
    	
    	
    	$obj = new Tb_sys_usuario();
    	$obj->get($this->usuario->ID_USUARIO);
    	if($obj->SENHA != $SENHAOLD)
    	{
    		throw  new Exception("Senha não cadastrada.");
    	}
    	
    	
    	$content = array(
		    	 'erro' =>  'false',
	    		 'msg' => utf8_encode("Agora informe sua nova senha nos campos abaixo!")
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'erro' =>  'true',
	    		 'msg' => utf8_encode($e->getMessage()) 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
    public function buscasenhaexcluiAction()
    {
    	try {
    
    		 
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender();
    		 
    		$pars = $this->_request->getParams();
    		$SENHA1 = $pars['SENHAEXCLUI'];
    		$SENHA2 = $pars['SENHAEXCLUIREPEAT'];
    		 
    		 
    		 
    		 if($SENHA1 != $SENHA2 || $SENHA1 == "" || $SENHA2 == "")
    		 {
    		     throw  new Exception("As senhas não conferem.");
    		 }
    		
    		$obj = new Tb_sys_usuario();
    		$obj->get($this->usuario->ID_USUARIO);
    		if($obj->SENHA != $SENHA1)
    		{
    			throw  new Exception("Senha incorreta do seu <br>usuário.");
    		}
    		 
    		 
    		$content = array(
    				'erro' =>  'false',
    				'msg' => utf8_encode("Para confimar a exclusão<br> clique no botão abaixo.")
    		);
    		 
    	} catch (Exception $e) {
    		$content = array(
    				'erro' =>  'true',
    				'msg' => utf8_encode($e->getMessage())
    		);
    	}
    	 
    
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'text/json')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    }
    
    public function excluircontaAction()
    {
    	try {
    
    		 
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender();
    		 
    		$pars = $this->_request->getParams();

    		
    		$obj = new Tb_sys_usuario();
    		$obj->get($this->usuario->ID_USUARIO);
    		$obj->delete();
    		
    		
    		
    		$content = array(
    				'erro' =>  'false',
    				'msg' => utf8_encode("Sua conta foi excluída com sucesso, você será redirecionado para a tela inicial.")
    		);
    		 
    	} catch (Exception $e) {
    		$content = array(
    				'erro' =>  'true',
    				'msg' => utf8_encode($e->getMessage())
    		);
    	}
    
    
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'text/json')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    }
    
    
 	public function excluirfotosAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	
    	if(!isset($pars['chkdel']) || count($pars['chkdel']) == 0)
    	{
    		throw  new Exception(("Para excluir você deve selecionar pelo menos uma foto."));
    	}
    	
    	$fotosexclui = $pars['chkdel'];
    	$jquerystr = "";
    	
    	foreach($fotosexclui as $chave => $valor)
    	{
    		@unlink($_SERVER['DOCUMENT_ROOT']."/www/uploads/perfis/" . $valor->ARQUIVO);
    		@unlink($_SERVER['DOCUMENT_ROOT']."/www/uploads/perfis/minichat_" . $valor->ARQUIVO);
    		@unlink($_SERVER['DOCUMENT_ROOT']."/www/uploads/perfis/minimsg_" . $valor->ARQUIVO);
    		
    		$fotosdel = new Tb_social_foto();
    		$fotosdel->get($valor);
    		$fotosdel->delete();
    		$jquerystr .= "#foto".$valor.",";
    		
    	}
    	
    	
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'msg' => utf8_encode("Foto(s) excluída(s) com sucesso!"),
    			 'jquerystr' =>  substr($jquerystr, 0, strlen($jquerystr)-1)
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode($e->getMessage()) 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
    public function limitaFotoUsuario($usuario_logado)
    {
    	$fotoPermitidas = 20;
    	
    	$obj = new Tb_glove_perfil();
    	$sql = "select count(*) as total from tb_social_foto as foto
    	inner join tb_social_albumfotos as album on foto.ID_ALBUM = album.ID_ALBUM
    	where album.ID_USUARIO = $usuario_logado";
    	$obj->query($sql);
    	$obj->fetch();
    	$total = $obj->total;
		if($total >= $fotoPermitidas)
		{
			throw  new Exception("Seu limite de fotos foi atingido! Total de fotos permitidos: $fotoPermitidas");
		}
    	
    }
    
	public function fotoperfilAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	
    	if(!isset($pars['chkdel']) || count($pars['chkdel']) == 0)
    	{
    		throw  new Exception(("Para excluir você deve selecionar pelo menos uma foto."));
    	}
    	
    	$foto = $pars['chkdel'];
    	
    	
    	$user = $this->usuario->ID_USUARIO;
    	$id_foto = $foto[0];

    	$obj = new Tb_glove_perfil();
    	$obj->get($user);
    	$obj->tb_social_foto = $id_foto;
    	$obj->update();
    	
    	
    	
    	$foto = new Tb_social_foto();
    	$foto->get($id_foto);
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'msg' => utf8_encode("Foto perfil alterada com sucesso!"),
    			 'file' => $foto->ARQUIVO 
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode($e->getMessage()) 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
	public function excluircomentarioAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$idComentario = $pars['idComentario'];
    	$idMensagem = $pars['idMensagem'];
    	
    	//$sql = "SELECT ID_USUARIO FROM tb_social_comentarios WHERE ID_COMENTARIO = '$idComentario'";
    	
    
    	$obj = new Tb_social_comentarios();
    	$obj->get($idComentario);
    	$obj->fetch();
    	
    	
    	if(($obj->tb_glove_perfil != $this->usuario->ID_USUARIO) && ($obj->tb_social_recados != $this->usuario->ID_USUARIO))
    	{
    		throw new Exception(("Esta mensagem não foi postada por você."));
    		
    	}
    	
    	
    	
    	$sql = "DELETE FROM tb_social_comentarios WHERE ID_COMENTARIO = '$idComentario'";
    	$obj = new Tb_glove_perfil();
    	$obj->query($sql);
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'post' => utf8_encode("Comentário excluído com sucesso!")
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode($e->getMessage()) 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    public function comentariodesgosteiAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$id = $pars['id'];
		
    
    	$sql = "SELECT * FROM tb_social_comentarios WHERE ID_COMENTARIO = '".$id."'";
    	$obj = new Tb_glove_perfil();
    	$obj->query($sql);
    	$obj->fetch();
    	$total_curtidas = $obj->CURTIDAS > 0 ? $obj->CURTIDAS  - 1 : 0;
    	$obj->destroy();
    	
    	
    	   	
    	
    	
    	$sql = "UPDATE  `tb_social_comentarios` SET  `CURTIDAS` =  '".$total_curtidas."' WHERE  `tb_social_comentarios`.`ID_COMENTARIO` = '".$id."' AND 1=1";
    	$obj = new Tb_glove_peso();
    	$obj->query($sql);
    	
    	    	
    	
    	
    	$obj = new Tb_socialcomentario_curtir();
    	$obj->where("ID_COMENTARIO = '$id' AND QUEM_CURTIU = '".$this->usuario->ID_USUARIO."' ");
    	$obj->delete(true);
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'msg' => utf8_encode("Você desfez isto!"),
    			 'total' => $total_curtidas
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode("Você já gosta disto!") 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
    public function gosteiAction()
    {
    	try {
    		
    	
    		
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$id = $pars['id'];
		
    
    	$sql = "SELECT * FROM tb_social_recados WHERE ID_RECADO = '".$id."'";
    	$obj = new Tb_glove_perfil();
    	$obj->query($sql);
    	$obj->fetch();
    	$total_curtidas = $obj->CURTIDAS + 1;
    	$dono = $obj->ID_USUARIO_DESTINATARIO;
    	$obj->destroy();
    	
    	
    	   	
    	
    	
    	$sql = "UPDATE  `tb_social_recados` SET  `CURTIDAS` =  '".$total_curtidas."' WHERE  `tb_social_recados`.`ID_RECADO` = '".$id."' AND 1=1";
    	$obj = new Tb_glove_peso();
    	$obj->query($sql);
    	
    	    	
    	
    	
    	$obj = new Tb_socialrecado_curtir();
    	$obj->tb_social_recados = $id;
    	$obj->tb_glove_perfil = $this->usuario->ID_USUARIO;
    	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    	$obj->insert();
    	
    	
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'msg' => utf8_encode("Você gostou disto!"),
    			 'total' => $total_curtidas,
    			 'data' => array("dono" => $dono, "remetente" =>  $this->usuario->ID_USUARIO)
	    		);
    	
    	} catch (Exception $e) {
    		
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode("Você já gosta disto!") 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
	 public function gosteicomentarioAction()
    {
    	try {
    		
    	
    		
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$id = $pars['id'];
		
    
    	$sql = "SELECT * FROM tb_social_comentarios WHERE ID_COMENTARIO = '".$id."'";
    	$obj = new Tb_glove_perfil();
    	$obj->query($sql);
    	$obj->fetch();
    	$dono = $obj->ID_USUARIO;
    	$total_curtidas = $obj->CURTIDAS + 1;
    	$obj->destroy();
    	
    	
    	   	
    	
    	
    	$sql = "UPDATE  `tb_social_comentarios` SET  `CURTIDAS` =  '".$total_curtidas."' WHERE  `tb_social_comentarios`.`ID_COMENTARIO` = '".$id."' AND 1=1";
    	$obj = new Tb_glove_peso();
    	$obj->query($sql);
    	$obj->destroy();
    	    	
    	
    	
    	$obj = new Tb_socialcomentario_curtir();
    	$obj->tb_social_comentarios = $id;
    	$obj->tb_glove_perfil = $this->usuario->ID_USUARIO;
    	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    	$obj->insert();
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'msg' => utf8_encode("Você gostou disto!"),
    			 'total' => $total_curtidas,
    			 'data' => array("dono" => $dono, "remetente" =>  $this->usuario->ID_USUARIO)
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode("Você já gosta disto!".$e->getMessage()) 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
 	public function desgosteiAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	$id = $pars['id'];
		
    
    	$sql = "SELECT * FROM tb_social_recados WHERE ID_RECADO = '".$id."'";
    	$obj = new Tb_glove_perfil();
    	$obj->query($sql);
    	$obj->fetch();
    	$total_curtidas = $obj->CURTIDAS > 0 ? $obj->CURTIDAS  - 1 : 0;
    	$obj->destroy();
    	
    	
    	   	
    	
    	
    	$sql = "UPDATE  `tb_social_recados` SET  `CURTIDAS` =  '".$total_curtidas."' WHERE  `tb_social_recados`.`ID_RECADO` = '".$id."' AND 1=1";
    	$obj = new Tb_glove_peso();
    	$obj->query($sql);
    	
    	    	
    	
    	
    	$obj = new Tb_socialrecado_curtir();
    	$obj->where("ID_RECADO = '$id' AND QUEM_CURTIU = '".$this->usuario->ID_USUARIO."' ");
    	$obj->delete(true);
    	
    	
    	$content = array(
		    	 'error' =>  'false',
	    		 'msg' => utf8_encode("Você desfez isto!"),
    			 'total' => $total_curtidas
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode("Você já gosta disto!") 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
	public function buscaatualizacoesAction()
    {
    	try {
    		
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$pars = $this->_request->getParams();
    	
        	
    	$content = array(
		    	 'error' =>  'false',
    			 'total' => My_Sites_Glove::contaatualizacoes($this->usuario->ID_USUARIO)
	    		);
    	
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' =>  'true',
	    		 'msg' => utf8_encode("Você já gosta disto!") 
	    		);
    	}
    	

        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
	public function getcomentariosAction()
    {
    	
    	$this->_helper->layout()->disableLayout();
    	
    	$pars = $this->_request->getParams();
  		$post = $pars['post'];
		$remetente = $pars['remetente'];
  		$pagina = $pars['pagina'];
  		
  		$comentarios = My_Sites_Glove::comentarios($post, $pagina, 10, $this->usuario->ID_USUARIO);
  		
 	
		$this->view->coments = $comentarios;
		$this->view->ID_REMETENTE_MSG = $remetente;
					
  		$this->render("ajax/comentarios");
  		
		$html = $this->getResponse()->getBody();
	
			
	    	$content = array(
		    	 'html' =>  $html,
		    	 'total' => $comentarios[2]
	    		);
		
			
	    	
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    public function addcomentarioAction()
    {
    	try {
    		
    	
    	
    	$pars = $this->_request->getParams();
  		$separa = str_replace("addcomentario", "", $pars['post']);
  		$exp = explode("_", $separa);
  		
  		$post = $exp[0];
  		$remetente = $exp[1]; 
  		
  		$comentario = $pars['msg'];
    	$id_usuario = $this->usuario->ID_USUARIO;
    	
    	
    	
  		$obj = new Tb_social_comentarios();
  		$obj->COMENTARIO = $comentario;
  		$obj->tb_social_recados = $post;
  		$obj->tb_glove_perfil = $id_usuario;
  		$obj->DT_CADASTRO = date("Y-m-d H:i:s");
  		$obj->DT_ULTIMAMODIFICACAO = date("Y-m-d H:i:s");
  		$obj->insert();
  		
  		/*$objcons = new Tb_social_recados();
  		$objcons->get($post);
  		$objcons->fetch();
  		*/
  		
  		$sql = "SELECT ID_USUARIO_REMETENTE FROM tb_social_recados WHERE ID_RECADO = '$post'";
    	$objcons = new Tb_glove_perfil();
    	$objcons->query($sql);
    	$objcons->fetch();
  		
  		
			
	    	$content = array(
		    	 'error' =>  'false',
	    		 'post' => $post,
	    		 'remetente' => $remetente,
	    		 'data' => array("dono" => $objcons->ID_USUARIO_DESTINATARIO, "remetente" =>  $this->usuario->ID_USUARIO)
	    		);
		
    	} catch (Exception $e) {
    		$content = array(
		    	 'error' => 'true',
	    		 'post' => $post,
    			 'remetente' => $remetente
	    		);
    	}	
	    	
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
    public function reportarsugestoesAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    
    	$pars = $this->_request->getParams();
    	$this->view->id = "";
    
    }
    
    public function reportarsugestoessalvarAction()
    {
    	try {
    		 
    
    		 
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender();
    
    		$auth = Zend_Auth::getInstance();
    		if (!($auth->hasIdentity())) {
    
    			throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
    		}
    
    		$id_usuario = $this->usuario->ID_USUARIO;
    		$post = $this->_request->getParams();
    
    		$id_post =  $post['id'];
    		$mensagem = $post['mensagem'];
    		$id_tipo = 3;
    
    
    		$obj = new Tb_socialauditoria();
    		$obj->MENSAGEM = $mensagem;
    		$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    		$obj->tb_socialauditoria_tipo = $id_tipo;
    		$obj->tb_glove_perfil = $id_usuario;

    
    		$obj->insert();
    
    
    
    
    		$msg = utf8_encode("Sugestão enviada com sucesso, <br>ela será analisada pela nossa equipe!");
    		$content = array('erro' => 'false', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
    
    	} catch (Exception $e) {
    
    		$msg = $e->getMessage();
    		$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
    		 
    	}
    
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'text/html')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    }
    
    
    
    public function reportarspamAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    	 
    	$pars = $this->_request->getParams();
    	$this->view->id = $pars['id'];
    	 
    }
    
    
    public function reportarspamsalvarAction()
    {
    	try {
    		 
    
    		 
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender();
    
    		$auth = Zend_Auth::getInstance();
    		if (!($auth->hasIdentity())) {
    
    			throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
    		}
    
    		$id_usuario = $this->usuario->ID_USUARIO;
    		$post = $this->_request->getParams();
    
    		$id_post =  $post['id'];
    		$mensagem = $post['mensagem'];
    		$id_tipo = 2;
    
    
    		$obj = new Tb_socialauditoria();
    		$obj->MENSAGEM = $mensagem;
    		$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    		$obj->tb_socialauditoria_tipo = $id_tipo;
    		$obj->tb_glove_perfil = $id_usuario;
    		$obj->ID_POST = $id_post;
    
    		$obj->insert();
    
    
    
    
    		$msg = utf8_encode("Report enviado com sucesso, <br>ele será analisado pela nossa equipe!");
    		$content = array('erro' => 'false', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
    
    	} catch (Exception $e) {
    
    		$msg = $e->getMessage();
    		$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
    			
    	}
    	 
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'text/html')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    }
  
    public function reportarabusoAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');    	
    	
    	$pars = $this->_request->getParams();
    	$this->view->id = $pars['id'];
    	
    }
    
       
    
    public function reportarabusosalvarAction()
    {
        try {
        	
        
    	
    		$this->_helper->layout()->disableLayout();
    		$this->_helper->viewRenderer->setNoRender();
    
    		$auth = Zend_Auth::getInstance();
    		if (!($auth->hasIdentity())) {
    				
    			throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
    		}
    
    		$id_usuario = $this->usuario->ID_USUARIO;
    		$post = $this->_request->getParams();

    		$id_post =  $post['id'];
    		$mensagem = $post['mensagem'];
    		$id_tipo = 1;
    		
    		
    		$obj = new Tb_socialauditoria();
    		$obj->MENSAGEM = $mensagem;
    		$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    		$obj->tb_socialauditoria_tipo = $id_tipo;
    		$obj->tb_glove_perfil = $id_usuario;
    		$obj->ID_POST = $id_post;
    		
    		$obj->insert();
    		
    		
    		
    		
    		$msg = utf8_encode("Report enviado com sucesso, <br>ele será analisado pela nossa equipe!");
    		$content = array('erro' => 'false', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
    
    		} catch (Exception $e) {
    		    
    		    $msg = $e->getMessage();
    		    $content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
    		   
    		}
    	  
    		$jsonData = Zend_Json::encode($content);
    		$this->getResponse()
    		->setHeader('Content-Type', 'text/html')
    		->setBody($jsonData)
    		->sendResponse();
    		exit;
    }
    
    
    public function compartilharAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    	 
    	$pars = $this->_request->getParams();
    	$this->view->id = $pars['id'];
    	 
    }
    
    
    
 	public function compartilharsalvarAction()
    {
    	try {
	      	$this->_helper->layout()->disableLayout();
		    $this->_helper->viewRenderer->setNoRender();
	    	
    	$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		 	throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
		}
		
		$id_usuario = $this->usuario->ID_USUARIO;
		$post = $this->_request->getParams();
    	
		
	
		$exp = explode("_",  $post['id']);
		
		$ID_RECADO_COMPARTILHADO = $exp[0];
		$ID_USUARIO_REMETENTE = $exp[1];
		$ID_USUARIO_DESTINATARIO = $exp[2];
		$ID_RECADO_PRIMEIRA_ORIGEM = $exp[3] == "" ? $ID_RECADO_COMPARTILHADO : $exp[3];
		$ID_TIPOPUBLICACAO = $exp[4];
		$ID_ALBUM =  $exp[5]  != "" ?  $exp[5] : "null";
		$ID_FOTO = $exp[6] != "" ? $exp[6] : "null";
		
	
		
		
		
		if($post['mensagem'] == "Escreva alguma coisa...")
		{
			$mensagem = "";	
		}else{
    	$mensagem = html_entity_decode($post['mensagem'], ENT_NOQUOTES, 'UTF-8');
    	$mensagem = strip_tags($mensagem, "<object><br><p><a><img><param><embed><iframe>");
    	$mensagem = addslashes($mensagem);
		}
		
    	$FL_PRIVATE = $post["private"];
    	$ID_USUARIO_COMPARTILHOU = $this->usuario->ID_USUARIO;
    	$FL_COMPARTILHAMENTO = 1;
    	$MENSAGEM_COMPARTILHAMENTO = $post['mensagem'];
    	$DT_COMPARTILHAMENTO = date("Y-m-d H:i:s");
    	$id_usuario = $this->usuario->ID_USUARIO;
    	
    		$obj = new Tb_glove_perfil();
    			$sql = "INSERT INTO  `glove`.`tb_social_recados` (
							`ID_USUARIO_REMETENTE` ,
							`ID_USUARIO_DESTINATARIO` ,
							`MENSAGEM` ,
							`FL_VISUALIZADO`,
							`DT_CADASTRO`,
							`FL_PRIVADA`,
							`ID_USUARIO_COMPARTILHOU`,
							`FL_COMPARTILHAMENTO`,
							`MENSAGEM_COMPARTILHAMENTO`,
							`ID_RECADO_COMPARTILHADO`,
							`DT_COMPARTILHAMENTO`,
							`ID_RECADO_PRIMEIRA_ORIGEM`,
							ID_TIPOPUBLICACAO,
							ID_ALBUM,
							ID_FOTO
							)
							VALUES (
							'".$id_usuario."',
							'".$ID_USUARIO_DESTINATARIO."',
							'', 
							0,  
							'".$DT_COMPARTILHAMENTO."',
							'".$FL_PRIVATE."',
							'".$ID_USUARIO_COMPARTILHOU."',
							'".$FL_COMPARTILHAMENTO."',
							'".$mensagem."',
							'".$ID_RECADO_COMPARTILHADO."',
							'".$DT_COMPARTILHAMENTO."',
							'".$ID_RECADO_PRIMEIRA_ORIGEM."',
							'".$ID_TIPOPUBLICACAO."',
							".$ID_ALBUM.",
							".$ID_FOTO."
						);";
    			
    			
    			
    			
    			
    			$obj->query($sql);
    			$obj->_getConnection()->close();
    			$obj->destroy();
    			 
	    		$msg = utf8_encode("Publicação compartilhada<br /> com sucesso!");
    		
    	
    	
    	
        $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => '');
		
		    
	    } catch (Exception $e) {
    		$msg = $e->getMessage();
	    	$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
	    }
	    
	    
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    public function addrecadoAction()
    {
    	try 
    	{
    		
	      	$this->_helper->layout()->disableLayout();
		    $this->_helper->viewRenderer->setNoRender();
	    	
    	$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		 	throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
		}
		
		$id_usuario = $this->usuario->ID_USUARIO;
		$post = $this->_request->getParams();
    	
		
		
		
		$id_amigo = $post['id_perfil'];
    	
    	
    	
    	
    	$is_amigo = $this->isFriend($id_amigo);
    	$fl_privada = $post['FL_PRIVADA'];
    	$ID_TIPOPUBLICACAO = $post['tb_social_tipo_publicacao'];   	
    	$dt = date("Y-m-d H:i:s");
    	
    	if($ID_TIPOPUBLICACAO == 1)
    	{
    	    
    	    if ($post['messageStatus'] == "") {
    	    		
    	    	throw new Exception(utf8_encode("Digite uma mensagem."));
    	    }
    	    
    	    $mensagem = html_entity_decode($post['messageStatus'], ENT_NOQUOTES, 'UTF-8');
    	    $mensagem = strip_tags($mensagem, "<object><br><p><a><img><param><embed><iframe>");
    	    $mensagem = addslashes($mensagem);
    		
    	}else if($ID_TIPOPUBLICACAO == 4) //se tipo for foto
    	{
    	    if ($post['message'] == "") {
    	    		
    	    	throw new Exception(utf8_encode("Digite uma mensagem."));
    	    }
    	    
    	    $mensagem = html_entity_decode($post['message'], ENT_NOQUOTES, 'UTF-8');
    	    $mensagem = str_replace("&nbsp;", "", $mensagem);
    	    $mensagem = strip_tags($mensagem, "<div><object><br><p><a><img><param><embed><iframe>");
    	    $mensagem = addslashes($mensagem);
    	    
    	   $file_recent_uploades = $post['file_recent_uploades'];    
    	   $pasta_upload_temp = $_SERVER['DOCUMENT_ROOT'].'/www/uploads/temp/';
    	   $pasta_upload_publicacoes = $_SERVER['DOCUMENT_ROOT'].'/www/uploads/publicacoes/';
    	   
    	   @unlink($pasta_upload_temp . 'tempmini_'.$file_recent_uploades);   	   
    	   if (copy($pasta_upload_temp . $file_recent_uploades, $pasta_upload_publicacoes . $file_recent_uploades)) 
    	   {
    	   		unlink($pasta_upload_temp . $file_recent_uploades);
    	   }
    	   
    	   
    	   /*$obj = new Tb_social_foto();
    	   $obj->tb_social_albumfotos = My_Sites_Glove::albumPadrao($id_usuario);
    	   $obj->ARQUIVO = $file_recent_uploades;
    	   $obj->DT_CADASTRO = date("Y-m-d H:i:s");
    	   $obj->save();
    	   $obj->_getConnection()->close();
    	   $obj->destroy();*/
    	   
    	   
    	   $mensagem = strip_tags($mensagem, "<img><div>");
    	   $dom = new Zend_Dom_Query($mensagem);
    	   $dom->setEncoding("utf-8");
    	   $results = $dom->query('img');
    	   $results2 = $dom->query('div');
    	   
    	   
    	 
    	   $legenda = null;
    	   $count = count($results2);
    	   $indice = 1;
    	   foreach ($results2 as $result) 
    	   {
    	   	 if($indice == 3)
    	   	 {
    	   	     $legenda = $post["legendaMidia"];
    	   	     $legenda = ltrim($legenda);
    	   	     $legenda = $legenda . "<br />";
    	   	 }
    	   	 
    	   	 $indice++;
    	   }
    	 	
    	 
    	   
    	   if($legenda == "Digite aqui sua legenda...<br />")
    	   {
    	       $legenda = "";
    	   }
    	   
    	   if($results->count() > 0)
    	   {
    	       foreach($results as $object)
    	       {
    	           $src = $object->getAttribute("src");
    	           $src = explode("?", $src);
    	           $src = $src[0];
    	           $src = str_replace("tempmini_", "", $src);
    	           $src = str_replace("/www/uploads/temp/", "/www/uploads/publicacoes/", $src);
    	           $mensagem = html_entity_decode("<img src=".$src."\"/>", ENT_NOQUOTES, 'UTF-8');
    	           $mensagem = addslashes($legenda . $mensagem);
    	       }
    	   }
    	   
    	 
    	
    	   
    	}else if($ID_TIPOPUBLICACAO == 2 ){ //se tipo for video

    	    $mensagem = $post['message'];
    	    $mensagem = strip_tags($mensagem, "<object><param><embed><iframe>");
    	    $mensagem = addslashes($mensagem);
    	 
    	}
    	
    	$obj = new Tb_glove_perfil();
    	$sql = "INSERT INTO  `glove`.`tb_social_recados` (
					`ID_USUARIO_REMETENTE` ,
					`ID_USUARIO_DESTINATARIO` ,
					`MENSAGEM` ,
					`FL_VISUALIZADO`,
					`DT_CADASTRO`,
					`FL_PRIVADA`,
					`ID_TIPOPUBLICACAO`
					)
					VALUES (
					'".$id_usuario."',  '".$id_amigo."', '".$mensagem."', 0,  '".$dt."', '".$fl_privada."', '".$ID_TIPOPUBLICACAO."' 
					);";
    			
    			$obj->query($sql);
    			$obj->_getConnection()->close();
    			$obj->destroy();
    			 
	    		$msg = utf8_encode("Mensagem enviada com <br />sucesso!");
    		
    	
    	
    	
        $content = array(
        		'erro' => 'false', 
        		'html' => '', 
        		'msg' => $msg, 
        		'data' => array("dono" => $id_amigo, "remetente" =>  $this->usuario->ID_USUARIO)
        	);
		
		    
	    } catch (Exception $e) {
    		$msg = $e->getMessage();
	    	$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
	    }
	    
	    
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    
	public function confirmaamigoAction()
    {
    	try {
	      	$this->_helper->layout()->disableLayout();
		    $this->_helper->viewRenderer->setNoRender();
	    	
    	$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		 	throw new Exception(utf8_encode("Sua sessão expirou favor efetuar o login novamente."));
		}
		
		$id_usuario = $this->usuario->ID_USUARIO;
		$post = $this->_request->getParams();
    	$id_amigo = $post['amigo'];
    	$status = $post['status'];
    	
    	if($status == 1)
    	{
    	
	    	$obj = new Tb_glove_perfil();
	    	$sql = "UPDATE  `tb_social_amigos` SET  `FL_ACEITOU` =  '".$status."' WHERE  `tb_social_amigos`.`ID_USUARIO` = '".$id_amigo."' AND  `tb_social_amigos`.`ID_AMIGO` = '".$id_usuario."';";
	    	$obj->query($sql);
	    	$obj->_getConnection()->close();
    		$obj->destroy(); 
	    	
	    	$obj = new Tb_glove_perfil();
	    	$sql = "INSERT INTO `tb_social_amigos` (ID_USUARIO, ID_AMIGO, DT_CADASTRO, FL_ACEITOU) 
	    			VALUES ('".$id_usuario."', '".$id_amigo."', NOW(), 1)";
	    	$obj->query($sql);
	    	$obj->_getConnection()->close();
    		$obj->destroy(); 
	    	
	    	$msg = utf8_encode("Confirmado com sucesso!");
    	
    	}else
    	{
			$obj = new Tb_glove_perfil();
			$sql = "DELETE FROM `tb_social_amigos` WHERE ID_USUARIO = '".$id_amigo."' AND ID_AMIGO = '".$id_usuario."'";
			$obj->query($sql);	 
			$obj->_getConnection()->close();
    		$obj->destroy(); 
	    	$msg = utf8_encode("A solicitação foi recusada.");    		
    	}
    	
    	
    	
        $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => '');
		
		    
	    } catch (Exception $e) {
    		$msg = $e->getMessage();
    		if(substr_count($msg, 'Duplicate') > 0)
    		{
    			$msg= "Confirmado com sucesso!";	
    		}
    		
	    	$content = array('erro' => 'true', 'html' =>  "", 'campos_erros' => "", 'error_html' => "", 'msg' => $msg);
	    }
	    
	    
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
 	
	public  function primeiroacessoAction()
    {
    	
		$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		   	$this->_response->setRedirect('/conta/logoff');
		}
		
		
		$this->view->usuario = $this->usuario;
		$id_usuario = $this->usuario->ID_USUARIO;
		
		$perfil = My_Sites_Glove::perfil($id_usuario);
		$this->view->perfil = $perfil;
		
		$this->_forward("editarperfil");
		
		/*echo "<pre>";
		print_r($perfil);
		echo "</pre>";*/
		
		$auth = Zend_Auth::getInstance();
		$storage = $auth->getStorage();
		
		
		if($storage->read() != null)
		{
			$this->view->logado = true;
		}else
		{
			$this->view->logado = false;
		}
		
    	
    }
    
	public function uploadfotoAction()
    {
    		
    
    	$pars = $this->_request->getParams();
    
    	
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $id_usuario = $pars['id_usuario'];
        
        $obj = new Tb_social_foto();
    	
        
       
    	$file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, 190, 210, true, $pasta_destino.$obj->ARQUIVO, 100);
        $uploadminifotochat = My_Plugins_Util::upload($file, $pasta_destino, 40, 40, true, $pasta_destino.$obj->ARQUIVO, 100);
        $uploadminifotomsg = My_Plugins_Util::upload($file, $pasta_destino, 50, 50, true, $pasta_destino.$obj->ARQUIVO, 100);
		
        
        
        @rename(
        	   $pasta_destino . $uploadminifotochat->getFileName(),
        	   $pasta_destino . 'minichat_'.$upload->getFileName()
        	   );
        	   
		@rename(
        	   $pasta_destino . $uploadminifotomsg->getFileName(),
        	   $pasta_destino . 'minimsg_'.$upload->getFileName()
        	   );
        

        $dadosAlbum = $pars['id_album'];
        
        
        $del_fotos = new Tb_social_foto();
        $del_fotos->where("ID_ALBUM = '$dadosAlbum'");
        $del_fotos->find(true);
        foreach ($del_fotos->allToObject() as $chave => $valor)
        {
            $obj_foto_del = new Tb_social_foto();
            $obj_foto_del->get($valor->ID_FOTO);
            
            @unlink(  $pasta_destino . $obj_foto_del->ARQUIVO);
            @unlink(  $pasta_destino . 'minichat_'.$obj_foto_del->ARQUIVO);
            @unlink(  $pasta_destino . 'minimsg_'.$obj_foto_del->ARQUIVO);
            
            $obj_foto_del->delete();
        }
        
        
        $obj->tb_social_albumfotos = $dadosAlbum; 
    	$obj->ARQUIVO = $upload->getFileName();
    	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    	$obj->save(); 
	   
    	
    	
    	$perfil = new Tb_glove_perfil();
    	$perfil->get($id_usuario);
    	$perfil->tb_social_foto =  $obj->ID_FOTO;
    	$perfil->save();
    	$perfil->_getConnection()->close();
    	$perfil->destroy();
    	 
    	
    	
    	    	
        $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000));
				
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;	
           
    }
    
    
	public function uploadfotoalbumAction()
    {
    	
    	
        try {
        	
        
        
    	$pars = $this->_request->getParams();
    	
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $id_usuario = $pars['id_usuario'];
        $this->limitaFotoUsuario($id_usuario);
        
        $obj = new Tb_social_foto();
    	
        
       
    	$file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, 800, 600, false, $pasta_destino.$obj->ARQUIVO);
         
        $dadosAlbum = $pars['id_album'];
        
        $obj->tb_social_albumfotos = $dadosAlbum; 
    	$obj->ARQUIVO = $upload->getFileName();
    	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    	$obj->save();
    	$id_nova_foto =  $obj->ID_FOTO;
	   	$obj->_getConnection()->close();
    	$obj->destroy(); 

    	
    	$obj = new Tb_social_albumfotos();
    	$obj->get($dadosAlbum);
    	$descricao = $obj->DESCRICAO; 
    	$obj->DT_MODIFICACAO =  date("Y-m-d H:i:s");
    	$obj->update();
    	$obj->destroy();
    	
    	
    	$obj = new Tb_glove_perfil();
    	$sql = "select * from tb_social_recados where ID_ALBUM = '".$dadosAlbum."'  and ID_USUARIO_REMETENTE = '".$id_usuario."'  and FL_COMPARTILHAMENTO = 0 ";
    	$query = $obj->query($sql);
    	
		$msg = utf8_encode($descricao);    
    	
    	if($query > 1)
    	{	
    	    $objupd = new Tb_glove_perfil();
    	    $sql = "update tb_social_recados set id_foto = '".$id_nova_foto."', MENSAGEM = '".$msg."', DT_CADASTRO = NOW() where ID_ALBUM = '".$dadosAlbum."'  and ID_USUARIO_REMETENTE = '".$id_usuario."'  and FL_COMPARTILHAMENTO = 0 and ID_FOTO is not null";
    	    $objupd->query($sql);    	    
    	}else{
    		
    	   $objins = new Tb_glove_perfil();
    	   $sql = " INSERT INTO `glove`.`tb_social_recados` (
    	    		`ID_RECADO` ,
    	    		`ID_USUARIO_REMETENTE` ,
    	    		`ID_USUARIO_DESTINATARIO` ,
    	    		`ID_TIPOPUBLICACAO` ,
    	    		`ID_ALBUM` ,
    	    		`ID_FOTO` ,
    	    		`MENSAGEM` ,
    	    		`COMPARTILHAMENTOS` ,
    	    		`CURTIDAS` ,
    	    		`FL_VISUALIZADO` ,
    	    		`DT_CADASTRO` ,
    	    		`FL_PRIVADA` ,
    	    		`ID_USUARIO_COMPARTILHOU` ,
    	    		`FL_COMPARTILHAMENTO` ,
    	    		`MENSAGEM_COMPARTILHAMENTO` ,
    	    		`ID_RECADO_COMPARTILHADO` ,
    	    		`DT_COMPARTILHAMENTO` ,
    	    		`ID_RECADO_PRIMEIRA_ORIGEM`
    	    )
    	    VALUES (
    	    		NULL , '".$id_usuario."', '".$id_usuario."', 5, '".$dadosAlbum."', '".$id_nova_foto."', '".$msg."', '0', '0', '0', NOW( ) , '0', NULL , '0', NULL , NULL , NULL , NULL
    	    );";
    	   $objins->query($sql);
    	    
    	}
    	
    	
    	
    	
    	
    	
    	
       	$content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000));
				
        } catch (Exception $e) {
            
            $content = array('erro' => 'true', 'msg' => utf8_encode($e->getMessage()), 'file' => '');
        }
        
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;	
           
    }
    
    
    public function uploadfotopublicacaoAction()
    {
    	 
    	 
    	$pars = $this->_request->getParams();
    	 
    	 
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	 
    	$id_usuario = $pars['id_usuario'];
    
    	$obj = new Tb_social_foto();
    	 
    
    	 
    	$file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
    	$pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
    	$upload = My_Plugins_Util::upload($file, $pasta_destino, 500, 400, false, $pasta_destino.$obj->ARQUIVO, 100);
    	$uploadminifoto = My_Plugins_Util::upload($file, $pasta_destino, 200, 130, true, $pasta_destino.$obj->ARQUIVO, 100);
    	  	
    	    	
    	@rename(
    			$pasta_destino . $uploadminifoto->getFileName(),
    			$pasta_destino . 'tempmini_'.$upload->getFileName()
    	);
    	
    	$dadosAlbum = $pars['id_album'];
    
    	/*$obj->tb_social_albumfotos = $dadosAlbum;
    	$obj->ARQUIVO = $upload->getFileName();
    	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    	$obj->save();
    	$obj->_getConnection()->close();
    	$obj->destroy();*/
    
    	$content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file_original' => $upload->getFileName(), 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000), 'fileminiatura' => 'tempmini_'.$upload->getFileName()."?rand=".rand(1, 100000000000));
    
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'text/json')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    	 
    }
    
    
    
    public  function salvaralbumAction()
    {
    	try {
    	
    	$this->_helper->layout()->disablelayout();
    	$this->_helper->viewRenderer->setNoRender();
		$pars = $this->_request->getParams();
		
		$id_usuario = $this->usuario->ID_USUARIO;
		
	
		
		$id_album = $pars['id_album'];
		$descricao = $pars['descricao'];
		$act = $pars['act'];
		
		$obj = new Tb_social_albumfotos();
		$obj->where("{tb_glove_perfil} = $id_usuario");
		if($obj->find() >= 5)
		{
			throw new Exception("Você atingiu o número máximo de álbuns por usuários.");
		} 
		
		
		if($act == "edit")
		{
			$obj->get($id_album);
			$msg = "Álbum atualizado com sucesso!";
			
		}else{
			
			$obj->DT_CADASTRO = date("Y-m-d H:i:s");
			$obj->FL_PADRAO = 0;
			$msg = "Álbum inserido com sucesso!";
		}
		
			$obj->tb_glove_perfil = $id_usuario;
			$obj->DESCRICAO = $descricao;
			$obj->ORDEM = 1;
			
			
		
			$obj->save();
				
		
			$content = array('erro' => 'false', 'msg' => $msg = utf8_encode($msg), 'id' => $obj->ID_ALBUM);
			
			$obj->_getConnection()->close();
    		$obj->destroy(); 
			 
		} catch (Exception $e){
			
			$content = array('erro' => 'true', 'msg' => utf8_encode('Houve em erro na tentativa. Detalhes do erro: '.$e->getMessage()));
			
		}        	
        		
  
        
        
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    
    }
    
    
	public  function verfotosAction()
    {
    	
    
    	if($this->getRequest()->isXmlHttpRequest())
		{
			
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    	
    	$pars = $this->_request->getParams();
    	$id = $pars['idfoto'];
    	
    	$obj = new Tb_social_foto();
		$obj->where("ID_ALBUM = '$id'");
		$obj->orderBy("DT_CADASTRO DESC");
		$obj->find();
		$this->view->fotos = $obj->allToObject();
		
		
		$this->view->usuario = $this->usuario;
		$id_usuario = $pars["idPerfil"];
		
		$perfil = My_Sites_Glove::perfil($id_usuario);
		
		
				
		Zend_Layout::getMvcInstance()->assign('pattern', $perfil->CSSCLASSPATTERN);
		Zend_Layout::getMvcInstance()->assign('color', $perfil->CSSCLASSCOLOR);
		
		$is_friend = $this->isFriend($perfil->tb_sys_usuario);
		$this->view->perfil = $perfil;
		
		
		
		$obj->_getConnection()->close();
    	$obj->destroy(); 
    }
    
    public  function buscaimagensajaxAction()
    {
    	$pars = $this->_request->getParams();
    	$id = $pars['id'];
    	
    	$obj = new Tb_social_foto();
		$obj->where("ID_ALBUM = '$id'");
		$obj->orderBy("DT_CADASTRO DESC");
		$obj->find();
		$this->view->fotos = $obj->allToObject();
		$this->render("ajax/buscaimagensajax");
		
		$obj->_getConnection()->close();
    	$obj->destroy(); 
    }
    
    public  function editalbumajaxAction()
    {
    	$pars = $this->_request->getParams();
    	
    	$id = $pars['id'];
    	$act = $pars['act'];
    	
    	$id_usuario = $this->usuario->ID_USUARIO;
    	
    	$obj = new Tb_social_albumfotos();
    	if($act == "edit")
    	{
	    	$obj->where("ID_ALBUM = '$id' AND ID_USUARIO = '$id_usuario'");
	    	$obj->find(true);
    	}
    	
    	$this->view->album = $obj->toObject();
    	$this->view->act = $pars['act'];
    	$this->render("ajax/editalbumajax");
    	
	   	$obj->_getConnection()->close();
    	$obj->destroy();     	
    }
    
    public  function buscaalbunsajaxAction()
    {
    	

		$this->view->usuario = $this->usuario;
		$id_usuario = $this->usuario->ID_USUARIO;
		
    	$this->view->albuns = My_Sites_Glove::albuns($id_usuario);
    	$this->render("ajax/buscaalbunsajax");	
    }    	
    
    
 
    public  function buscaalbunsajaxfrontendAction()
    {
		$pars = $this->_request->getParams();
		
    	$id_usuario = $pars["idPerfil"];
		$perfil = My_Sites_Glove::perfil($id_usuario);

		
    	$this->view->albuns = My_Sites_Glove::albuns($perfil->tb_sys_usuario);
    	$this->view->perfil = $perfil;
    	
    	$this->render("ajax/buscaalbunsajaxfrontend");	
    }    	
    
    
     public  function multmediaAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
		
		
		
    	
    	$this->view->usuario = $this->usuario;
		$id_usuario = $this->usuario->ID_USUARIO;
		
		$perfil = My_Sites_Glove::perfil($id_usuario);
		$this->view->fotoperfil = $perfil;
		$this->view->albumpadrao = My_Sites_Glove::albumPadrao($id_usuario);
        
    	
    }
       
    
    public  function meuperfilAction()
    {	
    	
    	
    		
    	if($this->getRequest()->isXmlHttpRequest())
		{
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    	
    	$pars = $this->_request->getParams();
    	
		$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		   	$this->_response->setRedirect('/conta/logoff');
		}
		
		
		$this->view->usuario = $this->usuario;
		$this->view->action = isset($pars["pagina"]) ? $pars["pagina"] : "";
		
		$this->view->albumpadrao = My_Sites_Glove::albumPadrao( $this->usuario->ID_USUARIO);
		
		$id_usuario = $pars["idPerfil"];
		
		
		
		
		$perfil = My_Sites_Glove::perfil($id_usuario);
		
	
		
		Zend_Layout::getMvcInstance()->assign('pattern', $perfil->CSSCLASSPATTERN);
		Zend_Layout::getMvcInstance()->assign('color', $perfil->CSSCLASSCOLOR);
		
		$is_friend = $this->isFriend($perfil->tb_sys_usuario);
		$this->view->perfil = $perfil;
		$this->view->isFriend = $is_friend;
		//$this->view->friends = My_Sites_Glove::amigos($perfil->tb_sys_usuario);
		//$this->view->totalSolicitacoes = My_Sites_Glove::contasolicitacoes($perfil->tb_sys_usuario);
		
		
		
		
		
		$this->view->pagina =  isset($pars['pagina']) ? $pars['pagina'] : null;
		
		
		if(isset($pars['pagina']))
		{
			switch ($pars['pagina']) {
				
			    case "perfil":
			        $this->view->is_friend =  $is_friend;
			    	$this->render("meuperfiloutro");
			    break;
				case "fotos":
					$this->render("paginas/fotos");
				break;	
				case "videos":
					$this->render("paginas/videos");
				break;		
				case "recado":
					$this->render("paginas/recado");
				break;
				case "editar":
					$this->_forward("editarperfil");
				break;
				case "friends":
					$this->_forward("friends");					
				break;
			}
		}
		
		
	
		
		
		
		if(@empty($pars['pagina']) && @$pars['pagina'] == "")
		{
			
		
			if(!empty($perfil->tb_sys_usuario) && $perfil->tb_sys_usuario == $this->usuario->ID_USUARIO){
					
				
			    $this->render("meuperfil");
				
				
			}else
			{
			
				
				if( $is_friend == false)
				{
				    $this->view->is_friend =  $is_friend;
					$this->render("meuperfiloutro");
					
				}else {
					
					if($is_friend->FL_ACEITOU == 1)
					{
						
							$this->render("meuperfil");
						
						
					}else {
						
					    $this->view->is_friend =  $is_friend;
						$this->render("meuperfiloutro");	
					}	
				}
			}
		}
		
		
		$auth = Zend_Auth::getInstance();
		$storage = $auth->getStorage();
		
		
		if($storage->read() != null)
		{
			$this->view->logado = true;
		}else
		{
			$this->view->logado = false;
		}
		
    	
    }

    
	public  function friendsAction()
    {
    	    	
    	if($this->getRequest()->isXmlHttpRequest())
		{
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    	
    	$pars = $this->_request->getParams();
    	
		$auth = Zend_Auth::getInstance();
		if (!($auth->hasIdentity())) {
			
		   	$this->_response->setRedirect('/conta/logoff');
		}
		
		
		$this->view->usuario = $this->usuario;
		$id_usuario = $pars["idPerfil"];
		
		
		
		
		$perfil = My_Sites_Glove::perfil($id_usuario);
		
		
		
		$is_friend = $this->isFriend($perfil->tb_sys_usuario);
		$this->view->perfil = $perfil;
		$this->view->isFriend = $is_friend;
		$page = isset($pars['page']) ? $pars['page'] : 1;
		
		//$this->view->friends = 
		//$this->view->pagina =  $page;
		
		
		$result = My_Sites_Glove::amigos($perfil->tb_sys_usuario, $page, 49);
		
		$content = array(
				'retorno' => $result["dados"],
				'total' => $result["total"],
				'total_paginas' =>  $result["total_paginas"],
				'paginaatual' => $page,
				'tamanho_pagina' => $result["TAMANHO_PAGINA"]
		);
		
		$this->view->resultado = $content;
		

    }
    
    public function solicitacoesAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');    	
    	
    }
    
    
    public function pegasolicitacoesAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    	 
    	$obj = new Tb_glove_perfil();
    	$sql = "SELECT tb_sys_usuario.SLUG, tb_sys_usuario.ID_USUARIO, tb_sys_usuario.*, tb_social_foto.ARQUIVO  FROM tb_social_amigos
    	INNER JOIN tb_sys_usuario ON tb_social_amigos.ID_USUARIO = tb_sys_usuario.ID_USUARIO
    	INNER JOIN tb_glove_perfil ON tb_glove_perfil.ID_USUARIO = tb_social_amigos.ID_USUARIO
    	LEFT JOIN tb_social_foto ON tb_social_foto.ID_FOTO = tb_glove_perfil.ID_FOTOPERFIL
    	WHERE
    	tb_social_amigos.ID_AMIGO = '".$this->usuario->ID_USUARIO."'
    	AND FL_ACEITOU = 0
    	ORDER BY tb_social_amigos.DT_CADASTRO DESC";
    	
    	
    	
    	$obj->query($sql);
    	$obj->fetch();
    	 
    	 
    	$dados = $obj->allToObject();
    	 
    	$this->view->modelo = $dados;
    	 
    	$this->render("ajax/solicitacoesajax");
    	$html = $this->getResponse()->getBody();
    	 
    	$content = array(
    			'html' =>  $html,
    			'dados' => $dados
    	);
    	 
    	 
    	 
    	 
    	$obj->_getConnection()->close();
    	$obj->destroy();
    	 
    	 
    	 
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'application/json')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    	 
    	 
    }
    
    
    public function conversasAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    
    }
    
    
    
    public function pegaconversaslogAction()
    {
    	
        
    	$this->_helper->_layout->setLayout('defaultblank');
    	
    	$remetente = $this->_request->getParam("remetente");
    	
    	$obj = new Tb_glove_perfil();
    	
    	$sql = "select 
		chat.ID_CHAT,
		chat.DATA_CADASTRO,
		chat.DATA_MODIFICACAO AS ULTIMA_MSG,
		chat.MENSAGEM,
		
		#Remetente
		usuario_remetente.ID_USUARIO as REMETENTE_ID_USUARIO,
		usuario_remetente.NOME as REMETENTE_NOME,
		usuario_remetente.SOBRENOME as REMETENTE_SOBRENOME,
		usuario_remetente.SLUG as REMETENTE_SLUG,
		
		#Destinatario
		usuario_destinatario.ID_USUARIO as DESTINARARIO_ID_USUARIO,
		usuario_destinatario.NOME as DESTINARARIO_NOME,
		usuario_destinatario.SOBRENOME as DESTINARARIO_SOBRENOME,
		usuario_destinatario.SLUG as DESTINARARIO_SLUG
		
		from 
		tb_social_chat as chat
		inner join tb_sys_usuario as usuario_remetente on usuario_remetente.ID_USUARIO = chat.ID_USUARIO_REMETENTE
		inner join tb_glove_perfil as perfil_remetente on usuario_remetente.ID_USUARIO = perfil_remetente.ID_USUARIO 
		left join tb_social_foto as foto_remetente on foto_remetente.ID_FOTO = perfil_remetente.ID_FOTOPERFIL
		
		inner join tb_sys_usuario as usuario_destinatario on usuario_destinatario.ID_USUARIO = chat.ID_USUARIO_DESTINATARIO
		inner join tb_glove_perfil as perfil_destinatario on perfil_destinatario.ID_USUARIO = usuario_destinatario.ID_USUARIO 
		left join tb_social_foto as foto_destinatario on foto_destinatario.ID_FOTO = perfil_destinatario.ID_FOTOPERFIL
		
		
		where 
		1=1
		and chat.ID_USUARIO_REMETENTE = '".$this->usuario->ID_USUARIO."'  
		and chat.ID_USUARIO_DESTINATARIO = '".$remetente."' 
		order by DATA_CADASTRO ASC";	 
    	
    	
    	$obj->query($sql);
    	$obj->fetch();
    
    
    	$dados = $obj->allToObject();
    
    	$this->view->modelo = $dados;
    	$this->view->id_user = $this->usuario->ID_USUARIO;
    
    	$this->render("ajax/conversasajaxlog");
    	$html = $this->getResponse()->getBody();
    
    	$content = array(
    			'html' =>  utf8_encode($html),
    			'dados' => $dados
    	);
    
    
    
    
    	$obj->_getConnection()->close();
    	$obj->destroy();
    
    
    
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'application/json')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    }
    
    
    public function pegaconversasAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    
    	$obj = new Tb_glove_perfil();
    	
    	$sql = "select 
		chat.ID_CHAT,
		chat.DATA_CADASTRO,
		MAX(chat.DATA_MODIFICACAO) ULTIMA_MSG,
		chat.MENSAGEM,
		
		#Remetente
		usuario_remetente.ID_USUARIO as REMETENTE_ID_USUARIO,
		usuario_remetente.NOME as REMETENTE_NOME,
		usuario_remetente.SOBRENOME as REMETENTE_SOBRENOME,
		usuario_remetente.SLUG as REMETENTE_SLUG,
		foto_remetente.ARQUIVO as REMETENTE_ARQUIVO,
		
		#Destinatario
		usuario_destinatario.ID_USUARIO as DESTINARARIO_ID_USUARIO,
		usuario_destinatario.NOME as DESTINARARIO_NOME,
		usuario_destinatario.SOBRENOME as DESTINARARIO_SOBRENOME,
		usuario_destinatario.SLUG as DESTINARARIO_SLUG,
		foto_destinatario.ARQUIVO as DESTINARARIO_ARQUIVO
		
		from 
		tb_social_chat as chat
		inner join tb_sys_usuario as usuario_remetente on usuario_remetente.ID_USUARIO = chat.ID_USUARIO_REMETENTE
		inner join tb_glove_perfil as perfil_remetente on usuario_remetente.ID_USUARIO = perfil_remetente.ID_USUARIO 
		left join tb_social_foto as foto_remetente on foto_remetente.ID_FOTO = perfil_remetente.ID_FOTOPERFIL
		
		inner join tb_sys_usuario as usuario_destinatario on usuario_destinatario.ID_USUARIO = chat.ID_USUARIO_DESTINATARIO
		inner join tb_glove_perfil as perfil_destinatario on perfil_destinatario.ID_USUARIO = usuario_destinatario.ID_USUARIO 
		left join tb_social_foto as foto_destinatario on foto_destinatario.ID_FOTO = perfil_destinatario.ID_FOTOPERFIL
		
		
		where 
		1=1
		and id_usuario_remetente = '".$this->usuario->ID_USUARIO."' 
		group by chat.ID_USUARIO_DESTINATARIO
		order by MAX(DATA_MODIFICACAO) DESC";	 
    	
    	$obj->query($sql);
    	$obj->fetch();
    
    
    	$dados = $obj->allToObject();
    
    	$this->view->modelo = $dados;
    
    	$this->render("ajax/conversasajax");
    	$html = $this->getResponse()->getBody();
    
    	$content = array(
    			'html' =>  $html,
    			'dados' => $dados
    	);
    
    
    
    
    	$obj->_getConnection()->close();
    	$obj->destroy();
    
    
    
    	$jsonData = Zend_Json::encode($content);
    	$this->getResponse()
    	->setHeader('Content-Type', 'application/json')
    	->setBody($jsonData)
    	->sendResponse();
    	exit;
    
    
    }
    

    
    
     public function atualizacoesajaxAction()
    {
    	$this->_helper->_layout->setLayout('defaultajax');    	

    	$pars = $this->_request->getParams();
    	$pagina = !isset($pars['pagina']) ? 1 : $pars['pagina'];
    	
    	$dados = My_Sites_Glove::atualizacoes($pagina, 8, $this->usuario->ID_USUARIO);
 		
    	
    	
    	$this->view->modelo = $dados[0]->alltoobject();
    	$this->view->total_paginas = $dados[1];
    	$this->view->pagina_atual = $pagina;
    	
    	$this->render("ajax/atualizacoesajax");	
    	
    }
    
    
 	public function atualizacoesAction()
    {
    	//$this->_helper->_layout->setLayout('defaultblank');    	
        $this->_helper->_layout->setLayout('defaultajax');
    
    }
    
  
    
 	
    public function isFriend($idPerfil)
    {
    	$obj = new Tb_glove_perfil();
    	$sql = "SELECT * FROM tb_social_amigos WHERE ID_USUARIO = '".$this->usuario->ID_USUARIO."' AND ID_AMIGO = '".$idPerfil." AND FL_ACEITOU = 1'";
    	
    	$total = $obj->query($sql);
    	$obj->fetch();
    	if($total > 0)
    	{	
    		
    		$data = $obj->toObject();
    		
    		
    		
	   		$obj->_getConnection()->close();
    		$obj->destroy();

    		return $data;
    	}else{
    		
    		return false;
    	}
    }
    
 	public function preDispatch() {
        $this->auth = Zend_Auth::getInstance();
    }
    
    
	public function logoffAction()
    {
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	Zend_Session::destroy();
    	@session_destroy();
    	
    	$pars = $this->_request->getParams();
    	if(isset($pars['another']))
    	{   	
    		$this->_redirect('/?another=place');
    	}else
    	{
    		$this->_redirect('/');
    	}
    	
    }
    
    public  function ploginAction()
    {
    	$pars = $this->_request->getParams();
    	
    	if(isset($pars['masterpage']) && $pars['masterpage'] == "false")
    	{
    		
    		$this->_helper->_layout->setLayout('defaultblank');
    	}
    	
    }
    
    
	public  function precuperarsenhaAction()
    {
    	
    	$this->_helper->_layout->setLayout('defaultblank');
    }
    
    
	public  function indicaramigoAction()
    {
    	
    	$this->_helper->_layout->setLayout('defaultblank');
    }
    
	public function logarAction() {
    	
    	//$this->_helper->_layout->setLayout('defaultblank');
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $request = $this->getRequest();
       
        $authAdapter = new My_Plugins_Autenticacao(addslashes($request->EMAIL), addslashes($request->SENHA));
        $result = $this->auth->authenticate($authAdapter);
       
        if ($result->isValid()) {
			
        	$msg = utf8_encode("Você será redirecionado para o seu perfil.");
        	$content = array('erro' => 'false', 'msg' =>  $msg);

        	$auth = Zend_Auth::getInstance();
			$this->auth = $auth;
	    	$this->usuario = $auth->getStorage()->read();
			
        	
			$id_usuario = $this->usuario->ID_USUARIO;
			
			
		
			$perfil = My_Sites_Glove::perfil($id_usuario);
			
			/*echo "<pre>";
			print_r($perfil);
			echo "</pre>";
			
			die($id_usuario);*/
			
			if($perfil->FL_ATIVO_usuario == 0)
			{
				$msg = utf8_encode("O seu usuário está inativo, ative-o pelo seu e-mail.");

			}else{
				
				$obj = new Tb_social_albumfotos();
				$obj->where("ID_USUARIO = $id_usuario");
				if($obj->find() == 0)
				{
						 	 
		       	 	 $album = new Tb_social_albumfotos();
		       	 	 $album->tb_glove_perfil = $perfil->tb_sys_usuario;
		       	 	 $album->DESCRICAO = "(sem nome)";
		       	 	 $album->DT_CADASTRO = date("Y-m-d H:i:s");
		       	 	 $album->FL_PADRAO = 1;
		       	 	 $album->insert();
		       	 	 $album->_getConnection()->close();
    				 $album->destroy();
       	 	 
				}
				
				if($perfil->FL_PRIMEIROACESSO_usuario == 1)
				{      	
	        		$this->_redirect("/conta/primeiroacesso");
				}else {
				    
				    if(isset($_SESSION["url_requisitada"]))
				    {
				        $this->_redirect($_SESSION["url_requisitada"]);
				    }
				    
					$this->_redirect("/meuperfil-".$id_usuario);
				}
			}
            
        } else {

        	// Do some error handling...
        	$msg = utf8_encode("Usuário ou senha inválidos...");
        	
        	echo utf8_encode("<script>window.parent.parent.$().toastmessage('showErrorToast', 'Usuário ou senha inválidos.');</script>");
        	
        	
        }
        
        $this->view->msg = $msg;
        
        $this->render("index/index", null, true);
        
        	/*	$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;*/
    }
    
    
	public function recuperarAction() {
    	
    	$this->_helper->layout()->disablelayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $request = $this->getRequest();
        
        $obj = new Tb_sys_usuario();
        $obj->where("EMAIL = '$request->EMAIL'");
        if($obj->find(true) > 0)
        {
        	
       
			// subject
			$subject = utf8_encode('Glove Brasil - Recuperação de Senha');
			
			// message
			$message = '
			<html>
			<head>
			<title>Glove Brasil Recuperação de Senha</title>
			</head>
			<body>
			<table width="500" height="154" border="0" cellpadding="8">
			  <tr>
			  <td><h2>Glove Brasil Solicitação de Recuperação de Senha</h2></td>
			  </tr>
			  <tr>
			    <td width="500">Olá '.$obj->NOME.', você solicitou através do site do Glove Brasil (www.glovebrasil.com.br) a recuperação de sua senha, segue abaixo seus dados de acesso:<br /><br />
			    Usuário: '.$obj->EMAIL.'<br />
			    Senha: '.$obj->SENHA.'<br /><br />
			    Obrigado por utilizar a nossa rede, a equipe do Glove Brasil agradece!<br /><br />
			    <a href="http://www.glovebrasil.com.br">http://www.glovebrasil.com</a>
			    </td>
			  </tr>
			</table>
			</body>
			</html>';
			
	
		try {
			
			$config = array('auth' => 'login',
					'username' => 'glove@glovebrasil.com.br',
					'password' => 'admglove');
			
			$transport = new Zend_Mail_Transport_Smtp('69.194.199.162', $config);
			
			$assunto = $subject;
			$mensagem = utf8_encode($message);
			$mail = new Zend_Mail("utf-8");
			$mail->setFrom("glove@glovebrasil.com.br", "Glove Brasil");
			$mail->setReplyTo("glove@glovebrasil.com.br", "Glove Brasil");
			$mail->setBodyHtml($mensagem);
			$mail->addTo($obj->EMAIL, $obj->NOME);
			$mail->setSubject($assunto);
			$mail->send($transport);
			
			
			$content = array('erro' => 'false', 'msg' => $msg = utf8_encode("Foi enviado um e-mail <br>com seus dados de acesso para o <br>seguinte endereço: <b>".$request->EMAIL."</b><br> caso não receba em sua caixa de entrada<br>verifique seus spams."));
			 
		} catch (Exception $e){
			
			$content = array('erro' => 'true', 'msg' => utf8_encode('Houve em erro na tentativa de envio do seu e-mail, tente novamente. Detalhes do erro: '.$e->getMessage()));
			
		}        	
        		
        	
        }else 
        {
        	$msg = utf8_encode("Usuário não encontrado <br />no Glove Brasil.");	
        	$content = array('erro' => 'true', 'msg' =>  $msg);	
        }
        
        
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }

		public function indicarAction() {
    	
		try {

			
			
			
    	$this->_helper->layout()->disablelayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $request = $this->getRequest();
        
       
        
        if(Lumine_Util::validateEmail($request->EMAIL) == false)
        {
        	throw  new Exception(utf8_encode("Informe um e-mail válido."));
        	
        }
        
       
       
			// subject
			$subject = utf8_encode('Glove Brasil - Convite');
			
			// message
			$message = '
			<html>
			<head>
			<title>Glove Brasil</title>
			</head>
			<body>
			<table width="500" height="154" border="0" cellpadding="8">
			  <tr>
			  <td><h2>Glove Brasil</h2></td>
			  </tr>
			  <tr>
			    <td width="500">Olá '.$request->NOME.' ('.$request->EMAIL.'), seu amigo '.$this->usuario->NOME.' lhe enviou um convite para participar da rede Glove Brasil!<br /><br />
			    Não perca tempo! Clique no link abaixo e efetue o seu cadastro em apenas 1 minuto.<br /><br />
			    <a href="http://www.glovebrasil.com.br/conta">http://www.glovebrasil.com.br</a>
			    </td>
			  </tr>
			</table>
			</body>
			</html>';
			
	
		
		
			$assunto = $subject;
			$mensagem = utf8_encode($message);
			$mail = new Zend_Mail("utf-8");
			$mail->setFrom("naoresponda@glovebrasil.com.br", "Glove Brasil");
			$mail->setReplyTo("suporte@glovebrasil.com.br", "Glove Brasil");
			$mail->setBodyHtml($mensagem);
			$mail->addTo($request->EMAIL, $request->NOME);
			$mail->setSubject($assunto);
			$mail->send();
			
			
			$content = array('erro' => 'false', 'msg' => $msg = utf8_encode("Convite enviado com sucesso para o seu amigo, <b>".$request->EMAIL."</b>"));
			 
		} catch (Exception $e){
			
			$content = array('erro' => 'true', 'msg' => $e->getMessage());
			
		}        	
        		
        	
       
        	
       
        
        
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
    private function enviaEmailParaConfirmacao($id_usuario)
    {
    	$this->_helper->layout()->disablelayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $request = $this->getRequest();
        
        $obj = new Tb_sys_usuario();
        $obj->get($id_usuario);
        if($obj->find(true) > 0)
        {
        	
       
			// subject
			$subject = utf8_encode('Glove Ativação de conta');
			
			// message
			$message = '
			<html>
			<head>
			<title>Glove Brasil Ativação de Usuário</title>
			</head>
			<body>
			<table width="500" height="154" border="0" cellpadding="8">
			  <tr>
			  <td><h2>Confirmação de cadastro Glove Brasil</h2></td>
			  </tr>
			  <tr>
			    <td width="500">Parabéns '.$obj->NOME.', você cadastrou sua conta com sucesso no Glove Brasil (www.glovebrasil.com.br) agora clique no link abaixo para acessar a sua conta.<br><br>
			    	
			    
			    
			    
			    <a href="http://www.glovebrasil.com.br">http://www.glovebrasil.com.br</a>
			    </td>
			  </tr>
			</table>
			</body>
			</html>';
			
	
		try {
		
			$assunto = $subject;
			$mensagem = utf8_encode($message);
			$mail = new Zend_Mail("utf-8");
			$mail->setFrom("naoresponda@glovebrasil.com.br", "Glove Brasil");
			$mail->setReplyTo("suporte@glovebrasil.com.br", "Glove Brasil");
			$mail->setBodyHtml($mensagem);
			$mail->addTo($obj->EMAIL, $obj->NOME);
			$mail->setSubject($assunto);
			$mail->send();
			
			
			$content = array('erro' => 'false', 'msg' => $msg = utf8_encode("Foi enviado um e-mail com seus dados de acesso para o seguinte endereço: <b>".$request->EMAIL."</b>"));
			 
		} catch (Exception $e){
			
			$content = array('erro' => 'true', 'msg' => utf8_encode('Houve em erro na tentativa de envio do seu e-mail, tente novamente. Detalhes do erro: '.$e->getMessage()));
			
		}        	
        		
        	
        }else 
        {
        	$msg = utf8_encode("Usuário não encontrado no Glove Brasil.");	
        	$content = array('erro' => 'true', 'msg' =>  $msg);	
        }
        
        
    }
    
	
    public function ativacaoAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$pars = $this->_request->getParams();
    	$id_usuario = $pars['usuario'];
    	
    	$obj = new Tb_sys_usuario();
    	$obj->get($id_usuario);
    	$obj->FL_ATIVO = 1;
    	$obj->update();
    	$obj->_getConnection()->close();
    	$obj->destroy();
    	
    	
    	
    	$script = "alert('Sua conta foi ativada com sucesso, favor efetuar o login!');";
    	$script .= "window.location = '/';";
		$scripts = $this->view->inlineScript();
		$scripts->appendScript($script);
    	
    }
    
    public function termosAction()
    {
    	$this->_helper->_layout->setLayout('defaultblank');
    	
    	
    }
    
 	public function salvartermosAction()
    {
    	
    	
    	$this->_helper->_layout->setLayout('defaultblank');
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$id = $this->usuario->ID_USUARIO;
    	
    	$obj = new Tb_sys_usuario();
    	$obj->get($id);
    	$obj->FL_PRIMEIROACESSO = 0;
    	$obj->ACEITOUTERMOS = 1;
    	
    	$obj->update();
    	
     	$obj->_getConnection()->close();
    	$obj->destroy();
    	   	
    	$script = 'window.parent.$.fancybox.close(true)';
		$scripts = $this->view->inlineScript();
		$scripts->appendScript($script);
    	
    	
    }
    
    
    
    public function fotosAction()
    {
    
    	if($this->getRequest()->isXmlHttpRequest())
		{
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    }
    
 	public function buscaAction()
    {
    	
    	
    	if($this->getRequest()->isXmlHttpRequest())
		{
    		$this->_helper->_layout->setLayout('defaultajax');
		}
    	
    	$idade = new Tb_glove_idade();
    	$idade->orderBy("ORDEM ASC")->find(true);
    	$idades = $idade->allToObject();
    	$idade->_getConnection()->close();
    	$idade->destroy();
    	
    	$altura = new Tb_glove_altura();
    	$altura->orderBy("ORDEM ASC")->find(true);
    	$alturas = $altura->allToObject();
    	$altura->_getConnection()->close();
    	$altura->destroy();
    	
    	$peso = new Tb_glove_peso();
    	$peso->orderBy("ORDEM ASC")->find(true);
    	$pesos = $peso->allToObject();
    	$peso->_getConnection()->close();
    	$peso->destroy();
    	
    	$tipo = new Tb_glove_tipo();
    	$tipo->orderBy("ORDEM ASC")->find(true);
    	$tipos = $tipo->allToObject();
    	$tipo->_getConnection()->close();
    	$tipo->destroy();
    	
    	$situacao = new Tb_glove_situacao();
    	$situacao->orderBy("ORDEM ASC")->find(true);
    	$situacoes = $situacao->allToObject();
    	$situacao->_getConnection()->close();
    	$situacao->destroy();
    	
    	$fumante = new Tb_glove_fumante();
    	$fumante->orderBy("ORDEM ASC")->find(true);
    	$fumantes = $fumante->allToObject();
    	$fumante->_getConnection()->close();
    	$fumante->destroy();
    	
    	$interesse = new Tb_glove_insteresse();
    	$interesse->orderBy("ORDEM ASC")->find(true);
    	$interesses = $interesse->allToObject();
    	$interesse->_getConnection()->close();
    	$interesse->destroy();
    	
    	
    	$disponibilidade = new Tb_glove_disponibilidade();
    	$disponibilidade->orderBy("ORDEM ASC")->find(true);
    	$disponivel = $disponibilidade->allToObject();
    	$disponibilidade->_getConnection()->close();
    	$disponibilidade->destroy();
    	
    	$bebida = new Tb_glove_bebidas();
    	$bebida->orderBy("ORDEM ASC")->find(true);
    	$bebidas = $bebida->allToObject();
    	$bebida->_getConnection()->close();
    	$bebida->destroy();
    	
    	
    	$id_usuario = $this->usuario->ID_USUARIO;
		
		$perfil = My_Sites_Glove::perfil($id_usuario);
		
		Zend_Layout::getMvcInstance()->assign('pattern', $perfil->CSSCLASSPATTERN);
		Zend_Layout::getMvcInstance()->assign('color', $perfil->CSSCLASSCOLOR);
		
		$is_friend = $this->isFriend($perfil->tb_sys_usuario);
		$this->view->perfil = $perfil;
		$this->view->isFriend = $is_friend;
    	
		$this->view->idade = $idades;
        $this->view->altura = $alturas;
        $this->view->peso = $pesos;
        $this->view->tipo = $tipos;
        $this->view->situacao = $situacoes;
        $this->view->fumante = $fumantes;
        $this->view->interesse = $interesses;
        $this->view->disponivel = $disponivel;
        $this->view->bebida = $bebidas;
        
        
        $auth = Zend_Auth::getInstance();
        $storage = $auth->getStorage();
         
         
        if($storage->read() != null)
        {
        	$this->view->logado = true;
        }else
        {
        	$this->view->logado = false;
        }
        
    }
    
    
	public function buscaresultadoajaxAction()
    {
    	$pars = $this->_request->getParams();
    	
    	$pagina = $pars['pagina'];
    	
    	$pagina = isset($pagina) ? $pagina : null;
    	$tamanhoPagina = 63;
    	
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
    	
    	/*echo "<pre>";
    	print_r($pars);
    	echo "</pre>";*/
    	
    	
    	$tb_glove_idade = isset($pars['tb_glove_idade']) ? $pars['tb_glove_idade'] : false;
    	$tb_glove_altura = isset($pars['tb_glove_altura']) ? $pars['tb_glove_altura'] : false;
    	$tb_glove_peso = isset($pars['tb_glove_peso'])? $pars['tb_glove_peso'] : false;
    	$tb_glove_tipo = isset($pars['tb_glove_tipo']) ? $pars['tb_glove_tipo'] : false;
    	$tb_glove_situacao = isset($pars['tb_glove_situacao']) ? $pars['tb_glove_situacao'] : false;
    	$tb_glove_fumante = isset($pars['tb_glove_fumante']) ? $pars['tb_glove_fumante'] : false;
    	$tb_glove_insteresse = isset($pars['tb_glove_insteresse']) ? $pars['tb_glove_insteresse'] : false;
    	$tb_glove_disponibilidade = isset($pars['tb_glove_disponibilidade']) ? $pars['tb_glove_disponibilidade'] : false;
    	$tb_glove_bebidas = isset($pars['tb_glove_bebidas']) ? $pars['tb_glove_bebidas'] : false;
    	$nome = isset($pars['NOME']) ? $pars['NOME'] : false;
    	
    	$tb_sys_cidade = isset($pars['tb_sys_cidade']) && !empty($pars['tb_sys_cidade']) ? $pars['tb_sys_cidade'] : false;
    	
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
		$obj->join($usuario);
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
    	
    	if($tb_glove_idade)
    	{
    		$obj->where("{tb_glove_idade} = $tb_glove_idade");
    	}
    	if($tb_glove_altura)
    	{
    		$obj->where("{tb_glove_altura} = $tb_glove_altura");
    	}
    	
    	if($tb_glove_peso)
    	{
    		$obj->where("{tb_glove_peso} = $tb_glove_peso");
    	}
    	
     	if($tb_glove_tipo)
    	{
    		$obj->where("{tb_glove_tipo} = $tb_glove_tipo");
    	}
    	
    	if($tb_glove_situacao)
    	{
    		$obj->where("{tb_glove_situacao} = $tb_glove_situacao");
    	}
    	
     	if($tb_glove_fumante)
    	{
    		$obj->where("{tb_glove_fumante} = $tb_glove_fumante");
    	}
    	
      	if($tb_glove_insteresse)
    	{
    		$obj->where("{tb_glove_insteresse} = $tb_glove_insteresse");
    	}
    	
       	if($tb_glove_disponibilidade)
    	{
    		$obj->where("{tb_glove_disponibilidade} = $tb_glove_disponibilidade");
    	}
    	
    	if($tb_glove_bebidas)
    	{
    		$obj->where("{tb_glove_bebidas} = $tb_glove_bebidas");
    	}
    	
    	if($tb_sys_cidade)
    	{
    		$obj->where("tb_sys_usuario.ID_CIDADE = $tb_sys_cidade");
    	}
    	
    	if($nome)
    	{
    	
    		$obj->where("CONCAT(tb_sys_usuario.NOME, ' ',  tb_sys_usuario.SOBRENOME) like '%$nome%'");
    	}	
    	
    	$total = $obj->count();
    	
    	$total_paginas = ceil($total / $TAMANHO_PAGINA);
    	
    	$obj->limit( $inicio, $TAMANHO_PAGINA );
    	
    	
    	$obj->find(true);
    	$result = $obj->allToObject();
    	
    	$obj->_getConnection()->close();
    	$obj->destroy();
    
    	
    		$content = array(
		    	 'retorno' => $result,
		    	 'total' => $total,
		    	 'total_paginas' =>  $total_paginas,
		    	 'paginaatual' => $pagina,
	    		 'tamanho_pagina' => $TAMANHO_PAGINA,
		  	     'inicio' => $inicio + 1,
		  		 'fim' => (count($result) < $TAMANHO_PAGINA) ? ($pagina * $TAMANHO_PAGINA) - ($TAMANHO_PAGINA - count($result)) : ($pagina * $TAMANHO_PAGINA)
	    	
 	    	);
    	
    	$this->view->resultado = $content;
    	
    	
    	$this->render("ajax/buscaresultadoajax");	
    	
    }
    
    
  	public function buscaresultadoAction()
    {
    	
        if($this->getRequest()->isXmlHttpRequest())
        {
        	$this->_helper->_layout->setLayout('defaultajax');
        }
        
      
        
    	
    	$pars = $this->_request->getParams();
    	$jsonData = Zend_Json::encode($pars);
    	
    	
    	
    	
    	$busca = new Zend_Session_Namespace('busca');
    	$busca->pars = $jsonData;
    	
    	
    	
    	
    	$id_usuario = $this->usuario->ID_USUARIO;
		
		$perfil = My_Sites_Glove::perfil($id_usuario);
		

		Zend_Layout::getMvcInstance()->assign('pattern', $perfil->CSSCLASSPATTERN);
		Zend_Layout::getMvcInstance()->assign('color', $perfil->CSSCLASSCOLOR);
		
		$is_friend = $this->isFriend($perfil->tb_sys_usuario);
		$this->view->perfil = $perfil;
		$this->view->isFriend = $is_friend;
	
    	
    	if(isset($pars['NOME']))
    	{
    		$this->view->json = $jsonData; 
    	}else
    	{
    		$this->view->json = $busca->pars; 
    	}
    	
    	
    	
    	$auth = Zend_Auth::getInstance();
    	$storage = $auth->getStorage();
    	
    	
    	if($storage->read() != null)
    	{
    		$this->view->logado = true;
    	}else
    	{
    		$this->view->logado = false;
    	}
    }
    
    
    
	public function chatsomAction()
    {
    	$this->_helper->layout()->disablelayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $request = $this->getRequest();
    
        
   		try
   		{
   			$obj = new Tb_glove_perfil();
   			$obj->get($this->usuario->ID_USUARIO);
   			$obj->FL_HABILITASOMCHAT = $request->cheched;
   			$obj->update();
		
			$content = array('erro' => 'false', 'msg' => $request->cheched);
			 
		} catch (Exception $e){
			
			$content = array('erro' => 'true', 'msg' => utf8_encode('Houve em erro na tentativa de envio do seu e-mail, tente novamente. Detalhes do erro: '.$e->getMessage()));
			
		}        	
        		
      
         
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    }
    
}
