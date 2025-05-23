<?php

namespace Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib;

/**
 * An object which can load PHP templates.
 *
 * @package   Barn2\barn2-lib
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 * @version   1.1
 * @internal
 */
interface Template_Loader
{
    /**
     * Return the HTML for the specified template.
     *
     * @param string $template_name The template name (e.g. 'path/to/template.php')
     * @param array $args           The template args.
     * @return string The template HTML.
     */
    public function get_template($template_name, array $args = []);
    /**
     * Output the HTML for the specified template.
     *
     * @param string $template_name The template name (e.g. 'path/to/template.php')
     * @param array $args           The template args.
     */
    public function load_template($template_name, array $args = []);
    /**
     * Output the HTML for the specified template, but only once. If this function is called multiple times with the
     * same template name, the template is only rendered once on the page.
     *
     * @param string $template_name The template name (e.g. 'path/to/template.php')
     * @param array $args           The template args.
     */
    public function load_template_once($template_name, array $args = []);
}
