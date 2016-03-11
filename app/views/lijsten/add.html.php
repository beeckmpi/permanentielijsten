 <script>
$(function() {
	$('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' });
});
</script>
<div class="container-fluid" style="width: 970px; margin: 0 auto;">
    <div class="row">
        <div class="col-md-12">
            <h3>Lijsten toevoegen</h3>
            <?=$this->form->create($lijsten, array('class' => 'no-shadow ', 'id' => 'add_lijst_form', 'name' => 'add_lijst_form')); ?>
            	<?php 
            	echo '<div class="checkbox">';                
            	foreach($locaties as $key => $locatie) {
            		echo '<div style="display:inline-block; width: 320px;margin: 0 0 5px"><input type=checkbox value='.$key.' name=district[] style="display:inline-block; margin:4px 10px 3px 0px;"><label for=district[] style="display:inline-block">'.$locatie.'</label></div>';		
            	} 
            	echo '</div>';
            	$types = array(
            	  				'' 			=> '--Selecteer--', 
            	   				'calamiteiten' 	=> 'Calamiteiten (permanentie)', 
            	   				'winterdienst'	=> 'Winterdienst', 
            			);
            		?>
                <div class="form-group">  
            	   <?=$this->form->field('type', array('label' => 'Type', 'class' => 'form-control', 'required' => true, 'list' => $types, 'type' => 'select'));?>
            	</div>
            	<div class="form-group">	
                	<?php $types = array(
                	  				'' 			=> '--Selecteer--', 
                	   				'medewerkers' 		=> 'Beurtrol Districtsmedewerkers', 
                	   				'leidinggevenden'	=> 'Beurtrol Leidinggevenden', 
                	   				'provinciaal'		=> 'Beurtrol Provinciaal Coördinator',
                	   				'EM'				=> 'Beurtol Sectie EM'
                			);
                		?>
                	<?=$this->form->field('subtype', array('label' => 'Subtype', 'class' => 'form-control', 'required' => true, 'list' => $types, 'type' => 'select'));?>
            	</div>
                <div class="form-group">    
            	   <?=$this->form->field('Startdatum', array('label' => 'Startdatum', 'required' => 'true','placeholder' => 'Startdatum',  'type' =>'input', 'size' => 10, 'class' => 'datepicker form-control', 'value' => '')); ?>
            	</div>
                <div class="form-group">    
            	   <?=$this->form->field('Einddatum', array('label' => 'Einddatum', 'required' => 'true', 'placeholder' => 'Einddatum', 'type' =>'input', 'size' => 10, 'class' => 'datepicker form-control', 'value' => '')); ?>
            	</div>
                <div class="form-group">    
            	   <button type="submit" class="btn btn-info">toevoegen</button>
                </div>   
            <?=$this->form->end(); ?>
        </div>
   </div>
</div>