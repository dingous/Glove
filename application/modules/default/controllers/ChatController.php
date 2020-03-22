<?php

class ChatController extends Zend_Controller_Action
{

    public function init()
    {
      	    $auth = Zend_Auth::getInstance();
			$this->auth = $auth;
	    	$this->usuario = $auth->getStorage()->read();
			$this->view->usuario = $this->usuario;
    }
	
    public function amigosAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
    	
    	$id_usuario = $this->usuario->ID_USUARIO;
    	$json = My_Sites_Glove::amigos($id_usuario, null, 500000000000);
    	
    	 $jsonData = Zend_Json::encode($json["dados"]);
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($jsonData)
			->sendResponse();
			exit;	
    }
    
    
   
    
    public function meAction()
    {	
    	try {
    		
    	
    	
    	if($this->auth->hasIdentity() == false)
    	{
    		throw  new Exception("Não está logado", 0);
    	}
    	
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    
    	$id_usuario = $this->usuario->ID_USUARIO;
    	
    	/*"id" : data.tb_sys_usuario,
									"nome" : data.NOME,
									"slug" : data.SLUG,									
									"foto" : data.ARQUIVO,	*/
    	$obj = new Tb_glove_perfil();
    	$obj2 = new Tb_sys_usuario();
    	$obj3 = new Tb_social_foto();
    	$obj->select("NOME, SLUG, ARQUIVO, tb_sys_usuario.ID_USUARIO as tb_sys_usuario");
    	$obj->join($obj2);
    	$obj->join($obj3, "LEFT");
    	$obj->get($id_usuario);
    	
    	$json = $obj->toObject();
    	
    	
    	$obj->_getConnection()->close();
    	$obj->destroy();
    	
    	} catch (Exception $e) {
    		
    		$json = false;
    		
    	}
    	
    	  $jsonData = Zend_Json::encode($json);
    	  $this->getResponse()
			->setHeader('Content-Type', 'application/json')
			->setBody($jsonData)
			->sendResponse();
			exit;	
    	
    }
	
    
    public function buscasessoesAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
    	
    	$id_usuario = $this->usuario->ID_USUARIO;
    	
    	
    	$sessaoLocais = new Zend_Session_Namespace('sessaoChat');
    	
    	$json = $sessaoLocais->registros;
    	
    	 $jsonData = Zend_Json::encode($json);
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($jsonData)
			->sendResponse();
			exit;
    }
    
    public function registerAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    
	    
	    $pars = $this->_request->getParams();
	    $id_usuario = $this->usuario->ID_USUARIO;
	    
	    $amigo = $pars['friend'];
	    
	    $sessaoLocais = new Zend_Session_Namespace('sessaoChat');
    	$acessados = $sessaoLocais->__get("registros");
    	if(!is_array($acessados))
    	{
    		$acessados = array();
    	}
    	    	
    	if(!array_key_exists($amigo, $acessados))
    	{
    		$sessaoLocais->registros[$amigo] = array($id_usuario, "maximizado");
    	} 
		
    	$json = $sessaoLocais->registros;
    	
    	 $jsonData = Zend_Json::encode($json);
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($jsonData)
			->sendResponse();
			exit;
    }
	
    
	public function windowstateAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    
	    
	    $pars = $this->_request->getParams();
	    $id_usuario = $this->usuario->ID_USUARIO;
	    $amigo = $pars['friend'];
	    $window_state = $pars['state'];
	    
	    $sessaoLocais = new Zend_Session_Namespace('sessaoChat');
    	$acessados = $sessaoLocais->__get("registros");
    	if(is_array($acessados))
    	{
	    	if(array_key_exists($amigo, $acessados))
	    	{
	    		$sessaoLocais->registros[$amigo] = array($id_usuario, $window_state);
	    	}
    	}
    	    	
    	 
		
    	$json = $sessaoLocais->registros;
    	
    	 $jsonData = Zend_Json::encode($json);
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($jsonData)
			->sendResponse();
			exit;
    }
    
 	public function unregisterAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    $pars = $this->_request->getParams();
	    $id_usuario = $this->usuario->ID_USUARIO;
	    
	    
	    
	    $amigo = $pars['friend'];
	    
	    $sessaoLocais = new Zend_Session_Namespace('sessaoChat');
    	$acessados = $sessaoLocais->__get("registros");
    	
    	    	
    	//if(array_key_exists($amigo, $acessados))
    	//{
    		unset($sessaoLocais->registros[$amigo]);
    	//} 
		
    	$json = $sessaoLocais->registros;
    	
    	 $jsonData = Zend_Json::encode($json);
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($jsonData)
			->sendResponse();
			exit;
    }
    
	public function registerlogAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    
	    
	    $pars = $this->_request->getParams();
	    $id_usuario = $this->usuario->ID_USUARIO;
	    
	    $amigo = $pars['friend'];
	    $msg = $pars['msg'];
	    
	    $sessaoLocais = new Zend_Session_Namespace('sessaoChat');
    	$acessados = $sessaoLocais->__get("logs");
    	if(!is_array($acessados))
    	{
    		$acessados = array();
    	}
    	    	
    	if(!array_key_exists($amigo, $acessados))
    	{
    		$sessaoLocais->logs[$amigo."_".$id_usuario] = $msg;
    	} 
		
    	$json = array("");
    	
    	 $jsonData = Zend_Json::encode($json);
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($jsonData)
			->sendResponse();
			exit;
    }
    
    public function getlogAction()
    {
    	$this->_helper->layout()->disableLayout();
	    $this->_helper->viewRenderer->setNoRender(true);
	    
	    
	    $pars = $this->_request->getParams();
	    $id_usuario = $this->usuario->ID_USUARIO;
	    
	    $sessaoLocais = new Zend_Session_Namespace('sessaoChat');
	    
	    $json = $sessaoLocais->logs;
	    
	    $obj = new Tb_glove_perfil();
    	$sql = "SELECT ID_CHAT,
    	 			   ID_USUARIO_REMETENTE,
    	 			   ID_USUARIO_DESTINATARIO,
    				   MENSAGEM 
    			FROM tb_social_chat AS MSG 
    			WHERE 1=1 
    			AND MSG.ID_USUARIO_DESTINATARIO = '$id_usuario' 
    			AND DATE_FORMAT(MSG.DATA_CADASTRO, '%d/%m/%Y') = DATE_FORMAT(NOW(), '%d/%m/%Y')
    			ORDER BY MSG.DATA_CADASTRO LIMIT 1
    			";
    	
    	
    	
    			
    			$obj->query($sql);
    			$obj->fetch();
    			//$json = $obj->allToObject();
    			$json = $obj->allToJSON(true, true);
    			
    			$obj->_getConnection()->close();
    			$obj->destroy();   
	    
	    
	     //$jsonData = Zend_Json::encode($json);
	     
    	$jsonData = $json;
    			
    	  $this->getResponse()
			->setHeader('Content-Type', 'text/json')
			->setBody($jsonData)
			->sendResponse();
			exit;
	    
    }
    
    
    public function getlogusuarioAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    
    	$pars = $this->_request->getParams();
    	$id_usuario = $this->usuario->ID_USUARIO;
    	$amigo = $pars["amigo"];
    	 
    	$obj = new Tb_glove_perfil();
    	 
    	$inicio = date("Y-m-d") . " 00:00:00";
    	$fim = date("Y-m-d") . " 11:59:59";
    	 
    	$sql = "SELECT * FROM tb_social_chat WHERE ID_USUARIO_DESTINATARIO = '".$id_usuario."' AND ID_USUARIO_REMETENTE = '".$amigo."' AND DATA_CADASTRO >= '".$inicio."'  AND DATA_CADASTRO <= '".$fim."'";
    	$obj->query($sql);
    	$obj->fetch();
    	$json = $obj->allToJSON(true, true);
    	$obj->_getConnection()->close();
    	$obj->destroy();
    
    	$this->getResponse()
    	->setHeader('Content-Type', 'text/json')
    	->setBody($json)
    	->sendResponse();
    	exit;
    }
    
    
}

