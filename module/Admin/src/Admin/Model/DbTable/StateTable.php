<?php
namespace Admin\Model\DbTable;

use Commons\Model\DbTable\AbstractDbTable;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Admin\Model\User;

class StateTable extends AbstractDbTable
{
    protected $_table_name = "states";

    protected $_array_object_prototype = 'Admin\Model\State';
}