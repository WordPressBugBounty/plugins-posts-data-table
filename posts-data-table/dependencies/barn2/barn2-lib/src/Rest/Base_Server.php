<?php

namespace Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Rest;

use Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Registerable;
/**
 * Abstract REST server.
 *
 * @package   Barn2\barn2-lib
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 * @version   1.0
 * @internal
 */
abstract class Base_Server implements Registerable, Rest_Server
{
    public function register()
    {
        \add_action('rest_api_init', [$this, 'register_routes']);
    }
    public function register_routes()
    {
        if (!\current_action('rest_api_init')) {
            \_doing_it_wrong(__METHOD__, 'This function must be called on <code>rest_api_init</code>', '1.0');
        }
        foreach ($this->get_routes() as $route) {
            $route->register();
        }
    }
    public function get_endpoints()
    {
        $endpoints = [];
        foreach ($this->get_routes() as $route) {
            $endpoints[$route->get_base()] = $route->get_endpoint();
        }
        return $endpoints;
    }
    public function get_namespace()
    {
        // implemented in sub-class.
    }
    public function get_routes()
    {
        // implemented in sub-class.
    }
}
