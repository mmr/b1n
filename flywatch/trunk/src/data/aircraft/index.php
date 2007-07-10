<?
/* $Id: index.php,v 1.17 2004/09/28 22:35:22 mmr Exp $ */
$page1_title = 'Aircraft';

/* Configuration Hash */
$reg_config = 
    array('ID' =>
              array('reg_data'  => 'id',
                    'db'        => 'acf_id',

                    'check'     => 'none',
                    'type'      => 'none',

                    'search'    => false,
                    'select'    => false,
                    'load'      => false,
                    'mand'      => false),
          'Delete IDs' =>
              array('reg_data'  => 'ids',
                    'db'        => 'none',

                    'check'     => 'none',
                    'type'      => 'none',

                    'search'    => false,
                    'select'    => false,
                    'load'      => false,
                    'mand'      => false),
          'Model' =>
              array('reg_data'  => 'acf_model',
                    'db'        => 'acf_model',

                    'check'     => 'none',
                    'type'      => 'text',
                    'extra'     => array('size'     => b1n_DEFAULT_SIZE,
                                         'maxlen'   => b1n_DEFAULT_MAXLEN),

                    'search'    => true,
                    'select'    => true,
                    'load'      => true,
                    'mand'      => true),
          'Desc' =>
              array('reg_data'  => 'acf_desc',
                    'db'        => 'acf_desc',

                    'check'     => 'none',
                    'type'      => 'textarea',
                    'extra'     => array('rows'     => b1n_DEFAULT_ROWS,
                                         'cols'     => b1n_DEFAULT_COLS,
                                         'wrap'     => b1n_DEFAULT_WRAP),

                    'search'    => true,
                    'select'    => true,
                    'load'      => true,
                    'mand'      => false),
          'Registry' =>
              array('reg_data'  => 'acf_registry',
                    'db'        => 'acf_registry',

                    'check'     => 'unique',
                    'type'      => 'text',
                    'extra'     => array('table'    => $page1,
                                         'size'     => b1n_DEFAULT_SIZE,
                                         'maxlen'   => b1n_DEFAULT_MAXLEN),

                    'search'    => true,
                    'select'    => true,
                    'load'      => true,
                    'mand'      => true),
          'HomeBase' =>
              array('reg_data'  => 'apt_id',
                    'db'        => 'apt_id',

                    'check'     => 'fk',
                    'type'      => 'select',
                    'extra'     => array('seltype'  => 'fk',
                                         'table'    => 'airport',
                                         'text'     => 'apt_name',
                                         'value'    => 'apt_id',
                                         'name'     => 'apt_id'),

                    'search'    => true,
                    'select'    => false,
                    'load'      => true,
                    'mand'      => true),
          'Operator' =>
              array('reg_data'  => 'opr_id',
                    'db'        => 'opr_id',

                    'check'     => 'fk',
                    'type'      => 'select',
                    'extra'     => array('seltype'  => 'fk',
                                         'table'    => 'operator',
                                         'text'     => 'opr_name',
                                         'value'    => 'opr_id',
                                         'name'     => 'opr_id'),

                    'search'    => true,
                    'select'    => true,
                    'load'      => true,
                    'mand'      => true),
          'OneLink Country Code' =>
              array('reg_data'  => 'acf_satcom_country_code',
                    'db'        => 'acf_satcom_country_code',

                    'check'     => 'numeric',
                    'type'      => 'text',
                    'size'      => 2,
                    'extra'     => array('size'     => 2,
                                         'maxlen'   => 2),

                    'search'    => true,
                    'select'    => false,
                    'load'      => true,
                    'mand'      => false),
          'OneLink City Code' =>
              array('reg_data'  => 'acf_satcom_city_code',
                    'db'        => 'acf_satcom_city_code',

                    'check'     => 'numeric',
                    'type'      => 'text',
                    'extra'     => array('size'     => 4,
                                         'maxlen'   => 4),

                    'search'    => true,
                    'select'    => false,
                    'load'      => true,
                    'mand'      => false),
          'OneLink' =>
              array('reg_data'  => 'acf_satcom',
                    'db'        => 'acf_satcom',

                    'check'     => 'numeric',
                    'type'      => 'text',
                    "extra"     => array("size"     => b1n_PHONE_DEFAULT_SIZE,
                                         "maxlen"   => b1n_PHONE_DEFAULT_MAXLEN),

                    'search'    => true,
                    'select'    => false,
                    'load'      => true,
                    'mand'      => false),
          'Max T/W' =>
              array('reg_data'  => 'acf_mtow',
                    'db'        => 'acf_mtow',

                    'check'     => 'numeric',
                    'type'      => 'text',
                    'extra'     => array('size'     => 10,
                                         'maxlen'   => 10),

                    'search'    => true,
                    'select'    => true,
                    'load'      => true,
                    'mand'      => false),
          'Noise Certification' =>
              array('reg_data'  => 'acf_noise_cert',
                    'db'        => 'acf_noise_cert',

                    'check'     => 'radio',
                    'type'      => 'select',
                    'extra'     => array('seltype'  => 'defined',
                                         'options'  => array('Stage II'   => 2,
                                                             'Stage III'  => 3,
                                                             'Stage IV'   => 4,
                                                             'Stage V'    => 5)),

                    'search'    => false,
                    'select'    => false,
                    'load'      => true,
                    'mand'      => false),
          'Vendor' =>
              array('reg_data'  => 'acf_vendor',
                    'db'        => 'acf_vendor',

                    'check'     => 'none',
                    'type'      => 'text',
                    'extra'     => array('size'     => b1n_DEFAULT_SIZE,
                                         'maxlen'   => b1n_DEFAULT_MAXLEN),

                    'search'    => true,
                    'select'    => true,
                    'load'      => true,
                    'mand'      => false),
          'Default' =>
              array('reg_data'  => 'acf_default',
                    'db'        => 'acf_default',

                    'check'     => 'boolean',
                    'type'      => 'radio',
                    'extra'     => array('options' => array('Yes'  => 1,
                                                            'No'   => 0)),

                    'search'    => false,
                    'select'    => true,
                    'load'      => true,
                    'mand'      => false));

/* getVars from $_REQUEST and put them in $reg_data hash */
$reg_data = b1n_regExtract($reg_config);

require(b1n_INCPATH . '/reg.inc.php');
?>
