<?php

class Admin_LoginController extends Zend_Controller_Action
{

	
    public function init()
    {    	
  		$this->view->modulo = $this->getRequest()->getModuleName();
    	$this->view->controlador = $this->getRequest()->getControllerName();
    }
	
    
	public function indexAction()
	{
		$this->_forward("login");
	}  
	
	public function loginAction()
	{
		$this->_helper->layout()->disablelayout();
	}  
	
	/**
     * Authentication object
     *
     * @var Zend_Auth
     */

    protected $auth = null;

    /**
     * Pre dispatch method
     *
     * This code will be executed before the the front controller tries to dispatch the current
     * request.
     */
    public function preDispatch() {
        
    	$this->auth = Zend_Auth::getInstance();
    }
 
    /**
     * Login action
     *
     * Someone tries to login. Use the auth object to see if the input is valid.
     */
    public function logarAction() {
    	
    	$this->_helper->layout()->disablelayout();
    	$this->_helper->viewRenderer->setNoRender();
    	
        $request = $this->getRequest();
        
      
        
        $authAdapter = new My_Plugins_Autenticacao(addslashes($request->username), addslashes($request->password));
        $result = $this->auth->authenticate($authAdapter);
       
        if ($result->isValid()) {
			
        	new My_Plugins_Superfish();
            $this->_redirect('/admin/index');
            
        } else {

        	// Do some error handling...
        	echo "Usuário ou senha inválidos...";
        }
    }
    
    public function logoffAction()
    {
    	$auth = Zend_Auth::getInstance();
    	$auth->clearIdentity();
    	Zend_Session::destroy();
    	$this->_redirect('/admin/login/');
    	
    }
    
}

