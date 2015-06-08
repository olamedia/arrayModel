<?php

namespace arrayModel;

class storageManager implements \ArrayAccess, \IteratorAggregate{
	protected static $_instance = null;
	protected $_storages = [];
	public static function getInstance(){
		if (null === self::$_instance){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function offsetSet($className, $value){
        $this->_storages[$className] = $value;
    }
    public function offsetExists($className){
        return \array_key_exists($className, $this->_storages);
    }
    public function offsetUnset($className){
        unset($this->_storages[$className]);
    }
    public function offsetGet($className){
        return \array_key_exists($className, $this->_storages)?$this->_storages[$className]:null;
    }
	public function getIterator(){
        return new \ArrayIterator($this->_storages);
    }
	public function toArray(){
		return $this->_storages;
	}
}
