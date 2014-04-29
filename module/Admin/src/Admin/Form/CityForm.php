<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;
use Admin\Model\City;
use Admin\Model\State;

class CityForm extends Form
{

    public function __construct($name = null)
    {
        $state = new State();
        $allStates = $state->fetchStates()->toArray();
        $newState = array(''=>'Select State');
        foreach($allStates as $state){
            $newState[$state['id']] = $state['state'];
        }
        parent::__construct('City');
        $this->setAttributes(array(
            'method' => 'post',
            'class' => 'city-form',
            'id' => 'city-form'
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
            'type' => 'Zend\Form\Element\Select',
            'name' => 'state',
            'required' => 'required',
            'attributes' => array(
                'required' => 'required',
                'id'=>'state',
                'options' => $newState
            )
        ));
        $this->add(array(
            'name' => 'city',
            'attributes' => array(
                'type' => 'text',
                'required' => 'required',
                'id' => 'city',
                'placeholder' => 'City'
            ),
            'options' => array(
                'label' => 'City'
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