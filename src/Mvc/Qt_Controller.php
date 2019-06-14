<?php

/**
 * Quantum PHP Framework
 * 
 * An open source software development framework for PHP
 * 
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.0.0
 */

namespace Quantum\Mvc;

use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Debugger\Debugger;
use Quantum\Routes\RouteController;
use Quantum\Libraries\Session\Session;
use Quantum\Mvc\Qt_View;
use Quantum\Mvc\Qt_Model;
use Quantum\Hooks\HookManager;

/**
 * Base Controller Class
 * 
 * Qt_Controller class is a base class that every controller should extend
 * 
 * @package Quantum
 * @subpackage MVC
 * @category MVC
 */
class Qt_Controller extends RouteController {

    /**
     * Reference of the Qt object
     * @var object 
     */
    private static $instance;

    /**
     * The data shared between layout and view
     * @var array 
     */
    private $sharedData = array();

    public function __construct() {}

    public function __before() {}

    public function __after() {}

    /**
     * Find model file
     *
     * Find Model file from current module or from top module
     *
     * @param string $modelName
     * @return string
     * @throws \Exception When module not found
     */
    private function findModelFile($modelName) {
        $modelClass = "\\Modules\\" . get_current_module() . "\\Models\\" . $modelName;
        if (class_exists($modelClass)) {
            return $modelClass;
        } elseif (class_exists("\\Base\\models\\" . $modelName)) {
           return  "\\Base\\models\\" . $modelName;
        } else {
            HookManager::call("handleModel", $modelName);
        }
    }

    /**
     * Model Factory 
     * 
     * Deliver an object of request model
     * 
     * @param string $modelName
     * @return Qt_Model
     * @throws \Exception When model is not istance of Qt_Model
     */
    public function modelFactory($modelName) {
        $modelClass = $this->findModelFile($modelName);

        $model = new $modelClass();

        if($model instanceof Qt_Model) {
            return $model;
        } else {
            throw new \Exception(_message(ExceptionMessages::NOT_INSTANCEE_OF_MODEL, [$modelName, Qt_Model::class]));
        }
    }

    /**
     * Sets a layout
     * 
     * @param string $layout
     * @uses Qt_View::setLayout 
     * @return void
     */
    protected function setLayout($layout) {
        Qt_View::setLayout($layout);
    }

    /**
     * Share
     * 
     * Set the shared data, which becomes available in layout and view
     * 
     * @param mixed $data
     * @return void
     */
    protected function share($data) {
        foreach ($data as $key => $val) {
            $this->sharedData[$key] = $val;
        }
    }

    /**
     * Render
     * 
     * Renders the view 
     * 
     * @param string $view
     * @param array $params
     * @param bool $output
     * @return void
     */
    public function render($view, $params = array(), $output = false) {
        if (filter_var(get_config('debug'), FILTER_VALIDATE_BOOLEAN)) {
            $debugbarRenderer = Debugger::runDebuger($view);
            $this->share(['debugbarRenderer' => $debugbarRenderer]);
        }

        new Qt_View(RouteController::$currentRoute);
        Qt_View::render($view, $params, $output, $this->sharedData);
    }

    /**
     * Output
     * 
     * Outputs the view
     * 
     * @param string $view
     * @param array $params
     * @return void
     */
    public function output($view, $params = array()) {
        Qt_View::output($view, $params, $this->sharedData);
    }

    /**
     * GetInstance
     * 
     * Gets the Qt singleton
     * 
     * @return object
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
