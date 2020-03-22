<?php 

class Admin_ConteudosController extends Zend_Controller_Action{ 

	public $id = 0;
	public $tipo_acao = "insert";
	public $targetPath = '/www/uploads/cms/foto_chamadas/';
	
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_CONTEUDO");
    	
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
		
	  
		
		$obj = new Tb_cms_tipoconteudo();
		$obj->orderby("DESCRICAO");
		$obj->find();
		$obj->fetch();
		
		
		
		$this->view->tipos = $obj->allToObject();
	}
	
	
	
	public function formAction()
	{
		
	}
	
		
	
	
	public function gridAction()
	{  
	
		$pagina = $this->getRequest()->getParam("pagina");
		$filtro = $this->getRequest()->getParam("filtro");
		$app = $this->getRequest()->getParam("app");
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
		
		
		
		$obj = new Tb_cms_conteudo();
		$obj->selectAs();
		$obj2 = new Tb_cms_tipoconteudo();
		
		$obj->selectAs($obj2, "%s_tipoconteudo");
		
		$obj->join($obj2);
		
		if($filtro != null)
		{
			$obj->where("ID_TIPO = $filtro");
		}
		
		if($app != null)
		{
			$obj->where("ID_APLICACAO = $app");
		}
		
		if($criterio != null)
		{
			//$obj->where("TITULO LIKE _utf8 '%".$criterio."%' COLLATE utf8_general_ci || CONTEUDO LIKE _utf8 '%".$criterio."%' COLLATE utf8_general_ci");
			$obj->where("TITULO LIKE  '%".$criterio."%' || CONTEUDO LIKE '%".$criterio."%'");
		}
		
		$quanti = $obj->find();
		$obj->limit( $inicio, $TAMANHO_PAGINA );
		$quanti_limit = $obj->find();
		$total_paginas = ceil($quanti / $TAMANHO_PAGINA);
        $obj->orderBy("ID_CONTEUDO DESC");
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
    	$modelo = new Tb_cms_conteudo();
    	$this->view->modelo = $modelo;
    	
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");;
		
    	    	
		$modelo = new Tb_cms_conteudo();
		$modelo->get($id);
    	
		$retorno = $modelo->allToObject();
    	$this->view->modelo = $retorno[0];
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		$this->view->id = $id;
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_conteudo();
	    	$obj->get($id);
	    	$obj->fetch();
	    	
        	if(file_exists($this->targetPath.$obj->FOTO_CHAMADA))
        	{
        		@unlink($this->targetPath.$obj->FOTO_CHAMADA);
        	}	    	
	    	
        	if($obj->FL_BLOQUEADOEXCLUSAO == 0)
			{
	    		$obj->delete();
	    		$content = array('erro' => 'false', 'html' => '', 'msg' => "Registro excluido com sucesso");
			}else
			{
				$content = array('erro' => 'true', 'html' => '', 'msg' => "Este registro está bloqueado para exclusão.");
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
    
	public function excluirselecionadosAction()
	    {
	    	try 
	    	{
	    		$itens = isset($_POST['exclui']) ? $_POST['exclui'] : array();
	    		if(count($itens) > 0)
	    		{
		    		foreach ($itens as $chave => $valor)
		    		{
				    	$obj = new Tb_cms_conteudo();
				    	$obj->get($chave);
				    	$obj->fetch();
				    	
			        	if(file_exists($this->targetPath.$obj->FOTO_CHAMADA))
			        	{
			        		@unlink($this->targetPath.$obj->FOTO_CHAMADA);
			        	}
				      	
				    	if($obj->FL_BLOQUEADOEXCLUSAO == 0)
				    	{
				    		$obj->delete();
				    	}
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
    	
    	$obj = new Tb_cms_conteudo();
    	
    	$post = $this->_request->getParams();
    	$id = $post["ID_CONTEUDO"];
    	
    	if($this->tipo_acao == "insert")
    	{
    		
    	}
    	elseif($this->tipo_acao == "edit")
    	{
    		$obj->get($id);
			    		    		
    	}
    	

    	
    	
    	
    	$obj->_setFrom($post);
    	
    	
    	
    	
    	$novastring = str_replace("'", "", $obj->TITULO);	    		
    		    
   	
    	
		$result = $obj->validate();
				
		
		
		//$obj->CONTEUDO = $post['CONTEUDO'];
		
		
		
		if(count($result) == 0){
			
			
			if($post['FL_DESTACAR'] == "1" && $post['tb_cms_tipoconteudo'] == 18)
        	 { 
        	 
        	 	
        	 	$objUp = new Tb_cms_conteudo();
        	 	$objUp->FL_DESTACAR = 0;
        	 	$objUp->where("FL_DESTACAR = 1 AND ID_TIPOCONTEUDO = 18");
        	 	$objUp->update(true);
        	 	$objUp->destroy();
        	 }
			
			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 }else
        	 {          	 
        	 	$msg = "Registro inserido com sucesso!";
        	 	$obj->DT_CADASTRO = date("Y-m-d H:i:s");
        	 }
			
        	 $obj->FOTO_CHAMADA = $post["txt_file_uploaded"];
        	 
        	 $obj->save();	
        	 
        	 $obj->URL_SEO = $obj->ID_CONTEUDO."_".$obj->tb_cms_tipoconteudo."_".My_Sites_RadioImbiara::urldinamica($novastring).".html";
        	 
        	 $obj->save();
        	 
        	 
			if($this->tipo_acao == "edit")
	    	{
	    		$obj2 = new Tb_cms_conteudo();
	    		$obj2->get($id);
	    		$obj2->FL_DESTACAR = $post['FL_DESTACAR'];
	    		$obj2->update();
				    		    		
	    	}
        	 
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
    
    
	public function uploadiconeAction()
    {
    	$pars = $this->_request->getParams();
    	
    	
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$tempFile = $_FILES['Filedata']['tmp_name'];
    	$extensao = explode(".", $_FILES['Filedata']['name']);
    	$extensao = $extensao[1];
    	
    	
    	$pasta_destino = $_POST["folder"];
    	
        $targetPath = $_SERVER['DOCUMENT_ROOT'].'/www'.$pasta_destino . '';

     
   
        if(isset($pars["ARQUIVO"]))
        {
        	
        	
        	if(file_exists($targetPath.$pars["ARQUIVO"]))
        	{
        		
        
        		if(unlink($targetPath.$pars["ARQUIVO"]))
        		{		
				     			
        		}
        	}
        }
        
        
      	$targetFile =  "fotochamada".time().".".$extensao;

        move_uploaded_file($tempFile,$targetPath.$targetFile);	     
		
      
        if(isset($pars["ID_CONTEUDO"]))
        {
           	$obj = new Tb_cms_conteudo();
	    	$obj->get($pars["ID_CONTEUDO"]);
	    	$obj->fetch();
	    	$obj->FOTO_CHAMADA = $targetFile;
	    	$obj->update(); 
        }
			
        $content = array('erro' => 'false', 'msg' => 'Arquivo enviado com sucesso!', 'file' => $targetFile."?rand=".rand(1, 100000000000), 'file_original' => $targetFile, 'file_antigo' => isset($pars["ARQUIVO"]) ? $pars["ARQUIVO"] : "");
				
	    $jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/json')
		->setBody($jsonData)
		->sendResponse();
		exit;	
           
    }

}

?>