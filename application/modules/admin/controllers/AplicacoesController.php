<?php

class Admin_AplicacoesController extends Zend_Controller_Action
{

   public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_APLICACAO");
    	
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
		
		
		
		$obj = new Tb_sys_aplicacao();
		$obj->selectAs();
		$obj2 = new Tb_sys_empresa();
		$obj->selectAs($obj2, "%s_empresa");
		
		$obj->join($obj2);
				
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
    	
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");;
		
    	
    	
		$modelo = new Tb_sys_aplicacao();
		$modelo->get($id);
    	
		$retorno = $modelo->toObject();
		
		
		
    	$this->view->modelo = $retorno;
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_sys_aplicacao();
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
				    	$obj = new Tb_sys_aplicacao();
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
    	
    	$obj = new Tb_sys_aplicacao();
    	$post = $this->_request->getParams();
    	$id = $post["ID_APLICACAO"];
    	
    	if($this->tipo_acao == "insert")
    	{
    		
    	}
    	elseif($this->tipo_acao == "edit")
    	{
    		$obj->get($id);
			    		    		
    	}
    	
    	
    	
    	
    	$obj->_setFrom($post);
    	
    	
    	
		$result = $obj->validate();
				
		
		
		if(count($result) == 0){

			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 }else
        	 {          	 
        	 	$msg = "Registro inserido com sucesso!";
        	 }

        	 $obj->save();	
        	 
        	 $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj);
			
		}else{
			
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = utf8_encode($erro);
				}
			}
			
			$validacaoToHtml = My_Plugins_Util::validacaoToHtml($array_erros);
			
			$this->render("form");
			$html = $this->getResponse()->getBody();
	    	$content = array('erro' => 'valida', 'html' =>  $html, 'campos_erros' => $array_erros, 'error_html' => $validacaoToHtml, 'msg' => 'Preencha os campos obrigatorios');
		}
		
        
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
				
				
    }
	
	
}

