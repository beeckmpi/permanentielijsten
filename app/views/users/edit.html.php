<h2>Gebruiker Bewerken</h2>
    <div class="row">
    <?=$this->form->create($user); ?>
    	<div class="col-md-4">
    		<?=$this->form->field('username_reference', array('type' => 'hidden', 'value' => $user->username));?>
    		<div class="form-group">
    	    	<div style="position:relative">
    	       		<?=$this->form->field('username', array('label' => 'Gebruikersnaam', 'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Gebruikersnaam', 'size' => 50, 'value' => $user->username)); ?>
    	        <div id="UsernameOkText" class="error-msg"></div></div>
    	    </div>
    	    <div class="form-group">
    	        <?=$this->form->field('user_ok', array('type' => 'hidden', 'value' => '1'));?>
    	        <?=$this->form->field('password', array('label' => 'Wachtwoord', 'class' => 'form-control', 'placeholder' => 'Wachtwoord', 'type' => 'password', 'size' => 50)); ?>
	        </div>
	        <div class="form-group">
    	        <div style="position:relative">
    	        <?=$this->form->field('repeat_password', array('label' => 'Wachtwoord herhalen', 'class' => 'form-control', 'placeholder' => 'Wachtwoord Herhalen', 'type' => 'password', 'size' => 50)); ?>
    	        <div id="passwordOkText" class="error-msg"></div></div>
	        </div>
	        <div class="form-group">
    	        <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '1'));?>
    	        <?=$this->form->field('location', array('label' => 'Locatie', 'class' => 'form-control', 'required' => true, 'list' => $locaties, 'type' => 'select', 'selected' => $user->location));?>
	        </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
        	   <?=$this->form->field('voornaam', array('label' => 'Voornaam', 'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Voornaam', 'size' => 50, 'value' => $user->voornaam)); ?>
        	</div>
        	<div class="form-group">
        	   <?=$this->form->field('achternaam', array('label' => 'Achternaam', 'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Achternaam', 'size' => 50, 'value' => $user->achternaam)); ?>
        	</div>
        	<div class="form-group">
        	   <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '0'));?>
        	</div>
        	<div class="form-group">
    	        <?php $rollen =	array(
    			        				'gebruiker'		=> 'Gebruiker', 
    			                         'personeel'     => 'Personeel',
    			        				'administrator' => 'Administrator'			        				
    								);
    			?>
    	        <?=$this->form->field('rol', array('label' => 'Rol', 'class' => 'form-control', 'required' => true, 'list' => $rollen, 'type' => 'select'));?>
	        </div>
        </div>
        <div class="col-md-10">
        <?=$this->form->submit('Gebruiker Bewerken', array('id' => 'gebruiker_bewerken', 'class' => 'btn btn-info')); ?>
        </div>
        
    <?=$this->form->end(); ?>
    </div>