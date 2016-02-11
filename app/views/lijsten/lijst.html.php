<!--[if lt IE 10 ]>
		<script>
		var is_ie_lt10 = true;
		alert('Dit werkt enkel in Mozilla Firefox of Google Chrome!');
		</script>
		<div style="margin-left:20px; font-size: 25px; margin-bottom: 50px;">Permanentielijsten bewerken werkt enkel in <strong>Mozilla Firefox of Google Chrome</strong>!</div>
<![endif]--> 
<!--[if !IE]>--><?php if (($login['rol'] == 'administrator') || ($login['location'] == $lijsten->district) || (strpos($login['location'], 'Alle districten') !== false && $login['provincie'] == $lijsten->provincie)){ ?>
	
    <div style="clear:both"></div>
    <div class="container-fluid">
	    <div class="row-fluid">
	    	<div class="span12">
	    		<div class="btn-group" style="float:right">
				    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				    Exporteren
				    <span class="caret"></span>
				    </a>
				    <ul class="dropdown-menu pull-right">
						<li>
							<?=$this->html->link('CSV', '/lijsten/export/csv/'.$lijsten->_id)?>
						</li>
						<li>
							<?=$this->html->link('MS Word', 'lijsten/export/doc/'.$lijsten->_id);?>
						</li>
						<li>
							<a href="#">HTML</a>
						</li>
				    </ul>
				    <?=$this->html->link('Bekijken', 'lijsten/view/'.$lijsten->type.'/'.$lijsten->subtype.'/'.$lijsten->districtscode.'/'.date('Y', $lijsten->Startdatum->sec), array('class' => 'btn'))?>
			    </div>
			    
			<?php if ($lijsten->type == "calamiteiten" && $lijsten->subtype == "leidinggevenden"){?>   
				<h4>Beurtrol <?=$lijsten->subtype?> <?=$lijsten->type?> wachtdienst (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
			<?php } else if ($lijsten->subtype == 'provinciaal') {?>
				<h4>Beurtrol Provinciaal CoÃ¶rdinator (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
			<?php } else if ($lijsten->subtype == 'EM') {?>
				<h4>Beurtrol Permanentie Sectie EM (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
			<?php } else { ?>
				<h4>Permanentielijst - <?=$locatie->district?> - <?=$locatie->districtnummer?> - <?=$lijsten->subtype?> <?=$lijsten->type?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
			<?php } ?>	    
	    	</div>
	    </div>	
	    <div class="row-fluid">
	    	
	    	<div class="span9">	
				<table id="permanentie_tabel" class="table-striped" data-url="add_permanentie/<?=$lijsten->_id?>" data-deleteurl="delete_permanentie/<?=$lijsten->_id?>" data-type="<?=$lijsten->type?>" data-subtype="<?=$lijsten->subtype?>">
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
							<th width="7%"><span class="icon-plus-sign"></span></th>
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
										$button = '<button class="btn btn-mini plus"><span class="icon-plus"></span></button>';
										echo '<td class="week">'.$i.'</td>';
									} else {
										$button =  '<div class="btn-group">
												  <a class="btn btn-mini dropdown-toggle" data-toggle="dropdown" href="#">
												    <span class="icon-edit"></span>
												  </a>
												  <ul class="dropdown-menu pull-right">
												    <li><a href="#nieuweLijn" class="add_row">Nieuwe Lijn...</a></li>
												    <li>'.$this->html->link('Lijn verwijderen...', 'lijsten/permanentie_week_remove/'.$lijsten->_id, array('class' => 'remove_row')).'</li>
												  </ul>
												</div>';
										echo '<td class="week"></td>';
									}							
									echo '<td class="van">'.date('d/m/Y', $lijsten_arr['permanentie']['week_'.$i][$key]['startdatum']).'</td>';
									echo '<td class="tot">'.date('d/m/Y',$lijsten_arr['permanentie']['week_'.$i][$key]['einddatum']).'</td>';
									if ($lijsten->subtype == "leidinggevenden" || $lijsten->subtype == "provinciaal" || $lijsten->subtype == "EM"){
										if (array_key_exists('personeelslid', $lijsten_arr['permanentie']['week_'.$i][$key])){
											$list_data = array('naam' => '', 'GSM' => '');
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['personeelslid'] as $number){			
												if(isset($number['naam'])){
													$list_data['naam'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="icon-remove-sign"></span><div class="hidden GSM">'.$number['GSM'].'</div></div>';
													$list_data['GSM'] .= '<div class="element GSM_element">'.$number['GSM'].'</div>';
												} 											
											}
											echo '<td class="leidingevende dropable">'.$list_data['naam'].'</td><td class="leidingevende GSM">'.$list_data['GSM'].'</td><td>'.$button.'</td></tr>';
										} else {
											echo '<td class="leidingevende dropable"></td><td class="leidingevende GSM"></td><td>'.$button.'</td></tr>';
										}																	
									} else if ($lijsten->type == "calamiteiten") {
										if (array_key_exists('medewerker', $lijsten_arr['permanentie']['week_'.$i][$key])){
											$list_data = array('medewerker' => '', 'GSM' => '');
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['medewerker'] as $number){
												if(isset($number['naam'])){
													$list_data['medewerker'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="icon-remove-sign"></span><div class="hidden GSM">'.$personeel[$number['naam']].'</div></div>';
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
													$list_data['arbeiders-vroeg'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="icon-remove-sign"></span></div>';												
												} 
											}
										}
										if(array_key_exists('arbeiders-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['arbeiders-laat'] as $number){			
												if(isset($number['naam'])){
													$list_data['arbeiders-laat'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="icon-remove-sign"></span></div>';												
												} 
											}
										}
										if (array_key_exists('wegentoezichters-vroeg', $lijsten_arr['permanentie']['week_'.$i][$key])) {
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-vroeg'] as $number){
												if(isset($number['naam'])){
													$list_data['wegentoezichters-vroeg'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="icon-remove-sign"></span></div>';												
												} 												
											}
										}
										if (array_key_exists('wegentoezichters-laat', $lijsten_arr['permanentie']['week_'.$i][$key])){
											foreach ($lijsten_arr['permanentie']['week_'.$i][$key]['wegentoezichters-laat'] as $number){
												if(isset($number['naam'])){
													$list_data['wegentoezichters-laat'] .=  '<div class="element drag"><span class="naam">'.$number['naam'].'</span><span class="icon-remove-sign"></span></div>';												
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
			<div class="span3">
	    		<div id="scrollbar1">
	    			<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
					<div class="viewport">
						<div class="overview">
				    		<ul id="werknemers">	 
				    			<?php foreach($personeel_lijst as $key){?>
				    				<?php if($key['GSM'] != ''){?>
				    					<li class="drag well" draggable="true"><span class="icon-move" style="margin-right: 5px"></span><span class="naam"><?=$key['naam']?></span><span class="icon-remove-sign"></span><span class="icon-pencil"></span><a class="icon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="<?=$key['GSM']?>"></a><div class="hidden GSM"><?=$key['GSM']?></div></li>
				    				<?php } else {?>
				    					<li class="drag well" draggable="true"><span class="icon-move" style="margin-right: 5px"></span><span class="naam"><?=$key['naam']?></span><span class="icon-remove-sign"></span><span class="icon-pencil"></span></li>
				    			<?php }} ?>							
							</ul>	
							<div class="hidden" id="deleteurl">remove_personeelslid/<?=$lijsten->_id?></div>
							<div id="wn_toevoegen" style="margin-top: 10px;">
								<form id="wn_form" action="add_personeelslid/<?=$lijsten->_id?>" style="margin-bottom:0px;">
									<input type="text" id="personeel" placeholder="Naam toevoegen" required="true" name="naam" val=""><br />
									<input type="text" id="GSM" placeholder="GSM nummer toevoegen" required="true" name="gsmnummer" val="">
									<input type="submit" value="toevoegen" id="personeel_toevoegen" class="btn" style="margin: 0px 0px; width: 100%">
								</form>
							</div>
						</div>						
					</div>
					
				</div>
	    	</div>
	    </div>
		<div id="myModal_personeel" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    	<h3>Persoon bewerken</h3>
		    </div>
		    <div class="modal-body">
		    	<p>
		    	<form id="wn_edit_form" action="edit_permanentie/<?=$lijsten->_id?>">
		    		<label for="naam">Naam</label>
					<input type="text" id="personeel_bewerken" placeholder="Naam toevoegen"required="true" name="naam" val=""><br />
					<input type="hidden" id="personeel_old" placeholder="Naam toevoegen" name="old_naam" val="">
					<label for="gsmnummer">GSM</label>
					<input type="text" id="GSM_bewerken" placeholder="GSM nummer toevoegen" required="true" name="gsmnummer" val="">
					<input type="hidden" id="GSM_old" placeholder="GSM nummer toevoegen" name="old_gsmnummer" val="">
				</form>
				</p>
		    </div>
		    <div class="modal-footer">
		    	<a href="#" class="btn close_modal">Sluiten</a>
		    	<a href="#" class="btn btn-primary save_modal">Bewaren</a>
		    </div>
		</div>	
		<div id="myModal_add_row" class="modal hide fade" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		    <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    	<h3>Rij toevoegen</h3>
		    </div>
		    <div class="modal-body">
		    	<p>
		    		<form id="week_add_form" action="permanentie_week_add/<?=$lijsten->_id?>">
				    	<label for="naam">Tussendatum tussen (<span id="begin_datum_min"></span> en <span id="eind_datum_max"></span>)</label>
						<input type="text" id="tussendatum" required="true" placeholder="tussendatum toevoegen" name="naam" val="" >
						<input type="hidden" id="week" name="week" val="">
						<input type="hidden" id="begin_datum" name="week" val="">
						<input type="hidden" id="eind_datum" name="week" val="">
					</form>
				</p>
		    </div>
		    <div class="modal-footer">
		    	<a href="#" class="btn close_modal">Sluiten</a>
		    	<a href="#" class="btn btn-primary save_modal">Bewaren</a>
		    </div>
		</div>	
		</div>
	<?php } else { ?>
		<h4>Deze pagina is niet toegangelijk!</h4>
	<?php } ?>
<!--<![endif]-->