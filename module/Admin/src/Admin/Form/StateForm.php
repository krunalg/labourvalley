<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class StateForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('State');
        $this->setAttributes(array(
            'method' => 'post',
            'class' => 'state-form',
            'id' => 'state-form'
        ));
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            ),
            'options' => array(
                'label' => 'Id'
            )
        ));
        $this->add(array(
            'name' => 'state',
            'attributes' => array(
                'type' => 'text',
                'required' => 'required',
                'id' => 'state',
                'placeholder' => 'State',
            		'class'=>'typehead',
                'autocomplete'=>'off'
            ),
            'options' => array(
                'label' => 'State'
            )
        ));
        $this->add(array(
            'name' => 'latitude',
            'attributes' => array(
                'type' => 'hidden'
            ),
            'options' => array(
                'label' => 'Latitude'
            )
        ));
        $this->add(array(
            'name' => 'longitude',
            'attributes' => array(
                'type' => 'hidden'
            ),
            'options' => array(
                'label' => 'Longitude'
            )
        ));
        $submitButton = new Element\Button('submit');
        $submitButton->setName('submit')
            ->setLabel('Submit')
            ->setAttributes(array(
            'class' => 'btn btn-primary',
            'type' => 'submit'
        ));
        $this->add($submitButton);
    }
}