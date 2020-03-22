<?php

class Admin_UsuariosController extends Zend_Controller_Action
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
	
    
	public function indexAction()
	{
		
	}
	

	
	public function formAction()
	{

		$estados = new Tb_sys_estado();	$estados->orderby("SGL_ESTADO")->find();
		$this->view->estados = $estados;	
		$this->view->modelo = new Tb_sys_usuario(); 
		

		
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
		
		
		 
		
		$obj = new Tb_sys_usuario();
		$obj2 = new Tb_sys_grupos();
		$obj->join($obj2, "INNER");
		
		$obj->selectAs();	
		$obj->selectAs($obj2, "%s_grupo");			
		
		$quanti = $obj->find();
		$obj->limit( $inicio, $TAMANHO_PAGINA );
		$quanti_limit = $obj->find();
		$total_paginas = ceil($quanti / $TAMANHO_PAGINA);
        $obj->orderBy("DT_CADASTRO DESC");
        $obj->find(true);
        $lista = $obj->allToObject();
        
        /*echo "<pre>";
        print_r($lista);
        echo "</pre>";*/

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
		
		$this->view->modelos = new Tb_sys_usuario();
		
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");

		$modelo = new Tb_sys_usuario();
		$modelo->join(new Tb_sys_cidade(), "LEFT");
		$modelo->get($id);
		$retorno = $modelo->toObject();
		
	
								
    	$this->view->modelos = $retorno;
    	$this->view->ID_USUARIO = $id;
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_sys_usuario();
	    	$obj->get($id);
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
				    	$obj = new Tb_sys_usuario();
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

    	
    	$id_usuario = $post["post"]["ID_USUARIO"];
     	
     	
    	
    	$obj = new Tb_sys_usuario();
    	$obj->get($id_usuario);
    	$obj->_setFrom($form);
    	
    	$result = $obj->validate();
				
		
		
		if(count($result) == 0){
			
			
			$contato = new Tb_sys_contato();
			$contato->get($obj->tb_sys_usuario_contato);
			$contato->_setFrom($form);
			$contato->save();
			
			$endereco = new Tb_sys_endereco();
			$endereco->get($obj->tb_sys_usuario_endereco);
			$endereco->_setFrom($form);
			$endereco->save();
			
			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 	$obj->DT_MODIFICACAO = date("Y-m-d H:i:s");
        	 }else
        	 {          	 	
        	 	$msg = "Registro atualizado com sucesso!";
        	 	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
        	 }
				
        	 $obj->tb_sys_contato = $contato->ID_CONTATO;
        	 $obj->tb_sys_endereco = $endereco->ID_ENDERECO;
        	 
        	 $obj->save();
        	 $json = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj->allToObject());
			
		}else{
			
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = ($erro);
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
    
  	public function treeselecionadosAction()
  	{
  		
  		$this->_helper->viewRenderer->setNoRender();
    	$post = $this->_helper->dingousJson();
    	    	
    	//$form = Zend_Controller_Action_Helper_DingousJson::paraArray($post["post"]["form"]);
    	
    	$id_grupo = $post["id"];
    	
    	$obj = new Tb_sys_grupos();
    	$obj->get($id_grupo);
    	$obj->_setFieldOption('tb_sys_admin_menus','lazy',true);
    	$obj->fetch();
    	$json = $obj->allToObject();
    	
    	  		
  		$jsonData = Zend_Json::encode($json);
				$this->getResponse()
				->setHeader('Content-Type', 'text/json')
				->setBody($jsonData)
				->sendResponse();
				exit;
  	}
	
}

