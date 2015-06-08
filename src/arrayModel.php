<?php

class arrayModel implements ArrayAccess, IteratorAggregate{
	protected $_data = null;
	protected $_changedKeys = [];
	protected $_fromDb = false;
	protected $_isDraft = true;
	public function __construct($g = [], $fromDb = false){
		$this->_data = $g;
		$this->_fromDb = $fromDb;
		$this->_isDraft = !$fromDb;
	}
	public function setFromDb($fromDb = true){
		$this->_fromDb = $fromDb;
		$this->_isDraft = false;
		return $this;
	}
	public function offsetSet($offset, $value){
        if (null === $offset){
            $this->_data[] = $value;
        }else{
            $this->_data[$offset] = $value;
        }
		$this->_changedKeys[$offset] = $offset;
		$this->_isDraft = true;
    }
    public function offsetExists($offset){
        return \array_key_exists($offset, $this->_data);
    }
    public function offsetUnset($offset){
        unset($this->_data[$offset]);
    }
    public function offsetGet($offset){
        return \array_key_exists($offset, $this->_data)?$this->_data[$offset]:null;
    }
	public function getIterator(){
        return new \ArrayIterator($this->_data);
    }
	public function toArray(){
		return $this->_data;
	}
	public function getKeys(){
		return \array_keys($this->_data);
	}
	public function getValues(){
		return \array_values($this->_data);
	}
	public function resetChangedKeys(){
		$this->_changedKeys = [];
	}
	public function getChangedKeys(){
		return \array_keys($this->_changedKeys);
	}
	private function _getStorage(){
		return arrayModel\storageManager::getInstance()[\get_class($this)];
	}
	public function insert(){
		if (!$this->_isDraft){
			return;
		}
		if ($this->_getStorage()->insert($this)){
			$this->_fromDb = true;
			$this->_isDraft = false;
			$this->_changedKeys = [];
		}
	}
	public function update(){
		if (!$this->_isDraft){
			return;
		}
		if ($this->_getStorage()->update($this)){
			$this->_isDraft = false;
			$this->_changedKeys = [];
		}
	}
	public function save(){
		if ($this->_fromDb){
			$this->update();
		}else{
			$this->insert();
		}
	}
}
