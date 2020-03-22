<?php

class My_Plugins_Categoriarecursivaretorno { public $quant; public $html;  }

class My_Plugins_Categoriarecursiva
{

		public $html = "";
		public $idCategoria;
		public $arrayloco = array();
        
        public function __construct()
        {

       
        }


		
    
       public function pegasubPastas($pai, $tipo)
       {
       		 $total = 0;
       		 $html2 = "";
       		 
       		  if ($tipo == "folder")
              {
              	
              	
              	 $_ultraPais = new Tb_gcool_categoria();
		      	  $_ultraPais->where("id = $pai");
		      	  $_ultraPais->find();
		      	  $_ultraPais->fetch(true);
		      	  $array = $_ultraPais->allToObject();
		      	  $_ultraPais->_getConnection()->close();
		     			    	
			  	
			    	
		    	    $total = count($array);
                    $html2 = "";
                    if ($total > 0)
                    {
                    	foreach ($array as $chave => $valor)
                        {
      						
                        	if($valor->parent_id == 1)
                        	{
                        		$profileFranquia = new Zend_Session_Namespace('franqueado'); 
								$franquia = $profileFranquia->__get("dadosFranqueado");
							
                        		$obj = new stdClass();
                        		$obj->title = $franquia->NOME_CIDADE . ' - ' . $franquia->SGL_ESTADO; 
                        		$this->arrayloco[] = $obj;
	                        	
                        	}else{
                        		$this->arrayloco[] = $valor;
	                        	$a = $this->pegasubPastas($valor->parent_id, $valor->type);
                        	}
                        	
                        }
                    }
			    	              	
              }
              
              $retorno = new My_Plugins_Categoriarecursivaretorno();
              $retorno->quant = $total;
              $retorno->html = $html2;
              
              return $retorno;
              
              
       }
    
      public function RenderizarTreeView()
      {
      	  $_ultraPais = new Tb_gcool_categoria();
      	  $_ultraPais->get($this->idCategoria);
      	  $_ultraPais->fetch(true);
      	  $ultraPais = $_ultraPais->toObject();
      	  $_ultraPais->_getConnection()->close();
      	  
      
      	   
	      $this->arrayloco[] = $ultraPais;
      	  
      	  if (count($ultraPais) > 0)
           {
           	
           	
                		$a = $this->pegasubPastas($ultraPais->parent_id, $ultraPais->type);

                		if ($a->quant == 0)
                        {
                           
                        }
                        else
                        {
                            

                        }
            
           }
      
      	
      }
      
   
}

