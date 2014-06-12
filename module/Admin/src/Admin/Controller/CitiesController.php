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
use Admin\Form\CityForm;
use Admin\Model\City;

class CitiesController extends AbstractController
{

    public function addAction()
    {
        $layout = $this->layout('layout/main');
        $cityForm = new CityForm();
        $city = new City();
        $this->setTitle(array(
            "Manage Cities"
        ), array(
            "Manage Cities",
            "Add, Edit, Delete Cities"
        ));
        $request = $this->getRequest();
        $cityList = $city->fetchCities()->toArray();
        if ($request->isPost()) {
            $cityForm->setInputFilter($city->getInputFilter());
            $cityForm->setData($request->getPost());
            if ($cityForm->isValid()) {
                $data = $cityForm->getData();
                $city->exchangeArray($data);
                try {
                    $status = $city->save($city->toArray());
                } catch (\Exception $ex) {
                    echo $ex->getMessage();
                }
                if ($status) {
                    if(isset($data['id']) && $data['id']!=""){
                        $this->flashMessenger()->addSuccessMessage('City has been updated successfully');
                    }else{
                        $this->flashMessenger()->addSuccessMessage('City has been added successfully');
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('City already exists');
                }
                $this->redirect()->toRoute('city-add');
            } else {
                // print_r($stateForm->getMessages());
            }
        }
        $view = new ViewModel(array(
            'cityForm' => $cityForm,
            'cities' => $cityList
        ));
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        $city = new City();
        $status = $city->deleteCity($id);
        if ($status) {
            $this->flashMessenger()->addSuccessMessage('City has been deleted successfully');
        } else {
            $this->flashMessenger()->addErrorMessage('Failed! City has not been deleted');
        }
        $this->redirect()->toRoute('city-add');
    }

    public function fetchAction()
    {
        $request = $this->getRequest();
        $id = $this->params('id');
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $city = new City();
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $result = $city->fetchCities($id);
            echo json_encode($result->toArray());
        }
        exit();
    }
}