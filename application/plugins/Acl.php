<?php

class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract {

    // переменная для перенаправления на страницу с ошибкой
    private $_controller = array(
        'controller' => 'error',
        'action' => 'denied'
    );

    public function __construct() {
        $acl = new Zend_Acl();

        // добавляем роли
        $acl->addRole(new Zend_Acl_Role('guest'));
        $acl->addRole(new Zend_Acl_Role('admin'));

        // добавляем ресурсы
        $acl->add(new Zend_Acl_Resource('sites'));
        $acl->add(new Zend_Acl_Resource('index'));
        $acl->add(new Zend_Acl_Resource('logs'));
        $acl->add(new Zend_Acl_Resource('auth'));
        $acl->add(new Zend_Acl_Resource('maps'));
        $acl->add(new Zend_Acl_Resource('best'));
        $acl->add(new Zend_Acl_Resource('news'));
        

        // если нет роли то все запрещаем
        $acl->deny();
        // админу по умолчанию разрешено все
        $acl->allow('admin', null);

        // гостю только контроллер с экшеном для входа
        $acl->allow('guest', 'auth', array(
            'index', 'check'
        ));
        
        $acl->allow('guest', 'maps', array(
            'cronmaps'
        ));
        
        $acl->allow('guest', array('module' => 'best', 'controller' => 'news'), array(
            'scan', 'redirect',
        ));

        // если надо запретить экшены в разрешенном контроллере
        /*$acl->deny('user', 'users', array(
            'login', 'registration'
        ));
         * 
         */

        Zend_Registry::set('acl', $acl);
    }

    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        // инициилизация объектов
        $auth = Zend_Auth::getInstance();
        $acl = Zend_Registry::get('acl');
        
        // если есть залогиненый пользователь
        if ($auth->hasIdentity()) {
            $role = $auth->getIdentity()->role;
        } else {
            $role = 'guest';
        }

        // если нет вообще такой роли, то будет гость 
        if (!$acl->hasRole($role)) {
            $role = 'guest';
        }

        // наши ресурсы
        $controller = $request->controller;
        $action = $request->action;

        // если контроллер не существует тогда нулл
        if (!$acl->has($controller)) {
            $controller = null;
        }

        // если не существует перенаправляем на ошибку
        if (!$acl->isAllowed($role, $controller, $action)) {
            $request->setControllerName($this->_controller['controller']);
            $request->setActionName($this->_controller['action']);
        }
    }

}
