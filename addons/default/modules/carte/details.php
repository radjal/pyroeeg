<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Carte module
 *
 * @author  Radja Lomas
 * @package PyroCMS\Core\Modules\Carte
 */
class Module_Carte extends Module
{
	public $version = '1.0.0';

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Carte',
			),
			'description' => array(
				'en' => 'Menu module.',
				'fr' => 'Module de gestion de carte.',
			),
			'frontend' => true,
			'backend' => true,
			'skip_xss' => true,
			'menu' => 'content',

			'roles' => array(
				'put_live', 'edit_live', 'delete_live'
			),

			'sections' => array(
				'posts' => array(
					'name' => 'carte:posts_title',
					'uri' => 'admin/carte',
					'shortcuts' => array(
						array(
							'name' => 'carte:create_title',
							'uri' => 'admin/carte/create',
							'class' => 'add',
						),
					),
				),
				'categories' => array(
					'name' => 'cat:list_title',
					'uri' => 'admin/carte/categories',
					'shortcuts' => array(
						array(
							'name' => 'cat:create_title',
							'uri' => 'admin/carte/categories/create',
							'class' => 'add',
						),
					),
				),
			),
		);

		if (function_exists('group_has_role'))
		{
			if(group_has_role('carte', 'admin_carte_fields'))
			{
				$info['sections']['fields'] = array(
							'name' 	=> 'global:custom_fields',
							'uri' 	=> 'admin/carte/fields',
								'shortcuts' => array(
									'create' => array(
										'name' 	=> 'streams:add_field',
										'uri' 	=> 'admin/carte/fields/create',
										'class' => 'add'
										)
									)
							);
			}
		}

		return $info;
	}

	public function 
                install()
	{
		$this->dbforge->drop_table('carte_categories');

		$this->load->driver('Streams');
		$this->streams->utilities->remove_namespace('cartes');

		// Just in case.
		$this->dbforge->drop_table('carte');

		if ($this->db->table_exists('data_streams'))
		{
			$this->db->where('stream_namespace', 'cartes')->delete('data_streams');
		}

		// Create the carte categories table.
		$this->install_tables(array(
			'carte_categories' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'slug' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true, 'key' => true),
				'title' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'unique' => true),
			),
		));

		$this->streams->streams->add_stream(
			'lang:carte:carte_title',
			'carte',
			'cartes',
			null,
			null
		);
/*
		// Add the intro field.
		// This can be later removed by an admin.
		$intro_field = array(
			'name'		=> 'lang:carte:intro_label',
			'slug'		=> 'intro',
			'namespace' => 'cartes',
			'type'		=> 'wysiwyg',
			'assign'	=> 'carte',
			'extra'		=> array('editor_type' => 'simple', 'allow_tags' => 'y'),
			'required'	=> true
		);
		$this->streams->fields->add_field($intro_field);
*/
		// Ad the rest of the carte fields the normal way.
		$carte_fields = array(
				'title' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false, 'unique' => true),
				'slug' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => false),
				'category_id' => array('type' => 'INT', 'constraint' => 11, 'key' => true),
                                'date_carte' => array('type' => 'DATE'),
				//'body' => array('type' => 'TEXT'),
				//'parsed' => array('type' => 'TEXT'),
				'keywords' => array('type' => 'VARCHAR', 'constraint' => 32, 'default' => ''),
				'author_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				'created_on' => array('type' => 'INT', 'constraint' => 11),
				'updated_on' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				'comments_enabled' => array('type' => 'ENUM', 'constraint' => array('no','1 day','1 week','2 weeks','1 month', '3 months', 'always'), 'default' => '3 months'),
				'status' => array('type' => 'ENUM', 'constraint' => array('draft', 'live'), 'default' => 'draft'),
				//'type' => array('type' => 'SET', 'constraint' => array('html', 'markdown', 'wysiwyg-advanced', 'wysiwyg-simple')),
				'preview_hash' => array('type' => 'CHAR', 'constraint' => 32, 'default' => ''),
		);
		return $this->dbforge->add_column('carte', $carte_fields);
	}

	public function uninstall()
	{
		$this->dbforge->drop_table('carte');
		$this->dbforge->drop_table('carte_categories');
		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}
}
