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
 * @since 2.0.0
 */

namespace Quantum\Factory;

use Quantum\Exceptions\ModelException;
use Quantum\Loader\Loader;
use Quantum\Mvc\QtModel;
use Quantum\Di\Di;

/**
 * ModelFactory Class
 *
 * @package Quantum
 * @category Factory
 */
Class ModelFactory
{

    /**
     * Get Model
     *
     * @param string $modelClass
     * @return object
     * @throws \Exception
     */
    public function get($modelClass): QtModel
    {
        if (!class_exists($modelClass)) {
            throw new ModelException(_message(ModelException::MODEL_NOT_FOUND, $modelClass));
        }

        $model = new $modelClass(Di::get(Loader::class));

        if (!$model instanceof QtModel) {
            throw new ModelException(_message(ModelException::NOT_INSTANCE_OF_MODEL, [$modelClass, QtModel::class]));
        }

        return $model;
    }

}
