<?php
namespace Admin\Model\DbTable;

use Commons\Model\DbTable\AbstractDbTable;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Admin\Model\User;

class UserTable extends AbstractDbTable
{

    protected $_table_name = "admin_users";

    protected $_array_object_prototype = 'Admin\Model\User';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new User());
        
        $this->initialize();
    }
}