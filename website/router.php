<?php
class Router
{
    // routes stores all routes in an array
    private $routes = array();
    
    // register registers a new route with a function to be called in that case
    public function register(string $pattern, callable $callback)
    {
        $this->routes[$pattern] = $callback;
    }
    
    // dispatch executes the route given an URI
    public function dispatch(string $uri)
    {
        // check all routes
        foreach ($this->routes as $pattern => $callback) {
            // check for regexp match
            if (preg_match($pattern, $uri, $params) === 1) {
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }
    }
}
