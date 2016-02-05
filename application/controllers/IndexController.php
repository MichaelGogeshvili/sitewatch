<?php

class IndexController extends App_Controller {

    public function init() {
        $this->view->styles = $this->styles;
        $this->view->scripts = $this->scripts;
    }

    public function indexAction() {
        // $this->_redirect('/sites/');
        // вывод загаловков
        $this->view->title = "Авторизация";
        $this->view->headTitle($this->view->title, 'PREPEND');
    }
}
