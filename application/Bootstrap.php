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

#   <<< Older Version; commented-out by Michael
#    protected function _initPlugins() {
#        $front = Zend_Controller_Front::getInstance();
#        $front->registerPlugin(new Application_Plugin_Acl());
#        return $front;
#    }
# ---
#    >>> Newer Version; written by Michael
    public function _initAcl() {
        // Создаём объект Zend_Acl
        $acl = new Zend_Acl();

        // указываем, что у нас есть ресурс index
        $acl->addResource('index');
        // ресурс add является потомком ресурса index
        $acl->addResource('add', 'index');
        $acl->addResource('sites','index');
        $acl->addResource('maps','index');
        $acl->addResource('logs','index');
        $acl->addResource('admin','index');

#        $acl->addResource('edit', 'index');
#        $acl->addResource('delete', 'index');
        $acl->addResource('error');
        $acl->addResource('auth');
        $acl->addResource('login', 'auth');
        $acl->addResource('logout', 'auth');
        // далее переходим к созданию ролей, которых у нас 2:
        // гость (неавторизированный пользователь)
        $acl->addRole('guest');
        // администратор, который наследует доступ от гостя
        $acl->addRole('admin', 'guest');
        // разрешаем гостю просматривать ресурс index
        $acl->allow('guest', 'index', array('index'));
        // разрешаем гостю просматривать ресурс auth и его подресурсы
        $acl->allow('guest', 'auth', array('index', 'login', 'logout'));
        // даём администратору доступ к ресурсам 'add', 'edit' и 'delete'
        $acl->allow('admin', 'index', array('add', 'edit', 'delete'));
        // разрешаем администратору просматривать страницу ошибок
        $acl->allow('admin', 'error');
        #$fc = Zend_Controller_Front::getInstance();
        // регистрируем плагин с названием AccessCheck, в который передаём
        // на ACL и экземпляр Zend_Auth
        #$fc->registerPlugin(new Application_Plugin_AccessCheck($acl, Zend_Auth::getInstance()));
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Application_Plugin_Acl());
    }

    /*
     * Инициализируем объект навигатора и передаем его в View
     *
     * @return Zend_Navigation
     */
    public function _initNavigation()
     {
        // Бутстрапим View
        $this->bootstrapView();
        $view = $this->getResource('view');
        
        $logBtn = (Zend_Auth::getInstance()->hasIdentity())? array(
                'controller' => 'auth',
                'action'     => 'logout',
                'label'      => 'Выйти'               
            ):array(
                'controller' => 'auth',
                'action'     => 'index',
                'label'      => 'Авторизация',
            );
        // Структура простого меню (можно вынести в XML)
        $menuM = array(
            array(
                'controller' => 'sites',
                'label'      => 'Список сайтов'               
            ),
            array(
                'controller' => 'admin',
                'action'     => 'index',
                'label'      => 'Проверка сайтов'               
            ),
            array(
                'module' => 'best',
                'controller' => 'news',
                'action'     => 'index',
                'label'      => 'Новости'               
            ),
            $logBtn
        );
        
        $menuN = array(
            array(
                'module' => 'best',
                'controller' => 'maps',
                'action'     => 'index',
                'label'      => 'Карты парсинга',
            ),
            array(
                'module' => 'best',
                'controller' => 'maps',
                'action'     => 'add',
                'label'      => 'Добавить новую карту',
            ),
            array(
                'module' => 'best',
                'controller' => 'news',
                'action'     => 'scan',
                'label'      => 'Запустить парсер новостей',
            ),
        );
        
        // Создаем новый контейнер на основе нашей структуры
        $menuMain = new Zend_Navigation($menuM);
        $menuNews = new Zend_Navigation($menuN);
        // Передаем контейнер в View
        $view->menuMain = $menuMain;
        $view->menuNews = $menuNews;
 
        //return $container;
    }

  

}


class App_Controller extends Zend_Controller_Action {

    protected $styles = array('bootstrap', 'style');
    protected $scripts = array('jquery-2.1.1.min', 'bootstrap', 'maps');
 
}
