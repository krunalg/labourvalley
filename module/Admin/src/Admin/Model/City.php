<?php
namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Commons\Model\AbstractModel;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Delete;

class City extends AbstractModel implements InputFilterAwareInterface
{

    public $id;

    public $city;

    public $state;

    public $latitude;
    
    public $longitude;
    
    public $created_by = null;

    public $modified_by = null;

    public $created;

    public $modified;

    protected $_db_table_name = 'Admin\Model\DbTable\CityTable';

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
        try {
            if (isset($data['id']) && $data['id'] == "") {
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

    public function fetchCities($state_id=null)
    {
        $this->getDbTable()->setArrayObjectPrototype ( 'ArrayObject' );
        $options = array(
            'columns' => array(
                'id',
                'city',
                'state'
            ),
            'where' => array(
                'cities.status' => 1
            ),
            'order' => array(
                'city'
            ),
            'joins' => array(
                array(
                    'name' => array(
                        's' => 'states'
                    ),
                    'on' => 's.id =  cities.state',
                    'columns' => array(
                        'state'
                    ),
                    'type' => 'left'
                )
            )
        );
        if($state_id!=null){
            $options['where']['cities.state'] = $state_id;
        }
        $states = $this->find($options);
        return $states;
    }
    public function deleteCity($id)
    {
        $delete = new Delete();
        $status = false;
        try {
            $delete->from($this->getDbTable()
                ->getTableName());
            $where = new \Zend\Db\Sql\Where();
            $where->equalTo('id', $id);
            $delete->where($where);
            $writeGateway = $this->getDbTable()->getWriteGateway();
            $rowsAffected = $writeGateway->delete($where);
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
                'name' => 'city',
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
                        )
                    )
                )
            ));
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
                            'encoding' => 'UTF-8'
                        )
                    )
                )
            ));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }

    public function fetchCity($id)
    {
        $this->getDbTable()->setArrayObjectPrototype ( 'ArrayObject' );
        try {
            $row = $this->find(array(
                'columns' => array(
                    'id',
                    'city',
                    'state'
                ),
                'where' => array(
                    'id' => $id,
                    'status' => 1
                )
            ))->current();
            return $row->getArrayCopy();
        } catch (\Exception $ex) {
            return array(
                'status' => 'failed',
                'data' => $ex->getMessage()
            );
        }
    }
}