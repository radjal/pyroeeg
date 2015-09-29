<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Commandes (Orders) model
 * 
 * @author		Radja Lomas
 * @package		custom\Commandes\Models
 */
class Commande_m extends MY_Model
{
        /**
         * Get a order based on the ID
             * 
             * @param int $id The ID of the order
             * @return array
         */
  	public function get($id)
  	{
            $return = $this->db->select('c.*')
            ->select('IF(c.user_id > 0, m.display_name, c.user_name) as user_name', false)
            ->select('IF(c.user_id > 0, u.email, c.user_email) as user_email', false)
            ->select('u.username, m.first_name, m.last_name')
            ->from('commandes c')
            ->join('users u', 'c.user_id = u.id', 'left')
            ->join('profiles m', 'm.user_id = u.id', 'left')

                    // If there is a order user id, make sure the user still exists
                    ->where('IF(c.user_id > 0, c.user_id = u.id, 1)')
            ->where('c.id', $id)
            ->get()
            ->row() ;
            return $return;
  	}
  	
	/**
	 * Get recent commandes
	 *
	 * 
	 * @param int $limit The amount of commandes to get
	 * @param int $is_active set default to only return active commandes
	 * @return array
	 */
  	public function get_recent($limit = 10, $is_active = 1)
  	{
            $this->_get_all_setup();
		
            $this->db
                    ->where('c.is_active', $is_active)
                    ->order_by('c.created_on', 'desc');

            if ($limit > 0)
            {
                    $this->db->limit($limit);
            }

            return $this->get_all();
  	}
  	
	/**
	 * Get something based on a module item
	 *
	 * @param string $module The name of the module
	 * @param int $entry_key The singular key of the entry (E.g: blog:post or pages:page)
	 * @param int $entry_id The ID of the entry
	 * @param bool $is_active Is the order active?
	 * @return array
	 */
  	public function get_by_entry($module, $entry_key, $entry_id, $is_active = true)
  	{
		$this->_get_all_setup();
		
    	$this->db
    		->where('c.module', $module)
    		->where('c.entry_id', $entry_id)
    		->where('c.entry_key', $entry_key)
    		->where('c.is_active', $is_active)
    		->order_by('c.created_on', Settings::get('commande_order'));
    	
	    return $this->get_all();
  	}
	
	/**
	 * Insert a new order
	 *
	 * @param array $input The data to insert
	 * @return bool
	 */
	public function insert($input, $skip_validation = false)
	{	
		return parent::insert(array(
			'user_id'			=> isset($input['user_id']) 		? 	$input['user_id']   :  0,
			'user_name'	   		=> isset($input['user_name'])		&& !isset($input['user_id'])	? 	ucwords(strtolower(strip_tags($input['user_name']))) : '',
			'user_email'                    => isset($input['user_email'])		&& !isset($input['user_id']) 	? 	strtolower($input['user_email']) 					: '',
			'user_website'                  => isset($input['user_website']) 	? 	prep_url(strip_tags($input['user_website'])) 		: '',
			'is_active'			=> ! empty($input['is_active']),
			'telephone'			=> isset($input['telephone']) 		? 	$input['telephone']   :  '',
			'heure_livraison'               => isset($input['heure_livraison'])     ? 	$input['heure_livraison']   :  '',
			'info_acces'                    => isset($input['info_acces']) 		? 	$input['info_acces']   :  '',
                        'info_payment'                  => isset($input['info_payment'])        ? 	$input['info_payment']   :  '',
			'adresse_livraison'             => isset($input['adresse_livraison']) 	? 	$input['adresse_livraison']   :  '',
			'company'			=> isset($input['company']) 		? 	$input['company']   :  '',
			'commande'			=> isset($input['commande']) 		? 	$input['commande']   :  '',
			'total'                         =>  ! empty($input['total']) 		? 	$input['total']   :  0,
			'message'			=>  isset($input['message']) 		? htmlspecialchars($input['message'], null, false) : '',
			'parsed'			=> parse_markdown(htmlspecialchars($input['message'], null, false)),
			'module'			=> $input['module'],
			'entry_id'			=> $input['entry_id'],
			'entry_title'                   => $input['entry_title'],
			'entry_key'			=> $input['entry_key'],
			'entry_plural'                  => $input['entry_plural'],
			'uri'				=> ! empty($input['uri']) ? $input['uri'] : null,
			'cp_uri'			=> ! empty($input['cp_uri']) ? $input['cp_uri'] : null,
			'created_on'                    => now(),
			'ip_address'                    => $this->input->ip_address(),
		));
	}
	
	/**
	 * Update an existing order
	 *
	 * @param int $id The ID of the order to update
	 * @param array $input The array containing the data to update
	 * @return void
	 */
	public function update($id, $input, $skip_validation = false)
	{
            //die(print_r($input));
		return parent::update($id, array(
	//		'user_name'			=> isset($input['user_name']) 		? 	ucwords(strtolower(strip_tags($input['user_name'])))    : '',
	//		'user_email'                    => isset($input['user_email']) 		? 	strtolower($input['user_email'])                        : '',
	//		'user_website'                  => isset($input['user_website']) 	? 	prep_url(strip_tags($input['user_website']))            : '',
			'telephone'			=> isset($input['telephone']) 		? 	$input['telephone']                                     :  '',
			'heure_livraison'       	=> isset($input['heure_livraison'])     ? 	$input['heure_livraison']                               :  '',
			'info_acces'            	=> isset($input['info_acces']) 		? 	$input['info_acces']                                    :  '',
			'info_payment'                  => isset($input['info_payment'])        ? 	$input['info_payment']                                  :  '',
			'adresse_livraison'             => isset($input['adresse_livraison']) 	? 	$input['adresse_livraison']                             :  '',
			'company'			=> isset($input['company']) 		? 	$input['company']                                       :  '',
			'total'                         => isset($input['total']) 		? 	$input['total']                                         :  0,
			'message'			=> isset($input['message']) 		?       htmlspecialchars($input['message'], null, false) : '',
			'commande'			=> isset($input['commande']) 		?       $input['commande'] : '',
			'parsed'			=> parse_markdown(htmlspecialchars($input['message'], null, false)),
		));
	}
	
	/**
	 * Approve an order
	 *
	 * @param int $id The ID of the order to approve
	 * @return mixed
	 */
	public function approve($id)
	{
		return parent::update($id, array('is_active' => true));
	}
	
	/**
	 * Unapprove an order
	 *
	 * @param int $id The ID of the order to unapprove
	 * @return mixed
	 */
	public function unapprove($id)
	{
		return parent::update($id, array('is_active' => false));
	}

	public function get_slugs()
	{
		$this->db
			->select('commandes.module, modules.name')
			->distinct()
			->join('modules', 'commandes.module = modules.slug', 'left');

		$slugs = parent::get_all();

		$options = array();
		
		if ( ! empty($slugs))
		{
			foreach ($slugs as $slug)
			{
				if ( ! $slug->name and ($pos = strpos($slug->module, '-')) !== false)
				{
					$slug->ori_module	= $slug->module;
					$slug->module		= substr($slug->module, 0, $pos);
				}

				if ( ! $slug->name and $module = $this->module_m->get_by('slug', plural($slug->module)))
				{
					$slug->name = $module->name;
				}

				//get the module name
				if ($slug->name and $module_names = unserialize($slug->name))
				{
					if (array_key_exists(CURRENT_LANGUAGE, $module_names))
					{
						$slug->name = $module_names[CURRENT_LANGUAGE];
					}
					else
					{
						$slug->name = $module_names['en'];
					}

					if (isset($slug->ori_module))
					{
						$options[$slug->ori_module] = $slug->name . " ($slug->ori_module)";
					}
					else
					{
						$options[$slug->module] = $slug->name;
					}
				}
				else
				{
					if (isset($slug->ori_module))
					{
						$options[$slug->ori_module] = $slug->ori_module;
					}
					else
					{
						$options[$slug->module] = $slug->module;
					}
				}
			}
		}

		asort($options);

		return $options;
	}

	/**
	 * Get something based on a module item
	 *
	 * @param string $module The name of the module
	 * @param int $entry_key The singular key of the entry (E.g: blog:post or pages:page)
	 * @param int $entry_id The ID of the entry
	 * @return bool
	 */
  	public function delete_by_entry($module, $entry_key, $entry_id)
	{
    	return $this->db
    		->where('module', $module)
    		->where('entry_id', $entry_id)
    		->where('entry_key', $entry_key)
    		->delete('commandes');
 	}
	
	/**
	 * Setting up the query for the get* functions
	 */
	private function _get_all_setup()
	{
            $this->_table = null;
            $this->db
                    ->select('c.*')
                            ->from('commandes c')
                    ->select('IF(c.user_id > 0, m.display_name, c.user_name) as user_name', false)
                    ->select('IF(c.user_id > 0, u.email, c.user_email) as user_email', false)
                    ->select('u.username, m.display_name')
                    ->join('users u', 'c.user_id = u.id', 'left')
                    ->join('profiles m', 'm.user_id = u.id', 'left');
	}
        

        /**
	 * Outputs HTML from order array
	 *
	 * @param array $product_detail_arr The order data.
	 * @return string 
         * 
         */
        public function order_html($product_detail_arr)
        { 
            $html = '<div class="order-details">' ;

            foreach ($product_detail_arr as $prod_v) {
                if(!empty($prod_v['order']['quantity'])) 
                {
                    
                    $html .= '<div class="order-product-info"><span class="order-qty">';
                    $html .= $prod_v['order']['quantity'] ;
                    $html .= '</span> X <span class="order-title">';
                    $html .= $prod_v['order']['title']  . "";
                    $html .= '</span></div> <div class="order-lines-info"><span class="order-ttc">';
                    $html .= round($prod_v['order']['price'], 2)  . "€ TTC";
                    $html .= '</span> <span class="order-ht">';                 
                    $html .= round($prod_v['order']['taxfree_price'], 2)  . "€ HT";
                    $html .= '</span> <span class="order-tva">TVA ';
                    $html .= round($prod_v['order']['tax_percent'], 1)  . "%";
                    $html .= '</span></div> <div class="order-lines-total"><span class="order-line-taxfree">';                    
                    $html .= $prod_v['order']['line_taxfree_total'] . "€ ST HT";         
                    $html .= '</span> <span class="order-line-total">';                    
                    $html .= $prod_v['order']['line_total'] . "€ ST TTC";
                    $html .= '</span></div><br/>';                    
                }
            }
            $html .= '<div class="order-totals"><span class="order-total-taxfree">Total HT: ' . number_format( $prod_v['total_taxfree'] ,2 ) .'€</span> ' ;
            $html .= '<span class="order-total">Total TTC: ' . number_format( $prod_v['total'] ,2 ) .'€</span></div>' ;
            return "$html</div>" ;
         }
         
         
	/**
	 * Treats the order string
	 *
	 * @param array $order_arr The order data.
	 * @return array 
	 */
	public function order_product_details($order_str)
        {
            $total = 0 ;
            $total_taxfree = 0 ;
            foreach (explode("|", $order_str) as $order_arr_key => $order_arr_value) {
                // split again each product from quantity 
                @list( $product_code, $product_qty ) = explode(":", $order_arr_value) ;
                
                // echo "prod : $product_code x $product_qty<br>" ;
                if (!empty($product_code)) {
                   // Get the product data from order string
                   $results = $this->db->select("id, title, price, price_tax, tax_band, value")
                                       ->from('firesale_products AS p')
                                       ->where("code='$product_code'")
                                       ->join('firesale_taxes_assignments AS t', 'p.tax_band = t.tax_id')
                                       ->get();           
                    $product_detail_arr[] = $results->result_array();
                    if (isset($product_detail_arr[$order_arr_key][0]))
                    {
                        $return[$order_arr_key]['order'] = $product_detail_arr[$order_arr_key][0] ;                         
                        $return[$order_arr_key]['order']['tax_percent'] = $return[$order_arr_key]['order']['value'] ;
                        $return[$order_arr_key]['order']['taxfree_price'] = number_format($return[$order_arr_key]['order']['price'] / (1 + ($return[$order_arr_key]['order']['tax_percent'])/100), 2) ;
                        $return[$order_arr_key]['order']['tax_value'] = number_format($return[$order_arr_key]['order']['price'] - $return[$order_arr_key]['order']['taxfree_price'], 2) ;
                        $return[$order_arr_key]['order']['quantity'] = $product_qty ;
                        $return[$order_arr_key]['order']['line_taxfree_total'] = number_format( $product_qty * $return[$order_arr_key]['order']['taxfree_price'] , 2 ) ;
                        $return[$order_arr_key]['order']['line_total'] = number_format( $product_qty * $return[$order_arr_key]['order']['price'] , 2 ) ;                        
                        $total = $total + $return[$order_arr_key]['order']['line_total'] ;
                        $total_taxfree = $total_taxfree + $return[$order_arr_key]['order']['line_taxfree_total'] ;
                    }
                }
            }
            $return[$order_arr_key]['total'] = $total ;
            $return[$order_arr_key]['total_taxfree'] = $total_taxfree ;
            return $return ;
        }

         
	/**
	 * changes stock quantity
	 *
	 * @param int $order_str order string
	 * @return bool
	 */
	public function change_stock_qty($order_str)
        {   $return = true;
        
                foreach (explode("|", $order_str) as $order_arr_key => $order_arr_value) {
                    // split again each product from quantity 
                    @list( $product_code, $product_qty ) = explode(":", $order_arr_value) ;

                        // echo "prod : $product_code x $product_qty<br>" ;
                        if (!empty($product_code) and $product_qty > 0) 
                        {
                            // Get the product stock level from order string
                            $results = $this->db->select("id, code, stock")
                                                ->from('firesale_products')
                                                ->where("code='$product_code'")
                                                ->get();          
                            if ($results === false ) 
                            {   // there must have been an error
                            $return = false ;
                            }
                            // get result array
                            $product[] = $results->result_array();
                            // calculate new stock
                            $new_stock['stock'] = $product[0][0]['stock'] - $product_qty ;
                            // correct if under zero
                            if($new_stock['stock'] < 0) 
                            {
                                $new_stock['stock'] = 0 ;
                            }
                            // update stock
                            $this->db->update('firesale_products', $new_stock, "code='$product_code'");                
                        }  
                }                
            return $return ;
        }

}
