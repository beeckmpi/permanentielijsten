<div class="container-fluid" style="width: 970px; margin: 0 auto;">
    <div class="row">
        <div class="col-md-12">
            <h2>Districten toevoegen</h2>
            <?=$this->form->create($location, array('id' => 'locations', 'class' => '')); ?>
                <div class="form-group">
            	   <?=$this->form->field('district', array('label' => 'District',  'class' => 'form-control', 'required' => 'true', 'placeholder' => 'District', 'size' => 50)); ?>
            	</div>	
            	<div class="form-group">
            	   <?=$this->form->field('districtnummer', array('label' => 'Districtnummer',  'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Districtnummer', 'size' => 50)); ?>	
            	</div> 
                <div class="form-group">
                	<?php $locaties = array(
                			        		'' 		=> '--Selecteer--', 
                			        		'WA' 	=> 'Antwerpen', 
                			        		'AWVB' 	=> 'Brussel', 
                			        		'EMT'   => 'EMT',
                			        		'AWVL' 	=> 'Limburg', 
                			        		'AWVOV' => 'Oost-Vlaanderen', 
                			        		'PCO' 	=> 'PCO', 
                			        		'AWVVB' => 'Vlaams Brabant', 
                			        		'AWVWV' => 'West-Vlaanderen'
                								);
                			?>
                	<label for="provincie">Provincie</label>
                	<select name="provincie" required="true" style="width: 220px;" class="form-control">
                		<?php foreach ($locaties as $key => $value) { ?>
                			<option value="<?=$key?>"><?=$value?></option>			
                		<?php } ?>		
                	</select>
            	</div> 
                <div class="form-group">
            	   <?=$this->form->field('telefoonnummer', array('label' => 'Telefoonnummer', 'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Telefoonnummer', 'size' => 50)); ?>
            	</div> 
                <div class="form-group">	
            	   <?=$this->form->field('doorschakelnummer', array('label' => 'Doorschakelnummer', 'class' => 'form-control', 'placeholder' => 'Doorschakelnummer', 'size' => 75)); ?>	
            	</div> 
                <div class="form-group">
            	   <?=$this->form->submit('District toevoegen', array('id' => 'locatie_toevoegen', 'class' => 'btn btn-info')); ?>
            	</div> 
            <?=$this->form->end(); ?>
       </div>
   </div>
</div>