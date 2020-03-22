<?php

class Admin_ModulosController extends Zend_Controller_Action
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
		
		
		$obj = new Tb_sys_modulo();
		$obj2 = new Tb_sys_aplicacao();
		$obj->join($obj2);
		
		$obj->selectAs();	
		$obj->selectAs($obj2, "%s_aplicacao");			
		
		$quanti = $obj->find();
		$obj->limit( $inicio, $TAMANHO_PAGINA );
		$quanti_limit = $obj->find();
		$total_paginas = ceil($quanti / $TAMANHO_PAGINA);
        $obj->orderBy("ID_MODULO DESC");
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

		$modelo = new Tb_sys_modulo();
		$modelo->_setFieldOption('tb_sys_aliasaction','lazy',true);
		$modelo->get($id);
    	
		$retorno = $modelo->allToObject();
    	$this->view->modelo = $retorno[0];
    	
    	$scripts = $this->view->inlineScript();
		$scripts->appendScript('$( "#tabs" ).tabs();');
		
		
    	$this->view->metodos = $this->formataMetodos($retorno[0]->CONTROLADOR);
    	
    	return $this->_forward('form');
    }
    
    public function excluirAction()
    {
    	try 
    	{
    		$id =  $this->getRequest()->getParam("id");;
	    	$obj = new Tb_sys_modulo();
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
    
    
    
	public function salvarAction()
    {	
    	$this->_helper->viewRenderer->setNoRender();
    	
    	$obj = new Tb_sys_modulo();
    	$post = $this->_request->getParams();
    	$id = $post["ID_MODULO"];
    	
    	if($this->tipo_acao == "insert")
    	{
    		
    	}
    	elseif($this->tipo_acao == "edit")
    	{
    		$obj->get($id);
			    		    		
    	}
		

		
		$controlador = $_SERVER['DOCUMENT_ROOT']."/application/modules/admin/controllers/".$post["CONTROLADOR"]."Controller.php";
    	
		if(!file_exists($controlador))
		{
			$fileHandle = fopen($controlador, 'w+') or die("can't open file");
			
			$str = "<?php \n\nclass Admin_".$post["CONTROLADOR"]."Controller extends Zend_Controller_Action{ \n\n";
			$str .= "\tpublic function indexAction() \n";
			$str .= "\t{\n\n";
			$str .= "\t}\n\n";
			$str .= "}\n\n?>";
			
			$escreve = fwrite($fileHandle, $str);
			
			fclose($fileHandle);
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
	
	
	
    
    
	public function formataMetodos($class)
	{
		
		/*if($class != "modulos" ){
			include $_SERVER['DOCUMENT_ROOT']."/application/modules/admin/controllers/".ucwords($class)."Controller.php";
		}
		
		
		$class_methods = get_class_methods("Admin_".ucwords($class)."Controller");
	
		$array = array();
		if(count($class_methods) > 0)
		{
		foreach($class_methods as $chave => $valor)
		{
			if(strpos($valor, "Action") == true)
			{
				$array[] = $valor;
			}
		}
		}
		
		return $array;*/
	}
	
  	
	
}

