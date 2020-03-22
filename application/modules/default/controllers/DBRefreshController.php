<?php

class DBRefreshController extends Zend_Controller_Action
{

    public function init()
    {
 		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
    }

    public function indexAction()
    {
        Lumine_Log::setLevel(3);
        Lumine::load('Reverse');
        
        
    	$this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        
        
        
        
        $a = Lumine_ConnectionManager::getInstance();
        $conf = $a->getConfigurationList();
        $lumineConfig = $conf['dao']->options;
        
     
		
		$obj = new Lumine_Reverse( $lumineConfig );
		$obj->start();
		
		
		echo "<b>".$lumineConfig['database']."</b> Atualizado com sucesso !";
      
    }


}

