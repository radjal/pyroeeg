<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['order/admin/categories(:any)'] = 'admin_categories$1';
$route['order/admin/streams(:any)'] = 'admin_streams$1';
$route['order/admin/fields(/:any)?'] = 'admin_fields$1';