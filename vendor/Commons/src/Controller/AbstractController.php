<?php
namespace Commons\Controller;

use Zend\Mvc\Controller\AbstractActionController as ActionController;

class AbstractController extends ActionController
{

    /**
     * set title and page details for each action
     *
     * @param array $headTitle            
     * @param array $pageDetails            
     */
    public function setTitle($headTitle = null, $pageDetails = "")
    {
        $viewHelper = $this->getServiceLocator()->get('viewHelperManager');
        $headTitleHelper = $viewHelper->get('headTitle');
        if ($headTitle != null) {
            if (is_array($headTitle)) {
                foreach ($headTitle as $title) {
                    $headTitleHelper->append($title);
                }
            } else 
                if (is_string($headTitle)) {
                    $headTitleHelper->append($headTitle);
                }
        }
        if (! empty($pageDetails)) {
            if (isset($pageDetails[0])) {
                $this->layout()->setVariable('pageTitle', $pageDetails[0]);
            }
            if (isset($pageDetails[1])) {
                $this->layout()->setVariable('pageDescr', $pageDetails[1]);
            }
        }
    }

    public function setErrorMessages($messages)
    {
        $errors = array();
        foreach ($messages as $key => $msg) {
            foreach ($msg as $text) {
                $errors[] = ucfirst($key) . " : " . strtolower($text);
            }
        }
        $this->layout()->setVariable('errorMessages', $errors);
    }
}