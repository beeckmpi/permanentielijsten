<?php
namespace app\controllers;

use lithium\security\Auth;
use upload\handler\UploadHandler;
use app\models\Software;
use app\models\File;

class SoftwareController extends \lithium\action\Controller {
	protected function _init() {
        $this->_render['negotiate'] = true;
        parent::_init();
		$login = Auth::check('member');
		if(!$login){
			return $this->redirect('/login');
		}		
    }
	
	public function index() {
		$login = Auth::check('member');
		$softwares = Software::find('all', array('order' => array('created' => 'DESC')));
		$files = File::find('all');
		return compact('login', 'softwares', 'files');
	}
	
	public function add() {
		$login = Auth::check('member');
		
		if ($this->request->data){
			$software = Software::create($this->request->data);		
			if($software->save()) {
				$file = File::create();
				foreach ($this->request->data['myfile'] as $key => $value){
					$size = $this->request->data['myfile'][$key]['size'];
					if ($size >= 600000001)
					{
						$chunksize = ($size / 20);
					} else if($size <= 600000000 && $size >= 100000000) {
						$chunksize = ($size / 10);
					} else if($size <= 100000000 && $size >= 10000000) {
						$chunksize = 10000000;
					} else {
						$chunksize = 1000000;
					}
					$save = $file->save(array('file' => $value, 'software_id' => (string) $software->_id, 'chunkSize' => 10000000));
					if(!$save){
						return compact('save');
					}
				}
				       	
	        }
		}
		$software = Software::create();
        return compact('login', 'software');
	}
	
	public function download(){
		
	}
	
	public function upload() {
		$class="upload\handler\UploadHandler";
		$upload = new $class();
	}
	
	
}

?>