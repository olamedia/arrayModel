<?php

namespace arrayModel;

class pgStorage{
	private static $_prepared = [];
	protected $_connectionString;
	protected $_connectType;
	protected $_link = null;
	public function __construct($connection_string, $connect_type = 0){
		$this->_connectionString = $connection_string;
		$this->_connectType = $connect_type;
	}
	public static function create($connection_string, $connect_type = 0){
		return new self($connection_string, $connect_type);
	}
	public function connect(){
		if (null === $this->_link){
			$this->_link = \pg_connect($this->_connectionString, $this->_connectType);
		}
		return $this;
	}
	public function getLink(){
		$this->connect();
		return $this->_link;
	}
	public function register($className, $tableName, $keyMap = [], $options = []){
		$storage = new arrayStorage($this);
		$storage->setTableName($tableName);
		$storage->setKeyMap($keyMap);
		$storage->setOptions($options);
		storageManager::getInstance()[$className] = $storage;
		return $this;
	}
	public function getTable($tableName){
		return new arrayStorage($this, $tableName);
	}
	private function _getWhere($storage, $array){
		$wherea = [];
		$pka = $storage->getPrimaryKey();
		$values = [];
		foreach ($pka as $k){
			$wherea[] = $k.' = ?';
			$values[] = $array[$k];
		}
		return [implode(' AND ', $wherea), $values];
	}
	public function insert($storage, $model){
		$a = $model->toArray();
		$pka = $storage->getPrimaryKeyFields();
		$aa = $storage->getAutoFields();
		$keys = [];
		$valueSubs = [];
		$values = [];
		foreach ($a as $k => $v){
			$keys[] = $k;
			if (in_array($k, $aa)){
				$valueSubs[] = 'DEFAULT';
			}else{
				$valueSubs[] = '?';
				$values[] = $v;
			}
		}
		foreach ($aa as $k){
			if (!isset($aa[$k])){
				$keys[] = $k;
				$valueSubs[] = 'DEFAULT';
			}
		}
		$tableName = $storage->getTableName();
		$stKey = 'insert/'.$tableName.'/'.\implode(',', $keys);
		$sql = 'INSERT INTO '.$tableName.' ('.\implode(',', $keys).') VALUES ('.\implode(', ', $valueSubs).')';
		if (count($aa)){
			$sql .= ' RETURNING '.implode(',', $aa);
		}
		//list($whereSql, $wValues) = $this->_getWhere($storage, $array);
		//$values = array_merge($values, $wValues);
		//$sql .= 
		
		\var_dump($stKey, $sql, $values);
		if (!isset(self::$_prepared[$stKey])){
			//$st = 
			\pg_prepare($this->getLink(), $stKey, $sql);
		}
		//$st = pg_prepare($this->getLink(), $statementKey, $sql);
		$q = pg_execute($this->getLink(), $stKey, $values);
		$aa = pg_fetch_assoc($q);
		foreach ($aa as $k => $v){
			$model[$k] = $v;
		}
		$model->setFromDb(true);
		$model->resetChangedKeys();
		var_dump($model);
	}
	public function update($tableName, $array){
		
	}
}