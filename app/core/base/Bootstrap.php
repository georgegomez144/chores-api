<?php

/**
 * Created by PhpStorm.
 * User: George Gomez
 * Date: 3/30/2017
 * Time: 9:12 AM
 */
class Bootstrap
{
    private $_file;
    private $_module;
    private $_controller;
    private $_action;
    private $_params = [];

    public function __construct()
    {
        REST::_access();
        $this->setRoutes(REST::getUrlParams()['params']);
        $this->_run();
    }

    /**
     * Run Bootstrap
     */
    private function _run()
    {
        $this->loadController();
        $this->_controller->{$this->_action.'Action'}($this->_params);
    }

    /**
     * @param $file
     */
    private function loadController()
    {
        if(file_exists($this->_file))
        {
            include $this->_file;
            $this->_controller = new $this->_controller();
        }
    }

    /**
     * @param array $routes
     */
    private function setRoutes(Array $routes)
    {
        $this->_file = ROOT.D.'app'.D.'modules'.D.$routes[0].D.'Controller'.D.ucfirst($routes[1]).'.php';
        $this->_module = $routes[0]; unset($routes[0]);
        $this->_controller = APP_NAME.'_'.ucfirst($this->_module).'_Controller_'.ucfirst($routes[1]); unset($routes[1]);
        $this->_action = $routes[2]; unset($routes[2]);
        $this->_params = REST::kvp($routes); $routes = null;
    }
}
