 <script>
$(function() {
	$('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });
});
</script>
<h3>Lijsten toevoegen</h3>
<?=$this->form->create($lijsten, array('class' => 'no-shadow ', 'id' => 'add_lijst_form', 'name' => 'add_lijst_form')); ?>
	<?php 
	echo '<div style="max-width: 500px;">';
	foreach($locaties as $key => $locatie) {
		echo '<div style="display:inline-block; width: 249px;"><input type=checkbox value='.$key.' name=district[] style="display:inline-block; margin:-3px 5px 0px 5px;"><label for=district[] style="display:inline-block">'.$locatie.'</label></div>';		
	} 
	echo '</div>';
	$types = array(
	  				'' 			=> '--Selecteer--', 
	   				'calamiteiten' 	=> 'Calamiteiten (permanentie)', 
	   				'winterdienst'	=> 'Winterdienst', 
			);
		?>
	<?=$this->form->field('type', array('label' => 'Type', 'required' => true, 'list' => $types, 'type' => 'select'));?>	
	<?php $types = array(
	  				'' 			=> '--Selecteer--', 
	   				'medewerkers' 		=> 'Beurtrol Districtsmedewerkers', 
	   				'leidinggevenden'	=> 'Beurtrol Leidinggevenden', 
	   				'provinciaal'		=> 'Beurtrol Provinciaal Coördinator',
	   				'EM'				=> 'Beurtol Sectie EM'
			);
		?>
	<?=$this->form->field('subtype', array('label' => 'Subtype', 'required' => true, 'list' => $types, 'type' => 'select'));?>
	<?=$this->form->field('Startdatum', array('label' => 'Startdatum', 'required' => 'true', 'type' =>'input', 'size' => 10, 'class' => 'datepicker', 'value' => '')); ?>
	<?=$this->form->field('Einddatum', array('label' => 'Einddatum', 'required' => 'true', 'type' =>'input', 'size' => 10, 'class' => 'datepicker', 'value' => '')); ?>
	<button type="submit" class="btn">toevoegen</button>
<?=$this->form->end(); ?>