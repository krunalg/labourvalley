<?php

namespace Commons\Model;

use Commons\Model\DbTable\AbstractDbTable;
use Zend\Db\Sql\Select;

abstract class AbstractModel {
	protected $_dbTableInstance;
	protected $_db_table_name;
	protected $_primary_key = 'id';
	
	/**
	 * Set the DB table for the model
	 *
	 * @param AbstractDbTable $dbTable        	
	 * @return \MCommons\Model\AbstractModel
	 */
	public function setDbTable(AbstractDbTable $dbTableInstance) {
		$this->_dbTableInstance = $dbTableInstance;
		return $this;
	}
	/**
	 * Return the Abstract DB Table Instance
	 *
	 * @return \MCommons\Model\DbTable\AbstractDbTable
	 * @throws \Exception
	 */
	public function getDbTable() {
		if (! $this->_dbTableInstance) {
			if (! $this->_db_table_name) {
				throw new \Exception ( "Please set the DbTable for the model " . get_class ( $this ) . " before proceeding!", 500 );
			}
			$this->setDbTable ( new $this->_db_table_name () );
		}
		return $this->_dbTableInstance;
	}
	
	/**
	 * Exchange the data with model variables
	 *
	 * @param array $data        	
	 * @return \Commons\Model\AbstractModel
	 */
	public function exchangeArray(array $data) {
		foreach ( $data as $key => $value ) {
			if (property_exists ( $this, $key )) {
				$this->$key = $value;
			}
		}
		return $this;
	}
	
	/**
	 * get array of the variables of respective class
	 *
	 * @return multitype:
	 */
	public function toArray() {
		$reflect = new \ReflectionClass ( $this );
		$props = $reflect->getProperties ( \ReflectionProperty::IS_PUBLIC );
		$arr = array ();
		foreach ( $props as $refProp ) {
			$arr [$refProp->name] = $this->{$refProp->name};
		}
		return $arr;
	}
	public function find(array $options = array()) {
		$select = new Select ();
		$select->from ( $this->getDbTable ()->getTableName () );
		
		// Select required columns
		if (isset ( $options ['columns'] )) {
			$select->columns ( $options ['columns'] );
		}
		
		// Set the where condition
		if (isset ( $options ['where'] )) {
			$select->where ( $options ['where'] );
		}
		
		// Set the order type
		if (isset ( $options ['order'] )) {
			$select->order ( $options ['order'] );
		}
		
		// Limit the number of output rows
		if (isset ( $options ['limit'] ) && $options ['limit']) {
			$select->limit ( $options ['limit'] );
		}
		// Set the offset to start the result from
		if (isset ( $options ['offset'] ) && $options ['offset']) {
			$select->offset ( $options ['offset'] );
		}
		
		// Set the GroupBy
		if (isset ( $options ['group'] ) && $options ['group']) {
			$select->group ( $options ['group'] );
		}
		
		// Set the Like
		if (isset ( $options ['like'] ) && $options ['like']) {
			$select->where->like ( $options ['like'] ['field'], $options ['like'] ['like'] );
		}
		
		// Set the Joins
		if (isset ( $options ['joins'] ) && $options ['joins'] && is_array($options ['joins'])) {
			foreach ($options['joins'] as $join){
				$select->join($join['name'], $join['on'],$join['columns'],$join['type']);
			}
		}
		$results = $this->getDbTable ()->getReadGateway ()->selectWith ( $select );
		return $results;
	}
	public function getPlatform($type = 'READ') {
		if (strtoupper ( $type ) == 'READ') {
			$platform = $this->getDbTable ()->getReadGateway ()->getAdapter ()->getPlatform ();
		} else if (strtoupper ( $type ) == 'WRITE') {
			$platform = $this->getDbTable ()->getWriteGateway ()->getAdapter ()->getPlatform ();
		} else {
			throw new \Exception ( "Please provie the platform type needed READ/WRITE" );
		}
		return $platform;
	}
}