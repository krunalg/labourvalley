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
        $state = new State();
        $this->setTitle(array(
            "Manage States"
        ), array(
            "Manage States",
            "Add, Edit, Delete States"
        ));
        $request = $this->getRequest();
        $stateList = $state->fetchStates()->toArray();
        if ($request->isPost()) {
            $stateForm->setInputFilter($state->getInputFilter());
            $stateForm->setData($request->getPost());
            if ($stateForm->isValid()) {
                $data = $stateForm->getData();
                $state->exchangeArray($data);
                try {
                    $status = $state->save($state->toArray());
                } catch (\Exception $ex) {
                    echo $ex->getMessage();
                }
                if ($status) {
                    $this->flashMessenger()->addSuccessMessage('State has been added successfully');
                } else {
                    $this->flashMessenger()->addErrorMessage('State already exists');
                }
                $this->redirect()->toRoute('state-add');
            } else {
                // print_r($stateForm->getMessages());
            }
        }
        $view = new ViewModel(array(
            'stateForm' => $stateForm,
            'states' => $stateList
        ));
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        $state = new State();
        $status = $state->deleteState($id);
        if ($status) {
            $this->flashMessenger()->addSuccessMessage('State has been deleted successfully');
        } else {
            $this->flashMessenger()->addErrorMessage('Failed! State has not been deleted');
        }
        $this->redirect()->toRoute('state-add');
    }

    public function fetchAction()
    {
        $request = $this->getRequest();
        $id = $this->params('id');
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $state = new State();
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $result = $state->fetchState($id);
            echo json_encode($result);
        }
        exit();
    }
}