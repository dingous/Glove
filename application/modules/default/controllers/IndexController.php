<?php

class IndexController extends Zend_Controller_Action
{

    protected $auth;
    protected $usuario;
    protected $array_formerrors = array();
    
    public function init()
    {
      
    }

    public function indexAction()
    {
    	//$this->_helper->_layout->setLayout('defaultblank');
    	$this->_helper->layout()->disableLayout();

    	$pars = $this->_request->getParams();
    	if(isset($pars['another']))
    	{
    		echo utf8_encode("<script>window.parent.parent.$().toastmessage('showToast', { text : 'Detectamos que você efetuou o login de um outro lugar, por segurança, você foi desconectado deste computador.', stayTime   : 900000, type : 'error' });</script>");
    	}
	} 
	
	public static function formataNome($nome)
	{
	  
	    
	    
		$nome = strtolower($nome);
		$preposicoes = array(" da ", " do ", " de ", " das ", " dos ");
		$nome = str_replace($preposicoes, " ", $nome);
		$exp = explode(" ", $nome);
		if(count($exp) > 1)
		{
			$nome = ucfirst($exp[0]) . " " . (count($exp) >= 2 ? ($exp[1] == "" ? ucfirst($exp[2]) : ucfirst($exp[1])) : ucfirst($exp[1]));
		}else
		{
			$nome = ucfirst($exp[0]);
		}
		return utf8_decode($nome);
	}
	
	  public function msgAction()
	  {
		  
			 
    		$this->_helper->layout()->disableLayout();

    		
    		$auth = Zend_Auth::getInstance();
    			
    		$exp1 = explode("?", $_SERVER['REQUEST_URI']);
    		$exp = explode("/", $exp1[0]);
    		$cod = $exp[3];
    		$cod = str_replace("?", "", $cod);
    		$user = $exp[4];
    			
    		
    		
    		$usuariocurt = new Tb_sys_usuario();
    		$usuariocurt->get($user);
    		
    		
    		$comentarios = new Tb_social_albumfotos();
    		
    		 
    		$sql = "select * from tb_social_recados as recado
    		inner join tb_sys_usuario as user on recado.ID_USUARIO_REMETENTE = user.ID_USUARIO
    		where
    		ID_RECADO = $cod";
    		
    		
    		$comentarios->query($sql);
    		$comentarios->fetch();
    		$dados =  $comentarios->toObject();
    			
    		if($auth->hasIdentity())
    		{
    			 
    			$logado = true;
    		
    		
    		
    			$usuario = $auth->getStorage()->read();
    			$this->view->usuario = $usuario;
    			
    			
    				
    			 
    		}else
    		{
    			 
    			$logado = false;
    			$_SESSION["url_requisitada"] = $_SERVER["REQUEST_URI"];
    				
    		}
    			
    			
    		$msg = $this->formataNome($usuariocurt->NOME . " " . $usuariocurt->SOBRENOME) . " curtiu uma publicação de " .$this->formataNome($comentarios->NOME . " " . $comentarios->SOBRENOME)." - Evento: ". $comentarios->MENSAGEM;
    		$msg = utf8_encode($msg);
    		$msg = stripslashes($msg);
    		$msg = str_replace("'", "", $msg);
    		$msg = str_replace("\\", "", $msg);
    		$msg = strip_tags($msg, "<img>");
			
    		$this->view->msg = $msg;
    		$this->view->logado = $logado;
    		$this->view->cod = $cod;
    		
    		
		
	  }

}

