<?php 

class Admin_BannersController extends Zend_Controller_Action{ 

	public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_BANNER");
    	
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
		$id =  $this->getRequest()->getParam("id");
		$modelo = new Tb_cms_banner();
		$modelo->get($id);
		$retorno = $modelo->allToObject();
    	$this->view->modelo = $retorno[0];
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
		
		
		
		$obj = new Tb_cms_banner();
		
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
    	
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");
		
    	    	
		
    	
		$modeloimg = new Tb_cms_bannerimagens();
		$modeloimg->where("ID_BANNER = $id");
		$modeloimg->orderBy("ORDEM ASC");
		$modeloimg->find(true);
		
		

    	$this->view->modeloimg = $modeloimg->allToObject();
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_banner();
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
    
    
    public function updateurlAction()
    {
    	$this->_helper->layout()->disablelayout();
	    $this->_helper->viewRenderer->setNoRender(); 
    	$pars = $this->_request->getParams();
    	
    	$id = $pars["id"];
    	$txt = $pars["txt"];
    	
    	$obj = new Tb_cms_bannerimagens();
    	
    	if(strpos($id, "url") !== false)
    	{
    		
    		$pk = str_replace("url", "", $id);
    		$obj->get($pk);
    		$obj->URL = $txt;
    		
    	}else if(strpos($id, "titulo") !== false)
    	{
    		
    		$pk = str_replace("titulo", "", $id);
    		$obj->get($pk);
    		$obj->TITULO = $txt;
    		
    	}else if(strpos($id, "descricao") !== false)
    	{
    		
    		$pk = str_replace("descricao", "", $id);
    		$obj->get($pk);
    		$obj->DESCRICAO = $txt;
    	}else if(strpos($txt, "_blank") !== false || strpos($txt, "_self") !== false)
    	{
    		$pk = str_replace("descricao", "", $id);
    		$obj->get($pk);
    		$obj->URL_TARGET = $txt;
    	
    	}
    	
    	
    	$obj->update();
    	
    }
    
	public function ordenaAction()
    {
    	
    	try 
    	{
    	
	    	$sort = $this->_request->getParam("sort");
	    	
	    	    	
	    	$lista = explode(",", $sort);
	    	
	    	if($sort != null)
	    	{
	    	
		    	foreach($lista as $chave => $valor)
		    	{
		    		$obj = new Tb_cms_bannerimagens();
		    		$obj->get($valor);
		    		$obj->fetch();
		    		$obj->ORDEM = $chave + 1;
		    		$obj->update();
		    	}
	    	}
	    	$this->_helper->layout()->disablelayout();
	    	$this->_helper->viewRenderer->setNoRender();  	
	    	
			$content = array('erro' => 'false', 'html' => '', 'msg' => utf8_encode('Ordernação efetuada com sucesso!'));
	    	
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
    
 	public function excluirfotobannerAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_bannerimagens();
	    	$obj->get($id);
	    	$obj->fetch();
	    	$obj->delete();
	    	
	    	$file = $_SERVER['DOCUMENT_ROOT']."/www/uploads/banners/".$obj->CAMINHO_FOTO;
	    	if(file_exists($file))
	    	{
	    		unlink($file);
	    	}
	    	
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
				    	$obj = new Tb_cms_banner();
				    	$obj->get($chave);
				    	$obj->fetch();
				    	$obj->delete();
				    	$content = array('erro' => 'false', 'html' => '', 'msg' => "Registros excluÃ­dos com sucesso!");
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
    	
    	$obj = new Tb_cms_banner();
    	$post = $this->_request->getParams();
    	$id = $post["ID_BANNER"];
    	
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
    
    
	public function uploadiconeAction()
    {
    	try {
    		
    	
    	
    	$pars = $this->_request->getParams();
    	    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	
    	
    	$width = $pars['WIDTH'];
    	$height = $pars['HEIGHT'];
    	$autocrop = ($pars['AUTOCROP'] == "true" ? true : false);

   		$file = $_FILES['Filedata'];
    	$pasta_destino = $_POST["folder"];
        $pasta_destino = $_SERVER['DOCUMENT_ROOT'].'/www/'.$pasta_destino . '/';
        $upload = My_Plugins_Util::upload($file, $pasta_destino, $width, $height, $autocrop, NULL);
		
        
        $maxPK = new Tb_cms_bannerimagens();
    	$maxPK->select("MAX(ID_FOTOBANNER) as pk");
    	$maxPK->find();
    	$maxPK->fetch();
        
        $maxOrdem = new Tb_cms_bannerimagens();
    	$maxOrdem->select("MAX(ORDEM) as ordem");
    	$maxOrdem->where("ID_BANNER = ".$pars["ID_BANNER"]."");
    	$maxOrdem->find();
    	$maxOrdem->fetch();
        
        
        if(isset($pars["ID_BANNER"]))
        {
        	
           	$obj = new Tb_cms_bannerimagens();
           	$obj->ID_FOTOBANNER = $maxPK->pk + 1;
	    	$obj->tb_cms_banner = $pars["ID_BANNER"];
	    	$obj->CAMINHO_FOTO = $upload->getFileName();
	    	$obj->ORDEM = $maxOrdem->ordem + 1;
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

}

?>