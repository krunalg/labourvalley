<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class LoginForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('Login');
        $this->setAttributes(array(
            'method' => 'post',
            'enctype' => 'multipart/form-data',
            'class' => 'login-form',
            'id' => 'login-form'
        ));
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type' => 'text',
                'class' => 'username',
                'required' => 'required',
                'id' => 'username',
                'placeholder' => 'Username'
            ),
            'options' => array(
                'label' => 'Username'
            )
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'id' => 'password',
                'required' => 'required',
                'placeholder' => 'Password'
            ),
            'options' => array(
                'label' => 'Password'
            )
        ));
        $submitButton = new Element\Button('submit');
        $submitButton->setName('submit')
            ->setLabel('Submit')
            ->setAttributes(array(
            'class' => 'btn btn-default btn-block',
            'type' => 'submit'
        ));
        $this->add($submitButton);
    }
}