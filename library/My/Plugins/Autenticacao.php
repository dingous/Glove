<?php

class My_Plugins_Autenticacao implements Zend_Auth_Adapter_Interface {

    /**
     * Username
     *
     * @var string
     */

    protected $username = null;

    /**
     * Password
     *
     * @var string
     */

    protected $password = null;

    /**
     * Class constructor
     *
     * The constructor sets the username and password
     *
     * @param string $username
     * @param string $password
     */

    public function __construct($username, $password) {

        $this->username = addslashes($username);
        $this->password = addslashes($password);

    }

 

    /**
     * Authenticate
     *
     * Authenticate the username and password
     *
     * @return Zend_Auth_Result
     */

    public function authenticate() {

        // Try to fetch the user from the database using the model

        $users = new Tb_sys_usuario();
        $users->selectAs();
        $empresa = new Tb_sys_empresa();
        $cidade = new Tb_sys_cidade();
        $estado = new Tb_sys_estado();
        $perfil = new Tb_glove_perfil();
        $foto = new Tb_social_foto();
        
        
        
        $users->selectAs($foto, "%s_foto");
       	$perfil->join($foto, "left");
        
        $users->selectAs($perfil, "%s_perfil");
       	$users->join($perfil, "left");
        
		$users->selectAs($estado, "%s_estado");
       	$cidade->join($estado, "left");
       	
        
        $users->selectAs($cidade, "%s_cidade");
       	$users->join($cidade, "left");
        
        $users->selectAs($empresa, "%s_empresa");
       	$users->join($empresa, "left");
       	
		$users->where("tb_sys_usuario.EMAIL = '".$this->username."'");
        $users->where("tb_sys_usuario.SENHA = '".$this->password."'");
        $users->find(true);                
		
        
        
        $user = $users->toObject();
        $users->_getConnection()->close();
        
		
    	
        
        //print_r($users->_getSQL());
        //die('aqui');
        
        //se a empresa for padrão do sistema
       if($user->tb_sys_empresa == 1)
       {
       	$apps = new Tb_sys_aplicacao();
        $apps->find(true);
       	
       }else
       {
        
        $apps = new Tb_sys_aplicacao();
        $apps->where("ID_EMPRESA = '$user->tb_sys_empresa' AND ID_APLICACAO != 1");
        $apps->find(true);
        
       }
        
       $aplicacoes = $apps->allToObject();
       $apps->_getConnection()->close();
        /*echo "<pre>";
        print_r($user);
        echo "</pre>";
        die();*/
     
      
        if($user->tb_sys_grupos != null)
        {
	        $acoes = new Tb_sys_modulo_has_tb_sys_aliasaction();
	        $sql =  "select acoes.ACAO as acao, acoes.CONTROLADOR as controlador, acoes.*, menus.* from "; 
			$sql .= "tb_sys_modulo_has_tb_sys_aliasaction as acoes ";
			$sql .=  "left join tb_sys_admin_menu as menus ";
			$sql .=  " on acoes.ACAO = menus.ACAO and acoes.CONTROLADOR = menus.CONTROLADOR"; 
			$sql .=  " where acoes.ID_GRUPO = $user->tb_sys_grupos";
			$sql .=  " order by menus.position";
			
			
			
					
	        $acoes->query($sql);
	        $menus = $acoes->allToObject();
	        $acoes->_getConnection()->close();
	       
	        $array = array();
	        foreach($menus as $chave => $valor)
	        {
	        	//$array[strtolower($valor->controlador."_".$valor->acao)] = $valor;
	        	$array[strtolower($valor->controlador."_".$valor->acao)] = "ok";
	        }
	       
	        //Para listar todos acessos do grupo acima basta dar um left join ao invés de inner
	 		//$user->menus  = $menus;
	 		$user->menus = $menus;
	 		$user->permissoes = $array;	
	 		$user->aplicacoes = $aplicacoes;
	 		
	 		/*echo "<pre>";
	 		print_r($menus);
	 		echo "</pre>";
	 		
	 		echo "<pre>";
	 		print_r($array);
	 		echo "</pre>";
	 		
	 		die();*/
        }

        $code = Zend_Auth_Result::FAILURE;

        $identity = null;

        $messages = array();

 
		        
        // Do we have a valid user?
        if ($users->count() > 0) {
        
            
	        	$code = Zend_Auth_Result::SUCCESS;
	            $identity = $user;
                  
          	
             
             

        } else {

            $messages[] = 'Erro na autenticacao';

        }

 

        return new Zend_Auth_Result($code, $identity, $messages);

    }

}
