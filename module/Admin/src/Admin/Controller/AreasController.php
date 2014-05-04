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
use Admin\Form\AreaForm;
use Admin\Model\Area;

class AreasController extends AbstractController
{

    public function addAction()
    {
        $layout = $this->layout('layout/main');
        $areaForm = new AreaForm();
        $area = new Area();
        $this->setTitle(array(
            "Manage Areas "
        ), array(
            "Manage Areas",
            "Add, Edit, Delete Areas"
        ));
        $request = $this->getRequest();
        $areaList = $area->fetchAreas()->toArray();
        //print_r($areaList);
        if ($request->isPost()) {
            $areaForm->setInputFilter($area->getInputFilter());
            $areaForm->setData($request->getPost());
            if ($areaForm->isValid()) {
                $data = $areaForm->getData();
                $area->exchangeArray($data);
                try {
                    $status = $area->save($area->toArray());
                } catch (\Exception $ex) {
                    echo $ex->getMessage();
                }
                if ($status) {
                    if (isset($data['id']) && $data['id'] != "") {
                        $this->flashMessenger()->addSuccessMessage('Area has been updated successfully');
                    } else {
                        $this->flashMessenger()->addSuccessMessage('Area has been added successfully');
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('Area already exists');
                }
                $this->redirect()->toRoute('area-add');
            } else {
                // print_r($stateForm->getMessages());
            }
        }
        $view = new ViewModel(array(
            'areaForm' => $areaForm,
            'areas' => $areaList
        ));
        return $view;
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        $city = new City();
        $status = $city->deleteCity($id);
        if ($status) {
            $this->flashMessenger()->addSuccessMessage('Area has been deleted successfully');
        } else {
            $this->flashMessenger()->addErrorMessage('Failed! Area has not been deleted');
        }
        $this->redirect()->toRoute('area-add');
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
            $result = $city->fetchCity($id);
            echo json_encode($result);
        }
        exit();
    }

    public function fetchstatesAction()
    {
        $request = $this->getRequest();
        $id = $this->params('id');
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $city = new City();
        $isAjax = $request->isXmlHttpRequest();
        if ($isAjax) {
            $result = $city->fetchCities($id);
            echo json_encode($result);
        }
        exit();
    }
}