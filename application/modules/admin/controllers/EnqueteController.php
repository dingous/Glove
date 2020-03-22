<?php 

class Admin_EnqueteController extends Zend_Controller_Action{ 


   public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_ENQUETE");
    	
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
		
		
		
		$obj = new Tb_cms_enquete();
		
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
	
	public static function pegaRespostasParaoGrid($id)
	{
		$resposta = new Tb_cms_enqueterespostas();
		$resposta->where("ID_ENQUETE = $id");
		$resposta->orderby("VOTOS DESC");
		$resposta->find(true);
		$respostas = $resposta->allToObject();
		
		$obj = new Tb_cms_enqueterespostas();
		$obj->select("SUM(VOTOS) as total_votos");
		$obj->where("ID_ENQUETE = ".$id."");
		$obj->find(true);
		
		return array($respostas,$obj->total_votos );
	}
    
	public function cadastrarAction()
    {
    	
        return $this->_forward('form');
       
    }
	
  	
    public function editarAction()
    {
    	$id =  $this->getRequest()->getParam("id");;

    	
    	
		$modelo = new Tb_cms_enquete();
		$modelo->get($id);
		
		
    	
		$retorno = $modelo->allToObject();
		
		
		
    	$this->view->modelo = $retorno[0];
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		
		$respostas = new Tb_cms_enqueterespostas();
		$respostas->where("ID_ENQUETE = '$id'");
		$respostas->orderby("ORDEM");
		$respostas->find();
		$respostas->fetch();
		$this->view->respostas = $respostas->allToObject();
		
		
    	
    	return $this->_forward('form');
    }
    
    
 public function updatefieldAction()
    {
    	$this->_helper->layout()->disablelayout();
	    $this->_helper->viewRenderer->setNoRender(); 
    	$pars = $this->_request->getParams();
    	
    	$id = $pars["id"];
    	$txt = $pars["txt"];
    	
    	$obj = new Tb_cms_enqueterespostas();
    	$obj->get($id);
    	$obj->RESPOSTA = $txt;
    	
    	
    	$obj->update();
    	
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_enquete();
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
    
    
    public function insererespostaAction()
    {
    	
    	$pars = $this->_request->getParams();
    	
    	
    
    	
    	$maxOrdem = new Tb_cms_enqueterespostas();
    	$maxOrdem->select("MAX(ORDEM) as ordem");
    	$maxOrdem->where("ID_ENQUETE = ".$pars["ID_ENQUETE"]."");
    	$maxOrdem->find();
    	$maxOrdem->fetch();
    	
    	
    	
    	$obj = new Tb_cms_enqueterespostas();
    	$obj->tb_cms_enquete = $pars["ID_ENQUETE"];
    	$obj->RESPOSTA = $pars['RESPOSTAS'];
    	$obj->ORDEM = $maxOrdem->ordem + 1;
    	$obj->VOTOS = 0;
    	$obj->save();
    	
    	
    	
    	$content = array('erro' => 'true', 'modelo' => $obj->toObject(), 'html' => 'Inserida com sucesso!', 'msg' => '' );
				
		
    	
    	
		$jsonData = Zend_Json::encode($content);
		$this->getResponse()
		->setHeader('Content-Type', 'text/html')
		->setBody($jsonData)
		->sendResponse();
		exit;	
		
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
		    		$obj = new Tb_cms_enqueterespostas();
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
    
    
	public function excluirselecionadosAction()
	    {
	    	try 
	    	{
	    		$itens = isset($_POST['exclui']) ? $_POST['exclui'] : array();
	    		if(count($itens) > 0)
	    		{
		    		foreach ($itens as $chave => $valor)
		    		{
				    	$obj = new Tb_cms_enquete();
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
    	
    	$obj = new Tb_cms_enquete();
    	$post = $this->_request->getParams();
    	$id = $post["ID_ENQUETE"];
    	
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
			
			if($post['FL_ATIVA'] == "1")
        	 { 
        	 	$objUp = new Tb_cms_enquete();
        	 	$objUp->FL_ATIVA = 0;
        	 	$objUp->where("FL_ATIVA = 1");
        	 	$objUp->update(true);
        	 	$objUp->destroy();
        	 }

			 if($this->tipo_acao == "edit")
        	 {
        	 	$msg = "Registro atualizado com sucesso!";
        	 }else
        	 {    
        	 	$obj->DT_CADASTRO = Zend_Date::now();      	 
        	 	$msg = "Registro inserido com sucesso!";
        	 }
			
        	         	 
        	 $obj->save();
        	 
        	 
        	
        	if($this->tipo_acao == "edit")
	    	{
	    		$obj2 = new Tb_cms_enquete();
	    		$obj2->get($id);
	    		$obj2->FL_ATIVA = $post['FL_ATIVA'];
	    		$obj2->update();
				    		    		
	    	}
	        	 
        	 
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
	    	$content = array('erro' => 'valida', 'html' =>  $html, 'campos_erros' => $array_erros, 'error_html' => $validacaoToHtml, 'msg' => 'Preencha os campos obrigatorios');
		}
		
        
        		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
				
				
    }
    
    
	public function excluirrespostaAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_cms_enqueterespostas();
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

}

