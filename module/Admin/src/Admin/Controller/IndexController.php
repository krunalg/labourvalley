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
use Admin\Form\LoginForm;
use Admin\Model\User;

class IndexController extends AbstractController
{

    public function indexAction()
    {
        $loginForm = new LoginForm();
        $layout = $this->layout('layout/login');
        $view = new ViewModel(array(
            'loginForm' => $loginForm
        ));
        $this->setTitle(array(
            "Login"
        ));
        $request = $this->getRequest();
        if ($request->isPost()) {
            $users = new User();
            $loginForm->setInputFilter($users->getInputFilter());
            $loginForm->setData($request->getPost());
            try {
                if ($loginForm->isValid()) {
                    $sm = $this->getServiceLocator();
                    $data = $loginForm->getData();
                    $adapter = $sm->get('AuthAdapter');
                    $adapter->setIdentity($data['username']);
                    $adapter->setCredential($data['password']);
                    
                    $service = $sm->get('AuthService');
                    $result = $service->authenticate($adapter);
                    if ($result->isValid()) {
                        $storage = $service->getStorage();
                        $storage->write($adapter->getResultRowObject(null, 'password'));
                        
                        return $this->redirect()->toRoute('home');
                    } else {
                        $this->redirect()->toRoute('login');
                    }
                    return array(
                        'form' => $loginForm,
                        'error_message' => array_pop($result->getMessages())
                    );
                }
            } catch (\Exception $ex) {
                echo $ex->getMessage();
            }
        }
        return $view;
    }

    public function homeAction()
    {
        $layout = $this->layout('layout/main');
        $username = $this->getAuthService()
            ->getStorage()
            ->read();
        $viewModel = new ViewModel(array(
            'name' => $username
        ));
        return $viewModel;
    }

    public function logoutAction()
    {
        $sm = $this->getServiceLocator();
        $service = $sm->get('AuthService');
        $service->clearIdentity();
        return $this->redirect()->toRoute('login');
    }
}