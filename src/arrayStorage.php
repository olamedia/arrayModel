<?php

namespace arrayModel;

class arrayStorage{
	protected $_storageDriver = null;
	protected $_tableName = null;
	protected $_keyMap = null;
	protected $_autoFields = [];
	protected $_primaryKey = [];
	protected $_primaryKeyFields = [];
	protected $_options = null;
	public function __construct($storageDriver){
		$this->_storageDriver = $storageDriver;
	}
	public function setTableName($tableName){
		$this->_tableName = $tableName;
	}
	public function getTableName(){
		return $this->_tableName;
	}
	public function setKeyMap($keyMap){
		$this->_keyMap = $keyMap;
		foreach ($this->_keyMap as $propertyName => $a){
			$fieldName = $propertyName;
			if (isset($a['field'])){
				$fieldName = $a['field'];
			}
			if (isset($a['primaryKey'])){
				$this->_primaryKey[] = $propertyName;
				$this->_primaryKeyFields[] = $fieldName;
			}
			if (isset($a['auto'])){
				$this->_autoFields[] = $fieldName;
			}
		}
	}
	public function getAutoFields(){
		return $this->_autoFields;
	}
	public function getPrimaryKey(){
		return $this->_primaryKey;
	}
	public function getPrimaryKeyFields(){
		return $this->_primaryKey;
	}
	public function setOptions($options){
		$this->_options = $options;
	}
	public function insert($array){
		$this->_storageDriver->insert($this, $array);
	}
	public function update($array){
		$this->_storageDriver->update($this, $array);
	}
}
