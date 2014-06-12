<?php
namespace Admin\Model\DbTable;

use Commons\Model\DbTable\AbstractDbTable;

class AreaTable extends AbstractDbTable
{
    protected $_table_name = "areas";

    protected $_array_object_prototype = 'Admin\Model\Area';
}