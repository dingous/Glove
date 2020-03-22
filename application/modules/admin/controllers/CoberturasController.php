<?php

class Admin_CoberturasController extends Zend_Controller_Action
{

   public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_COBERTURA");
    	
    	if($this->id != 0 && $this->id != "")
    	{
    		$this->tipo_acao = "edit";
    	}
    	
  		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
    	
    	    	    	
    	$auth = Zend_Auth::getInstance();
    	$read = $auth->getStorage()->read();
    	$this->view->aplicacoes = $read->aplicacoes;
  	
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
		$app = $this->getRequest()->getParam("app");
		
		$TAMANHO_PAGINA = 20;
		
		if($pagina == null)
		{
			$inicio = 0;
		}else
		{
			$inicio = ($pagina - 1) * $TAMANHO_PAGINA;
		}
		
		
		
		$obj = new Tb_cms_coberturas();
		
		if($app != null)
		{
			$obj->where("ID_APLICACAO = $app");
		}
				
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

		$modelo = new Tb_cms_coberturas();
		$modelo->_setFieldOption('tb_sys_aliasaction','lazy',true);
		$modelo->get($id);
    	
		$retorno = $modelo->allToObject();
    	$this->view->modelo = $retorno[0];
    
    	return $this->_forward('form');
    }
    
    
    public function excluifotos($idCobertura)
    {
    	$obj = new Tb_cms_coberturasfotos();
    	$obj->where("ID_COBERTURA = $idCobertura");
    	$obj->find(true);
    	
    	foreach($obj->alltoobject() as $chave => $valor)
    	{
    		$objdel = new Tb_cms_coberturasfotos();
    		$objdel->get($valor->ID_FOTO);
    		
    		unlink($_SERVER['DOCUMENT_ROOT']."/www/uploads/cms/coberturas/".$valor->CAMINHO_FOTO);
    		
    		$objdel->delete();
    	}
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_coberturas();
	    	$obj->get($id);
	    	$obj->fetch();
	    	
	    	
	    		    	
	    	$this->excluifotos($obj->ID_COBERTURA);
	    	
	    	
	    	
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
    
    
	public function excluifotoAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_coberturasfotos();
	    	$obj->get($id);
	    	$obj->fetch();
  	
	    	
	    	unlink($_SERVER['DOCUMENT_ROOT']."/www/uploads/cms/coberturas/".$obj->CAMINHO_FOTO);
	    	
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
    
    
	public function fotocapaAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_coberturasfotos();
	    	$obj->get($id);
	    	$obj->fetch();
  	
	    	$obj2 = new Tb_cms_coberturas();
	    	$obj2->get($obj->tb_cms_coberturas);
	    	$obj2->fetch();
	    	$obj2->CAMINHO_FOTOCAPA = $obj->CAMINHO_FOTO;
	    	$obj2->update();
	    	
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
				    	$obj = new Tb_cms_coberturas();
				    	$obj->get($chave);
				    	$obj->fetch();
				    	
				    	$this->excluifotos($obj->ID_COBERTURA);
				    	
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
    	
    	$obj = new Tb_cms_coberturas();
    	$post = $this->_request->getParams();
    	$id = $post["ID_COBERTURA"];
    	
    	if($this->tipo_acao == "insert")
    	{
    		$obj->DT_CADASTRO = date("Y-m-d H:i:s");
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
        	 
        	 $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj, 'tipo_acao' => $this->tipo_acao);
			
		}else{
			
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = ($erro);
				}
			}
			
			$validacaoToHtml = My_Plugins_Util::validacaoToHtml($array_erros);
			
			$this->render("form");
			$html = $this->getResponse()->getBody();
	    	$content = array('erro' => 'valida', 'html' =>  $html, 'campos_erros' => $array_erros, 'error_html' => $validacaoToHtml, 'msg' => 'Preencha os campos obrigatorios', 'tipo_acao' => $this->tipo_acao);
		}
		
        
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
				
				
    }
	
	
	
    
    
	public function uploadAction()
    {
    	try {
    		
    	
    	
    	$pars = $this->_request->getParams();
    	    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	 $file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, 0, 600, false);
        
		
    
      
        
        
        if(isset($pars["ID_COBERTURA"]))
        {
        	
           	$obj = new Tb_cms_coberturasfotos();
	    	$obj->tb_cms_coberturas = $pars["ID_COBERTURA"];
	    	$obj->CAMINHO_FOTO = $upload->getFileName();
	    	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
	    	$obj->insert();
	    	
	    	
        }
			
        $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000), 'file_original' => $upload->getFileName(), 'modelo' => $obj);

    	} catch (Exception $e) {

    		$content = array('erro' => 'true', 'msg' => $e->getMessage());
    		
    	}
        
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;
           
    }

    
    public function gridfotosAction()
    {
    	$this->_helper->layout()->disableLayout();
    	
    	$pars = $this->_request->getParams();
    	
    	$obj = new Tb_cms_coberturasfotos();
    	$obj->where("ID_COBERTURA = $pars[id]")->orderby("DT_CADASTRO DESC")->find(true);
    	
    	
    	
    	$this->view->fotos = $obj->allToObject();
    	
    	
    }
    

}

