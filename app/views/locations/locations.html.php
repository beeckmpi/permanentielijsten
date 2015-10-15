<h2>Locaties</h2>
<?=$this->form->create($location, array('action' => 'locations/add', 'id' => 'locations', 'class' => 'form-horizontal')); ?>
	<input type="text" name="locatie" placeholder="locatie" size="50" />
   
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
	<select name="provincie" required="true" style="width: 340px;">
		<?php foreach ($locaties as $key => $value) { ?>
			<option value="<?=$key?>"><?=$value?></option>			
		<?php } ?>		
	</select>
	
	<?=$this->form->submit('Locatie toevoegen', array('id' => 'locatie_toevoegen')); ?>
	
<?=$this->form->end(); ?>
    <table id="users_table">
    	<thead>
    		<tr>
	    		<th>Locatie</th>
	    		<th>Provincie</th>
	    		<th width="5%">Actief</th>
	    		<th width="5%">&nbsp;</th>
    		</tr>
    	</thead>
    	<tbody>
    		 <?php foreach ($locations as $locatie) { ?>
    		 	<?php 
    		 		if($locatie->actief == 'ja'){
    		 			$activeren = 'deactiveren';
    		 		} else {
    		 			$activeren = 'activeren';
    		 		}
				?>
	            <tr><td><?=$locatie->locatie; ?></td><td><?=$locatie->provincie;?></td><td><?=$locatie->actief;?></td><td><?=$this->html->link($activeren, '/producten/locaties/'.$activeren.'/'.$locatie->_id);?></td></tr>
	        <?php } ?>
    	</tbody>
    </table>