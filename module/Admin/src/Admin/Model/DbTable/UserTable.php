<?php
namespace Users\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Commons\Model\DbTable\AbstractDbTable;

class UserTable extends AbstractDbTable
{

    protected $_table_name = "admin_users";
    protected $_array_object_prototype = 'Admin\Model\User';
    
    public function saveUser(User $user)
    {
        $data = array(
            'email' => $user->email,
            'name' => $user->name,
            'password' => $user->password
        );
        $id = (int) $user->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array(
                    'id' => $id
                ));
            } else {
                throw new \Exception('User ID does not exist');
            }
        }
    }

    public function getUser($username)
    {
        $rowset = $this->tableGateway->select(array(
            'username' => $username
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find user $username");
        }
        return $row;
    }
}