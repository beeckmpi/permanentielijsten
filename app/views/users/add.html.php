<h2>Gebruiker toevoegen</h2>
    <?=$this->form->create($user); ?>
    <div class="row">
    	<div class="col-md-4">
	    	<div style="position:relative">
	    	    <div class="form-group">
    	       		<?=$this->form->field('username', array('label' => 'Gebruikersnaam', 'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Gebruikersnaam', 'size' => 50)); ?>   
    	            <div id="UsernameOkText" class="error-msg"></div></div>
	            </div>
	            <div class="form-group">
        	        <?=$this->form->field('user_ok', array('type' => 'hidden', 'value' => '1'));?>
        	        <?=$this->form->field('password', array('label' => 'Wachtwoord', 'class' => 'form-control', 'placeholder' => 'Wachtwoord', 'required' => true, 'type' => 'password', 'size' => 50)); ?>
        	    </div>
        	    <div class="form-group">
        	        <div style="position:relative">
        	        <?=$this->form->field('repeat_password', array('label' => 'Wachtwoord herhalen', 'class' => 'form-control', 'placeholder' => 'Wachtwoord Herhalen', 'required' => true, 'type' => 'password', 'size' => 50)); ?>
        	        <div id="passwordOkText" class="error-msg"></div></div>
        	    </div>
        	    <div class="form-group">
        	        <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '0'));?>
        	    </div>
        	    <div class="form-group">
        	        <?=$this->form->field('location', array('label' => 'Locatie', 'class' => 'form-control', 'required' => true, 'list' => $locaties, 'type' => 'select', 'style' => 'width: 340px;'));?>
        	    </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
        	   <?=$this->form->field('voornaam', array('label' => 'Voornaam', 'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Voornaam', 'size' => 50)); ?>
        	</div>
        	<div class="form-group">
        	   <?=$this->form->field('achternaam', array('label' => 'Achternaam', 'class' => 'form-control', 'required' => 'true', 'placeholder' => 'Achternaam', 'size' => 50)); ?>
        	</div>
        	<div class="form-group">
        	   <?=$this->form->field('password_ok', array('type' => 'hidden', 'class' => 'form-control', 'value' => '0'));?>
        	</div>
	        <?php $rollen =	array(
			        				'gebruiker'		=> 'Gebruiker',
			        				'personeel'     => 'Personeel', 
			        				'administrator' => 'Administrator'			        				
								);
			?>
			<div class="form-group">
	           <?=$this->form->field('rol', array('label' => 'Rol', 'class' => 'form-control', 'required' => true, 'list' => $rollen, 'type' => 'select'));?>
	       </div>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <?=$this->form->submit('Gebruiker aanmaken', array('id' => 'gebruiker_toevoegen', 'class'=>'btn btn-info')); ?>
            </div>
        </div>
    </div>
    <?=$this->form->end(); ?>