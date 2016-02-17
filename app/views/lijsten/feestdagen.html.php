<?php if (($login['rol'] == 'administrator') ){ ?>
	<?php 
	if(!is_object($feestdagen['data'])) {
		$feestdagen['data'] = array(
			'Nieuwjaar' => date('Y').'-01-01',
			'Paasmaandag' => date('Y').'-04-01',
			'Feest_van_de_arbeid' => date('Y').'-05-01',
			'olhh' => date('Y').'-05-01',
			'Pinkstermaandag' => date('Y').'-05-15',
			'Feest_van_de_vlaamse_gemeenschap' => date('Y').'-07-11',
			'nationale_feestdag' => date('Y').'-07-21',
			'olvh' => date('Y').'-08-15',
			'Allerheiligen' => date('Y').'-11-01',
			'Allerzielen' => date('Y').'-11-02',
			'Wapenstilstand' => date('Y').'-11-11',
			'Koningsdag' => date('Y').'-11-15',
			'Kerstdag' => date('Y').'-12-25',
			'Kerstverlof1' => date('Y').'-12-26',
			'Kerstverlof2' => date('Y').'-12-27',
			'Kerstverlof3' => date('Y').'-12-28',
			'Kerstverlof4' => date('Y').'-12-29',
			'Kerstverlof5' => date('Y').'-12-30',
			'Kerstverlof6' => date('Y').'-12-31',
			
		);
	} else {
		foreach($feestdagen['data'] as $feestdag => $datum){
			$feestdagen['data'][$feestdag] =  date('Y-m-d', $datum->sec);
		}
	}
	$jaren = array();
	for ($i=2013; $i <= (date('Y')+2); $i++){
		$jaren[$i] = $i;	
	}?>
	<h2>Feestdagen Bewerken</h2>
	<div><?=$this->form->create($feestdagen); ?>
	    <div class=row>
            <div class="col-md-4 col-sm-6">
        	    <?php if ($message !== ''){ ?>
        		  <div class="error" id="message" style="margin-bottom: 20px"><?php echo html_entity_decode($message)?></div>
        		<?php } else { ?>
        		    <div class="error" id="message" style="margin-bottom: 20px; display:none"></div>
        		<?php } ?>
        	</div>
        </div>
		<div class=row>
            <div class="col-md-3 col-sm-6">
		       <?=$this->form->field('Jaar', array('id' => 'Jaar', 'class' => 'form-control', 'label' => 'Jaar', 'required' => true, 'list' => $jaren, 'type' => 'select', 'selected' => date('Y')));?>
		    </div>
		</div>
		<div class=row>
		    <div class="col-md-3 col-sm-6">
			    <div class="form-group">
				    <?=$this->form->field('Nieuwjaar', array('type' => 'date', 'class' => 'form-control', 'label' => 'Nieuwjaar', 'value' => $feestdagen['data']['Nieuwjaar'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
				<div class="form-group">
				    <?=$this->form->field('Paasmaandag', array('type' => 'date', 'class' => 'form-control', 'label' => 'Paasmaandag', 'value' => $feestdagen['data']['Paasmaandag'], 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Feest_van_de_arbeid', array('type' => 'date', 'class' => 'form-control', 'label' => 'Feest van de arbeid','disabled' => 'disabled', 'value' => $feestdagen['data']['Feest_van_de_arbeid'], 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('olhh', array('type' => 'date', 'class' => 'form-control', 'label' => 'Onze Lieve Heer Hemelvaart', 'value' => $feestdagen['data']['olhh'], 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Pinkstermaandag', array('type' => 'date', 'class' => 'form-control', 'label' => 'Pinkstermaandag', 'value' => $feestdagen['data']['Pinkstermaandag'], 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Feest_van_de_vlaamse_gemeenschap', array('type' => 'date', 'class' => 'form-control', 'label' => 'Feest van de Vlaamse Gemeenschap', 'value' => $feestdagen['data']['Feest_van_de_vlaamse_gemeenschap'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">                    
				    <?=$this->form->field('nationale_feestdag', array('type' => 'date', 'class' => 'form-control', 'label' => 'Nationale Feestdag', 'value' => $feestdagen['data']['nationale_feestdag'], 'disabled' => 'disabled', 'size' => 50)); ?>
				</div>  
			</div>
			<div class="col-md-3 col-sm-6">	
                <div class="form-group">		
				    <?=$this->form->field('olvh', array('type' => 'date', 'class' => 'form-control', 'label' => 'Onze-Lieve-Vrouw Hemelvaart', 'value' => $feestdagen['data']['olvh'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Allerheiligen', array('type' => 'date', 'class' => 'form-control', 'label' => 'Allerheiligen', 'value' => $feestdagen['data']['Allerheiligen'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">	
				    <?=$this->form->field('Allerzielen', array('type' => 'date', 'class' => 'form-control', 'label' => 'Allerzielen', 'value' => $feestdagen['data']['Allerzielen'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Wapenstilstand', array('type' => 'date', 'class' => 'form-control', 'label' => 'Wapenstilstand', 'value' => $feestdagen['data']['Wapenstilstand'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">
                    <?=$this->form->field('Koningsdag', array('type' => 'date', 'class' => 'form-control', 'label' => 'Koningsdag', 'value' => $feestdagen['data']['Koningsdag'],'disabled' => 'disabled', 'size' => 50)); ?>
                </div>
                <div class="form-group">
                    <?=$this->form->field('Kerstdag', array('type' => 'date', 'class' => 'form-control', 'label' => 'Kerstdag', 'value' => $feestdagen['data']['Kerstdag'],'disabled' => 'disabled', 'size' => 50)); ?>
                </div>
                <div class="form-group">
                    <?=$this->form->field('Kerstverlof1', array('type' => 'date', 'class' => 'form-control', 'label' => 'Kerstverlof', 'value' => $feestdagen['data']['Kerstverlof1'],'disabled' => 'disabled', 'size' => 50)); ?>
                </div>			
			</div>
			<div class="col-md-3 col-sm-6">
                <div class="form-group">			
				    <?=$this->form->field('Kerstverlof2', array('type' => 'date', 'class' => 'form-control', 'label' => 'Kerstverlof', 'value' => $feestdagen['data']['Kerstverlof2'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Kerstverlof3', array('type' => 'date', 'class' => 'form-control', 'label' => 'Kerstverlof', 'value' => $feestdagen['data']['Kerstverlof3'],'disabled' => 'disabled', 'size' => 50)); ?>
                </div>
                <div class="form-group">			
				    <?=$this->form->field('Kerstverlof4', array('type' => 'date', 'class' => 'form-control', 'label' => 'Kerstverlof', 'value' => $feestdagen['data']['Kerstverlof4'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Kerstverlof5', array('type' => 'date', 'class' => 'form-control', 'label' => 'Kerstverlof', 'value' => $feestdagen['data']['Kerstverlof5'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
                <div class="form-group">
				    <?=$this->form->field('Kerstverlof6', array('type' => 'date', 'class' => 'form-control', 'label' => 'Kerstverlof', 'value' => $feestdagen['data']['Kerstverlof6'],'disabled' => 'disabled', 'size' => 50)); ?>
				</div>
			</div>			      
		</div>    
		<div class="row">
		    <div class="col-md-3 col-sm-6">
		        <div class="form-group">                    
                    <?=$this->form->submit('Feestdagen bewaren', array('id' => 'feestagen_bewaren', 'class' => 'btn btn-primary')); ?>
                </div>
		    </div>
		</div>
    	<?=$this->form->end(); ?>    		
	</div>
<?php } ?>