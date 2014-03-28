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
use Users\Model\UserTable;

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
        if($request->isPost()){
            $users = new User();
            $loginForm->setInputFilter($users->getInputFilter());
            $loginForm->setData($request->getPost());
        	if($loginForm->isValid()){
        	    $users->exchangeArray($loginForm->getData());
        	   exit;
        	}else{
        	    echo "fuck bhosdike";
        	    exit;
        	}
        }
        return $view;
    }

    public function registerAction()
    {
        $view = new ViewModel();
        $view->setTemplate('admin/index/register');
        return $view;
    }

    public function loginAction()
    {
        $view = new ViewModel();
        $view->setTemplate('admin/index/login');
        return $view;
    }
}