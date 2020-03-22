<?php 

class Admin_ConteudosbloqueadosController extends Zend_Controller_Action{ 

	public $id = 0;
	public $tipo_acao = "insert";
	
	public function bloquearAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$id = $this->_request->getParam("id");
		$obj = new Tb_cms_conteudo();
		$obj->get($id);
		$obj->fetch();
		$obj->FL_BLOQUEADOEXCLUSAO = 1;
		$obj->update();
		
		$content = array('erro' => 'false', 'html' => '', 'msg' => '');
		

		
		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
	}
	
	public function desbloquearAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		
		$id = $this->_request->getParam("id");
		$obj = new Tb_cms_conteudo();
		$obj->get($id);
		$obj->fetch();
		$obj->FL_BLOQUEADOEXCLUSAO = 0;
		$obj->update();
		
		$content = array('erro' => 'false', 'html' => '', 'msg' => '');
		
		$jsonData = Zend_Json::encode($content);
				$this->getResponse()
				->setHeader('Content-Type', 'text/html')
				->setBody($jsonData)
				->sendResponse();
				exit;
	}
	
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_LOCUTOR");
    	
    	if($this->id != 0 && $this->id != "")
    	{
    		$this->tipo_acao = "edit";
    	}
    	
  		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
  	
    }
	
    
	public function indexAction()
	{
		$obj = new Tb_cms_tipoconteudo();
		$obj->orderby("DESCRICAO");
		$obj->find();
		$obj->fetch();
		
		
		
		$this->view->tipos = $obj->allToObject();
	}
	
	
	
	
	
	public function gridAction()
	{  
	
		$pagina = $this->getRequest()->getParam("pagina");
		$filtro = $this->getRequest()->getParam("filtro");
		
		$TAMANHO_PAGINA = 2000;
		
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
	
    

}

?>