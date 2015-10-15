<h2>Gebruiker toevoegen</h2>
    <?=$this->form->create($user); ?>
    	<div class="left-form">
	    	<div style="position:relative">
	       		<?=$this->form->field('username', array('label' => 'Gebruikersnaam', 'required' => 'true', 'placeholder' => 'Gebruikersnaam', 'size' => 50)); ?>
	        <div id="UsernameOkText" class="error-msg"></div></div>
	        <?=$this->form->field('user_ok', array('type' => 'hidden', 'value' => '1'));?>
	        <?=$this->form->field('password', array('label' => 'Wachtwoord', 'placeholder' => 'Wachtwoord', 'required' => true, 'type' => 'password', 'size' => 50)); ?>
	        <div style="position:relative">
	        <?=$this->form->field('repeat_password', array('label' => 'Wachtwoord herhalen', 'placeholder' => 'Wachtwoord Herhalen', 'required' => true, 'type' => 'password', 'size' => 50)); ?>
	        <div id="passwordOkText" class="error-msg"></div></div>
	        <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '0'));?>
	        <?=$this->form->field('location', array('label' => 'Locatie', 'required' => true, 'list' => $locaties, 'type' => 'select', 'style' => 'width: 340px;'));?>
        </div>
        <div class="right-form">
        	<?=$this->form->field('voornaam', array('label' => 'Voornaam', 'required' => 'true', 'placeholder' => 'Voornaam', 'size' => 50)); ?>
        	<?=$this->form->field('achternaam', array('label' => 'Achternaam', 'required' => 'true', 'placeholder' => 'Achternaam', 'size' => 50)); ?>
        	 <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '0'));?>
	        <?php $rollen =	array(
			        				'gebruiker'		=> 'Gebruiker', 
			        				'administrator' => 'Administrator'			        				
								);
			?>
	        <?=$this->form->field('rol', array('label' => 'Rol', 'required' => true, 'list' => $rollen, 'type' => 'select'));?>
        </div>
        <?=$this->form->submit('Gebruiker aanmaken', array('id' => 'gebruiker_toevoegen')); ?>
        
    <?=$this->form->end(); ?>