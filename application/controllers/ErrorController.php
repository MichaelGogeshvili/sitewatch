<?php

// application/controllers/ErrorController.php

class ErrorController extends App_Controller {
    
    protected $_redirector = null;
    
    public function init(){
        $this->view->styles = $this->styles;
        $this->view->scripts = $this->scripts;
    }

    public function errorAction() {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;
    }
    
    public function deniedAction() {
        if(!empty($_SERVER['REQUEST_URI'])){
            $this->redirect('/auth?referer='.$_SERVER['REQUEST_URI']);
        }else{
            $this->redirect('/auth/');
        }
    }

}
