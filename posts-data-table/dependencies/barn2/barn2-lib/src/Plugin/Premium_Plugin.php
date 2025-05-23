<?php

namespace Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Plugin;

use Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Plugin\Admin\Plugin_Updater;
use Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Plugin\License\Admin\License_Key_Setting;
use Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Plugin\License\Admin\License_Notices;
use Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Plugin\License\EDD_Licensing;
use Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Plugin\License\License_Checker;
use Barn2\Plugin\Posts_Table_Search_Sort\Dependencies\Lib\Plugin\License\Plugin_License;
/**
 * Extends Simple_Plugin to add additional functions for premium plugins (i.e. with a license key).
 *
 * @package   Barn2\barn2-lib
 * @author    Barn2 Plugins <support@barn2.com>
 * @license   GPL-3.0
 * @copyright Barn2 Media Ltd
 * @version   2.0
 * @internal
 */
class Premium_Plugin extends Simple_Plugin implements Licensed_Plugin
{
    /**
     * Constructs a new premium plugin with the supplied plugin data.
     *
     * @param array  $data                 {
     * @type int     $id                   (required) The plugin ID. This should be the EDD Download ID.
     * @type string  $name                 (required) The plugin name.
     * @type string  $version              (required) The plugin version, e.g. '1.2.3'.
     * @type string  $file                 (required) The main plugin __FILE__.
     * @type boolean $is_woocommerce       true if this is a WooCommerce plugin.
     * @type boolean $is_edd               true if this is an EDD plugin.
     * @type string  $documentation_path   The path to the plugin documentation, relative to https://barn2.com
     * @type string  $settings_path        The plugin settings path, relative to /wp-admin
     * @type string  $license_setting_path The license setting path, relative to /wp-admin. Only specify if different to $settings_path.
     * @type string  $legacy_db_prefix     Legacy DB prefix. Only for older plugins which migrated from the previous license system.
     *                                     }
     */
    public function __construct(array $data)
    {
        parent::__construct(\array_merge(['license_setting_path' => '', 'legacy_db_prefix' => ''], $data));
        $this->data['license_setting_path'] = \ltrim($this->data['license_setting_path'], '/');
        $this->add_service('license', new Plugin_License($this->get_id(), EDD_Licensing::instance(), $this->get_legacy_db_prefix()));
        $this->add_service('plugin_updater', new Plugin_Updater($this, EDD_Licensing::instance()));
        $this->add_service('license_checker', new License_Checker($this->get_file(), $this->get_license()));
        $this->add_service('license_setting', new License_Key_Setting($this->get_license(), $this->is_woocommerce(), $this->is_edd()));
        $this->add_service('license_notices', new License_Notices($this));
    }
    public function maybe_load_plugin()
    {
        if ($this->requirements()->check()) {
            \add_action('after_setup_theme', [$this, 'start_standard_services']);
            if ($this->get_license()->is_valid()) {
                \add_action('after_setup_theme', [$this, 'start_premium_services']);
            }
        }
    }
    public function get_license()
    {
        return $this->get_service('license');
    }
    public function get_license_setting()
    {
        return $this->get_service('license_setting');
    }
    public function has_valid_license()
    {
        return $this->get_license()->is_valid();
    }
    public function get_license_page_url()
    {
        // Default to plugin settings URL if there's no license setting path.
        return !empty($this->data['license_setting_path']) ? \admin_url($this->data['license_setting_path']) : parent::get_settings_page_url();
    }
    public function get_legacy_db_prefix()
    {
        return $this->data['legacy_db_prefix'];
    }
}
