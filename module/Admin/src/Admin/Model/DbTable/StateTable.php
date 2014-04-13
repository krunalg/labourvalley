<?php
namespace Admin\Model\DbTable;

use Commons\Model\DbTable\AbstractDbTable;

class StateTable extends AbstractDbTable
{
    protected $_table_name = "states";

    protected $_array_object_prototype = 'Admin\Model\State';
}