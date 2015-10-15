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
			        		'AWVA' 	=> 'Antwerpen', 
			        		'AWVB' 	=> 'Brussel', 
			        		'AWVL' 	=> 'Limburg', 
			        		'AWVOV' => 'Oost-Vlaanderen', 
			        		'PCO' 	=> 'PCO', 
			        		'AWVVB' => 'Vlaams Brabant', 
			        		'AWVWV' => 'West-Vlaanderen'
								);
			?>
	<div style="display: inline-block">
	<label for="provincie">Provincie</label>
	<select name="provincie" style="width: 220px;">
		<?php foreach ($provincies as $key => $value) { ?>
			<option value="<?=$key?>"><?=$value?></option>			
		<?php } ?>		
	</select>	
	</div>
	<div style="display:inline-block">
	<?=$this->form->field('District', array('label' => 'District', 'list' => $locaties, 'type' => 'select', 'style' => 'display:inline-block'));?>
	</div>
<div class="row">
	<div class="span6 well">
			<div class="nav-header" style="font-size:larger">Permanente Wachtdienst (Calamiteiten)</div>
			<ul id="calamiteiten-lijst">				
				<?php foreach($calamiteiten as  $lijst) {?>
					<?php if ($lijst->subtype == "medewerkers") { ?>
						<lI><?=$this->html->link('Beurtrol Districtsmedewerkers '.$lijst->districtscode.' '.$lijst->district.' ('.date('Y', $lijst->Startdatum->sec).'-'.date('Y', $lijst->Einddatum->sec).')', 'lijsten/view/'.$lijst->type.'/'.$lijst->subtype.'/'.$lijst->districtscode.'/'.date('Y', $lijst->Startdatum->sec))?></lI>
					<?php } else if ($lijst->subtype == "leidinggevenden") {?>
						<?php if (strpos($lijst->district, 'Alle districten') !== FALSE){?>
							<lI><?=$this->html->link('Beurtrol leidinggevenden calamiteiten wachtdienst ('.$lijst->provincie.') ('.date('Y', $lijst->Startdatum->sec).'-'.date('Y', $lijst->Einddatum->sec).')', 'lijsten/view/'.$lijst->type.'/'.$lijst->subtype.'/'.$lijst->districtscode.'/'.date('Y', $lijst->Startdatum->sec))?></lI>
						<?php } else { ?>
							<lI><?=$this->html->link('Beurtrol Leidinggevenden '.$lijst->districtscode.' '.$lijst->district.'  ('.date('Y',$lijst->Startdatum->sec).'-'.date('Y',$lijst->Einddatum->sec).')','lijsten/view/'.$lijst->type.'/'.$lijst->subtype.'/'.$lijst->districtscode.'/'.date('Y', $lijst->Startdatum->sec))?></lI>
						<?php } ?>
					<?php } else {?>
						<lI><?=$this->html->link('Beurtrol Permanentie Sectie EM ('.$lijst->provincie.') ('.date('Y', $lijst->Startdatum->sec).'-'.date('Y', $lijst->Einddatum->sec).')', 'lijsten/view/'.$lijst->type.'/'.$lijst->subtype.'/'.$lijst->districtscode.'/'.date('Y', $lijst->Startdatum->sec))?></lI>
				<?php }} ?>
			</ul>
	</div>
	<div class="span6 well">
			<div class="nav-header" style="font-size:larger">Winterdienst</div>
			<ul id="winterdienst-lijst">			
				<?php foreach($winterdienst as $lijst) {?>
					<?php if ($lijst->subtype == "medewerkers") { ?>
						<lI><?=$this->html->link('Beurtrol Districtsmedewerkers '.$lijst->districtscode.' '.$lijst->district.' ('.date('Y', $lijst->Startdatum->sec).'-'.date('Y', $lijst->Einddatum->sec).')', 'lijsten/view/'.$lijst->type.'/'.$lijst->subtype.'/'.$lijst->districtscode.'/'.date('Y', $lijst->Startdatum->sec))?></lI>
					<?php } else if ($lijst->subtype == "leidinggevenden") {?>
						<lI><?=$this->html->link('Beurtrol Leidinggevenden '.$lijst->districtscode.' '.$lijst->district.' ('.date('Y', $lijst->Startdatum->sec).'-'.date('Y', $lijst->Einddatum->sec).')', 'lijsten/view/'.$lijst->type.'/'.$lijst->subtype.'/'.$lijst->districtscode.'/'.date('Y', $lijst->Startdatum->sec))?></lI>
					<?php } else if ($lijst->subtype == "provinciaal") {?>
						<lI><?=$this->html->link('Beurtrol Provinciaal Coördinator  ('.$lijst->provincie.') ('.date('Y', $lijst->Startdatum->sec).'-'.date('Y', $lijst->Einddatum->sec).')', 'lijsten/view/'.$lijst->type.'/'.$lijst->subtype.'/'.$lijst->districtscode.'/'.date('Y', $lijst->Startdatum->sec))?></lI>
						
				<?php }} ?>
			</ul>
	</div>
</div>