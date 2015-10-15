<?php
namespace app\models;

class Lijsten extends \lithium\data\Model {
	protected $_schema = array(
			'_id'	=>	array('type' => 'id'),
			'district'	=>	array('type' => 'string', 'null' => false),
			'type'	=>	array('type' => 'string', 'null' => false),
			'subtype'	=>	array('type' => 'text', 'null' => false),
			'Startdatum'	=>	array('type' => 'date'),
			'Einddatum'	=>	array('type' => 'date')
	);
}	

?>