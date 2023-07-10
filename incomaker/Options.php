<?php
/*
 * Incomaker for Woocommerce
 * Copyright (C) 2021-2022 Incomaker s.r.o.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Incomaker;

class Options implements Singletonable
{

	private $incomaker_options;

	public function __construct()
	{
		add_action('admin_menu', array($this, 'incomaker_add_plugin_page'));
		add_action('admin_init', array($this, 'incomaker_page_init'));
		add_filter('plugin_action_links_incomaker/incomaker.php', array($this, 'add_action_links'));
	}

	public function incomaker_add_plugin_page()
	{
		add_options_page(
			'Incomaker', // page_title
			'Incomaker', // menu_title
			'manage_options', // capability
			'incomaker', // menu_slug
			array($this, 'incomaker_create_admin_page') // function
		);
	}

	public function valExists($key)
	{
		return !empty($this->incomaker_options[$key]);
	}

	public function getVal($key, $default = '')
	{
		return $this->valExists($key) ? esc_attr($this->incomaker_options[$key]) : $default;
	}

	public function incomaker_create_admin_page()
	{
		$this->incomaker_options = get_option('incomaker_option'); ?>

		<div class="wrap">
			<h2>Incomaker</h2>
			<p>Marketing automation with artificial intelligence</p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields('incomaker_option_group');
				do_settings_sections('incomaker-admin');
				submit_button();
				?>
			</form>
		</div>
	<?php }

	public function add_action_links($links)
	{
		$after = array(
			'settings' => sprintf('<a href="%s">%s</a>', admin_url('options-general.php?page=incomaker'), __('Settings', 'incomaker')),
		);
		return array_merge($links, $after);
	}

	public function incomaker_page_init()
	{
		register_setting(
			'incomaker_option_group', // option_group
			'incomaker_option', // option_name
			array($this, 'incomaker_sanitize') // sanitize_callback
		);

		add_settings_section(
			'incomaker_setting_section', // id
			'Settings', // title
			array($this, 'incomaker_section_info'), // callback
			'incomaker-admin' // page
		);

		add_settings_field(
			'api_key', // id
			'API Key', // title
			array($this, 'api_key_callback'), // callback
			'incomaker-admin', // page
			'incomaker_setting_section', // section
			[
				'label_for' => 'api_key'
			]
		);

		add_settings_field(
			'account_id', // id
			'Account ID', // title
			array($this, 'account_id_callback'), // callback
			'incomaker-admin', // page
			'incomaker_setting_section' // section
		);

		add_settings_field(
			'plugin_id', // id
			'Plugin ID', // title
			array($this, 'plugin_id_callback'), // callback
			'incomaker-admin', // page
			'incomaker_setting_section' // section
		);
	}

	public function incomaker_sanitize($input)
	{
		$keyOld = $this->getVal('api_key');

		$sanitary_values = array();
		if (isset($input['api_key'])) {
			$sanitary_values['api_key'] = sanitize_text_field($input['api_key']);
		}

		$keyNew = $sanitary_values['api_key'];
		$valuesSet = $this->valExists('account_id') && $this->valExists('plugin_id');

		if (empty($keyNew)) {
			$sanitary_values['account_id'] = '';
			$sanitary_values['plugin_id'] = '';
		} else if (($keyOld != $keyNew) || !$valuesSet) {
			$incomakerApi = new IncomakerApi($keyNew);
			$info = $incomakerApi->getPluginInfo();
			$sanitary_values['account_id'] = $info->accountUuid;
			$sanitary_values['plugin_id'] = $info->pluginUuid;
		}

		return $sanitary_values;
	}

	public function incomaker_section_info()
	{

	}

	public function api_key_callback()
	{
		$value = isset($this->incomaker_options['api_key']) ? esc_attr($this->incomaker_options['api_key']) : '';

		?>
		<input class="regular-text" type="text" name="incomaker_option[api_key]" id="api_key" value="<?= $value ?>">
		<p>When proper API Key is set, data from your e-shop will be shared with Incomaker.</p>
		<p>You will get your API Key in <a target="_blank" href="https://my.incomaker.com/admin/plugin_profile.xhtml">Eshop Info</a> section of your
			account at <strong>incomaker.com</strong>.</p>
		<p>By filling your Incomaker API Key you agree to our <a href="https://www.incomaker.com/en/terms-and-conditions">Terms & Conditions</a>.</p>
		<?php
	}

	public function account_id_callback()
	{
		printf(
			'<input class="regular-text" type="text" name="incomaker_option[account_id]" id="account_id" value="%s" disabled>',
			isset($this->incomaker_options['account_id']) ? esc_attr($this->incomaker_options['account_id']) : ''
		);
	}

	public function plugin_id_callback()
	{
		$value = $this->getVal('plugin_id');
		?>
		<input class="regular-text" type="text" name="incomaker_option[plugin_id]" id="plugin_id" value="<?= $value ?>" disabled>
		<?php
	}

	private static $singleton = null;

	public static function getInstance()
	{
		if ((self::$singleton == null) && (is_admin())) {
			self::$singleton = new Options();
		}
		return self::$singleton;
	}
}
