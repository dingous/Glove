<?php

class Admin_PadraoController extends Zend_Controller_Action
{
	
	public $id = 0;
	public $tipo_acao = "insert";
	
    public function init()
    {
        /* Initialize action controller here */
    	
    	$this->id = $this->getRequest()->getParam("ID");
    	
    	if($this->id != 0 && $this->id != "")
    	{
    		$this->tipo_acao = "edit";
    	}
    	
  		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
  		
    	
    	
		
    }
	
    
	public function verAction()
	{
		
	}
	
	public function insAction()
	{
		
	}
	
	public function edtAction()
	{
		
	}
	
	
	public function excAction()
	{
		
	}
	
	public function salvarAction()
	{
		
	}
	
	
}

