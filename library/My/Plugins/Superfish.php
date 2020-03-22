<?php

class My_Plugins_Retorno { public $quant; public $html;  }

class My_Plugins_Superfish
{

		public $html = "";
        
        public function __construct()
        {
            $this->RenderizarTreeView();
            
            $userProfileNamespace = new Zend_Session_Namespace('interface');
			$userProfileNamespace->menus = $this->html;
        }


	 public function pegaPastas()
    {
    	$auth = Zend_Auth::getInstance();
    	$storage = $auth->getStorage();
  	    	    	
    	$dados = $storage->read();
    	$dados = $dados->menus;
    	
    	$array = array();
    	foreach($dados as $chave => $valor)
    	{
    		if($valor->parent_id == 2)
    		{
    			$array[] = $valor;
    		}
    		
    	}

    	return $array;
    }

		
    
       public function pegasubPastas($pai, $tipo)
       {
       		 $total = 0;
       		 $html2 = "";
       		 
       		  if ($tipo == "folder")
              {
              		$auth = Zend_Auth::getInstance();
			    	$storage = $auth->getStorage();
			  	    	    	
			    	$dados = $storage->read();
			    	$dados = $dados->menus;
			    	
			    	$array = array();
			    	foreach($dados as $chave => $valor)
			    	{
			    		if($valor->parent_id == $pai)
			    		{
			    			$array[] = $valor;
			    		}
			    		
			    		
			    	}
			    	
		    	    $total = count($array);
                    $html2 = "";
                    if ($total > 0)
                    {
                    	foreach ($array as $chave => $valor)
                        {
                        	$a = $this->pegasubPastas($valor->id, $valor->type);
                        	
                        	$controlador = strtolower($valor->CONTROLADOR); $controlador = str_replace("controller", "", $controlador);
                			$acao = strtolower($valor->ACAO); $acao = str_replace("action", "", $acao);

                			
                			
                			if($valor->ICONE != "" && $valor->ICONE != null)
                			{
                        		$html2 .= "<li class=\"current\"><a href=\"/admin/$controlador/$acao\">" . "<img src=\"/thumbs/index/w/15/h/15/type/1/file/".$valor->ICONE."\" hspace='20'/> " . utf8_encode($valor->title) . "</a>";
                			}else 
                			{
                				$html2 .= "<li class=\"current\"><a href=\"/admin/$controlador/$acao\">" . utf8_encode($valor->title) . "</a>";
                			}
                        	
                        	
                       		if ($a->quant == 0)
                            {
                                $html2 .= "</li>";
                            }
                            else
                            {

                                $html2 .= "<ul>";

                                $html2 .= $a->html;

                                $html2 .= "</ul>\n";
                                $html2 .= "</li>\n";
                            }
                        }
                    }
			    	              	
              }
              
              $retorno = new My_Plugins_Retorno();
              $retorno->quant = $total;
              $retorno->html = $html2;
              
              return $retorno;
              
              
       }
    
      public function RenderizarTreeView()
      {
      	  $ultraPais = $this->pegaPastas();
      	  
      	   $this->html .= "<ul class=\"sf-menu\">";
      	  
      	  if (count($ultraPais) > 0)
           {
           		foreach ($ultraPais as $chave => $valor)
                {
                	$a = $this->pegasubPastas($valor->id, $valor->type);
                	
                	$controlador = strtolower($valor->CONTROLADOR); $controlador = str_replace("controller", "", $controlador);
                	$acao = strtolower($valor->ACAO); $acao = str_replace("action", "", $acao);
                	
                	
                			if($valor->ICONE != "" && $valor->ICONE != null)
                			{
                        		$this->html .= "<li class=\"current\"><a href=\"/admin/$controlador/$acao\">" . "<img src=\"/thumbs/index/w/15/h/15/type/1/file/".$valor->ICONE."\" />" . utf8_encode($valor->title) . "</a>";
                			}else 
                			{
                				$this->html .= "<li class=\"current\"><a href=\"/admin/$controlador/$acao\">" . utf8_encode($valor->title) . "</a>";
                			}
                	
                	
                	
                 		if ($a->quant == 0)
                        {
                            $this->html .= "</li>";
                        }
                        else
                        {

                            $this->html .= "<ul>";

                            $this->html .= $a->html;

                            $this->html .= "</ul>";
                            $this->html .= "</li>\n";
                        }
                }
           }
           
           
          
            $this->html .= "</ul>";
      	
      }
}

