<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin\Controller;

use Commons\Controller\AbstractController;
use Zend\View\Model\ViewModel;
use Admin\Form\StateForm;
use Admin\Model\State;

class StatesController extends AbstractController
{

    public function addAction()
    {
        $layout = $this->layout('layout/main');
        $stateForm = new StateForm();
        $view = new ViewModel(array(
            'stateForm' => $stateForm
        ));
        $this->setTitle(array(
            "Manage States"
        ), array(
            "Manage States",
            "Add, Edit, Delete States"
        ));
        $request = $this->getRequest();
        try {
            if ($request->isPost()) {
                $state = new State();
                $stateForm->setInputFilter($state->getInputFilter());
                $stateForm->setData($request->getPost());
                if ($stateForm->isValid()) {
                    $data = $stateForm->getData();
                    $state->name = $data['state'];
                    $state->created = date("Y-m-d");
                    $state->modified = date("Y-m-d");
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
        return $view;
    }
}