<?php
/**
*
* @package phpBB Extension - Set forum width
* @copyright (c) 2017 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\setforumwidth\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\request\request		$request
	* @param \phpbb\template\template	$template
	* @param \phpbb\user				$user
	* @param \phpbb\language\language	$language
	* @param string					 $root_path
	* @param string					 $php_ext
	*/
	public function __construct(
		\phpbb\request\request $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\language\language $language,
		$root_path,
		$php_ext
	)
	{
		$this->request 		= $request;
		$this->template 	= $template;
		$this->user 		= $user;
		$this->language		= $language;
		$this->root_path 	= $root_path;
		$this->php_ext 		= $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.ucp_prefs_personal_data'				=> 'ucp_prefs_personal_data',
			'core.ucp_prefs_personal_update_data'		=> 'ucp_prefs_personal_update_data',
			'core.page_header_after'					=> 'page_header_after',
		);
	}

	public function ucp_prefs_personal_data($event)
	{
		$this->language->add_lang('common', 'dmzx/setforumwidth');

		$event['data'] = array_merge($event['data'], array(
			'set_width'	=> $this->request->variable('set_width', (int) $this->user->data['user_set_width']),
		));

		$array = $event['error'];

		if (!function_exists('validate_data'))
		{
			include($this->root_path . 'includes/functions_user.' . $this->php_ext);
		}

		$validate_array = array(
			'set_width'		=>	array('num', true, 625, 1800),
		);

		$error = validate_data($event['data'], $validate_array);

		$event['error'] = array_merge($array, $error);
	}

	public function ucp_prefs_personal_update_data($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], array(
			'user_set_width'	=> $event['data']['set_width'],
		));
	}

	public function page_header_after($event)
	{
		$this->language->add_lang('common', 'dmzx/setforumwidth');

		$this->template->assign_vars(array(
			'SET_WIDTH'				=> $this->user->data['user_set_width'],
			'U_SET_WIDTH_PROFILE'	=> append_sid("{$this->root_path}ucp.$this->php_ext", 'i=ucp_prefs&amp;mode=personal'),
		));
	}
}
