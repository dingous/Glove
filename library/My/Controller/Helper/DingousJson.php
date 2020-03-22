<?php
/**
 * GenerateRandom Action Helper
 *
 * @desc Cria uma string randômica
 * @uses Zend_Controller_Action_Helper
 */
class Zend_Controller_Action_Helper_DingousJson
    extends Zend_Controller_Action_Helper_Abstract 
{ 
    /** 
     * @var array Parameters detected in raw content body 
     */ 
    protected $_bodyParams = array(); 

	
	public function getHeaders()
		{
				$headers = array();
				foreach ($_SERVER as $k => $v)
				{
				if (substr($k, 0, 5) == "HTTP_")
				{
				$k = str_replace('_', ' ', substr($k, 5));
				$k = str_replace(' ', '-', ucwords(strtolower($k)));
				$headers[$k] = $v;
				}
				}
				return $headers;
		}
	
    /** 
     * Do detection of content type, and retrieve parameters from raw body if 
     * present 
     * 
     * @return void 
     */ 
    public function init() 
    { 
    	
    	
       $headers = $this->getHeaders();
    	
    	
        $request     = $this->getRequest(); 
        
        
        
        //$contentType = $request->getHeader('content-type'); 
        $contentType = $headers['Accept'];
        
        $rawBody     = $request->getRawBody();
        if (!$rawBody) { 
            return; 
        } 
        switch (true) { 
            case (strstr($contentType, 'application/json')): 
            	
                $this->setBodyParams(Zend_Json::decode($rawBody)); 
                break; 
            case (strstr($contentType, 'application/xml')): 
                $config = new Zend_Config_Xml($rawBody); 
                $this->setBodyParams($config->toArray()); 
                break; 
            default: 
                if ($request->isPut()) { 
                    parse_str($rawBody, $params); 
                    $this->setBodyParams($params); 
                } 
                break; 
        } 
    } 

    /** 
     * Set body params 
     * 
     * @param  array $params 
     * @return Scrummer_Controller_Action 
     */ 
    public function setBodyParams(array $params) 
    { 
        $this->_bodyParams = $params; 
        return $this; 
    } 
	
   
    
    /** 
     * Retrieve body parameters 
     * 
     * @return array 
     */ 
    public function getBodyParams() 
    { 
        return $this->_bodyParams; 
    } 

    /** 
     * Get body parameter 
     * 
     * @param  string $name 
     * @return mixed 
     */ 
    public function getBodyParam($name) 
    { 
        if ($this->hasBodyParam($name)) { 
            return $this->_bodyParams[$name]; 
        } 
        return null; 
    } 

    /** 
     * Is the given body parameter set? 
     * 
     * @param  string $name 
     * @return bool 
     */ 
    public function hasBodyParam($name) 
    { 
        if (isset($this->_bodyParams[$name])) { 
            return true; 
        } 
        return false; 
    } 

    /** 
     * Do we have any body parameters? 
     * 
     * @return bool 
     */ 
    public function hasBodyParams() 
    { 
        if (!empty($this->_bodyParams)) { 
            return true; 
        } 
        return false; 
    } 

    /** 
     * Get submit parameters 
     * 
     * @return array 
     */ 
    public function getSubmitParams() 
    { 
        if ($this->hasBodyParams()) { 
            return $this->getBodyParams(); 
        } 
        return $this->getRequest()->getPost(); 
    } 
	
    public static function paraArray($array)
    {
    	$ret = array();
    	foreach($array as $chave => $valor)
    	{
    		$ret[$valor["name"]] = $valor["value"];
    	}
    	
    	return $ret;
    }
    
    public function direct() 
    { 
        return $this->getSubmitParams(); 
    } 
} 
