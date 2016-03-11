<!--[if lt IE 10 ]>
		<script>
		var is_ie_lt10 = true;
		alert('Dit werkt enkel in Mozilla Firefox of Google Chrome!');
		</script>
		<div style="margin-left:20px; font-size: 25px; margin-bottom: 50px;">Permanentielijsten bewerken werkt enkel in <strong>Mozilla Firefox of Google Chrome</strong>!</div>
<![endif]--> 
<!--[if !IE]>--><?php if (($login['rol'] == 'administrator') || ($login['location'] == $lijsten->district) || (strpos($login['location'], 'Alle districten') !== false && $login['provincie'] == $lijsten->provincie)){ ?>
    <div class="container-fluid">
	    <div class="row">
	    	<div class="col-md-9">
	    		<div class="dropdown" style="float:right">
				    <?=$this->html->link('Bekijken', 'lijsten/view/'.$lijsten->type.'/'.$lijsten->subtype.'/'.$lijsten->districtscode.'/'.date('Y', $lijsten->Startdatum->sec), array('class' => 'btn btn-info'))?>
			    </div>
			    
			<?php if ($lijsten->type == "calamiteiten" && $lijsten->subtype == "leidinggevenden"){?>   
                <?php if (strstr($lijsten->district, 'Alle districten') !== FALSE){?>
                    <h4>Beurtrol <?=$lijsten->subtype?> <?=$lijsten->type?> wachtdienst (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
                <?php } else { ?>
                    <h4>Beurtrol <?=$lijsten->subtype?> <?=$lijsten->type?> wachtdienst - <?=$locatie->district?> - <?=$locatie->districtnummer?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
                <?php } ?>
            <?php } else if ($lijsten->subtype == 'provinciaal') {?>
                <h4>Beurtrol Provinciaal Coördinator (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
            <?php } else if ($lijsten->subtype == 'EM') {?>
                <h4>Beurtrol Permanentie Sectie EM (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
            <?php } else { ?>
                <h4>Permanentielijst - <?=$locatie->district?> - <?=$locatie->districtnummer?> - <?=$lijsten->subtype?> <?=$lijsten->type?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
            <?php } ?>   
	    	</div>
	    	<div class="col-md-3">
	    	    <button class="btn btn-warning btn-sm" id="addPersonen">Toevoegen uit personenlijst</button>
	    	</div>
	    </div>	
	    <div class="row">
	    	
	    	<div class="col-md-9">	
				<table id="permanentie_tabel" class="table table-striped" data-url="add_permanentie/<?=$lijsten->_id?>" data-deleteurl="delete_permanentie/<?=$lijsten->_id?>" data-type="<?=$lijsten->type?>" data-subtype="<?=$lijsten->subtype?>">
					<div class="tabel_t">
					<thead>
						<tr>
							<?php if ($lijsten->subtype == "leidinggevenden" || $lijsten->subtype == "provinciaal" || $lijsten->subtype == "EM"){?>
								<th width="6%" class="th_w">Week</th>
								<th width="9%" class="th_v">Van</th>
								<th width="9%" class="th_t">Tot</th>
								<th width="35%" class="th_l" style="position: relative">Naam leidinggevende</th>
								<th width="35%"class="th_g" style="position: relative">GSM nummer</th>
							<?php } else if ($lijsten->type == "calamiteiten"){?>
								<th width="61px">Week</th>
								<th width="86px">Van</th>
								<th width="86px">Tot</th>
								<th width="47%">Wegentoezichters</th>
								<th width="47%">GSM nummer</th>
							<?php } else {?>
								<th width="61px">Week</th>
								<th colSpan=2>Datum Van - Tot</th>
								<th colSpan=2>Wegentoezichters</th>
								<th colSpan=2>Lader/Technisch assistent</th>
								<th width="2%"></th>
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
							<th width="7%"><span class="glyphicon glyphicon-plus"></span></th>
							</tr>			
					</thead>
					<tbody>
						<?php
						
							function cmp($a,$b){
								return strcmp($a["startdatum"], $b["startdatum"]);
							}
							$year = date('Y',$lijsten->Startdatum->sec);
				    		$i = date('W', $lijsten->Startdatum->sec);
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
								usort($lijsten_arr['permanentie']['week_'.$i], "cmp");
								foreach ($lijsten_arr['permanentie']['week_'.$i] as $key => $value){
									asort($value);
									echo '<tr id="week_'.$i.'_'.$key.'">';
									if($key==0){
										$button = '<button class="btn btn-default btn-xs plus" ><span class="glyphicon glyphicon-plus"></span></button>';
										echo '<td class="week"><div class="element">'.$i.'</div></td>';
									} else {
										$button =  '<div class="dropdown">
												  <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" href="#">
												    <span class="glyphicon glyphicon-edit"></span>
												  </button>
												  <ul class="dropdown-menu pull-right">
												    <li><a href="#nieuweLijn" class="add_row">Nieuwe Lijn...</a></li>
												    <li>'.$this->html->link('Lijn verwijderen...', 'lijsten/permanentie_week_remove/'.$lijsten->_id, array('class' => 'remove_row')).'</li>
												  </ul>
												</div>';
										echo '<td class="week"></td>';
									}							
									echo '<td class="van"><div class="element">'.date('d/m/Y', $lijsten_arr['permanentie']['week_'.$i][$key]['startdatum']).'</div></td>';
									echo '<td class="tot"><div class="element">'.date('d/m/Y',$lijsten_arr['permanentie']['week_'.$i][$key]['einddatum']).'</div></td>';
									if ($lijsten->subtype == "leidinggevenden" || $lijsten->subtype == "provinciaal" || $lijsten->subtype == "EM"){
									    $list_data = array('naam' => '', 'GSM' => '');
										if (array_key_exists('personeelslid', $lijsten_arr['permanentie']['week_'.$i][$key])){											
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['personeelslid'] as $number){			
												if(isset($number['naam'])){
													$list_data['naam'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="glyphicon glyphicon-remove-sign"></span><div class="hidden GSM">'.$number['GSM'].'</div></div>';
													$list_data['GSM'] .= '<div class="element GSM_element">'.$number['GSM'].'</div>';
												} 											
											}
											echo '<td class="leidingevende dropable">'.$list_data['naam'].'</td><td class="leidingevende GSM">'.$list_data['GSM'].'</td><td>'.$button.'</td></tr>';
										} else {
											echo '<td class="leidingevende dropable"></td><td class="leidingevende GSM"></td><td>'.$button.'</td></tr>';
										}																	
									} else if ($lijsten->type == "calamiteiten") {
									    $list_data = array('medewerker' => '', 'GSM' => '');
										if (array_key_exists('medewerker', $lijsten_arr['permanentie']['week_'.$i][$key])){											
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['medewerker'] as $number){
												if(isset($number['naam']) && isset($personeel[$number['naam']])){
													$list_data['medewerker'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="glyphicon glyphicon-remove-sign"></span><div class="hidden GSM">'.$personeel[$number['naam']].'</div></div>';
													$list_data['GSM'] .= '<div class="element GSM_element">'.$personeel[$number['naam']].'</div>';												
												} 												
											}
										}
										echo '<td class="medewerker dropable">'.$list_data['medewerker'].'</td><td class="medewerker GSM">'.$list_data['GSM'].'</td><td>'.$button.'</td></tr>';																			
									} else {
										$list_data = array('wegentoezichters-vroeg' => '', 'wegentoezichters-laat' => '', 'arbeiders-vroeg' => '', 'arbeiders-laat' => '');
										if (array_key_exists('arbeiders-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-vroeg'] as $number){			
												if(isset($number['naam'])){
													$list_data['arbeiders-vroeg'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="glyphicon glyphicon-remove-sign"></span></div>';												
												} 
											}
										}
										if(array_key_exists('arbeiders-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-laat'] as $number){			
												if(isset($number['naam'])){
													$list_data['arbeiders-laat'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="glyphicon glyphicon-remove-sign"></span></div>';												
												} 
											}
										}
										if (array_key_exists('wegentoezichters-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])) {
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-vroeg'] as $number){
												if(isset($number['naam'])){
													$list_data['wegentoezichters-vroeg'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="glyphicon glyphicon-remove-sign"></span></div>';												
												} 												
											}
										}
										if (array_key_exists('wegentoezichters-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-laat'] as $number){
												if(isset($number['naam'])){
													$list_data['wegentoezichters-laat'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="glyphicon glyphicon-remove-sign"></span></div>';												
												} 												
											}
										}
										echo '<td class="wegentoezichters-vroeg dropable">'.$list_data['wegentoezichters-vroeg'].'</td><td class="wegentoezichters-laat dropable">'.$list_data['wegentoezichters-laat'].'</td><td class="arbeiders-vroeg dropable">'.$list_data['arbeiders-vroeg'].'</td><td class="arbeiders-laat dropable">'.$list_data['arbeiders-laat'].'</td><td>'.$button.'</td></tr>';								
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
					</div>
				</table>
			</div>	
			<div class="col-md-3">
	    		<div id="scrollbar1">
	    			<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
					<div class="viewport">
						<div class="overview">
						    
				    		<ul id="werknemers">	 
				    			<?php foreach($personeel_lijst as $key){
				    			    $info = '';
				    			    if(array_key_exists('vlimpersnummer', $key)){
				    			        $vlimpersnummer = '<div class="hidden vlimpersnummer">'.$key["vlimpersnummer"].'</div>';
				    			        if ($key['vlimpersnummer']!= ''){
                                            $info = 'Vlimpers: '.$key['vlimpersnummer'];
                                        }
				    			    } else {
				    			        $vlimpersnummer = '<div class="hidden vlimpersnummer"></div>';
				    			    }
				    				($info=='') ? $info = 'GSM: '.$key['GSM'] : $info .= ' - GSM: '.$key['GSM'];
				    				?>
				    				<li class="drag well" draggable="true"><span class="glyphicon glyphicon-move" style="margin-right: 5px"></span><span class="naam"><?=$key['naam']?></span><span class="glyphicon glyphicon-remove-sign"></span><span class="glyphicon glyphicon-pencil"></span><a class="glyphicon glyphicon-info-sign" data-placement="bottom" style="color: #333" data-toggle="tooltip" title="" data-original-title="<?=$info?>"></a><div class="hidden GSM"><?=$key['GSM']?></div><?php echo $vlimpersnummer?></li>
				    			<?php } ?>							
							</ul>	
							<div class="hidden" id="deleteurl">remove_personeelslid/<?=$lijsten->_id?></div>
							<div id="wn_toevoegen" style="margin-top: 10px;">
							    
								<form id="wn_form" action="add_personeelslid/<?=$lijsten->_id?>" style="margin-bottom:0px;">
								    <input type="text"  class="form-control" id="vlimpersnummer" placeholder="Vlimpersnummer" required="true" name="vlimpersnummer" value="" style="margin: 3px 0">
									<input type="text"  class="form-control" id="personeel" placeholder="Naam" required="true" name="naam" value="" style="margin: 3px 0">
									<input type="text" class="form-control" id="GSM" placeholder="GSM" required="true" name="gsmnummer" value="" style="margin: 3px 0">
									<input type="submit" value="toevoegen" id="personeel_toevoegen" class="btn btn-default">
								</form>
							</div>
						</div>						
					</div>
					
				</div>
	    	</div>
	    </div>
		<div id="myModal_personeel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		    <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
        			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        		    	<h3>Persoon bewerken</h3>
        		    </div>
        		    <div class="modal-body">
        		    	<p>
        		    	<form id="wn_edit_form" action="edit_permanentie/<?=$lijsten->_id?>">
        		    	    <label for="naam">Vlimpersnummer</label>
                            <input type="text" id="vlimpersnummer_bewerken"  class="form-control" placeholder="Vlimpersnummer"required="true" name="vlimpersnummer" val=""><br />
                            <input type="hidden" id="vlimpersnummer_old" name="old_vlimpers" val="">
        		    		<label for="naam">Naam</label>
        					<input type="text" id="personeel_bewerken"  class="form-control" placeholder="Naam"required="true" name="naam" val=""><br />
        					<input type="hidden" id="personeel_old" name="old_naam" val="">
        					<label for="gsmnummer">GSM</label>
        					<input type="text" id="GSM_bewerken"  class="form-control" placeholder="GSM" required="true" name="gsmnummer" val="">
        					<input type="hidden" id="GSM_old"name="old_gsmnummer" val="">
        				</form>
        				</p>
        		    </div>
        		    <div class="modal-footer">
        		    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save_modal">Bewaren</button>
        		    </div>
            	</div>
            </div>
		</div>	
		<div id="myModal_add_row" class="modal fade" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
        		    <div class="modal-header">
        			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        		    	<h3>Rij toevoegen</h3>
        		    </div>
        		    <div class="modal-body">
        		    	<p>
        		    		<form id="week_add_form" action="permanentie_week_add/<?=$lijsten->_id?>">
        				    	<label for="naam">Tussendatum tussen (<span id="begin_datum_min"></span> en <span id="eind_datum_max"></span>)</label>
        						<input type="text" id="tussendatum" class="form-control" required="true" placeholder="tussendatum toevoegen" name="naam" val="" >
        						<input type="hidden" id="week" name="week" val="">
        						<input type="hidden" id="begin_datum" name="week" val="">
        						<input type="hidden" id="eind_datum" name="week" val="">
        					</form>
        				</p>
        		    </div>
        		    <div class="modal-footer">
        		    	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        		    	<button type="button" class="btn btn-primary save_modal">Bewaren</button>
        		    </div>
        		</div>
            </div>
		</div>	
		<div id="myModal_add_personeel" class="modal fade" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>Personen toevoegen</h3>
                    </div>
                    <div class="modal-body">
                        <input id="hidden_id" type="hidden" value="<?=$lijsten->_id?>">
                        <form id="personeel_buttons" action="personeel/list/<?=$lijsten->_id?>">                            
                            <div style="margin-bottom: 18px">
                                <button class="btn btn-default btn-xs select-all">Selecteer iedereen</button> <button class="btn btn-default btn-xs deselect-all">Selectie ongedaan maken</button>
                            </div>
                            <div class="" data-toggle="buttons" id="personeel_buttons_group">
                           <?php foreach ($personeelsleden as $key => $personeel) {
                               $show = true;
                               foreach ($personeel_lijst as $key2){
                                    if ($key2['naam']===$personeel['naam']) {
                                        $show =false;
                                    }
                               }
                               if ($show){
                           ?>
                              <label class="btn btn-default btn-xs btn-group-label" style="margin: 0 6px 12px 0px">
                                <input type="checkbox" autocomplete="off" id="<?=$personeel['_id']?>" value="off" name="personeel[<?=$personeel['_id']?>]" checked> <?=$personeel['naam']?>
                              </label>
                           <?php }} ?>
                           </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                        <button type="button" class="btn btn-info save_personeel_modal">Toevoegen</button>
                    </div>
                </div>
            </div>
        </div>  
		</div>
	<?php } else { ?>
		<h4>Deze pagina is niet toegangelijk!</h4>
	<?php } ?>
<!--<![endif]-->