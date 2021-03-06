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
 * @since 2.2.0
 */

namespace Quantum\Exceptions;

/**
 * DiException class
 * 
 * @package Quantum
 * @category Exceptions
 */
class DiException extends \Exception
{
    const NOT_FOUND = 'Dependency `{%1}` not defined';
}
