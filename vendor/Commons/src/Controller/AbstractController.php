<?php
namespace Commons\Controller;

use Zend\Mvc\Controller\AbstractActionController as ActionController;

class AbstractController extends ActionController
{

    public function setTitle($headTitle = null)
    {
        $viewHelper = $this->getServiceLocator()->get('viewHelperManager');
        $headTitleHelper = $viewHelper->get('headTitle');
        if ($headTitle != null) {
            if (is_array($headTitle)) {
                foreach ($headTitle as $title) {
                    $headTitleHelper->append($title);
                }
            }else if(is_string($headTitle)){
                $headTitleHelper->append($headTitle);
            }
        }
    }
}