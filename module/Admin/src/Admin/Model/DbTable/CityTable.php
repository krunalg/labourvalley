<?php
namespace Admin\Model\DbTable;

use Commons\Model\DbTable\AbstractDbTable;

class CityTable extends AbstractDbTable
{
    protected $_table_name = "cities";

    protected $_array_object_prototype = 'Admin\Model\City';
}