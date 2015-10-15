<?php

use app\models\Users;
use lithium\security\Password;

Users::applyFilter('save', function($self, $params, $chain) {
    if ($params['data']) {
        $params['entity']->set($params['data']);
        $params['data'] = array();
    } 
    $record = $params['entity'];
    //password is provided, we need to hash it
	if(!empty($record->password)){
		$record->password = lithium\util\String::hash($record->password);
	}    
    return $chain->next($self, $params, $chain);
});

?>