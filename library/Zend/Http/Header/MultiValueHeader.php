<?php
namespace Zend\Http\Header;

abstract class MultiValueHeader implements HeaderDescription
{
	protected $values = array();
	
	/** 
	 * @param string $value
	 * @param array | string array('level' => 2) or 'q=0.5'
	 */
	public function addValue($value,$spec = null)
	{
        $value = trim($value);

		if(substr_count($value,';')){
			list($value,$spec) = explode(';',$value,2);
		}
		
		$this->values[$value] = $this->parseSpec($spec);
		return $this;
	}
	
	/**
	 * Parses spec from either string or array. Returns array
	 * to assign to value.
	 * 
	 * @param mixed $spec
	 * @return array
	 * @throws \InvalidArgumentException
	 */
	protected function parseSpec($spec)
	{
	    if(null === $spec){
	        return array();
	    }
	    
	    $params = array();
	    if(is_string($spec)){
		    //Spec may contain level= and q= parameters, seperated by a semi-colon ';'
			//text/html;level=2;q=0.4		
			$spec = explode(';',$spec);
            foreach($spec as $s){
                list($param,$value) = explode('=',trim($s));
                $params[$param] = $value;
            }
	    } else if(is_array($spec)){
	        $params = $spec;
	    }
	    
	    //Filter and validate params
	    foreach($params as $param => &$val){
	        if($param == 'q'){
	            if((is_string($val) && !preg_match('#^1|(0?\.{1}[0-9]{1})$#',$val)) || (is_float($val) && !($val < 1 && $val > 0))){
	                throw new \InvalidArgumentException(sprintf('%s is not a valid quality factor value. Must be a float value between 0 and 1.',$val));
	            }
	            $val = (float)$val;
	        } else if($param == 'level'){
	            if(!is_int($val) && !preg_match('#^\d{1}$#',$val)){
	                throw new \InvalidArgumentException(sprintf('%s is not a valid level value. Must be a single digit integer.',$val));
	            }
	            $val = (int)$val;
	        } else {
	            throw new \InvalidArgumentException(sprintf('%s is not a valid parameter.',$param));
	        }
	    }
	    return $params;
	}
	
	public function addValues($values)
	{
	    if(!is_array($values)){
	        $values = explode(',',$values);
	    }
	    
	    foreach($values as $value){
	        $spec = null;
	        if(is_array($value)){
	            $spec = isset($value[1]) ? $value[1] : null;
	            $value = $value[0];
	        } 
	        $this->addValue($value,$spec);
	    }
	    return $this;
	}
	
	public function getQualityFactor($value)
	{
	    if(!isset($this->values[$value])){
	        return false;
	    }
		
	    return isset($this->values[$value]['q']) 
	        ? $this->values[$value]['q'] : 1;
	}
	
	public function getLevel($value)
	{
		if(!isset($this->values[$value])){
	        return false;
	    }

		return isset($this->values[$value]['level']) 
		    ? $this->values[$value]['level'] : false;
	}
	
	public function hasValue($value)
	{
	    return isset($this->values[$value]);
	}
	
	public function getFieldValue()
	{
		$values = array();
	    foreach($this->values as $value => $spec){
	        $value .= isset($spec['q']) ? ';q='.$spec['q'] : '';
	        $value .= isset($spec['level']) ? ';level='.$spec['level'] : '';
	        $values[] = $value;
	    }
	    return implode(',',$values);
	}
	
	public function toString()
	{
	    return $this->getFieldName() . ': ' . $this->getFieldValue();
	}
}