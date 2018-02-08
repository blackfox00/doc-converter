<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace DocConverter;

/**
 * Description of Utils
 *
 * @author JH
 */
class Utils {

    use \Nette\StaticClass;

    /**
     * Get element class name (without namespace)
     * @param mixed $class Any object
     * @return string
     */
    public static function getPureClassName($class) {
        return substr(strrchr(get_class($class), "\\"), 1);
    }

    public static function getImplementingClasses($interfaceName) {
        return array_filter(
                get_declared_classes(), function( $className ) use ( $interfaceName ) {
            return in_array($interfaceName, class_implements($className));
        }
        );
    }

}
