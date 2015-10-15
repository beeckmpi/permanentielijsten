<?php
namespace app\models;

class Werkaanvragen extends \lithium\data\Model {
		protected $_schema = array(
			'_id'	=>	array('type' => 'id'),
			'username'	=>	array('type' => 'string', 'null' => false),
			'titel'	=>	array('type' => 'string', 'null' => false),
			'Werkaanvraag'	=>	array('type' => 'text', 'null' => false),
			'updated'	=>	array('type' => 'datetime', 'null' => false),
			'created'	=>	array('type' => 'date'),
			'status'	=>	array('type' => 'text', 'null' => false),
);
}
?>