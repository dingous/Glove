<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	protected $_front;

	
	protected function _initAutoload()
    {
    	
        // Bootstrap layouts
		Zend_Layout::startMvc(array(
		    'layoutPath' => APPLICATION_PATH .  '/modules/default/layouts',
		    'layout' => 'default',
			'pluginClass' => 'My_Plugins_Layout'	
				    
		));
		
				
    }
	
     protected function _initTimeZone(){
        $date = $this->getOption('date');
        date_default_timezone_set($date['timezone']);
        
 
    }
    
    
	protected function _initViewController() 
	{
		Zend_Session::start();
		$this->bootstrap('FrontController');
	    $this->_front = $this->getResource('FrontController');
	
	}
	
 	protected function _initPlaceholders()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');
        
        $router = $this->_front->getRouter();
	    $req = new Zend_Controller_Request_Http();
	    $router->route($req);
	    
	    $module = $req->getModuleName();
   
		
	    
	    if($module == "admin")
	    {
        	$view->headTitle(utf8_encode('Invento Novo - Gerenciador de Conteúdo'))->setSeparator(' :: ');
	    }
    	else if($module == "default")
    	{
    		$view->headTitle(utf8_encode('Glove Brasil'))->setSeparator(' - ');
    		Zend_Registry::set("APLICACAO_CONTEXTO", 2);
    		
    	}
        
    	     	
    	 //$view->headMeta()->appendName('keywords', utf8_encode("".$franquia->NOME_CIDADE.", ".$franquia->SGL_ESTADO.", Guia, Cool"));
    	 //$view->headMeta()->appendName('description', utf8_encode("Guia cool"));
    	
        
    }
	
	
	
	protected function _initTranslate()
 	{
 		try {
 			$translate = new Zend_Translate('Array', APPLICATION_PATH .'/languages/pt_BR/Zend_Validate.php', 'pt_BR');
 			Zend_Validate_Abstract::setDefaultTranslator($translate);
 		}catch(Exception $e) {
 			die($e->getMessage());
 		}
 	}
	
	
 
    
  
    
	protected function _initHelpers()
	{
	    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	    $viewRenderer->initView();
	 
	    // add zend view helper path
	    $viewRenderer->view->addHelperPath('My/View/Helper/');
	 
	    // add zend action helper path
	    Zend_Controller_Action_HelperBroker::addPath('My/Controller/Helper/');
	}
	
	/**
     * Bootstrap the view doctype
     * 
     * @return void
     */
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->setEncoding('UTF-8');
        $view->doctype('XHTML1_TRANSITIONAL');
    }
    
    
    
	protected function _initLogado()
	{
		$router = $this->_front->getRouter();
	    $req = new Zend_Controller_Request_Http();
	    $router->route($req);
	    
	    $module = $req->getModuleName();
	    $controller = $req->getControllerName();
	    $action = $req->getActionName();
		$controlAction = $controller ."/".$action;
	    
		
		$actionPermitidos = array(
							"categorias/uploadicone",
							"categorias/uploadiconegr",
							"menus/uploadicone",
							"artista/uploadicone",
							"conteudos/uploadicone",
							"banners/uploadicone",
							"locutor/uploadicone",
							"coberturas/upload",
							"destaques/uploadicone",
							"conta/uploadfoto",
							"conta/uploadfotoalbum",
							"conta/uploadfotopublicacao"
							);
							
	    if($module == "admin" && !in_array($controlAction, $actionPermitidos))
	    {
	    	
			if($controller != "login")
			{	    
			    $auth = Zend_Auth::getInstance();
			    if (!($auth->hasIdentity())) {
			    	
			    	$response = new Zend_Controller_Response_Http();
	            	$response->setRedirect('/admin/login/logoff');
	            	$this->_front->setResponse($response);
	            	
	            	
			    } else 
			    {
			    
			    	
			    }
			}else
			{
				
			}
			
			
	    }else if($module == "default")
	    {
	    		    	
	    	$auth = Zend_Auth::getInstance();
	    	if (!($auth->hasIdentity())) {
	    		
	    		
	    		
	    	}
	    }
	    
	    
	}
	
	protected function _initLumine()
	{
		//esta instancia está atrapalhando a carregar as imagens
		new Lumine_ApplicationContext();
		//Lumine_Log::setLevel(3);
	}
  

}

