<?php
namespace Users\Model;

use Commons\Model\DbTable\AbstractDbTable;

class UserTable extends AbstractDbTable
{
    protected $_table_name = "admin_users";

    protected $_array_object_prototype = 'Admin\Model\User';
}