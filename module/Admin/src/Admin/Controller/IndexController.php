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

class IndexController extends AbstractController
{

    public function indexAction()
    {
        $layout = $this->layout('layout/login');
        $view = new ViewModel();
        $this->setTitle(array("Login"));
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