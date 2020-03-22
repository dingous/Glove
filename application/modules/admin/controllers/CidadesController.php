<?php 

class Admin_CidadesController extends Zend_Controller_Action{ 

	public $id = 0;
	public $tipo_acao = "insert";
	public $targetPath = '/www/uploads/cms/foto_chamadas/';
	
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_CIDADE");
    	
    	if($this->id != 0 && $this->id != "")
    	{
    		$this->tipo_acao = "edit";
    	}
    	
  		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
    	
    	if($this->getRequest()->getActionName() != "uploadicone")
    	{
    	
    	  $auth = Zend_Auth::getInstance();
    	  $read = $auth->getStorage()->read();
    	  $this->view->aplicacoes = $read->aplicacoes;
    	} 
    	 
  	
    }
	
	public function getestadoAction()
	{
		$id = $this->_request->getParam("idPais");
			
		$obj = new Tb_sys_estado();
		$obj->where("ID_PAIS = '".$id."'");
		$obj->find(true);
		$json = $obj->allToJSON(true);
		
		$obj->_getConnection()->close();
		
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($json)
		->sendResponse();
		exit;	
	}
    
	public function indexAction()
	{
				
		$obj = new Tb_sys_pais();
		$obj->orderby("NOME_PAIS");
		$obj->find();
		$obj->fetch();
		
		$paises = $obj->allToObject();
		
		$obj->_getConnection()->close();
		
		$this->view->paises = $paises;
	}
	
	
	
	public function formAction()
	{
		$obj = new Tb_sys_pais();
		$obj->orderby("NOME_PAIS");
		$obj->find();
	
		$this->view->paises = $obj;
	}
	
		
	
	
	public function gridAction()
	{  
	
		$pagina = $this->getRequest()->getParam("pagina");
		$pais = $this->getRequest()->getParam("pais");
		$estado = $this->getRequest()->getParam("estado");
		$criterio = $this->getRequest()->getParam("criterio");
		$criterio = addslashes($criterio);
		$criterio = htmlspecialchars($criterio, ENT_QUOTES);
		
		
		$TAMANHO_PAGINA = 20;
		
		if($pagina == null)
		{
			$inicio = 0;
		}else
		{
			$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
		}
		
		
		
		$obj = new Tb_sys_cidade();
		$obj->selectAs();
		$obj2 = new Tb_sys_estado();
		$obj3 = new Tb_sys_pais();
		
		
		$obj->selectAs($obj3, "%s_pais");
		$obj2->join($obj3);
		
		$obj->selectAs($obj2, "%s_uf");
		$obj->join($obj2);
		
		
		
		if($pais != null)
		{
			$obj->where("tb_sys_estado.ID_PAIS = $pais");
		}
		
		if($estado != null)
		{
			$obj->where("tb_sys_cidade.ID_ESTADO = $estado");
		}
		
		if($criterio != null)
		{
			//$obj->where("TITULO LIKE _utf8 '%".$criterio."%' COLLATE utf8_general_ci || CONTEUDO LIKE _utf8 '%".$criterio."%' COLLATE utf8_general_ci");
			$obj->where("NOME_CIDADE LIKE  '%".$criterio."%'");
		}
		
		$quanti = $obj->find();
		$obj->limit( $inicio, $TAMANHO_PAGINA );
		$quanti_limit = $obj->find();
		$total_paginas = ceil($quanti / $TAMANHO_PAGINA);
        $obj->orderBy("NOME_CIDADE ASC");
        $obj->find(true);
        $lista = $obj->allToObject();
		
        
        $obj->_getConnection()->close();
       
        
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
    	/*$modelo = new Tb_cms_conteudo();
    	$this->view->modelo = $modelo;*/
    	
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");;
		
    	    	
		$modelo = new Tb_sys_cidade();
		
		$obj = new Tb_sys_estado();
		
			
		$modelo->join($obj);
		$modelo->get($id);
    	
		$retorno = $modelo->allToObject();
    	$this->view->modelo = $retorno[0];
    	
    	
		
		$this->view->id = $id;
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_sys_cidade();
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
				    	$obj = new Tb_sys_cidade();
				    	$obj->get($chave);
				    	$obj->fetch();
				    	
			        
				    	$obj->delete();
		    		}  
		    		
		    		$content = array('erro' => 'false', 'html' => '', 'msg' => "Registros excluídos com sucesso!");
		    		
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
    	
    	$obj = new Tb_sys_cidade();
    	
    	$post = $this->_request->getParams();
    	$id = $post["ID_CIDADE"];
    	
    	if($this->tipo_acao == "insert")
    	{
    		
    	}
    	elseif($this->tipo_acao == "edit")
    	{
    		$obj->get($id);
			    		    		
    	}
    	

    	
    	
    	
    	$obj->_setFrom($post);
    	
    	
    	$obj->NOME_CIDADE = utf8_decode($post['NOME_CIDADE']);
    	
   	
    	
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

?>