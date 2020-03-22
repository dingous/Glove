<?php
class My_Plugins_Plugin extends Zend_Controller_Plugin_Abstract
{
		
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {

    	
		 $front = Zend_Controller_Front::getInstance();
		 $router = $front->getRouter();
		
		 
		  $route = new Zend_Controller_Router_Route_Regex(
				    '(.+)-(\d+)',
				    array(
				        'controller' => 'conta',
				        'action'     => 'meuperfil',
				    	'module' => 'default' 
				    ),
				    array(
				    	1 => 'slugPerfil',
				    	2 => 'idPerfil'
				    ),
				    '%s-%s'
				);
			$router->addRoute('perfil', $route);
		  
		 
		 $route = new Zend_Controller_Router_Route_Regex(
				    '(.+)-(\d+)/(.+)',
				    array(
				        'controller' => 'conta',
				        'action'     => 'meuperfil',
				    	'module' => 'default' 
				    ),
				    array(
				    	1 => 'slugPerfil',
				    	2 => 'idPerfil',
				    	3 => 'pagina'
				    ),
				    '%s-%s/%s'
				);
			$router->addRoute('perfil2', $route);
			
			
			$route = new Zend_Controller_Router_Route_Regex(
				    '(.+)-(\d+)/fotos/(\d+)',
				    array(
				        'controller' => 'conta',
				        'action'     => 'verfotos',
				    	'module' => 'default' 
				    ),
				    array(
				    	1 => 'slugPerfil',
				    	2 => 'idPerfil',
				    	3 => 'idfoto'
				    ),
				    '%s-%s/fotos/%s'
				);
			$router->addRoute('perfil3', $route);
			
			
			
		 
    }
 
    public function pegaEstadosComLocais()
    {
    	$sql = "select uf.ID_ESTADO, uf.SGL_ESTADO, uf.NOME_ESTADO from tb_gcool_local as local
		inner join tb_sys_cidade as cidade on local.ID_CIDADE = cidade.ID_CIDADE
		inner join tb_sys_estado as uf on uf.ID_ESTADO = cidade.ID_ESTADO
		group by uf.ID_ESTADO";
    	
    	$obj = new Tb_sys_estado();
    	$obj->query($sql);
    	$obj->fetch();
    	$dados = $obj->allToObject();
    	$obj->_getConnection()->close();
    	$obj->destroy();
    	return $dados;
    }
    
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
		  
    	 $auth = Zend_Auth::getInstance();
         $sessao_usuario = $auth->getStorage()->read();
    	
         
         
		  $profile = new Zend_Session_Namespace('perfil');
              	
		  $pars  = $request->getParams(); 		
		  //print_r($pars);
		  if(isset($pars['slugPerfil']) && $pars['slugPerfil'] != "meuperfil")
		  {
		  	
		  	$idPerfil = $pars['idPerfil'];
		  	
		  	$obj = new Tb_sys_usuario();
		  	$obj->get($idPerfil);
		  	$obj->fetch();
		  	
		  	if(!empty($obj->ID_USUARIO))
		  	{
		  		$profile->slug = $obj->SLUG."-".$obj->ID_USUARIO;
		  		$profile->idPerfil = $obj->ID_USUARIO;
		  	}else {
		  		
		  		//header("location: /conta/naoencontrado");
		  		
		  	}
		  	
		  	$obj->_getConnection()->close();
    		$obj->destroy();
		  	
		  }else 
		  {
		  	
		  	if(isset($sessao_usuario) && $sessao_usuario->ID_USUARIO != null)
		  	{
		  		$profile->slug = "meuperfil-".$sessao_usuario->ID_USUARIO;
		  		$profile->idPerfil = $sessao_usuario->ID_USUARIO;
		  		
		  	}else
		  	{
		  		//header("location: /conta/naoencontrado");
		  	}
		  	
		  }  	
    	 
		
		  
		//$this->getResponse()->appendBody("<p>routeShutdown() called</p>\n");
    }
 
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
    	
        //$this->getResponse()->appendBody("<p>dispatchLoopStartup() called</p>\n");
    }
 
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
         parent::preDispatch($request);         
         
		//verifica se e uma requisicao via ajax
		if($request->isXmlHttpRequest())
		{
			$ViewHelper = Zend_Controller_Action_HelperBroker::getStaticHelper("ViewRenderer");
			$ViewHelper->setNoRender(true);
			Zend_Layout::getMvcInstance()->disableLayout();
		}
	
		$module = strtolower( $request->getModuleName() );
		$controller = strtolower( $request->getControllerName() );
		$action = strtolower( $request->getActionName() );
		
		if($module == "admin")
		{
		 
        $auth = Zend_Auth::getInstance();
        $a = $auth->getStorage()->read();
        
      
        
       
        if($auth->hasIdentity())
        {
        	//echo "<pre>";
        	//print_r($a->permissoes);
        	//echo "</pre>";
        	//die();
        	
        	if($controller != "login")
        	{
        	
	        $indice = strtolower($controller."controller_".$action."action");
	        if(!(array_key_exists($indice, $a->permissoes)))
	        {
				
	        	//se for ajax
				if($request->isXmlHttpRequest())
				{
	        		$this->getResponse()->appendBody(utf8_encode("<p>Você não tem permissão para acessar esta página.</p>\n"));
	        		die('Você não tem permissão para acessar esta página ou este tipo de ação!');
					
				}else
				{
					$this->getResponse()->appendBody(utf8_encode("<p>Você não tem permissão para acessar esta página.</p>\n"));
	        		die('Você não tem permissão para acessar esta página ou este tipo de ação!');
	        		
				}
	        }
        	}
        }
		}
        
        
		
		//$this->getResponse()->appendBody("<p>preDispatch() called</p>\n");
    }
 
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        //$this->getResponse()->appendBody("<p>postDispatch() called</p>\n");
    }
 
    public function dispatchLoopShutdown()
    {
        //$this->getResponse()->appendBody("<p>dispatchLoopShutdown() called</p>\n");
    }
}


