<?php
function upicrm_get_referer() {
	$ref = '';
	if ( ! empty( $_REQUEST['_wp_http_referer'] ) )
		$ref = $_REQUEST['_wp_http_referer'];
	else if ( ! empty( $_SERVER['HTTP_REFERER'] ) )
		$ref = $_SERVER['HTTP_REFERER'];

	if ( $ref !== $_SERVER['REQUEST_URI'] )
		return $ref;
	return false;
}

function upicrm_get_user_lead_id() {
    return isset($_COOKIE['old_lead_id']) ? $_COOKIE['old_lead_id'] : 0;
}

function upicrm_set_new_user($id) {
    setcookie("old_lead_id", $id);
}

function upicrm_load($load) {
    switch ($load) {
        case 'excel':
            $path = 'resources/includes/PHPExcel.php';
        break;
    }
    require_once( UPICRM_PATH . $path ); 
}


?>
