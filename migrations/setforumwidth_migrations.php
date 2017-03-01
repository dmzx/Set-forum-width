<?php
/**
*
* @package phpBB Extension - Set forum width
* @copyright (c) 2017 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\setforumwidth\migrations;

class setforumwidth_migrations extends \phpbb\db\migration\migration
{
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_set_width'	=> array('USINT', 1152),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'	=> array(
					'user_set_width',
				),
			),
		);
	}
}
