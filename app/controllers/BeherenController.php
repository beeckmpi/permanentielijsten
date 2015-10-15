<?php
namespace app\controllers;

use lithium\security\Auth;


class beherenController extends \lithium\action\Controller {
	static $actief;
	static $breadcrumb;
	protected function _init() {
        $this->_render['negotiate'] = true;
        parent::_init();
		$login = Auth::check('member');
		self::$actief = array('start' => '', 'lijsten' => '', 'beheren' => 'active');
		self::$breadcrumb = array(array('url' => 'beheren', 'naam' => 'Beheren'));
		if(!$login){
			return $this->redirect('/login');
		}		
    }
	public function index ()
	{
		$login = Auth::check('member');
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		return compact('login','actief', 'breadcrumb');
	}
}
	