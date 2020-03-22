<?php 

class Admin_DestaquesController extends Zend_Controller_Action{ 

	public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = trim($this->getRequest()->getParam("ID_DESTAQUE"));
    	
    	
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
	
    
	public function indexAction()
	{
		
	}
	
	
	
	public function formAction()
	{
		
	}
	
	public function uploadiconeAction()
    {
    	$pars = $this->_request->getParams();
    	
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	    	
    	$id = isset($pars["ID_DESTAQUE"]) ? $pars["ID_DESTAQUE"] : null;

    	$obj = new Tb_cms_destaques();
		
    	
    	$file = $_FILES['Filedata'];
		
		
			
		
		
    	if(!empty($id))
    	{

		    $obj->get($id);
		    $obj->fetch();
    	}
    	
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, 0, 120, false, $pasta_destino.$obj->CAMINHO_FOTO);
        
       	$obj->CAMINHO_FOTO = $upload->getFileName();
    	$obj->update(); 
    	
   	    $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $upload->getFileName()."?rand=".rand(1, 100000000000), 'file_original' => $upload->getFileName());
	
    	    	
       			
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;	
           
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
		
		
		
		$obj = new Tb_cms_destaques();
		
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
	
	
	function getInnerHTML($Node)
	{
	     $Body = $Node->ownerDocument->documentElement->firstChild->firstChild;
	     $Document = new DOMDocument();     
	     $Document->appendChild($Document->importNode($Body,true));
	     return $Document->saveHTML();
	}
    
	public function cadastrarAction()
    {
    	
    
    	$obj = new Tb_cms_destaques();
    	$this->view->modelo = $obj;
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");;
		
    	    	
		$modelo = new Tb_cms_destaques();
		$modelo->get($id);
    	
		$retorno = $modelo->allToObject();
    	$this->view->modelo = $retorno[0];
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_destaques();
	    	$obj->get($id);
	    	$obj->fetch();
	    	
	    	@unlink("/www/uploads/radio/artistas/".$obj->CAMINHO_IMAGEM);
	    	
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
				    	$obj = new Tb_cms_destaques();
				    	$obj->get($chave);
				    	$obj->fetch();
				    	
				    	@unlink("/www/uploads/radio/artistas/".$obj->CAMINHO_FOTO);
				    	
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
    	
    	$obj = new Tb_cms_destaques();
    	$post = $this->_request->getParams();
    	$id = isset($post["ID_DESTAQUE"]) ? $post["ID_DESTAQUE"] : null;
    	
    	if($this->tipo_acao == "insert")
    	{
    		$obj->DT_CADASTRO = date("Y-m-d H:i:s");
    	}
    	elseif($this->tipo_acao == "edit")
    	{
    		$obj->get($id);
			    		    		
    	}
    	
    	
    	
    	
    	$obj->_setFrom($post);
    	$obj->CAMINHO_FOTO = $post["txt_file_uploaded"];
    	
    	
    	
    	
		$result = $obj->validate();
				
		
		
		if(count($result) == 0){

			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
        	 }else
        	 {          	 
        	 	$msg = "Registro inserido com sucesso!";
        	 }

        	 
        	 
      	 	
        	 	 
      	 	      	 	 
        	 $obj->save();
      	 	 
        	 $content = array('erro' => 'false', 'html' => '', 'msg' => $msg, 'modelo' => $obj);
			
		}else{
			
			
			foreach ( $result as $campo => $erro ){
				if ( $erro != 1 ){
					$array_erros[$campo] = ($erro);
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