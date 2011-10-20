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
        
		//Check if priority spec in value string
		if(substr_count($value,';')){
			list($value,$spec) = explode(';',$value,2);
			
			//Spec may contrain level= and q= paramaters, seperated by a semi-colon ';'
			//text/html;level=2;q=0.4
			//@todo complete multi-param spec parsing
			if(substr_count($spec,';')){
			    $spec = explode(';',$spec);
			}
		}
   
		//Priority may be q= or level=
		if(null !== $spec){
		    
		    if(is_array($spec)){
		        if(isset($spec['level'])){
		            $type = 'level';
		            $val = (int)$spec['level'];
		        } elseif(isset($spec['q'])){
		            $type = 'q';
		            $val = floatval($spec['q']);
		        }
		    } else {
			    list($type,$val) = explode('=',trim($spec));
		    }
		    
			if($type == 'q' || $type == 'level'){
			    $this->values[$value] = array($type => $val);
			    
			} else {
			    throw new \InvalidArgumentException(sprintf('%s is not a valid header value priority parameter.',$type));
			}
		} else {
		    $this->values[$value] = array();
		}
		return $this;
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
		
	    return isset($this->values[$value]['q']) ? $this->values[$value]['q'] : 1;
	}
	
	public function getLevel($value)
	{
		if(!isset($this->values[$value])){
	        return false;
	    }

		return isset($this->values[$value]['level']) ? $this->values[$value]['level'] : false;
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