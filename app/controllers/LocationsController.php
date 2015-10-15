<?php

namespace app\controllers;

use lithium\security\Auth;
use app\models\Locations;

class LocationsController extends \lithium\action\Controller {
	static $actief;
	static $breadcrumb;
	protected function _init() {
        $this->_render['negotiate'] = true;
        parent::_init();
		$login = Auth::check('member');
		self::$actief = array('start' => '', 'lijsten' => '', 'beheren' => 'active');
		self::$breadcrumb = array(array('url' => '/permanentielijsten/lijsten', 'naam' => 'Lijsten'));
		if(!$login){
			return $this->redirect('/login');
		}		
    }
	
	public function index($ajax = null, $order_by = 'locatie', $order_by_sort = 'ASC', $tagnummer = null, $type = null, $component = null, $in_dienst_name = null, $locatie = null){
		$login = Auth::check('member');
		$locations = Locations::find('all', array('order' => array('locatie' => 'ASC')));
		$this->request->data['actief'] = 'ja';
		
		return compact('login', 'location', 'locations');
	}
	
	
	public function add($ajax = null)
	{
		$login = Auth::check('member');
		$location = Locations::create($this->request->data);
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		if (($this->request->data) && $location->save()) {
		 	return compact('login', 'location', 'actief', 'breadcrumb');
		}
		
		return compact('login', 'location', 'product', 'actief', 'breadcrumb');
	}
	 
}
?>