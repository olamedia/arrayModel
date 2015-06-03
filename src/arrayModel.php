<?php

class arrayModel implements ArrayAccess, IteratorAggregate{
	protected $_data = null;
	public function __construct($g){
		$this->_data = $g;
	}
	public function offsetSet($offset, $value){
        if (is_null($offset)){
            $this->_data[] = $value;
        }else{
            $this->_data[$offset] = $value;
        }
    }
    public function offsetExists($offset){
        return array_key_exists($offset, $this->_data);
    }
    public function offsetUnset($offset){
        unset($this->_data[$offset]);
    }
    public function offsetGet($offset){
        return array_key_exists($offset, $this->_data)?$this->_data[$offset]:null;
    }
	public function getIterator(){
        return new ArrayIterator($this->_data);
    }
	public function toArray(){
		return $this->_data;
	}
}
