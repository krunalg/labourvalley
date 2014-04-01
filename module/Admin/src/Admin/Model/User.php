<?php
namespace Admin\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Commons\Model\AbstractModel;

class User extends AbstractModel implements InputFilterAwareInterface
{

    public $user_id;

    public $firstname;

    public $lastname;

    public $username;

    public $email;

    public $password;

    public $avatar;

    public $status;

    public $is_super_user;

    protected $inputFilter;

    protected $_db_table_name = 'Admin\Model\DbTable\UserTable';

    protected $_primary_key = 'user_id';

    /**
     * Encode password into md5
     *
     * @param string $clear_password            
     */
    public function setPassword($clear_password)
    {
        $this->password = md5($clear_password);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (! $this->inputFilter) {
            $inputFilter = new InputFilter();
            
            $inputFilter->add(array(
                'name' => 'username',
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
                'name' => 'password',
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
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}