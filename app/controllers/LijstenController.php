<?php
namespace app\controllers;

use lithium\security\Auth;
//use PHPWord;
use app\models\Lijsten;
use app\models\Feestdagen;
use app\models\Locations;
use app\models\File;
use app\models\Personeelsleden;

class lijstenController extends \lithium\action\Controller {
	static $actief;
	static $breadcrumb;
	protected function _init() {
        $this->_render['negotiate'] = true;
        parent::_init();
		$login = Auth::check('member');
		self::$actief = array('start' => '', 'overzicht' => '', 'lijsten' => 'active', 'beheren' => '');
		self::$breadcrumb = array(array('url' => '/permanentielijsten/lijsten', 'naam' => 'Lijsten'));
			
    }
	static function cmp_obj($a, $b)
    {
        $al = strtolower($a->name);
        $bl = strtolower($b->name);
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }
	
	public function index($year='0', $provincie = '0', $district = '0') {
		$login = Auth::check('member');
		
		if($year == '0'){
			$begin = date('Y-m-d H:i:s',mktime(0, 0, 0, date("m"), date("d"), date("Y")));
			$einde = date('Y-m-d H:i:s',mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));
		} else {
			$begin = date('Y-m-d H:i:s',mktime(0, 0, 0, 1, 1, $year));
			$einde = date('Y-m-d H:i:s',mktime(0, 0, 0, 1,1, $year+1));
		}		
		$filter = array('type' => 'calamiteiten', 'Einddatum' => array('$gte' => $begin, '$lt' => $einde));
		if($provincie != '0'){
			$filter['provincie'] = $provincie;
		}
		if($district != '0'){
			$filter['districtscode'] = $district;
		}
				
		$calamiteiten = Lijsten::find('all', 
			array('conditions' => $filter, 
				'fields' => array(
					'permanentie' => 0,
					'personeel' => 0
				), 
				'order' => 
					array(
						'subtype' => 'DESC', 
						'districtscode' => 'ASC', 
						'Einddatum' => 'DESC'
					)
				)
			);
		$filter['type'] = 'winterdienst';
		$winterdienst = Lijsten::find('all', 
			array(
				'conditions' =>	$filter,
				'fields' => 
					array(
						'permanentie' => 0,	
						'personeel' => 0
					), 
				'order' => 
					array(
						'subtype' => 'DESC', 
						'districtscode' => 'ASC', 
						'Einddatum' => 'DESC'
					)
				)
			);
		$locations = Locations::find('all', array('order' => array('district' => 'ASC')));
		$actief = self::$actief;		
		$locaties[0] = 'Alle Districten';
		foreach ($locations as $key => $location){
			$provincie = $locations[$key]['provincie'];
			$district = $locations[$key]['district'];
			$districtnummer = $locations[$key]['districtnummer'];
			$locaties[$districtnummer] = $district.' ('.$provincie.')';
		}
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		return compact('login','actief', 'breadcrumb', 'calamiteiten', 'winterdienst', 'locaties', 'year');
	}

    public function overzicht($date = '', $provincie = '', $district = ''){
        $login = Auth::check('member');     
        
        if ($date == ''){
            $date =  new \MongoDate(strtotime(date('Y-m-d')));
        }
        $lijsten = Lijsten::find('all', array('conditions' =>  array('Startdatum' => array('$lte' => $date), 'Einddatum' => array('$gte' => $date)), 'order' => array('provincie' => 'ASC', 'districtscode' => 'ASC')));
        $lijsten = $lijsten->data();
        
       
        $locations = Locations::find('all', array('order' => array('district' => 'ASC')));
        $actief = array('start' => '', 'overzicht' => 'active', 'lijsten' => '', 'beheren' => '');;        
        foreach ($locations as $key => $location){
            $provincie = $locations[$key]['provincie'];
            $district = $locations[$key]['district'];
            $districtnummer = $locations[$key]['districtnummer'];
            if($login['rol'] != 'administrator'){
                if ($provincie == $login['location']){                                      
                    $locaties[$districtnummer] = $district;
                }
            } else {
                $locaties[$districtnummer] = $district.' ('.$provincie.')';
            }
        }
        $breadcrumb = self::$breadcrumb;
        $breadcrumb[] = array('naam' => 'Lijsten overzicht');
        return compact('login', 'lijsten', 'personeel', 'locaties', 'actief', 'breadcrumb', 'date');
    }

	public function add() {		
		$login = Auth::check('member');		
		if(!$login){
			return $this->redirect('/login');
		}	
		if ($this->request->data){
			$checkboxes = $this->request->data['district'];
			$this->request->data["Startdatum"] = date('Y-m-d H:i:s', strtotime($this->request->data['Startdatum']));
			$this->request->data["Einddatum"] = date('Y-m-d H:i:s', strtotime($this->request->data['Einddatum']));
			$this->request->data["personeel"] = array();
			$this->request->data["permanentie"] = array();
			foreach ($checkboxes as $checkbox) {
				$locatie = Locations::find('first', array('conditions' => array('districtnummer' => $checkbox), 'sort' => array('provincie' => 'ASC')));	
				$this->request->data['district'] = $locatie->district;
				$this->request->data['districtscode'] = $locatie->districtnummer;	
				$this->request->data['provincie'] = $locatie->provincie;			
				$lijsten = Lijsten::create($this->request->data);
				if ($lijsten->save()) {
					self::add_permanentie_schema($lijsten);
				}				
			}
		}
		$lijsten = Lijsten::create();
		$locations = Locations::find('all', array('order' => array('provincie' => 'ASC', 'district' => 'ASC')));
		$actief = self::$actief;		
		foreach ($locations as $key => $location){
			$provincie = $locations[$key]['provincie'];
			$district = $locations[$key]['district'];
			$districtnummer = $locations[$key]['districtnummer'];
			if($login['rol'] != 'administrator'){
				if ($provincie == $login['location']){										
					$locaties[$districtnummer] = $district;
				}
			} else {
				$locaties[$districtnummer] = $district.' ('.$provincie.')';
			}
		}
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Lijsten toevoegen');
		return compact('login', 'lijsten', 'locaties', 'actief', 'breadcrumb');
	}

	public function lijst($type, $subtype, $districtscode, $startjaar)
	{
		$login = Auth::check('member');
		$lijsten = Lijsten::find('first', array('conditions' => array('type' => $type, 'subtype' => $subtype, 'districtscode' => $districtscode, 'Startdatum' => array('$gte' => $startjaar.'-01-01', '$lte' => $startjaar.'-12-31'))));
		$lijsten_arr = $lijsten->data();
		$personeel_lijst = array();
		foreach ($lijsten->personeel as $personeelslid){
			$personeel_lijst[] = array('naam' => $personeelslid->naam, 'GSM' => $personeelslid->GSM, 'vlimpersnummer' => $personeelslid->vlimpersnummer);
		}
		usort($personeel_lijst, function($a, $b)
		{
		     return strcmp($a["naam"], $b["naam"]);
		});
        $personeelsleden = Personeelsleden::find('all', array('conditions' => array('provincie' => $lijsten->provincie, 'districtcode' => $lijsten->districtscode), 'order' => array('naam' => 'ASC')));
		$locatie = Locations::find('first', array('conditions' => array('district' => $lijsten->district)));
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Lijsten bekijken');
		$url = $this->request->env('base');
		return compact('login', 'lijsten', 'lijsten_arr', 'personeel_lijst', 'locatie', 'actief', 'breadcrumb', 'url', 'personeelsleden');
	}

	public function view($type, $subtype, $districtscode, $startjaar)
	{
		$login = Auth::check('member');
		$lijsten = Lijsten::find('first', array('conditions' => array('type' => $type, 'subtype' => $subtype, 'districtscode' => $districtscode, 'Startdatum' => array('$gte' => $startjaar.'-01-01', '$lte' => $startjaar.'-12-31'))));
		$lijsten_arr = $lijsten->data();
		$personeel = array();
		foreach ($lijsten->personeel as $personeelslid){
			$personeel[] = array('naam' => $personeelslid->naam, 'GSM' => $personeelslid->GSM);
		}
		usort($personeel, function($a, $b)
		{
		     return strcmp($a["naam"], $b["naam"]);
		});
		$locatie = Locations::find('first', array('conditions' => array('district' => $lijsten->district)));
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Lijsten bekijken');
        if(!function_exists("array_column"))
        {
        
            function array_column($array,$column_name)
            {
        
                return array_map(function($element) use($column_name){return $element[$column_name];}, $array);
        
            }
        
        }
		return compact('login', 'lijsten', 'lijsten_arr', 'personeel', 'locatie', 'actief', 'breadcrumb');
	}

	public function add_personeelslid()
	{
		$login = Auth::check('member');	
		if(!$login){
			return $this->redirect('/login');
		}		
		$id = $this->request->id;
		$data = Lijsten::Update(array('$addToSet' => array('personeel' => array('vlimpersnummer' => $this->request->data['vlimpersnummer'], 'naam' => $this->request->data['naam'], 'GSM' => $this->request->data['GSM']))), array('_id' => $this->request->id));
        $lijst = Lijsten::find('first', array('conditions' => array('_id' => $this->request->id)));
        $personeelslid = array(
            'naam' => $this->request->data['naam'],
            'GSM' => $this->request->data['GSM'],
            'provincie' => $lijst->provincie,
            'district' => $lijst->district,
            'districtcode' => $lijst->districtscode,
            'vlimpersnummer' => $this->request->data['vlimpersnummer']
        );
        $personeelsleden[] = $personeelslid;
        if(!array_intersect($personeelsleden, $personeelslid)){
           $personeelsleden_array = Personeelsleden::create($personeelslid);
           $personeelsleden_array->save();
        }        	
		return compact('login', 'data','id');		 			
	}

	public function remove_personeelslid()
	{
		$login = Auth::check('member');	
		if(!$login){
			return $this->redirect('/login');
		}		
		$id = $this->request->id;
		$data = Lijsten::Update(array('$pull' => array('personeel' => array('naam' => $this->request->data['naam'], 'GSM' => $this->request->data['GSM']))), array('_id' => $this->request->id));
        $personeelslid = Personeelsleden::find('first', array('conditions' => array('naam' => $this->request->data['naam'], 'GSM' => $this->request->data['GSM'])));
        $personeelslid_arr = $personeelslid->data();
        $persID= $personeelslid_arr['_id'];
		return compact('login', 'data','persID');		 			
	}
	
	public function add_permanentie(){
		$login = Auth::check('member');	
		if(!$login){
			return $this->redirect('/login');
		}	
		$week = explode('_', $this->request->data['week']);
		$type = $this->request->data['type'];
		$subtype = $this->request->data['subtype'];
		if (($subtype == "leidinggevenden") || ($subtype == "provinciaal")){
			$data = Lijsten::Update(array('$addToSet' => array('permanentie.week_'.$week[1].'.'.$week[2].'.personeelslid' => array('naam' => $this->request->data['naam'], 'GSM' => $this->request->data['GSM']))), array('_id' => $this->request->id));	
		} else if ($type == "calamiteiten" && $subtype == "medewerkers") {
			$data = Lijsten::Update(array('$addToSet' => array('permanentie.week_'.$week[1].'.'.$week[2].'.medewerker' => array('naam' => $this->request->data['naam']))), array('_id' => $this->request->id));	
		} else if ($type == "winterdienst" && $subtype == "medewerkers") {
			$data = Lijsten::Update(array('$addToSet' => array('permanentie.week_'.$week[1].'.'.$week[2].'.'.$this->request->data['personeelstype'] => array('naam' => $this->request->data['naam']))), array('_id' => $this->request->id));			
		}
		return compact('login', 'data');
	}

	public function edit_permanentie(){
		$login = Auth::check('member');	
		if(!$login){
			return $this->redirect('/login');
		}	
		$lijsten = Lijsten::find('first', array('conditions' => array('_id' => $this->request->id)));
		$lijst = $lijsten->data();
		foreach ($lijst['personeel'] as $key => $value){
			if($lijst['personeel'][$key]['naam'] == $this->request->data['old_naam']){
				$lijst['personeel'][$key]['naam'] = $this->request->data['naam'];
                $lijst['personeel'][$key]['vlimpersnummer'] = $this->request->data['vlimpersnummer'];
			}
			if(array_key_exists('GSM', $lijst['personeel'][$key])){
				if($lijst['personeel'][$key]['naam'] == $this->request->data['old_naam'] && $lijst['personeel'][$key]['GSM'] == $this->request->data['old_gsmnummer']){
					$lijst['personeel'][$key]['GSM'] = $this->request->data['gsmnummer'];
				}			
			} else {
			    $lijst['personeel'][$key]['GSM'] = $this->request->data['gsmnummer'];
			}			
		}
		$personeelstypes = array('personeelslid', 'arbeider', 'medewerker', 'wegentoezichters-vroeg', 'wegentoezichters-laat', 'arbeiders-vroeg', 'arbeiders-laat');
		foreach ($lijst['permanentie'] as $week => $week_value){
			foreach ($lijst['permanentie'][$week] as $week_key => $week_key_value){
				foreach ($personeelstypes as $type){
					if(array_key_exists($type, $lijst['permanentie'][$week][$week_key])){
						foreach ($lijst['permanentie'][$week][$week_key][$type] as $personeel_key => $item){
							if (array_key_exists('gsmnummer', $this->request->data)){
								if(array_key_exists('GSM', $lijst['permanentie'][$week][$week_key][$type][$personeel_key])){
									if($lijst['permanentie'][$week][$week_key][$type][$personeel_key]['GSM'] == $this->request->data['old_gsmnummer'] && $lijst['permanentie'][$week][$week_key][$type][$personeel_key]['naam'] == $this->request->data['old_naam']){
										$lijst['permanentie'][$week][$week_key][$type][$personeel_key]['GSM'] = $this->request->data['gsmnummer'];
									}
								}
							} else if(array_key_exists('naam', $lijst['permanentie'][$week][$week_key][$type][$personeel_key])){
								if($lijst['permanentie'][$week][$week_key][$type][$personeel_key]['naam'] == $this->request->data['old_naam']){
									$lijst['permanentie'][$week][$week_key][$type][$personeel_key]['naam'] = $this->request->data['naam'];
								}
							}
							
						}						
					}	
				}								
			}			
		}
		$lijsten->save($lijst);
		return compact('lijst');
	}

	public function delete_permanentie(){
		$login = Auth::check('member');	
		if(!$login){
			return $this->redirect('/login');
		}	
		$week = explode('_', $this->request->data['week']);
		$type = $this->request->data['type'];
		$subtype = $this->request->data['subtype'];
		if (($subtype == "leidinggevenden") || ($subtype == "provinciaal")){
			if (($this->request->data['GSM']=='') || (!$this->request->data['GSM'])){
				$this->request->data['GSM'] = null;
			}
			$data = Lijsten::Update(array('$pull' => array('permanentie.week_'.$week[1].'.'.$week[2].'.personeelslid' => array('naam' => $this->request->data['naam'], 'GSM' => $this->request->data['GSM']))), array('_id' => $this->request->id));	
		} else if ($type == "calamiteiten" && $subtype == "medewerkers") {
			$data = Lijsten::Update(array('$pull' => array('permanentie.week_'.$week[1].'.'.$week[2].'.'.$this->request->data['personeelstype'] => array('naam' => $this->request->data['naam']))), array('_id' => $this->request->id));	
		} else if ($type == "winterdienst" && $subtype == "medewerkers") {
			$data = Lijsten::Update(array('$pull' => array('permanentie.week_'.$week[1].'.'.$week[2].'.'.$this->request->data['personeelstype'] => array('naam' => $this->request->data['naam']))), array('_id' => $this->request->id));			
		}
	}

	public function permanentie_week_add(){
		$login = Auth::check('member');	
		if(!$login){
			return $this->redirect('/login');
		}	
		$data = $this->request->data;
		$week_arr = explode('_', $data['week']);
		$lijsten = Lijsten::find('first', array('conditions' => array('_id' => $this->request->id)));
		$lijst = $lijsten->data();
		$week = $week_arr[2] +1;
		$startdatum_arr = explode('/', $data['begindatum']);
		$startdatum = $startdatum_arr[2].'-'.$startdatum_arr[1].'-'.$startdatum_arr[0];
		$tussendatum_arr = explode('/', $data['tussendatum']);
		$tussendatum = $tussendatum_arr[2].'-'.$tussendatum_arr[1].'-'.$tussendatum_arr[0];
		$einddatum_arr = explode('/', $data['einddatum']);
		$einddatum = $einddatum_arr[2].'-'.$einddatum_arr[1].'-'.$einddatum_arr[0];
		$lijst['permanentie'][$week_arr[0].'_'.$week_arr[1]][$week_arr[2]]['einddatum'] =  new \MongoDate(strtotime($tussendatum));
		$lijst['permanentie'][$week_arr[0].'_'.$week_arr[1]][] =  array('startdatum' => new \MongoDate(strtotime($tussendatum)), 'einddatum' => new \MongoDate(strtotime($einddatum)));
		$lijsten->save($lijst);
		$row = '<tr id="week_'.$week_arr[1].'_'.$week.'" class="highlight"><td class="week"></td><td class="van">'.$data['tussendatum'].'</td><td class="tot">'.$data['einddatum'].'</td>';
		$datum = $data['tussendatum'];
		if($lijsten->type == 'winterdienst'){
			if($lijsten->subtype == 'medewerkers'){
				$row .= '<td class="wegentoezichters-vroeg dropable"></td><td class="wegentoezichters-laat dropable"></td><td class="arbeiders-vroeg dropable"></td><td class="arbeiders-laat dropable"></td><td>';
			}else{
				$row .= '<td class="leidingevende dropable"></td><td class="leidingevende GSM"></td><td>';
			}						
		} else {
			if($lijsten->subtype == 'medewerkers'){
				$row .= '<td class="medewerker dropable"></td><td class="arbeider dropable"></td><td>';
			}else{
				$row .= '<td class="leidingevende dropable"></td><td class="leidingevende GSM"></td><td>';
			}	
		}
		$row .= '<div class="btn-group">
					  <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
					    <span class="icon-edit"></span>
					  </a>
					  <ul class="dropdown-menu pull-right">
					     <li><a href="#nieuweLijn" class="add_row">Nieuwe Lijn...</a></li>
						 <li><a href="/permanentielijsten/lijsten/permanentie_week_remove/'.$lijsten->_id.'" class="remove_row">Lijn verwijderen...</a></li>
					  </ul>
					</div>';
			$row .= '</td></tr>';
		return compact('login', 'row', 'datum');
	}

	public function permanentie_week_remove(){
		$login = Auth::check('member');	
		if(!$login){
			return $this->redirect('/login');
		}	
		$data = $this->request->data;
		$week_arr = explode('_', $data['week']);		
		$lijsten = Lijsten::find('first', array('conditions' => array('_id' => $this->request->id)));
		$lijst = $lijsten->data();
		$einddatum = $lijst['permanentie'][$week_arr[0].'_'.$week_arr[1]][$week_arr[2]]['einddatum'];
		unset($lijst['permanentie'][$week_arr[0].'_'.$week_arr[1]][$week_arr[2]]);
		$lijst['permanentie'][$week_arr[0].'_'.$week_arr[1]][($week_arr[2]-1)]['einddatum'] =  $einddatum;
		$einddatum = date('d/m/Y', $einddatum);
		$row_einddatum = 'week_'.$week_arr[1].'_'.($week_arr[2]-1);
		$lijsten->save($lijst);
		return compact('login', 'einddatum', 'pulled', 'row_einddatum');
	}

	public function add_permanentie_schema($lijsten = array()){
		$login = Auth::check('member');
		if(!$login){
			return $this->redirect('/login');
		}	
		$lijsten = Lijsten::find('all', array('conditions' => array('_id' => $lijsten->_id)));
		$data = array();
		foreach ($lijsten as $lijst)
		{
			$year = date('Y',$lijst->Startdatum->sec);
			$i = date('W', $lijst->Startdatum->sec);
			$eind_week = date('W', $lijst->Einddatum->sec);
            $huidige_week = $lijst->Startdatum->sec;
			$weken = array();    		
			while ($huidige_week < $lijst->Einddatum->sec){
				if ($i <=9) {
			    	$digit = '0';
			    } else {
			    	$digit = '';
			    }
                $huidige_week = strtotime($year."W".$digit.$i);
				$y = $i;
				$y++;								
				if ($i == date('W', mktime(0,0,0,12,28,$year))){
				    $previous_i = $i;
                    $previous_year = $year;
					$i = 0;                   
					$year++;
                    $weken['week_'.$previous_i][$previous_i] = array('startdatum' => new \MongoDate(strtotime($previous_year."W".$digit.$previous_i)), 'einddatum' => new \MongoDate(strtotime($year."W01")));
				} else {
				    $weken['week_'.$i][0] = array('startdatum' => new \MongoDate(strtotime($year."W".$digit.$i)), 'einddatum' => new \MongoDate(strtotime($year."W".$digit.$y)));
				}
				$i++;	
			}	
			$data[] = Lijsten::Update(array('$set' => array('permanentie' => $weken)), array('_id' => $lijst->_id));	
		}		
		return compact('login', 'data');	
	}
	
	public function clean_permanentie_schema(){
		$login = Auth::check('member');
		if(!$login){
			return $this->redirect('/login');
		}
        $lijsten = Lijsten::find('all');
        $lijsten_arr = $lijsten->data();
        $data = '';
        foreach($lijsten_arr as $lijst){
		    Lijsten::Update(array('$set' => array('permanentie' => $lijst['permanentie'][0][0])), array('_id' => $lijst['_id']));
            $data .= 'District: '.$lijst['district'].', type: '.$lijst['type'].',  subtype: '.$lijst['subtype']. ': OK<br />';           
        }
		return compact('login', 'data', $permanentie);
	}
	
	public function add_weken(){
		$login = Auth::check('member');
		$week53 = array('startdatum'  => new \MongoDate(strtotime("2016W09")), 'einddatum' => new \MongoDate(strtotime("2016W10")));
		$data = Lijsten::Update(array("permanentie.0.week_9" => array($week53)), array('Startdatum' => array('$gt' => new \MongoDate(strtotime("2015-01-01 00:00:00")))));
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Lijsten bekijken');
		return compact('login', 'actief', 'breadcrumb', 'data', 'week53');
	}
	
	public function add_provincie() {
		$login = Auth::check('member');
		$lijsten = Lijsten::find('all');
		$locaties = '';
		foreach($lijsten as $key => $lijst){
			$locatie = Locations::find('first', array('conditions' => array('districtnummer' => $lijsten[$key]['districtscode'])));
			Lijsten::Update(array('$set' => array('provincie' => $locatie->provincie)), array('_id' => $lijsten[$key]['_id']));	
			$locaties .= $lijsten[$key]['districtscode'].' => '.$locatie->provincie.' SET<br />';
		}
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Lijsten bekijken');
		return compact('login', 'actief', 'breadcrumb', 'locaties');
	}
	
	public function get_feestdagen($year=2016){
		$login = Auth::check('member');
		$feestdagen = Feestdagen::find('first', array('conditions' => array('Jaar' => $year)));
		$message = '';
		if(is_object($feestdagen)){
			foreach($feestdagen['data'] as $feestdag => $datum){
				$feestdagen['data'][$feestdag] =  date('Y-m-d', $datum->sec);
			}
		} else {
			$feestdagen['data']["Nieuwjaar"] = date('Y-m-d', strtotime($year.'-01-01'));
			$feestdagen['data']["Paasmaandag"] = date('Y-m-d',strtotime($year.'-04-01'));
			$feestdagen['data']["Feest_van_de_arbeid"] = date('Y-m-d',strtotime($year.'-05-01'));
			$feestdagen['data']["olhh"] = date('Y-m-d',strtotime($year.'-05-01'));
			$feestdagen['data']["Pinkstermaandag"] = date('Y-m-d',strtotime($year.'-05-15'));
			$feestdagen['data']["Feest_van_de_vlaamse_gemeenschap"] = date('Y-m-d',strtotime($year.'-07-11'));
			$feestdagen['data']["nationale_feestdag"] = date('Y-m-d',strtotime($year.'-07-21'));
			$feestdagen['data']["olvh"] = date('Y-m-d',strtotime($year.'-08-15'));
			$feestdagen['data']["Allerheiligen"] = date('Y-m-d',strtotime($year.'-11-01'));
			$feestdagen['data']["Allerzielen"] = date('Y-m-d',strtotime($year.'-11-02'));
            $feestdagen['data']["Wapenstilstand"] = date('Y-m-d',strtotime($year.'-11-11'));
            $feestdagen['data']["Koningsdag"] = date('Y-m-d',strtotime($year.'-11-15'));
			$feestdagen['data']["Kerstdag"] = date('Y-m-d',strtotime($year.'-12-25'));
			$feestdagen['data']["Kerstverlof1"] = date('Y-m-d',strtotime($year.'-12-26'));
			$feestdagen['data']["Kerstverlof2"] = date('Y-m-d',strtotime($year.'-12-27'));
			$feestdagen['data']["Kerstverlof3"] = date('Y-m-d',strtotime($year.'-12-28'));
			$feestdagen['data']["Kerstverlof4"] = date('Y-m-d',strtotime($year.'-12-29'));
			$feestdagen['data']["Kerstverlof5"] = date('Y-m-d',strtotime($year.'-12-30'));
			$feestdagen['data']["Kerstverlof6"] = date('Y-m-d',strtotime($year.'-12-31'));
			$message = 'De feestdagen van '.$year.' zijn nog niet ingevoerd.';
		}
		return compact('feestdagen', 'message');
	}
	
	public function feestdagen($year = '') {
		$login = Auth::check('member');
		$message = '';
		if($year == ''){
			$year = date('Y');
		}
		if ($this->request->data){
			$this->request->data['data']["Nieuwjaar"] = new \MongoDate( strtotime($this->request->data['Jaar'].'-01-01'));
			$this->request->data['data']["Paasmaandag"] = new \MongoDate(strtotime($this->request->data['Paasmaandag']));
			$this->request->data['data']["Feest_van_de_arbeid"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-05-01'));
			$this->request->data['data']["olhh"] = new \MongoDate(strtotime($this->request->data['olhh']));
			$this->request->data['data']["Pinkstermaandag"] = new \MongoDate(strtotime($this->request->data['Pinkstermaandag']));
			$this->request->data['data']["Feest_van_de_vlaamse_gemeenschap"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-07-11'));
			$this->request->data['data']["nationale_feestdag"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-07-21'));
			$this->request->data['data']["olvh"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-08-15'));
			$this->request->data['data']["Allerheiligen"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-11-01'));
			$this->request->data['data']["Allerzielen"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-11-02'));
            $this->request->data['data']["Wapenstilstand"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-11-11'));
            $this->request->data['data']["Koningsdag"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-11-15'));
			$this->request->data['data']["Kerstdag"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-12-25'));
			$this->request->data['data']["Kerstverlof1"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-12-26'));
			$this->request->data['data']["Kerstverlof2"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-12-27'));
			$this->request->data['data']["Kerstverlof3"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-12-28'));
			$this->request->data['data']["Kerstverlof4"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-12-29'));
			$this->request->data['data']["Kerstverlof5"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-12-30'));
			$this->request->data['data']["Kerstverlof6"] = new \MongoDate(strtotime($this->request->data['Jaar'].'-12-31'));
			$feestdagen = Feestdagen::find('first', array('conditions' => array('Jaar' => $this->request->data['Jaar'])));
			if (is_object($feestdagen)){
				$feestdagen = $this->request->data;
				if($feestdagen->save()){
					$message = 'Feestdagen van '.$this->request->data['Jaar'].' zijn bewaard.';
				}	
			} else {
				$feestdagen = Feestdagen::create($this->request->data);
				if($feestdagen->save()){
					$message = 'Feestdagen van '.$this->request->data['Jaar'].' zijn toegevoegd aan de databank.';
				}
			}					
		} else {
			$feestdagen = Feestdagen::find('first', array('conditions' => array('Jaar' => $year)));
			if(!is_object($feestdagen)){
				$feestdagen = Feestdagen::create();
                $message = htmlentities('<span class="alert alert-danger" style="margin-bottom: 15px">De feestdagen van '.$year.' zijn nog niet ingevoerd.</span>');
			}
		}
		$actief = self::$actief;
		$breadcrumb = self::$breadcrumb;
		$breadcrumb[] = array('naam' => 'Feestdagen bekijken');
		return compact('login', 'actief', 'breadcrumb', 'feestdagen', 'message');
	}

    public function urenPerMaand($lijstID, $maand, $year){
        $login = Auth::check('member');
        $lijsten = Lijsten::find('first', array('conditions' => array('_id' => $lijstID)));
        $lijsten_arr = $lijsten->data();
        $feestdagen = Feestdagen::find('first', array('conditions' => array('Jaar' => $year)));
        $actief = self::$actief;
        $breadcrumb = self::$breadcrumb;
        $locatie = Locations::find('first', array('conditions' => array('district' => $lijsten->district)));
        $breadcrumb[] = array('naam' => 'Feestdagen bekijken');
        return compact('login', 'actief', 'breadcrumb', 'lijsten', 'locatie', 'lijsten_arr', 'feestdagen', 'year', 'maand');
    }
    
    public function personeel($type, $id){
        $login = Auth::check('member');
        $actief = self::$actief;
        $breadcrumb = self::$breadcrumb;
        $breadcrumb[] = array('naam' => 'Personeel');
        if ($type==='list'){
            $data = $this->request->data;
            $personeelsleden = array();
            foreach($data['personeel'] as $key => $value){
                if($value=='on'){
                    $info = '';
                    $personeelslid = Personeelsleden::find('first', array('conditions' => array('_id' => $key)));
                    $personeelslid_arr = $personeelslid->data();
                    $data = Lijsten::Update(array('$addToSet' => array('personeel' => array('vlimpersnummer' => $personeelslid->vlimpersnummer, 'naam' => $personeelslid->naam, 'GSM' => $personeelslid->GSM))), array('_id' => $id));
                    if ($personeelslid->vlimpersnummer!= ''){
                        $info = 'Vlimpers: '.$personeelslid->vlimpersnummer;
                    }
                    ($info=='') ? $info = 'GSM: '.$personeelslid->GSM : $info .= ' - GSM: '.$personeelslid->GSM;
                    $personeelsleden[$key] = '<li class="drag well" draggable="true"><span class="glyphicon glyphicon-move" style="margin-right: 5px"></span><span class="naam">'.$personeelslid->naam.'</span><span class="glyphicon glyphicon-remove-sign"></span><span class="glyphicon glyphicon-pencil"></span><a class="glyphicon glyphicon-info-sign" data-placement="bottom" style="color: #333" data-toggle="tooltip" title="" data-original-title="'.$info.'"></a><div class="hidden GSM">'.$personeelslid->GSM.'</div><div class="hidden vlimpersnummer">'.$personeelslid->vlimpersnummer.'</div></li>';
                }
            }            
            return compact('login', 'actief', 'breadcrumb', 'personeelsleden');
        } else if ($type==='edit'){
            $personeelslid = Personeelsleden::find('first', array('conditions' => array('_id' => $id)));
            $personeelslid_arr = $personeelslid->data();
            return compact('login', 'actief', 'breadcrumb', 'personeelslid_arr');
        } else if ($type==='remove'){
            $personeelslid = Personeelsleden::remove(array('_id' => $id));
            return compact('login', 'actief', 'breadcrumb', 'personeelslid', 'id');
        }else if ($type==="save"){
            $data = $this->request->data;
            if (($login['rol'] == 'administrator') || ($login['rol'] == 'personeel')){
                $locatie = Locations::find('first', array('conditions' => array('districtnummer' => $data['districtscode'])));
            }else {
                $locatie = Locations::find('first', array('conditions' => array('district' => $login['location'])));
            }
            $data['provincie'] = $locatie->provincie;
            $data['district'] = $locatie->district;
            $data['districtcode'] = $data['districtscode'];
            unset($data['vlimpersnummer_old'], $data['personeel_old'], $data['GSM_old']);
            $personeel = Personeelsleden::update($data, array('_id' => $id));
            $personeel_data = Personeelsleden::find('first', array('conditions' => array('_id' => $id)));
            $begin = date('Y-m-d H:i:s',mktime(0, 0, 0, date("m"), date("d"), date("Y")));
            $einde = date('Y-m-d H:i:s',mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));                                     
            $filter = array('Einddatum' => array('$gte' => $begin, '$lt' => $einde), 'provincie' => $personeel_data->provincie);
            $lijsten = Lijsten::find('all', array('conditions' => $filter));
            $lijsten_arr = $lijsten->data();
            foreach($lijsten_arr as $key => $lijst){
                $change = false;
                foreach ($lijst['personeel'] as $key => $value){
                    $gsm = preg_replace('/\D+/', '', $lijst['personeel'][$key]['GSM']);
                    $gsm_old = preg_replace('/\D+/', '', $this->request->data['GSM_old']);
                    if($lijst['personeel'][$key]['naam'] === $this->request->data['personeel_old'] && $gsm === $gsm_old){
                        $lijst['personeel'][$key]['naam'] = $this->request->data['naam'];
                        $lijst['personeel'][$key]['vlimpersnummer'] = $this->request->data['vlimpersnummer'];
                        $lijst['personeel'][$key]['GSM'] = $this->request->data['GSM'];
                        $change = true;
                    }          
                }
                if ($change){
                    $personeelstypes = array('personeelslid', 'arbeider', 'medewerker', 'wegentoezichters-vroeg', 'wegentoezichters-laat', 'arbeiders-vroeg', 'arbeiders-laat');
                    foreach ($lijst['permanentie'] as $week => $week_value){
                        foreach ($lijst['permanentie'][$week] as $week_key => $week_key_value){
                            foreach ($personeelstypes as $type){
                                if(array_key_exists($type, $lijst['permanentie'][$week][$week_key])){
                                    foreach ($lijst['permanentie'][$week][$week_key][$type] as $personeel_key => $item){
                                        if (array_key_exists('GSM', $this->request->data)){
                                            if(array_key_exists('GSM', $lijst['permanentie'][$week][$week_key][$type][$personeel_key])){
                                                 $gsm = preg_replace('/\D+/', '', $lijst['personeel'][$key]['GSM']);
                                                if($gsm === $gsm_old && $lijst['permanentie'][$week][$week_key][$type][$personeel_key]['naam'] == $this->request->data['personeel_old']){
                                                    $lijst['permanentie'][$week][$week_key][$type][$personeel_key]['GSM'] = $this->request->data['GSM'];
                                                }
                                            }
                                        } else if(array_key_exists('naam', $lijst['permanentie'][$week][$week_key][$type][$personeel_key])){
                                           
                                            if($lijst['permanentie'][$week][$week_key][$type][$personeel_key]['naam'] == $this->request->data['personeel_old']){
                                                $lijst['permanentie'][$week][$week_key][$type][$personeel_key]['naam'] = $this->request->data['naam'];
                                            }
                                        }                                    
                                    }                       
                                }   
                            }                               
                        }           
                    }
                    $lijsten->save($lijst);
                }
            }
            return compact('personeel', 'id', 'personeel_data', 'lijsten_arr', 'personeel_data');
        } else {           
            return compact('login', 'actief', 'breadcrumb');
        }
    }
    
    public function personeelsleden($naam = '', $gsm = '', $district = ''){
        $login = Auth::check('member');
        if(!$login){
            return $this->redirect('/login');
        }
        $ajax = false;
        $filter = array();
        if ($naam !== ''&&$naam!='empty'){
            $filter['naam'] = array('$regex' => new \MongoRegex("/^$naam/i"));
            $ajax = true;
        }
        if($gsm !== '' && $gsm!='empty'){
            $filter['GSM'] = array('$regex' => new \MongoRegex("/^$gsm/i"));
            $ajax = true;
        }
         if($district !== '' && $district!='empty'){
             $filter['districtcode'] = $district;
            $ajax = true;
        }
        if (($login['rol'] !== 'administrator') && ($login['rol'] !== 'personeel')){
            $filter['district'] = $login['location'];
        }
        if($gsm=='empty' || $naam =='empty'){
            $ajax = true;
        }
        $personeelsleden_obj = Personeelsleden::find('all', array('conditions' => $filter, 'order' => array('naam' => 'ASC', 'district' => 'DESC')));
        $personeelsleden = $personeelsleden_obj->data();
        $locations = Locations::find('all', array('order' => array('district' => 'ASC')));  
        $locaties= array('0' =>'--selecteer district--');
        foreach ($locations as $key => $location){
            $provincie = $locations[$key]['provincie'];
            $districtnummer = $locations[$key]['districtnummer'];
            $district = $locations[$key]['district'];
            $locaties[$districtnummer] =  $district.' ('.$locations[$key]['provincie'].')';
        }
        $actief = array('personeel' => 'active');
        $breadcrumb = self::$breadcrumb;
        $breadcrumb[] = array('naam' => 'Feestdagen bekijken');   
        if ($ajax) {
            $this->_render['layout'] = 'ajax';
            $table =  $this->render(array('data' => compact('login', 'actief', 'breadcrumb', 'personeelsleden'), 'head' => false, 'template' => 'personeel_table')); 
        }                    
        return compact('login', 'actief', 'breadcrumb', 'locaties', 'personeelsleden', 'table');
    }

    public function createPersoneelslijst(){
        $login = Auth::check('member');
        if(!$login){
            return $this->redirect('/login');
        }
        $begin = date('Y-m-d H:i:s',mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $einde = date('Y-m-d H:i:s',mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));             
        $filter = array('Einddatum' => array('$gte' => $begin, '$lt' => $einde));                
        $lijsten = Lijsten::find('all', array('conditions' => $filter, 'order' => array('naam' => 'ASC')));
        $personeelsleden = array(); 
        $personeelsleden_clean = array();        
        foreach ($lijsten as $key => $lijst){
            $personeelslid_clean = array();
            foreach ($lijst['personeel'] as $key2 => $value){                
                $personeelslid = $personeelslid_clean = array(
                    'naam' => $lijst['personeel'][$key2]['naam'],
                    'GSM' => $lijst['personeel'][$key2]['GSM'],
                    'provincie' => $lijst['provincie'],
                    'district' => $lijst['district'],
                    'districtcode' => $lijst['districtscode'],
                    'vlimpersnummer' => ''
                );
                $personeelslid_clean['GSM'] = preg_replace('/\D+/', '', $lijst['personeel'][$key2]['GSM']);
                $personeelslid_clean['naam'] = strtolower($lijst['personeel'][$key2]['naam']);
                $exists = false;
                foreach ($personeelsleden_clean as $key => $value){
                    if(($personeelsleden_clean[$key]['naam']===$personeelslid_clean['naam']) && ($personeelsleden_clean[$key]['GSM']===$personeelslid_clean['GSM'])){
                        $exists = true;
                    }
                }
                if(!$exists){
                    $personeelsleden_clean[] = $personeelslid_clean;
                    $personeelsleden[] = $personeelslid;
                    $personeelsleden_array = Personeelsleden::create($personeelslid);
                    $personeelsleden_array->save();
                }
            }
        }
        $actief = self::$actief;
        $breadcrumb = self::$breadcrumb;
        $breadcrumb[] = array('naam' => 'Personeelsleden Aanmaken');
        return compact('login', 'actief', 'breadcrumb', 'personeelsleden_clean');
    }
    
    public function exportUrenPerMaand($lijstID, $maand, $year, $type){
        $lijsten = Lijsten::find('first', array('conditions' => array('_id' => $lijstID)));
        $lijsten_arr = $lijsten->data();
        $feestdagen = Feestdagen::find('first', array('conditions' => array('Jaar' => $year)));
        $last_day = date('t', strtotime($year.'-'.$maand.'-1'));
        setlocale(LC_ALL, 'nld_NLD');
        
        $personeelsleden = array();
        $vlimpersnummer = array();
        foreach ($lijsten->personeel as $key => $value){
            $personeelsleden[$value['naam']] = (int) 0;
            $vlimpersnummer[$value['naam']] = $value['vlimpersnummer'];
        }
        $maanden_lijst = array();
        $first_month = (int) date('n', $lijsten->Startdatum->sec);
        $first_year = (int) date('Y', $lijsten->Startdatum->sec);
        $date_loop = $lijsten->Startdatum->sec;
        $maanden_lijst[$first_month.'-'.$first_year] = date('F Y', strtotime('01-'.$first_month.'-'.$first_year));
        while ($date_loop<$lijsten->Einddatum->sec){
            $first_month++;
            $date_loop = strtotime('01-'.$first_month.'-'.$first_year);
            $maanden_lijst[$first_month.'-'.$first_year] = strftime('%B %Y', strtotime('01-'.$first_month.'-'.$first_year));
            if ($first_month==12){
                $first_year++;
                $first_month = 0;
            }        
        }
        $tr = array();
        
        $feestdagen_vol = array(
            "Nieuwjaar"=> "Nieuwjaar",
            "Paasmaandag"=> "Paasmaandag",
            "Feest_van_de_arbeid"=> "Feest van de arbeid",
            "olhh"=> "Hemelvaart",
            "Pinkstermaandag"=> "Pinkstermaandag",
            "Feest_van_de_vlaamse_gemeenschap"=> "Feest van de vlaamse gemeenschap",
            "nationale_feestdag"=> "Nationale feestdag",
            "olvh"=> "Onze lieve vrouw hemelvaart",
            "Allerheiligen"=> "Allerheiligen",
            "Allerzielen"=> "Allerzielen",
            "Wapenstilstand"=> "Wapenstilstand",
            "Koningsdag"=> "Koningsdag",
            "Kerstdag"=> "Kerstdag",
            "Kerstverlof1"=> "Kerstverlof",
            "Kerstverlof2"=> "Kerstverlof",
            "Kerstverlof3"=> "Kerstverlof",
            "Kerstverlof4"=> "Kerstverlof",
            "Kerstverlof5"=> "Kerstverlof",
            "Kerstverlof6"=> "Kerstverlof"
        );
        for($i=1;$i < $last_day;$i++) {
            $tr[$i] = array(
                'feestdag' => '',
                'naam' => '',
                'speciaal' => false,
                'speciaal_class' => '',
                'uren' => (int) 16,
                'datetime' => strtotime($year.'-'.$maand.'-'.$i)
            );
            
            $day = strftime('%A', $tr[$i]['datetime']);
            if ($day == 'zaterdag' || $day == 'zondag'){
                $tr[$i]['feestdag'] = $day;
                $tr[$i]['speciaal'] = true;
            }
            foreach($feestdagen['data'] as $key => $value) {
                if (date('Y-m-d', $tr[$i]['datetime']) === date('Y-m-d', $value->sec)){
                    $tr[$i]['feestdag'] = $feestdagen_vol[$key];
                    $tr[$i]['speciaal'] = true;
                }
            } 
            $week = intval(date('W', $tr[$i]['datetime']));
            if(array_key_exists('week_'.$week, $lijsten_arr['permanentie'])){
                foreach($lijsten_arr['permanentie']['week_'.$week] as $key => $value){
                    if(is_object($lijsten_arr['permanentie']['week_'.$week][$key]['startdatum'])){
                        $startdatum = $lijsten_arr['permanentie']['week_'.$week][$key]['startdatum']->sec;
                    } else {
                        $startdatum = $lijsten_arr['permanentie']['week_'.$week][$key]['startdatum'];
                    }
                    if(is_object($lijsten_arr['permanentie']['week_'.$week][$key]['einddatum'])){
                        $einddatum = $lijsten_arr['permanentie']['week_'.$week][$key]['einddatum']->sec;
                    } else {
                        $einddatum = $lijsten_arr['permanentie']['week_'.$week][$key]['einddatum'];
                    }
                    if ($tr[$i]['datetime'] >= $startdatum && $tr[$i]['datetime'] <= $einddatum){
                        if ($lijsten->subtype == "leidinggevenden" || $lijsten->subtype == "provinciaal" || $lijsten->subtype == "EM"){
                            if (array_key_exists('personeelslid', $lijsten_arr['permanentie']['week_'.$week][$key])){
                                $tr[$i]['naam'] = $lijsten_arr['permanentie']['week_'.$week][$key]['personeelslid'][0]['naam'];
                            }
                        } else if ($lijsten->type == "calamiteiten") {
                            if (array_key_exists('medewerker', $lijsten_arr['permanentie']['week_'.$week][$key])){
                                $tr[$i]['naam'] = $lijsten_arr['permanentie']['week_'.$week][$key]['medewerker'][0]['naam'];
                            }
                        }
                    }                        
                }
            }
            if ($tr[$i]['speciaal']) {
                $tr[$i]['uren'] = (int) 24;
                $tr[$i]['speciaal_class'] = 'special';
            }
            if($tr[$i]['naam']!=''){
                $personeelsleden[$tr[$i]['naam']] = $personeelsleden[$tr[$i]['naam']] + $tr[$i]['uren'];            
            } else {
                $tr[$i]['uren'] = '';
            }
        }
        
        if($type == 'totalen'){
            $filename = "D:/xampp/htdocs/files/permanentielijst_totalen_".$lijsten->district.'_'.$maand.'_'.$year.'_'.date('d_m_Y__H_i_s').'.csv';
            $fp = fopen($filename, 'w' ) or die("Can't open php://output");
            header("Content-Type:application/csv"); 
            header("Content-Disposition:attachment;filename=permanentielijst_totalen_".$lijsten->district.'_'.$maand.'_'.$year.'_'.date('d_m_Y__H_i_s').'.csv'); 
            if ($lijsten->type == "calamiteiten" && $lijsten->subtype == "medewerkers"){
                fputcsv($fp, array('Totalen Beurtrol'.$lijsten->subtype.' '.$lijsten->type.' wachtdienst - '.$lijsten->district.' ('.date('Y',$lijsten->Startdatum->sec).'-'.date('Y',$lijsten->Einddatum->sec).')'), ';'); 
            } else if ($lijsten->subtype == 'provinciaal') {
                fputcsv($fp, array('Totalen Beurtrol Provinciaal Coördinator '.str_replace('Alle districten', '', $lijsten->district).' ('.date('Y',$lijsten->Startdatum->sec).'-'.date('Y',$lijsten->Einddatum->sec).')'), ';');
            }                
            fputcsv($fp, array('De totalen van '.strftime('%B %Y', strtotime('01-'.$maand.'-'.$year))));
            fputcsv($fp, array(' '), ';');
            fputcsv($fp, array('Vlimpers', 'Namen', 'Uren', 'Premie'), ';');
            $totaal_value = 0;
            $totaal_geld = 0;
            ksort($personeelsleden);
            foreach($personeelsleden as $key => $value){
                if($value==0){
                    $className='hiddenPersoneel';
                } else {
                    $className='';
                }
                $geld = 0; 
                $totaal_value += $value;
                if ($value >= 21 && $value <=50){
                    $geld = (string) 75;
                    $totaal_geld += 75;
                } else if ($value >= 51 && $value <=100){
                    $geld = (string) 100;
                    $totaal_geld += 100;
                } else if ($value >= 101 && $value <=200){
                    $geld = (string) 125;
                    $totaal_geld += 125;
                } else if ($value >= 201){
                    $geld = (string) 140;
                    $totaal_geld += 125;
                }
               fputcsv($fp, array($vlimpersnummer[$key], $key, $value, $geld), ';');
            }                  
            fputcsv($fp, array('Totaal', '', $totaal_value, $totaal_geld), ';');
            fclose($fp) or die("Can't close php://output"); ;
            $this->redirect('http://10.36.8.89/files/permanentielijst_totalen_'.$lijsten->district.'_'.$maand.'_'.$year.'_'.date('d_m_Y__H_i_s').'.csv');
        } else if ($type=='overzicht'){
            $filename = "D:/xampp/htdocs/files/permanentielijst_overzicht_".$lijsten->district.'_'.$maand.'_'.$year.'_'.date('d_m_Y__H_i_s').'.csv';
            $fp = fopen($filename, 'w' ) or die("Can't open php://output");
            header("Content-Type:application/csv"); 
            header("Content-Disposition:attachment;filename=permanentielijst_totalen_".$lijsten->district.'_'.$maand.'_'.$year.'_'.date('d_m_Y__H_i_s').'.csv');
            if ($lijsten->type == "calamiteiten" && $lijsten->subtype == "medewerkers"){
                fputcsv($fp, array('Totalen Beurtrol'.$lijsten->subtype.' '.$lijsten->type.' wachtdienst - '.$lijsten->district.' ('.date('Y',$lijsten->Startdatum->sec).'-'.date('Y',$lijsten->Einddatum->sec).')'), ';');
            } else if ($lijsten->subtype == 'provinciaal') {
                fputcsv($fp, array('Totalen Beurtrol Provinciaal Coördinator '.str_replace('Alle districten', '', $lijsten->district).' ('.date('Y',$lijsten->Startdatum->sec).'-'.date('Y',$lijsten->Einddatum->sec).')'), ';');
            }                
            fputcsv($fp, array('Overzicht van '.strftime('%B %Y', $tr[$i]['datetime'])));
            fputcsv($fp, array(' '), ';');
            fputcsv($fp, array('Datum', 'Speciaal', 'Naam', 'Uren'), ';');
            for($i=1;$i < $last_day;$i++) { 
                fputcsv($fp, array(strftime('%A %d %B %Y', $tr[$i]['datetime']), $tr[$i]['feestdag'], $tr[$i]['naam'], $tr[$i]['uren']), ';');
            } 
            fclose($fp) or die("Can't close php://output"); ;
            $this->redirect('http://10.36.8.89/files/permanentielijst_overzicht_'.$lijsten->district.'_'.$maand.'_'.$year.'_'.date('d_m_Y__H_i_s').'.csv');
        } 
    }
	
	public function export($type, $id){
		$lijsten = Lijsten::find('first', array('conditions' => array('_id' => $id)));
		$lijsten_arr = $lijsten->data();
		if ($type=="csv") {
			$fp = fopen('D:/xampp/htdocs/files/permanentielijst_'.$lijsten->type.'_'.$lijsten->subtype.'_'.$lijsten->districtscode.'.csv', 'w' );
			if ($lijsten->subtype == "leidinggevenden" || ($lijsten->subtype == "provinciaal")){
				fputcsv($fp, array('Week', 'Van', 'Tot', 'Naam leidinggevende', 'GSM Nummer'), ';'); 
			} else if ($lijsten->type == "calamiteiten") {
				fputcsv($fp, array('Week', 'Van', 'Tot', 'Wegentoezichters', 'Arbeiders'), ';'); 			
			} else {
				fputcsv($fp, array('Week', 'Datum Van - Tot', 'Wegentoezichters', 'Lader/Technisch assistent'), ';'); 	
				fputcsv($fp, array('Week', 'Van', 'Tot', '08.00u - 20.00u', '20.00u - 08.00u', '08.00u - 20.00u', '20.00u - 08.00u'), ';'); 
			}
			$year = date('Y',$lijsten->Startdatum->sec);
			$i = date('W', $lijsten->Startdatum->sec);
			$eind_week = date('W', $lijsten->Einddatum->sec);
			if ($i == $eind_week)
			{
				$eind_week--;
			}				    		
			foreach ($lijsten_arr['permanentie'] as $test){
				if ($i <=9) {
					$digit = '0';
				} else {
					$digit = '';
				}
				$y = $i;
				$y++;
				foreach ($lijsten_arr['permanentie']['week_'.$i] as $key => $value){					
					if($key==0){
						$week = $i;
					} else {
						$week = '';
					}									
					$van = date('d/m/Y', $lijsten_arr['permanentie']['week_'.$i][$key]['startdatum']);
					$tot = date('d/m/Y',$lijsten_arr['permanentie']['week_'.$i][$key]['einddatum']);
					if ($lijsten->subtype == "leidinggevenden" || ($lijsten->subtype == "provinciaal")){									
						if (array_key_exists('personeelslid', $lijsten_arr['permanentie']['week_'.$i][$key])){
							$list_data = array('naam' => '', 'GSM' => '');
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['personeelslid'] as $number){			
								if(isset($number['naam'])){
									$list_data['naam'] .=  $number['naam'].' ';
									$list_data['GSM'] .= $number['GSM'].' ';
								} 											
							}
							fputcsv($fp, array($week, $van, $tot, $list_data['naam'], $list_data['GSM']), ';'); 
						} 																
					} else if ($lijsten->type == "calamiteiten") {
						$list_data = array('medewerker' => '', 'arbeider' => '');
						if (array_key_exists('arbeider', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeider'] as $number){			
								if(isset($number['naam'])){
									if($list_data['arbeider']!=''){
										$list_data['arbeider'] .= "\r\n";
									}
									$list_data['arbeider'] .= $number['naam'];												
								} 
							}
						}
						if (array_key_exists('medewerker', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['medewerker'] as $number){
								if(isset($number['naam'])){
									if($list_data['medewerker']!=''){
										$list_data['medewerker'] .= "\r\n";
									}
									$list_data['medewerker'] .=  $number['naam'];												
								} 												
							}
						}
						fputcsv($fp, array($week, $van, $tot, $list_data['medewerker'], $list_data['arbeider']), ';', '"'); 																			
					} else {									 
						$list_data = array('wegentoezichters-vroeg' => '', 'wegentoezichters-laat' => '', 'arbeiders-vroeg' => '', 'arbeiders-laat' => '');
						if (array_key_exists('arbeiders-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-vroeg'] as $number){			
								if(isset($number['naam'])){
									if($list_data['arbeiders-vroeg']!=''){
										$list_data['arbeiders-vroeg'] .= "\r\n";
									}
									$list_data['arbeiders-vroeg'] .= mb_convert_encoding($number['naam'], 'UCS-2LE', 'UTF-8');												
								} 
							}
						}
						if(array_key_exists('arbeiders-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-laat'] as $number){			
								if(isset($number['naam'])){
									if($list_data['arbeiders-laat']!=''){
										$list_data['arbeiders-laat'] .= "\r\n";
									}
									$list_data['arbeiders-laat'] .=  mb_convert_encoding($number['naam'], 'UCS-2LE', 'UTF-8');												
								} 
							}
						}
						if (array_key_exists('wegentoezichters-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])) {
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-vroeg'] as $number){
								if(isset($number['naam'])){
									if($list_data['wegentoezichters-vroeg']!=''){
										$list_data['wegentoezichters-vroeg'] .= "\r\n";
									}
									$list_data['wegentoezichters-vroeg'] .=  $number['naam'];												
								} 												
							}
						}
						if (array_key_exists('wegentoezichters-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-laat'] as $number){
								if(isset($number['naam'])){
									if($list_data['wegentoezichters-laat']!=''){
										$list_data['wegentoezichters-laat'] .= "\r\n";
									}
									$list_data['wegentoezichters-laat'] .=  $number['naam'];												
								} 												
							}
						}
						fputcsv($fp, array($week, $van, $tot, $list_data['wegentoezichters-vroeg'], $list_data['wegentoezichters-laat'], $list_data['arbeiders-vroeg'], $list_data['arbeiders-laat'] ), ';', '"'); 		
					}								
				}
				if ($i == 52){
					$i = 0;
					$year++;
				}
				$i++;
				
			}
			fclose($fp);
			$this->redirect('http://10.36.8.89/files/permanentielijst_'.$lijsten->type.'_'.$lijsten->subtype.'_'.$lijsten->districtscode.'.csv');
		} else if ($type == "doc") {
			$filename = 'permanentielijst_'.$lijsten->type.'_'.$lijsten->subtype.'_'.$lijsten->districtscode.'.doc';
			$table = '<html><center><table><thead>';
			if ($lijsten->subtype == "leidinggevenden"){
				$table .= '<tr><th width="2%">Week</th><th width="2%">Van</th><th width="2%">Tot</th><th width="47%">Naam leidinggevende</th><th width="47%">GSM nummer</th></tr>';
			} else if ($lijsten->type == "calamiteiten") {
				$table .= '<tr><th width="2%">Week</th><th width="2%">Van</th><th width="2%">Tot</th><th width="47%">Wegentoezichters</th><th width="47%">Arbeiders</th></tr>';		
			} else {
				$table .= '<tr><th width="2%">Week</th><th colSpan=2>Datum Van - Tot</th><th colSpan=2>Wegentoezichters</th><th colSpan=2>Lader/Technisch assistent</th></tr>';
				$table .= '<tr><th>Week</th><th width="2%">Van</th><th width="2%">Tot</th><th>08.00u - 20.00u</th><th>20.00u - 08.00u</th><th>08.00u - 20.00u</th><th>20.00u - 08.00u</th></tr>';
			}
			$table .= '</thead><tbody>';
			$year = date('Y',$lijsten->Startdatum->sec);
			$i = date('W', $lijsten->Startdatum->sec);
			$eind_week = date('W', $lijsten->Einddatum->sec);		   		
			foreach ($lijsten_arr['permanentie'] as $test){
				if ($i <=9) {
					$digit = '0';
				} else {
					$digit = '';
				}
				$y = $i;
				$y++;
				foreach ($lijsten_arr['permanentie']['week_'.$i] as $key => $value){
					$table .= '<tr id="week_'.$i.'_0">';
					if($key==0){
						$table .=  '<td class="week">'.$i.'</td>';
					} else {
						$table .=  '<td class="week"></td>';
					}									
					$table .=  '<td class="van">'.date('d/m/Y',$lijsten_arr['permanentie']['week_'.$i][$key]['startdatum']).'</td>';
					$table .=  '<td class="tot">'.date('d/m/Y',$lijsten_arr['permanentie']['week_'.$i][$key]['einddatum']).'</td>';
						if ($lijsten->subtype == "leidinggevenden" || ($lijsten->subtype == "provinciaal")){									
							if (array_key_exists('personeelslid', $lijsten_arr['permanentie']['week_'.$i][$key])){
								$list_data = array('naam' => '', 'GSM' => '');
								foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['personeelslid'] as $number){			
									if(isset($number['naam'])){
										$list_data['naam'] .=  $number['naam'];
										$list_data['GSM'] .= $number['GSM'];
									} 											
								}
								$table .=  '<td>'.htmlentities($list_data['naam']).'</td><td>'.$list_data['GSM'].'</td></tr>';
							} else {
								$table .=  '<td></td><td></td></tr>';
							}																	
						} else if ($lijsten->type == "calamiteiten") {
						$list_data = array('medewerker' => '', 'arbeider' => '');
						if (array_key_exists('arbeider', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeider'] as $number){			
								if(isset($number['naam'])){
									if($list_data['arbeider']!=''){
										$list_data['arbeider'] .=  "<br />";
									}
									$list_data['arbeider'] .= htmlentities($number['naam']);												
								} 
							}
						}
						if (array_key_exists('medewerker', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['medewerker'] as $number){
								if(isset($number['naam'])){
									if($list_data['medewerker']!=''){
										$list_data['medewerker'] .=  "<br />";
									}
									$list_data['medewerker'] .=  htmlentities($number['naam']);												
								} 												
							}
						}
						fputcsv($fp, array($week, $van, $tot, $list_data['medewerker'], $list_data['arbeider']), ';', '"'); 																			
					} else {									 
						$list_data = array('wegentoezichters-vroeg' => '', 'wegentoezichters-laat' => '', 'arbeiders-vroeg' => '', 'arbeiders-laat' => '');
						if (array_key_exists('arbeiders-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-vroeg'] as $number){			
								if(isset($number['naam'])){
									if($list_data['arbeiders-vroeg']!=''){
										$list_data['arbeiders-vroeg'] .= "<br />";
									}
									$list_data['arbeiders-vroeg'] .= htmlentities($number['naam']);												
								} 
							}
						}
						if(array_key_exists('arbeiders-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-laat'] as $number){			
								if(isset($number['naam'])){
									if($list_data['arbeiders-laat']!=''){
										$list_data['arbeiders-laat'] .=  "<br />";
									}
									$list_data['arbeiders-laat'] .=  htmlentities($number['naam']);												
								} 
							}
						}
						if (array_key_exists('wegentoezichters-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])) {
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-vroeg'] as $number){
								if(isset($number['naam'])){
									if($list_data['wegentoezichters-vroeg']!=''){
										$list_data['wegentoezichters-vroeg'] .=  "<br />";
									}
									$list_data['wegentoezichters-vroeg'] .=  htmlentities($number['naam']);												
								} 												
							}
						}
						if (array_key_exists('wegentoezichters-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
							foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-laat'] as $number){
								if(isset($number['naam'])){
									if($list_data['wegentoezichters-laat']!=''){
										$list_data['wegentoezichters-laat'] .=  "<br />";
									}
									$list_data['wegentoezichters-laat'] .=  htmlentities($number['naam']);												
								} 												
							}
						}
							$table .= '<td>'.$list_data['wegentoezichters-vroeg'].'</td><td>'.$list_data['wegentoezichters-laat'].'</td><td>'.$list_data['arbeiders-vroeg'].'</td><td>'.$list_data['arbeiders-laat'].'</td></tr>';
						}								
					}
					if ($i == 52){
						$i = 0;
						$year++;
					}
					$i++;
				}
			$table .= '</tbody></table></html>';
			 $this->_render['layout'] = 'html';
			return compact('filename', 'table');
		}		
	}
}