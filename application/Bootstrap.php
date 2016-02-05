<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initTimeZone() {

        date_default_timezone_set("Europe/Moscow");
    }
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->headTitle('Глобал Профи')->setSeparator('::'); //.$this->user->role
        //if($this->user) $view->headTitle()->append($this->user->role);
        Zend_Layout::startMvc(array('layout' => 'layout'));
    }
    //
    
    
    protected function _initAutoload() {
        $front = $this->bootstrap("frontController")->frontController;
        $front->addModuleDirectory(APPLICATION_PATH . '/modules/');
        $modules = $front->getControllerDirectory();

        foreach (array_keys($modules) as $module){
            $mod = array(
                'namespace' => ucfirst($module),
                'basePath' => $front->getModuleDirectory($module));
            $loader = new Zend_Application_Module_Autoloader($mod);
        }

        $loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Application',
            'basePath' => APPLICATION_PATH
        ));

        return $loader;
    }

  

}


class App_Controller extends Zend_Controller_Action {

    protected $styles = array('bootstrap', 'style');
    protected $scripts = array('jquery-2.1.1.min', 'bootstrap');
 
}
