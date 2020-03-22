<?php

class Admin_MenuseconteudosController extends Zend_Controller_Action
{
    

	public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	$this->id = $this->getRequest()->getParam("id");

    	if($this->id != 0)
    	{
    		$this->tipo_acao = "edit";
    	}
    	
    	$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();

    }
	
	public function uploadiconeAction()
    {
    	$pars = $this->_request->getParams();
    	
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        
        
        $obj = new Tb_sys_admin_menu();
    	$obj->get($pars["id_menu"]);
    	$obj->fetch();
        
       
    	$file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, 15, 15, true, $pasta_destino.$obj->ICONE);
               
    	
    	$obj->ICONE = $upload->getFileName();
    	$obj->update(); 
			
         $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000));
				
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;	
           
    }
    public function salvarAction()
    {
    	$this->_helper->viewRenderer->setNoRender();
    	$post = $this->_helper->dingousJson();
    	    	
    	$form = Zend_Controller_Action_Helper_DingousJson::paraArray($post["post"]["form"]);

    	
    	
    	$id = $post["post"]["id_menu"];
    	$obj = new Tb_sys_admin_menu();
    	$obj->get($id);
    	
    	
    	$separa = explode("_", $form["tb_sys_aliasaction"]);
    	
    	$obj->ID_MODULO = $form["tb_sys_modulo"];
    	$obj->ACAO = $separa[0];
    	$obj->CONTROLADOR = $separa[1];
    	$obj->_setFrom($form);
    	
    	$result = $obj->validate();
				
		
		
		if(count($result) == 0){

			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 	
        	 }else
        	 {          	 	
        	 	$msg = "Registro atualizado com sucesso!";
        	 }

        	 
        	 $obj->save();
        	 $json = array('erro' => 'false', 'html' => 'xxx', 'msg' => $msg, 'modelo' => $obj->allToObject());
			
		}else{
			
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = $erro;
				}
			}
			
			$validacaoToHtml = My_Plugins_Util::validacaoToHtml($array_erros);
			
			$this->render("form");
			$html = $this->getResponse()->getBody();
	    	$json = array(
    						'erro' => 'valida',
	    					 'html' =>  $html,
	    					 'campos_erros' => $array_erros,
	    					 'error_html' => $validacaoToHtml,
	    					 'msg' => 'Preencha os campos obrigatorios'
	    					);
		}
    	
    	
    	
    	
    	
    	
    	$jsonData = Zend_Json::encode($json);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
    	
    	 
    	
    }
    
   
    
	
	
    public function indexAction ()
    {

        $jstree = new My_Plugins_jstree_classtreeMenusConteudos();
        
        
        if(isset($_GET["reconstruct"])) {
			$jstree->_reconstruct();
			die();
		}
		if(isset($_GET["analyze"])) {
			echo $jstree->_analyze();
			die();
		}
		
		if(isset($_REQUEST["operation"]) && strpos($_REQUEST["operation"], "_") !== 0 && method_exists($jstree, $_REQUEST["operation"])) {
			header("HTTP/1.0 200 OK");
			header('Content-type: application/json; charset=utf-8');
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Pragma: no-cache");
			echo $jstree->{$_REQUEST["operation"]}($_REQUEST);
			die();
		}
		header("HTTP/1.0 404 Not Found"); 

    }
    
    
    
    
 	public function editarAction()
    {
    	$id = $this->_request->getParam("id");
    	$modelo =  Tb_sys_admin_menu::staticGet($id);

    	/*$modulos = new Tb_sys_modulo();  $modulos->orderby("DESCRICAO_MODULO"); $modulos->find();
    	
    	$this->_helper->viewRenderer->setNoRender();
    	$this->view->modelo = $modelo->allToObject();
    	$this->view->modulos = $modulos;*/
      	$this->render("form");
    	
    }
    
    
    
	public function getactionsAction()
	{
		$id = $this->_request->getParam("idModulo");
		
		$obj = new Tb_sys_modulo();
		$obj->get($id);
		$obj->fetch();
		
		$classe = ucwords($obj->CONTROLADOR)."Controller";
		$metodos = Admin_MenusController::getMetodos($classe);
		
		$array = array();
		
		
		//print_r($metodos);
		foreach($metodos as $chave => $valor)		
		{
			if(strpos($valor->getName(), "Action") == true){
				$array[] = array("ACAO" => $valor->name, "CONTROLADOR" => $classe);
			}
		}
		
		
    	$jsonData = Zend_Json::encode($array);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;
		
	}
	
	
    
    
    public static function getMetodos($classe)
    {
       
	    	require_once $_SERVER['DOCUMENT_ROOT']."/application/modules/admin/controllers/".$classe.".php";
	    	$fileReflect = new Zend_Reflection_File($_SERVER['DOCUMENT_ROOT']."/application/modules/admin/controllers/".$classe.".php");
	    	$methods = $fileReflect->getClass("Admin_".$classe)->getMethods(); 
	    	return $methods;
    		
    }
    
    
    public static function getBlocoDocs($classe, $metodo)
    {	
    	try {
    		$reflectorMethod = new Zend_Reflection_Method($classe, $metodo);
    		$docblock = $reflectorMethod->getDocblock();
	 		return $docblock;
 		
    	} catch (Exception $e) {
    	}
    
    }
  
    
    

}
