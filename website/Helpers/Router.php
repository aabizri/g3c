<?php

namespace Helpers;

class Router
{

    private const depth = 1;

    /**
     * @param string $category
     * @param string $action
     * @return bool false if it doesn't exist
     */
    public static function route(string $category, string $action, array $get, array $post): bool {
        // Get the fully qualified name of the class
        $classname = "\\Controllers\\".$category;

        // Check that the class exists
        // doc: https://secure.php.net/manual/fr/function.class-exists.php
        if (!class_exists($classname)) {
            return false;
        }

        // Get method name
        $methodname = $action;

        // Check that the method exists
        // doc: https://secure.php.net/manual/fr/function.method-exists.php
        if (!method_exists($classname, $methodname)) {
            return false;
        }

        // Set the callback
        $callback = [
            $classname,
            $methodname,
        ];

        // Set the params
        $params = [
            $get,
            $post,
        ];

        // Call that
        // doc: https://secure.php.net/manual/fr/function.call-user-func-array.php
        call_user_func_array($callback, $params);
        return true;
    }
}
