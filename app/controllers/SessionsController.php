<?php
namespace app\controllers;

use lithium\security\Auth;

class SessionsController extends \lithium\action\Controller {
	static $actief;
	static $breadcrumb;
	protected function _init() {
        $this->_render['negotiate'] = true;
        parent::_init();
		
		self::$actief = array('start' => 'active', 'lijsten' => '', 'beheren' => '');
		self::$breadcrumb = array(array('naam' => 'Inloggen'));
			
    }
    public function add() {
    	$noauth = false;
		$login = Auth::check('member');
        if (Auth::check('member', $this->request)) {
            return $this->redirect('/');
        } 
        // Handle failed authentication attempts
         if ($this->request->data){
        //Login failed, trigger the error message
	        $noauth = true;
	    }
	    //Return noauth status
	    $actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
	    
	    return compact('noauth','login', 'actief', 'breadcrumb');
    }

    public function delete() {
        Auth::clear('member');
        return $this->redirect('/');
    }
}
?>