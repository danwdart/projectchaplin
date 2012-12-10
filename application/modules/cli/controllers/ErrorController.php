<?php
class ErrorController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                echo 'Not Found - property'.PHP_EOL;
                break;
            default:
                echo 'something broke horribly'.PHP_EOL;
                echo get_class($errors->exception).PHP_EOL;
                echo $errors->exception->getMessage().PHP_EOL;
                echo $errors->exception->getTraceAsString().PHP_EOL;  
                break;
        }
 
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            echo get_class($this->exception);
            echo $this->exception->getMessage();
            echo $this->exception->getTraceAsString();
        }        
        //$this->_request);
    }
}

