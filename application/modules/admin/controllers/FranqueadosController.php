<?php

class Admin_FranqueadosController extends Zend_Controller_Action
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
	
    
	public function indexAction()
	{
		
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
	
	public function gridaliasAction()
	{

		$obj = new Tb_sys_aliasaction();
		$obj->where("ID_MODULO = "+$this->id+"");
		$obj->find();
		$obj->fetch();
		$this->view->dados = $obj->allToObject();
		$class_methods = get_class_methods(__CLASS__);
		$this->view->metodos = $class_methods;
		
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
		
		
		 
		
		$obj = new Tb_sys_franqueado();
		$quanti = $obj->find();
		$obj->limit( $inicio, $TAMANHO_PAGINA );
		$quanti_limit = $obj->find();
		$total_paginas = ceil($quanti / $TAMANHO_PAGINA);
        $obj->orderBy("NOME_FRANQUEADO ASC");
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

    	$obj = new Tb_sys_franqueado();
    	$obj->selectAs();
    	$obj_cidade = new Tb_sys_cidade();
    	$obj->selectAs($obj_cidade, "%s_cidade");
   		$obj_estado = new Tb_sys_estado();
   		$obj->selectAs($obj_estado, "%s_estado");
    	$obj_usuario = new Tb_sys_usuario();
    	$obj->selectAs($obj_usuario, "%s_usuario");
    	$obj_endereco = new Tb_sys_endereco();
    	$obj->selectAs($obj_endereco, "%s_endereco");
    	$obj_contato = new Tb_sys_contato();
    	$obj->selectAs($obj_contato, "%s_contato");
    	
    	$obj_cidade->join($obj_estado);
    	$obj_usuario->join($obj_endereco, "LEFT");
    	$obj_usuario->join($obj_contato, "LEFT");
    	$obj->join($obj_usuario, "LEFT");
    	$obj->join($obj_cidade, "LEFT");   	   	
    	$obj->get($id);
    	$obj->find();
    	$obj->fetch();
    	
    	//echo $obj->_getSQL();
    	
    	$this->view->modelo = $obj->allToObject();
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
	    	$obj = new Tb_sys_modulo();
	    	$obj->get($this->id);
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
				    	$obj = new Tb_sys_modulo();
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
    
	public function insertAction()
    {	
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$obj = new Tb_sys_franqueado();
    	$post = $this->_request->getParams();
    	$id = $post["ID_USUARIO"];
    	   	    	
    	    	
    	if($this->tipo_acao == "edit")
    	{
    		$obj->get($id);	    		
    	}
    	
    	$endereco = Tb_sys_endereco::staticGet($obj->tb_sys_endereco);
		if($endereco->numrows() == 0) $endereco->ID_ENDERECO = null;
		$endereco->_setFrom($post);
		$endereco->save();
       
       
    	$contato = Tb_sys_contato::staticGet($obj->tb_sys_contatos);
    	if($contato->numrows() == 0) $contato->ID_CONTATO = null;
		$contato->_setFrom($post);
    	$contato->save();
    	
    	
    	$obj->_setFrom($post);
    	$obj->tb_sys_grupos = 1;
    	$obj->tb_sys_tipousuario = 1;
    	$obj->tb_sys_cidade = 1;
    	$obj->tb_sys_usuario_endereco = $endereco->ID_ENDERECO;
    	$obj->tb_sys_usuario_contato = $contato->ID_CONTATO;
    	
           	       	
    	
    	
		$result = $obj->validate();
				
		
		
		if(count($result) == 0){

			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 }else
        	 {          	 	
        	 	$obj->save();
        	 	$msg = "Registro inserido com sucesso!";
        	 }
	        	 
        	 $content = array('erro' => 'false', 'html' => '', 'msg' => $msg);
			
		}else{
			
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = $erro;
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

