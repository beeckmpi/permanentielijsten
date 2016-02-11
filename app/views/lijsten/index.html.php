<h2>Lijsten</h2>
	<?php $types = array(0 => date('d-m-Y'));
		for($start_year = 2013; $start_year<= date('Y',mktime(0, 0, 0, date("m"), date("d"), date("Y")+3)); $start_year++){
			$types[$start_year] = date('d-m-Y',mktime(0, 0, 0, 1, 1, $start_year));
		}
		?>
	<div style="display:inline-block">
		<?=$this->form->field('type', array('label' => 'Lijst van', 'list' => $types, 'type' => 'select', 'id' => 'lijst_van'));?>
	</div>
	<?php $provincies = array(
			        		0 		=> 'Alle provincies', 
			        		'WA' 	=> 'Antwerpen', 
			        		'AWVB' 	=> 'Brussel', 
			        		'AWVL' 	=> 'Limburg', 
			        		'WOV' => 'Oost-Vlaanderen', 
			        		'PCO' 	=> 'PCO', 
			        		'AWVVB' => 'Vlaams Brabant', 
			        		'AWVWV' => 'West-Vlaanderen'
								);
			?>
	<div style="display: inline-block">
	<label for="provincie">Provincie</label>
	<select name="provincie" style="width: 220px;">
		<?php foreach ($provincies as $key => $value) { ?>
			<?php if ($login['provincie'] == $key) {
				$select = 'selected="selected';
			} else {
				$select = '';
			}?> 
			<option value="<?=$key?>" <?=$select; ?>><?=$value?></option>			
		<?php } ?>		
	</select>	
	</div>
	<div style="display:inline-block">
	<?=$this->form->field('District', array('label' => 'District', 'list' => $locaties, 'type' => 'select', 'style' => 'display:inline-block'));?>
	</div>
<div class="row lists">
	<div class="span6 ">
			<div class="nav-header" style="font-size:larger">Calamiteiten</div>
							
				<?php foreach($calamiteiten as  $lijst) {?>
				    <?php if ($lijst->subtype == "medewerkers") {
				        $medewerkers[] = $lijst;
				    } else if ($lijst->subtype == "leidinggevenden") {
				        $leidinggevenden[] = $lijst;
				    }} ?>
			<ul id="calamiteiten-provinciaal-coordinator-lijst">
				<?php foreach ($leidinggevenden as $key => $value){ ?>
				    <lI class="provinciaal_coordinator"><?=$this->html->link('Beurtrol Provinciaal Coördinator Permanentie '.str_replace('Alle districten ', '', $value->district).' ('.date('Y', $value->Startdatum->sec).'-'.date('Y', $value->Einddatum->sec).')', 'lijsten/view/'.$value->type.'/'.$value->subtype.'/'.$value->districtscode.'/'.date('Y', $value->Startdatum->sec))?></lI>
				<?php } ?>
		  </ul>
		  <ul id="calamiteiten-districtmedewerkers-lijst">
				<?php foreach ($medewerkers as $key => $value){?>
					<lI><?=$this->html->link('Beurtrol Districtsmedewerkers '.$value->districtscode.' '.$value->district.' ('.date('Y', $value->Startdatum->sec).'-'.date('Y', $value->Einddatum->sec).')', 'lijsten/view/'.$value->type.'/'.$value->subtype.'/'.$value->districtscode.'/'.date('Y', $value->Startdatum->sec))?></lI>
				<?php }?>               
			</ul>
	</div>
	<div class="span6 ">
			<div class="nav-header" style="font-size:larger">Winterdienst</div>		
				<?php 
				$medewerkers = array();
                $leidinggevenden = array();
                $provinciaal = array();
				foreach($winterdienst as $lijst) {?>
				    <?php 
                    if ($lijst->subtype == "provinciaal") {
                        $provinciaal[] = $lijst;
                    } else if ($lijst->subtype == "medewerkers") {
                        $medewerkers[] = $lijst;
                    } else if ($lijst->subtype == "leidinggevenden") {
                        $leidinggevenden[] = $lijst;
                }} ?>	
		  <ul id="winterdienst-provinciaal-coordinator-lijst">
                <?php foreach ($provinciaal as $key => $value){ ?>
                    <lI class="provinciaal_coordinator"><?=$this->html->link('Beurtrol Provinciaal Coördinator Permanentie '.str_replace('Alle districten ', '', $value->district).' ('.date('Y', $value->Startdatum->sec).'-'.date('Y', $value->Einddatum->sec).')', 'lijsten/view/'.$value->type.'/'.$value->subtype.'/'.$value->districtscode.'/'.date('Y', $value->Startdatum->sec))?></lI>
                <?php } ?>
          </ul>
		  <ul id="winterdienst-leidinggevenden-lijst">
                <?php foreach ($leidinggevenden as $key => $value){ ?>
                    <lI class="provinciaal_coordinator"><?=$this->html->link('Beurtrol Leidinggevenden '.str_replace('Alle districten ', '', $value->district).' ('.date('Y', $value->Startdatum->sec).'-'.date('Y', $value->Einddatum->sec).')', 'lijsten/view/'.$value->type.'/'.$value->subtype.'/'.$value->districtscode.'/'.date('Y', $value->Startdatum->sec))?></lI>
                <?php } ?>
          </ul>
          <ul id="winterdienst-districtmedewerkers-lijst">
                <?php foreach ($medewerkers as $key => $value){?>
                    <lI><?=$this->html->link('Beurtrol Districtsmedewerkers '.$value->districtscode.' '.$value->district.' ('.date('Y', $value->Startdatum->sec).'-'.date('Y', $value->Einddatum->sec).')', 'lijsten/view/'.$value->type.'/'.$value->subtype.'/'.$value->districtscode.'/'.date('Y', $value->Startdatum->sec))?></lI>
                <?php }?>               
          </ul>    
	</div>
</div>