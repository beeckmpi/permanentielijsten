<h2>Districten toevoegen</h2>
<?=$this->form->create($location, array('id' => 'locations', 'class' => '')); ?>
	<?=$this->form->field('district', array('label' => 'District', 'required' => 'true', 'placeholder' => 'District', 'size' => 50)); ?>	
	<?=$this->form->field('districtnummer', array('label' => 'Districtnummer', 'required' => 'true', 'placeholder' => 'Districtnummer', 'size' => 50)); ?>	
	<?php $locaties = array(
			        		'' 		=> '--Selecteer--', 
			        		'AWVA' 	=> 'Antwerpen', 
			        		'AWVB' 	=> 'Brussel', 
			        		'AWVL' 	=> 'Limburg', 
			        		'AWVOV' => 'Oost-Vlaanderen', 
			        		'PCO' 	=> 'PCO', 
			        		'AWVVB' => 'Vlaams Brabant', 
			        		'AWVWV' => 'West-Vlaanderen'
								);
			?>
	<label for="provincie">Provincie</label>
	<select name="provincie" required="true" style="width: 220px;">
		<?php foreach ($locaties as $key => $value) { ?>
			<option value="<?=$key?>"><?=$value?></option>			
		<?php } ?>		
	</select>
	<?=$this->form->field('telefoonnummer', array('label' => 'Telefoonnummer', 'required' => 'true', 'placeholder' => 'Telefoonnummer', 'size' => 50)); ?>	
	<?=$this->form->field('doorschakelnummer', array('label' => 'Doorschakelnummer', 'placeholder' => 'Doorschakelnummer', 'size' => 75)); ?>	
	
	<?=$this->form->submit('District toevoegen', array('id' => 'locatie_toevoegen')); ?>
	
<?=$this->form->end(); ?>