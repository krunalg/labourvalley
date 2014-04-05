<?php
namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Commons\Model\AbstractModel;
use Zend\Db\Sql\Select;

class State extends AbstractModel implements InputFilterAwareInterface
{

    public $id;

    public $state;

    public $created_by = null;

    public $modified_by = null;

    public $created;

    public $modified;

    protected $_db_table_name = 'Admin\Model\DbTable\StateTable';

    protected $_primary_key = 'id';

    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function save($data)
    {
        $data = $this->toArray();
        $writeGateway = $this->getDbTable()->getWriteGateway();
        $select = new Select ();
        $select->from ( $this->getDbTable ()->getTableName () );
        $select->columns ( array () );
        
        $select->columns ( array (
            'count'=>new \Zend\Db\Sql\Expression('COUNT(*)')
        ) );
        
        $select->where ( array (
            'search_status' => 1
        ) );
        $count = $this->getDbTable ()->setArrayObjectPrototype ( 'ArrayObject' )->getReadGateway ()->selectWith ( $select );
        try {
            if (! isset($data['id']) && $data['id'] == "") {
                $data['created'] = date("Y-m-d h:i:s");
                $rowsAffected = $writeGateway->insert($data);
            } else {
                $data['modified'] = date("Y-m-d h:i:s");
                $rowsAffected = $writeGateway->update($data, array(
                    'id' => $this->id
                ));
            }
        } catch (\Exception $ex) {
            echo $ex->getCode();
        }
        return $rowsAffected;
    }

    public function fetchStates()
    {
        $states = $this->find(array(
            'columns' => array(
                'id',
                'state'
            ),
            'where' => array(
                'status' => 1
            )
        ));
        return $states;
    }

    public function deleteState($id)
    {
        $status = false;
        try {
            $data['status'] = 0;
            $writeGateway = $this->getDbTable()->getWriteGateway();
            $rowsAffected = $writeGateway->update($data, array(
                'id' => $id
            ));
            if ($rowsAffected > 0) {
                $status = true;
            }
            return $rowsAffected;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
        return $status;
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'state',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StripTags'
                    ),
                    array(
                        'name' => 'StringTrim'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 100
                        ),
                        array(
                            'name' => 'Db\RecordExists',
                            'options' => array(
                                'table' => 'states',
                                'field' => 'state'
                            )
                        )
                    )
                )
            ));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}