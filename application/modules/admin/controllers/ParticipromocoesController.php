<?php 

class Admin_ParticipromocoesController extends Zend_Controller_Action{ 


    public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID_TIPO");
    	
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
	
		
	public function visualizaparticipantesAction()
	{
		$pars = $this->_request->getParams();
		$id = $pars["id"];
		
		if(isset($pars['mod']) && $pars['mod'] == 'print')
		{
			$this->_helper->layout()->disablelayout();
			$this->view->modo = "print";
			
		}else 
		{
			$this->view->modo = "view";
		}
		
		$obj = new Tb_radio_promocaoparticipante();
		$obj->where("ID_CONTEUDO = '$id'");
		$obj->order("DT_CADASTRO DESC");
		$obj->find(true);
		
		$this->view->participantes = $obj->allToObject();
		$this->view->id = $id;
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
		
		
		
		$obj = new Tb_cms_conteudo();
		
		$obj->where("ID_TIPOCONTEUDO = 17");
		if($app != null)
		{
			$obj->where("ID_APLICACAO = $app");
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
	
    
	
	
	
	
	
    
    
	
}

	