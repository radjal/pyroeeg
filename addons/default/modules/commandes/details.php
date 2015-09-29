<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Commandes module
 *
 * @author  Radja Lomas
 * @package custom\Modules\Commandes
 */
class Module_Commandes extends Module
{

	public $version = '1.1.4';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Orders',
				'fr' => 'Commandes',
			),
			'description' => array(
				'en' => 'Users can send orders for all the modules.',
				'fr' => 'Les utilisateurs et peuvent passer des commandes pour tout les modules.',
			),
			'frontend' => false,
			'backend' => true,
			'menu' => 'content',
                    
                        'sections' => array(
                                'orders' => array(
					'name' => 'commandes:commandes_title',
					'uri' => 'admin/commandes',
				),
				'reductions' => array(
					'name' => 'commandes:reduction_codes_title',
					'uri' => 'admin/commandes/reductions',
					'shortcuts' => array(
						array(
							'name' => 'commandes:reduction_create_title',
							'uri' => 'admin/commandes/reductions/create',
							'class' => 'add',
						),
					),
				),
			),
		);
	}

	public function install()
	{
		$this->dbforge->drop_table('commandes');
		$this->dbforge->drop_table('commandes_promo');
		$this->dbforge->drop_table('commande_blacklists');

		$tables = array(
			'commandes' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'is_active' => array('type' => 'INT', 'constraint' => 1, 'default' => 0),
				'user_id' => array('type' => 'INT', 'constraint' => 11, 'default' => 0),
				'user_name' => array('type' => 'VARCHAR', 'constraint' => 40, 'default' => ''),
				'user_email' => array('type' => 'VARCHAR', 'constraint' => 80, 'default' => ''), // @todo Shouldn't this be 255?
				'user_website' => array('type' => 'VARCHAR', 'constraint' => 255),
				'commande' => array('type' => 'TEXT'),
				'heure_livraison' => array('type' => 'VARCHAR', 'constraint' => 40, 'default' => ''),
				'info_acces' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
				'info_payment' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
				'adresse_livraison' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
				'company' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
				'telephone' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
				'reduction_code' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
				'reduction_price' => array('type' => 'decimal ', 'constraint' => '5,2', 'default' =>  '0.00' ),
				'total' => array('type' => 'decimal ', 'constraint' => '5,2', 'default' =>  '0.00' ),
				'total_taxfree' => array('type' => 'decimal ', 'constraint' => '5,2', 'default' =>  '0.00' ),
				'message' => array('type' => 'TEXT'),
				'parsed' => array('type' => 'TEXT'),
				'module' => array('type' => 'VARCHAR', 'constraint' => 40),
				'entry_id' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => 0),
				'entry_title' => array('type' => 'char', 'constraint' => 255, 'null' => false),
				'entry_key' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
				'entry_plural' => array('type' => 'varchar', 'constraint' => 100, 'null' => false),
				'uri' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
				'cp_uri' => array('type' => 'varchar', 'constraint' => 255, 'null' => true),
				'created_on' => array('type' => 'INT', 'constraint' => 11, 'default' => '0'),
				'ip_address' => array('type' => 'VARCHAR', 'constraint' => 45, 'default' => ''),
			),
			'commandes_promo' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'code' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
                                'date_carte' => array('type' => 'DATE'),
                                'price' => array('type' => 'decimal', 'extra' => array('decimal_places' => '3', 'min_value' => '0.00')),
				'percent' => array('type' => 'INT', 'constraint' => 2, 'default' => 0),
				'rule' => array('type' => 'VARCHAR', 'constraint' => 255),
			),
			'commande_blacklists' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'website' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
				'email' => array('type' => 'VARCHAR', 'constraint' => 150, 'default' => ''),
			),
		);

		if ( ! $this->install_tables($tables))
		{
			return false;
		}

		// Install the setting
		$settings = array(

			array(
				'slug' => 'enable_commandes',
				'title' => 'Autoriser les commandes',
				'description' => 'Activer la fonctionnalité de commande.',
				'type' => 'radio',
				'default' => true,
				'value' => true,
				'options' => '1=Enabled|0=Disabled',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'commandes',
				'order' => 968,
			),
			array(
				'slug' => 'moderate_commandes',
				'title' => 'Valider les commandes',
				'description' => 'Obliger la validation manuelle des commandes.',
				'type' => 'radio',
				'default' => true,
				'value' => true,
				'options' => '1=Enabled|0=Disabled',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'commandes',
				'order' => 967,
			),
			array(
				'slug' => 'commande_order',
				'title' => 'Ordre des commandes',
				'description' => 'L\'ordre d\'affichage des commandes.',
				'type' => 'select',
				'default' => 'ASC',
				'value' => 'ASC',
				'options' => 'ASC=Oldest First|DESC=Newest First',
				'is_required' => 1,
				'is_gui' => 1,
				'module' => 'commandes',
				'order' => 966,
			),

		);

		foreach ($settings as $setting)
		{
			if ( ! $this->db->insert('settings', $setting))
			{
				return false;
			}
		}

		return true;
	}

	public function uninstall()
	{
		$this->dbforge->drop_table('commandes');
		$this->dbforge->drop_table('commandes_promo');
		$this->dbforge->drop_table('commande_blacklists');
		
		$this->db->delete('settings', array('module' => 'commandes'));
		
		return true;
	}

	public function upgrade($old_version)
	{
            $tables = array(
                'commandes_promo' => array(
                    'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
                    'code' => array('type' => 'VARCHAR', 'constraint' => 255, 'default' => ''),
                    'date_carte' => array('type' => 'DATE'),
                    'price' => array('type' => 'decimal', 'extra' => array('decimal_places' => '3', 'min_value' => '0.00')),
                    'percent' => array('type' => 'INT', 'constraint' => 2, 'default' => 0),
                    'rule' => array('type' => 'VARCHAR', 'constraint' => 255),               ),
            );
            $this->install_tables($tables) ;
            
            $fields = array(
                    'total_taxfree' => array('type' => 'decimal ', 'constraint' => '5,2', 'default' =>  '0.00' ),
                    'reduction_price' => array('type' => 'decimal ', 'constraint' => '5,2', 'default' =>  '0.00' ),
            );
            $this->dbforge->add_column('commandes', $fields) ;       
            
            $fields_mod = array(
                    'total' => array('type' => 'decimal ', 'constraint' => '5,2', 'default' =>  '0.00' ),
            );
            $this->dbforge->modify_column('commandes', $fields_mod) ;

//            $this->dbforge->drop_table('commande_blacklists');		
            return true;
	}

}
