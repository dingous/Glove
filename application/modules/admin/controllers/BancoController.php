<?php

class Admin_BancoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		
		
		
		include 'lumine-conf.php';	
		require_once 'lumine/lib/Reverse.php';
		
		//$cfg = new Lumine_Configuration($lumineConfig);
		//$con = Lumine_ConnectionManager::getInstance();
		//$con->create("aaa", $cfg);
		
		$obj = new Lumine_Reverse( $lumineConfig );				
		$obj->start();
		 
		
		$this->_helper->viewRenderer->setNoRender(); 
		$this->getResponse()->appendBody("<p>Classe de Modelos Atualizadas com sucesso!</p>\n");
    }

    public function indexAction()
    {
        // action body
    }


}

