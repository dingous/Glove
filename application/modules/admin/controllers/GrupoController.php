<?php

class Admin_GrupoController extends Zend_Controller_Action
{
	
	public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_MODULO");
    	
    	if($this->id != 0 && $this->id != "")
    	{
    		$this->tipo_acao = "edit";
    	}
    	
  		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
  		
    	
    	
		
    }
	
    
    
   /**
	* Lista grupos cadastrados
	* @author Diego Salinas
	* @return mixed
	*/
	public function indexAction()
	{
		
	}
	
	
	public function salvaraliasAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$post = $this->_request->getParams();
		$obj = new Tb_sys_aliasaction();
		$obj->tb_sys_modulo = $post["ID_MODULO"];
		$obj->DESCRICAO_ALIAS = $post["DESCRICAO_ALIAS"];
		$obj->NOME_ALIAS = $post["NOME_ALIAS"];
		$obj->save();
			
	}
	
	public function deletaraliasAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$post = $this->_request->getParams();
		$obj = new Tb_sys_aliasaction();
		$obj->get($post["id"]);
		$obj->delete();
			
	}
	
	public function formAction()
	{

		$estados = new Tb_sys_estado();	$estados->orderby("SGL_ESTADO")->find();
		$this->view->estados = $estados;	
		

		
	}
	
	public function getcidadesAction()
	{
		$id = $this->_request->getParam("idCidade");
			
		$obj = new Tb_sys_cidade();
		$obj->where("ID_ESTADO = '".$id."'");
		$obj->find(true);
		$json = $obj->allToJSON(true);
		
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($json)
		->sendResponse();
		exit;	
	}
	

	
	public function gridAction()
	{  
	
		$pagina = $this->getRequest()->getParam("pagina");
		
		$TAMANHO_PAGINA = 20;
		
		if($pagina == null)
		{
			$inicio = 0;
		}else
		{
			$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
		}
		
		
		 
		
		$obj = new Tb_sys_grupos();
		$quanti = $obj->find();
		$obj->limit( $inicio, $TAMANHO_PAGINA );
		$quanti_limit = $obj->find();
		$total_paginas = ceil($quanti / $TAMANHO_PAGINA);
        $obj->orderBy("DT_CADASTRO DESC");
        $obj->find(true);
        $lista = $obj->allToObject();

        $this->view->total_registros = $quanti;
        $this->view->lista = $lista;
        $this->view->total_paginas = $total_paginas;
        $this->view->pagina = $quanti == 0 ? 0 : $pagina;
        $this->view->proxima = ($pagina+1) <= $total_paginas ? ($pagina+1) : $pagina;
        $this->view->anterior = ($pagina-1) >= 1 ? ($pagina-1) : 1;
        $this->view->ultima = $total_paginas;
		
	
		
        
	}
	
    
	public function cadastrarAction()
    {
    	
		$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs({ disabled: [1] });');
		
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");;

		$modelo = new Tb_sys_grupos();
		$modelo->_setFieldOption('tb_sys_modulo_has_tb_sys_aliasaction','lazy',true);
		$modelo->get($id);
		

		
		$modulos = new Tb_sys_modulo();  $modulos->orderBy("DESCRICAO_MODULO"); $modulos->find();  $obj_modulo = $modulos->allToObject();

		
		//$controladores = scandir($_SERVER['DOCUMENT_ROOT']."/application/modules/admin/controllers");
		$this->view->controladores = $modulos;
		
		
		$retorno = $modelo->allToObject();
		
		
    	$array = array();
		foreach($retorno[0]->tb_sys_modulo_has_tb_sys_aliasaction as $chave => $valor)
		{
			$array[] = $valor->ACAO."_".$valor->CONTROLADOR."_".$valor->tb_sys_modulo;
		}
		
		
		
    	$this->view->modelo = $retorno[0];
    	$this->view->modulos = $obj_modulo;
    	$this->view->ID_GRUPO = $id;
    	$this->view->marcados = $array;
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id_grupo =  $this->getRequest()->getParam("id");
    		
    		    	
	    	$obj_menus_grupos_del = new Tb_sys_modulo_has_tb_sys_aliasaction();
	    	$obj_menus_grupos_del->where("ID_GRUPO = $id_grupo");
	    	$obj_menus_grupos_del->find();
	    	$obj_menus_grupos_del->delete(true);
    		
	    	$obj = new Tb_sys_grupos();
	    	$obj->get($id_grupo);
	    	$obj->fetch();
	    	$obj->delete();
	    	$content = array('erro' => 'false', 'html' => '', 'msg' => "Registro excluido com sucesso");
	    	
    	} catch (Exception $e) 
    	{
				
			 $content = array('erro' => 'true', 'html' => '', 'msg' => $e->getMessage());
				
		}
			$jsonData = Zend_Json::encode($content);
			$this->getResponse()
			->setHeader('Content-Type', 'text/html')
			->setBody($jsonData)
			->sendResponse();
			exit;	
    }
    
	public function excluirselecionadosAction()
	    {
	    	try 
	    	{
	    		$itens = isset($_POST['exclui']) ? $_POST['exclui'] : array();
	    		if(count($itens) > 0)
	    		{
		    		foreach ($itens as $chave => $valor)
		    		{
				    	$obj = new Tb_sys_grupos();
				    	$obj->get($chave);
				    	$obj->fetch();
				    	$obj->delete();
				    	$content = array('erro' => 'false', 'html' => '', 'msg' => "Registros excluídos com sucesso!");
		    		}
		    	}else
		    	{
		    		$content = array('erro' => 'true', 'html' => '', 'msg' => "Selecione um registro");
		    	}
		    	
	    	} catch (Exception $e) 
	    	{
					
				 $content = array('erro' => 'true', 'html' => '', 'msg' => $e->getMessage());
					
			}
				$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;	
    }
    

	
	
	
	public function salvarAction()
    {
    	$this->_helper->viewRenderer->setNoRender();
    	$post = $this->_helper->dingousJson();
    	    	
    	$form = Zend_Controller_Action_Helper_DingousJson::paraArray($post["post"]["form"]);

    
    	
    	$id_grupo = $post["post"]["ID_GRUPO"];
    	$selecionados = $post["post"]["selecionados"];
    	
    	if($id_grupo != "")
    	{
    	
	    	$obj_menus_grupos_del = new Tb_sys_modulo_has_tb_sys_aliasaction();
	    	$obj_menus_grupos_del->where("ID_GRUPO = $id_grupo");
	    	$obj_menus_grupos_del->find();
	    	$obj_menus_grupos_del->delete(true);
	    	
	    	foreach($selecionados as $chave => $valor)
	    	{
				$separa = explode("_", $valor);
				$acao = $separa[0];
				$controlador = $separa[1];
				$modulo = $separa[2]; 
				
	    		
	    		$obj_menus_grupos = new Tb_sys_modulo_has_tb_sys_aliasaction();
	    		$obj_menus_grupos->tb_sys_modulo = $modulo;
	    		$obj_menus_grupos->ACAO = $acao;
	    		$obj_menus_grupos->CONTROLADOR = $controlador;
	    		$obj_menus_grupos->tb_sys_grupos = $id_grupo;
	    		$obj_menus_grupos->insert();
	    	}
    	}
    	
    	
    	
   
    	
    	$obj = new Tb_sys_grupos();
    	$obj->get($id_grupo);
    	$obj->DESCRICAO = $form["DESCRICAO"];
    	
    	$result = $obj->validate();
				
		
		
		if(count($result) == 0){

			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 	
        	 }else
        	 {          	 	
        	 	$msg = "Registro atualizado com sucesso!";
        	 	$obj->DT_CADASTRO = Zend_Date::now();
        	 }

        	 
        	 $obj->save();
        	 
        	         	         	
        	 
        	 $json = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj->ID_GRUPO);
			
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
    
  	

    
  
    
    
	public function preDispatch()
    {
    	
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

