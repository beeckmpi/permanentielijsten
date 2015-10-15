<h2>Gebruiker Bewerken</h2>
    <?=$this->form->create($user); ?>
    	<div class="left-form">
    		<?=$this->form->field('username_reference', array('type' => 'hidden', 'value' => $user->username));?>
	    	<div style="position:relative">
	       		<?=$this->form->field('username', array('label' => 'Gebruikersnaam', 'required' => 'true', 'placeholder' => 'Gebruikersnaam', 'size' => 50, 'value' => $user->username)); ?>
	        <div id="UsernameOkText" class="error-msg"></div></div>
	        <?=$this->form->field('user_ok', array('type' => 'hidden', 'value' => '1'));?>
	        <?=$this->form->field('password', array('label' => 'Wachtwoord', 'placeholder' => 'Wachtwoord', 'type' => 'password', 'size' => 50)); ?>
	        <div style="position:relative">
	        <?=$this->form->field('repeat_password', array('label' => 'Wachtwoord herhalen', 'placeholder' => 'Wachtwoord Herhalen', 'type' => 'password', 'size' => 50)); ?>
	        <div id="passwordOkText" class="error-msg"></div></div>
	        <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '1'));?>
	        <?=$this->form->field('location', array('label' => 'Locatie', 'required' => true, 'list' => $locaties, 'type' => 'select', 'selected' => $user->location));?>
        </div>
        <div class="right-form">
        	<?=$this->form->field('voornaam', array('label' => 'Voornaam', 'required' => 'true', 'placeholder' => 'Voornaam', 'size' => 50, 'value' => $user->voornaam)); ?>
        	<?=$this->form->field('achternaam', array('label' => 'Achternaam', 'required' => 'true', 'placeholder' => 'Achternaam', 'size' => 50, 'value' => $user->achternaam)); ?>
        	 <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '0'));?>
	        <?php $rollen =	array(
			        				'gebruiker'		=> 'Gebruiker', 
			        				'administrator' => 'Administrator'			        				
								);
			?>
	        <?=$this->form->field('rol', array('label' => 'Rol', 'required' => true, 'list' => $rollen, 'type' => 'select'));?>
        </div>
        <?=$this->form->submit('Gebruiker Bewerken', array('id' => 'gebruiker_bewerken')); ?>
        
    <?=$this->form->end(); ?>