
    <div style="clear:both"></div>
    <div class="container-fluid" style="width: 970px; margin: 0 auto;">
	    <div class="row">
	    	<div class="col-md-12">
	    	    <div  style="float:right">
    	    		<div class="dropdown"style="display:inline-block">    	    		    
    				    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    				    Exporteren
    				    <span class="caret"></span>
    				    </button>
    				    
    				    <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
    						<li>
    							<?=$this->html->link('CSV', '/lijsten/export/csv/'.$lijsten->_id)?>
    						</li>
    						<li>
    							<?=$this->html->link('MS Word', 'lijsten/export/doc/'.$lijsten->_id);?>
    						</li>
    						<li>
    							<a href="#">HTML</button>
    						</li>
    				    </ul>
    				</div>
				    <?php 
				    if (($login['rol'] == 'administrator' || ($login['rol'] == 'personeel' && $login['provincie'] == $lijsten->provincie)) || (strpos($login['location'], 'Alle districten') !== false && $login['provincie'] == $lijsten->provincie)){
				        if ($lijsten->type == "calamiteiten"){
                            echo $this->html->link('<i class="glyphicon glyphicon-time"></i>', 'lijsten/urenPerMaand/'.$lijsten->_id.'/'.date('n', $lijsten->Startdatum->sec).'/'.date('Y', $lijsten->Startdatum->sec), array('escape' => false, 'class' => 'btn btn-success', 'style'=>'margin-right:5px'));
                        }                        
                    } 
				    if (($login['rol'] == 'administrator') || ($login['location'] == $lijsten->district) || (strpos($login['location'], 'Alle districten') !== false && $login['provincie'] == $lijsten->provincie)){
				     	echo $this->html->link('Bewerken', 'lijsten/lijst/'.$lijsten->type.'/'.$lijsten->subtype.'/'.$lijsten->districtscode.'/'.date('Y', $lijsten->Startdatum->sec), array('class' => 'btn btn-info'));
				    } 
				    ?>
				 </div>   
			<?php if ($lijsten->type == "calamiteiten" && $lijsten->subtype == "leidinggevenden"){?>   
				<?php if (strstr($lijsten->district, 'Alle districten') !== FALSE){?>
					<h4>Beurtrol <?=$lijsten->subtype?> <?=$lijsten->type?> wachtdienst (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
				<?php } else { ?>
					<h4>Beurtrol <?=$lijsten->subtype?> <?=$lijsten->type?> wachtdienst - <?=$locatie->district?> - <?=$locatie->districtnummer?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
				<?php } ?>
			<?php } else if ($lijsten->subtype == 'provinciaal') {?>
				<h4>Beurtrol Provinciaal Coördinator <?=str_replace('Alle districten', '', $lijsten->district)?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
			<?php } else if ($lijsten->subtype == 'EM') {?>
				<h4>Beurtrol Permanentie Sectie EM (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
			<?php } else { ?>
				<h4>Permanentielijst - <?=$locatie->district?> - <?=$locatie->districtnummer?> - <?=$lijsten->subtype?> <?=$lijsten->type?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
			<?php } ?>	    
	    	</div>
	    </div>	
	    <div class="row">
	    	
	    	<div class="col-md-12">	
				<table id="permanentie_tabel" class="table table-striped" data-url="<?=$base_url?>/lijsten/add_permanentie/<?=$lijsten->_id?>" data-deleteurl="<?=$base_url?>/lijsten/delete_permanentie/<?=$lijsten->_id?>" data-type="<?=$lijsten->type?>" data-subtype="<?=$lijsten->subtype?>">
					<thead>
						<tr>
							<?php if ($lijsten->subtype == "leidinggevenden" || $lijsten->subtype == "provinciaal" || $lijsten->subtype == "EM"){?>
								<th width="2%">Week</th>
								<th width="2%">Van</th>
								<th width="2%">Tot</th>
								<th width="47%">Naam leidinggevende</th>
								<th width="47%">GSM nummer</th>
							<?php } else if ($lijsten->type == "calamiteiten"){?>
								<th width="2%">Week</th>
								<th width="2%">Van</th>
								<th width="2%">Tot</th>
								<th width="47%">Wegentoezichters</th>
								<th width="47%">GSM nummer</th>
							<?php } else {?>
								<th width="2%">Week</th>
								<th colSpan=2>Datum Van - Tot</th>
								<th colSpan=2>Wegentoezichters</th>
								<th colSpan=2>Lader/Technisch assistent</th>								
							</tr>
							<tr>
								<th>Week</th>
								<th width="2%">Van</th>
								<th width="2%">Tot</th>
								<th>08.00u - 20.00u</th>
								<th>20.00u - 08.00u</th>
								<th>08.00u - 20.00u</th>
								<th>20.00u - 08.00u</th>
							<?php } ?>							
							</tr>					
					</thead>
					<tbody>
						<?php
							$year = date('Y',$lijsten->Startdatum->sec);
				    		$i = date('W', $lijsten->Startdatum->sec);
				    		$eind_week = date('W', $lijsten->Einddatum->sec);
				    		if ($i == $eind_week)
				    		{
				    			$eind_week--;
				    		}
				    		$personeel = array();
				    		foreach ($lijsten_arr['personeel'] as $persoon) {
				    		    $personeel[$persoon['naam']] = $persoon['GSM'];
				    		};
				    		foreach ($lijsten_arr['permanentie'] as $test){
				    			if ($i <=9) {
				    				$digit = '0';
				    			} else {
				    				$digit = '';
				    			}
								$y = $i;
								$y++;
								foreach ($lijsten_arr['permanentie']['week_'.$i] as $key => $value){
									echo '<tr id="week_'.$i.'_0">';
									if($key==0){
										echo '<td class="week">'.$i.'</td>';
									} else {
										echo '<td class="week"></td>';
									}									
									echo '<td class="van">'.date('d/m/Y', $lijsten_arr['permanentie']['week_'.$i][$key]['startdatum']).'</td>';
									echo '<td class="tot">'.date('d/m/Y',$lijsten_arr['permanentie']['week_'.$i][$key]['einddatum']).'</td>';
									if ($lijsten->subtype == "leidinggevenden" || $lijsten->subtype == "provinciaal" || $lijsten->subtype == "EM"){									
										if (array_key_exists('personeelslid', $lijsten_arr['permanentie']['week_'.$i][$key])){
											$list_data = array('naam' => '', 'GSM' => '');
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['personeelslid'] as $number){			
												if(isset($number['naam'])){
													$list_data['naam'] .=  '<div><span class="naam">'.$number['naam'].'</span><div class="hidden GSM">'.$number['GSM'].'</div></div>';
													$list_data['GSM'] .= '<div>'.$number['GSM'].'</div>';
												} 											
											}
											echo '<td>'.$list_data['naam'].'</td><td>'.$list_data['GSM'].'</td></tr>';
										} else {
											echo '<td></td><td></td></tr>';
										}																	
									} else if ($lijsten->type == "calamiteiten") {
										if (array_key_exists('medewerker', $lijsten_arr['permanentie']['week_'.$i][$key])){
											$list_data = array('naam' => '', 'GSM' => '');
                                            foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['medewerker'] as $number){            
                                                if(isset($number['naam'])){
                                                    $list_data['naam'] .=  '<div><span class="naam">'.$number['naam'].'</span><div class="hidden GSM">'.$personeel[$number['naam']].'</div></div>';
                                                    $list_data['GSM'] .= '<div>'.$personeel[$number['naam']].'</div>';
                                                }                                           
                                            }
                                            echo '<td>'.$list_data['naam'].'</td><td>'.$list_data['GSM'].'</td></tr>';
										}																		
									} else {									 
										$list_data = array('wegentoezichters-vroeg' => '', 'wegentoezichters-laat' => '', 'arbeiders-vroeg' => '', 'arbeiders-laat' => '');
										if (array_key_exists('arbeiders-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-vroeg'] as $number){
											    if(isset($personeel[$number['naam']])&& $personeel[$number['naam']] != ''){
                                                   $list_data['arbeiders-vroeg'] .=  '<div><i class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="'.$personeel[$number['naam']].'"></i><div class="hidden GSM">'.$personeel[$number['naam']].'</div>&nbsp;&nbsp;';
                                                }   			
												if(isset($number['naam'])){
												    if ($list_data['arbeiders-vroeg'] == ''){
                                                        $list_data['arbeiders-vroeg'] = '<div>';
                                                    }
													$list_data['arbeiders-vroeg'] .=  '<span class="naam">'.$number['naam'].'</span>';												
												}                                                 
                                                if(isset($number['naam'])){
                                                    $list_data['arbeiders-vroeg'] .= '</div>';
                                                }   
											}
										}
										if(array_key_exists('arbeiders-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-laat'] as $number){
											    if(isset($personeel[$number['naam']])&& $personeel[$number['naam']] != ''){
                                                   $list_data['arbeiders-laat'] .=  '<div><i class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="'.$personeel[$number['naam']].'"></i><div class="hidden GSM">'.$personeel[$number['naam']].'</div>&nbsp;&nbsp;';
                                                }  			
												if(isset($number['naam'])){
												    if ($list_data['arbeiders-laat'] == ''){
                                                        $list_data['arbeiders-laat'] = '<div>';
                                                    }
													$list_data['arbeiders-laat'] .=  '<span class="naam">'.$number['naam'].'</span>';												
												}                                                  
                                                if(isset($number['naam'])){
                                                    $list_data['arbeiders-laat'] .= '</div>';
                                                } 
											}
										}
										if (array_key_exists('wegentoezichters-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])) {
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-vroeg'] as $number){
											    if(isset($personeel[$number['naam']])&& $personeel[$number['naam']] != ''){
                                                   $list_data['wegentoezichters-vroeg'] .=  '<div><i class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="'.$personeel[$number['naam']].'"></i><div class="hidden GSM">'.$personeel[$number['naam']].'</div>&nbsp;&nbsp;';
                                                }
												if(isset($number['naam'])){
												    if ($list_data['wegentoezichters-vroeg'] == ''){
                                                        $list_data['wegentoezichters-vroeg'] = '<div>';
                                                    }
													$list_data['wegentoezichters-vroeg'] .=  '<span class="naam">'.$number['naam'].'</span>';												
												}                                                   
                                                if(isset($number['naam'])){
                                                    $list_data['wegentoezichters-vroeg'] .= '</div>';
                                                }  												
											}
										}
										if (array_key_exists('wegentoezichters-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-laat'] as $number){
											    if(isset($personeel[$number['naam']])&& $personeel[$number['naam']] != ''){
                                                   $list_data['wegentoezichters-laat'] .=  '<div><i class="glyphicon glyphicon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="'.$personeel[$number['naam']].'"></i><div class="hidden GSM">'.$personeel[$number['naam']].'</div>&nbsp;&nbsp;';
                                                }
												if(isset($number['naam'])){
												    if ($list_data['wegentoezichters-laat'] == ''){
                                                        $list_data['wegentoezichters-laat'] = '<div>';
                                                    }
													$list_data['wegentoezichters-laat'] .=  '<span class="naam">'.$number['naam'].'</span>';												
												}    
                                                if(isset($number['naam'])){
                                                    $list_data['wegentoezichters-laat'] .= '</div>';
                                                }												
											}
										}
										echo '<td>'.$list_data['wegentoezichters-vroeg'].'</td><td>'.$list_data['wegentoezichters-laat'].'</td><td>'.$list_data['arbeiders-vroeg'].'</td><td>'.$list_data['arbeiders-laat'].'</td></tr>';
									}								
								}
								if ($i == date('W', mktime(0,0,0,12,28,$year))){
									$i = 0;
									$year++;
								}
								$i++;
				    		}

						?>
					
					</tbody>
				</table>
			</div>	
	    </div>
