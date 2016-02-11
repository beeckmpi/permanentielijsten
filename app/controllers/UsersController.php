<?php

namespace app\controllers;

use lithium\security\Auth;
use app\models\Users;
use app\models\Locations;

class UsersController extends \lithium\action\Controller {
	static $actief;
	static $breadcrumb;
	 protected function _init() {
        $this->_render['negotiate'] = true;
        parent::_init();
		$login = Auth::check('member');
		self::$actief = array('start' => '', 'lijsten' => '', 'beheren' => 'active');
		self::$breadcrumb = array(array('url' => 'beheren', 'naam' => 'Beheren'), array('url' => 'users', 'naam' => 'Gebruikers'));
		if(!$login){
			return $this->redirect('/login');
		}		
    }
    public function index($show = 'recent') {
        $users = Users::all();
		$login = Auth::check('member');
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[1] = array('naam' => 'Gebruikers');
        return compact('users', 'login', 'actief', 'breadcrumb');
    }

    public function add() {
    	$login = Auth::check('member');
    	if ($login['rol']!='administrator'){
			return $this->redirect('/');
		}    	
    	unset($this->request->data['repeat_password']);
		unset($this->request->data['user_ok']);
		unset($this->request->data['password_ok']);
        $user = Users::create($this->request->data);

        if (($this->request->data) && $user->save()) {
            $users = Users::all();
        	return compact('users');
        }
		$locations = Locations::find('all', array('order' => array('district' => 'ASC')));	
		foreach ($locations as $key => $location){
			$provincie = $locations[$key]['provincie'];
			$district = $locations[$key]['district'];
			if($login['rol'] != 'administrator'){
				if ($provincie == $login['location']){										
					$locaties[$district] = $district.' ('.$locations[$key]['provincie'].')';
				}
			} else {
				$locaties[$district] =  $district.' ('.$locations[$key]['provincie'].')';
			}
		}
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Gebruiker toevoegen');
        return compact('user','login','actief', 'breadcrumb', 'locaties');
    }
	
	public function edit($username){
		$login = Auth::check('member');
		$user = Users::find('first', array('conditions' => array('username' => $username)));
		if ($login['rol']!='administrator' && ($user->username != $login->username)){
			return $this->redirect('/');
		}
		if ($this->request->data){
			$user->username = $this->request->data['username'];
			if (!empty($this->request->data['password'])){
				$user->password = $this->request->data['password'];
			}
			$user->location = $this->request->data['location'];
			$user->voornaam = $this->request->data['voornaam'];
			$user->achternaam = $this->request->data['achternaam'];
			$user->rol = $this->request->data['rol'];
			if($user->save()){
				return $this->redirect('users/view/'.$username);
			}			
		}  	
		$locations = Locations::find('all', array('order' => array('district' => 'ASC')));	
		foreach ($locations as $key => $location){
			$provincie = $locations[$key]['provincie'];
			$district = $locations[$key]['district'];
			if($login['rol'] != 'administrator'){
				if ($provincie == $login['location']){										
					$locaties[$district] = $district.' ('.$locations[$key]['provincie'].')';
				}
			} else {
				$locaties[$district] =  $district.' ('.$locations[$key]['provincie'].')';
			}
		}
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Gebruiker bewerken');
        return compact('user','login','actief', 'breadcrumb', 'locaties');
	}
	
	public function password(){
		$login = Auth::check('member');
		$user = Users::find('first', array('conditions' => array('username' => $login['username'])));
		if ($this->request->data){
			if (Auth::check('member', $this->request)) {
				if ($this->request->data['new_password'] == $this->request->data['repeat_password']){
					$user->password = $this->request->data['new_password'];
					$user->save();
				}
			}
		}
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Wachtwoord bewerken');
		return compact('user', 'login','actief', 'breadcrumb');
	}
	
	public function view($username = '') {
		if($username==''){
			return $this->redirect('/users/');
		}
		$login = Auth::check('member');
		$user = Users::find('first', array('conditions' => array('username' => $username)));		
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Gebruiker details');
        return compact('user','login','actief', 'breadcrumb');
	}
	
	public function username_check($name)
	{
		$user = Users::find('first', array('conditions' => array('username' => $name)));
		return compact('user');
	}
}

?>