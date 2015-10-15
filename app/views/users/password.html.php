<h2>Wachtwoord Bewerken</h2>
    <?=$this->form->create($user); ?>
    	<div class="left-form">    		
    		<?=$this->form->field('username', array('type' => 'hidden', 'value' => $user->username)); ?>
	        <?=$this->form->field('password', array('label' => 'Oud wachtwoord', 'placeholder' => 'Wachtwoord', 'type' => 'password', 'size' => 50)); ?>
	        <div style="position:relative">
	        <?=$this->form->field('new_password', array('label' => 'Nieuw wachtwoord', 'placeholder' => 'Nieuw wachtwoord', 'type' => 'password', 'size' => 50)); ?>
	        <?=$this->form->field('repeat_password', array('label' => 'Nieuw wachtwoord herhalen', 'placeholder' => 'Nieuw wachtwoord herhalen', 'type' => 'password', 'size' => 50)); ?>
	        <div id="passwordOkText" class="error-msg"></div></div>
	        <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '1'));?>
        </div>
        <?=$this->form->submit('Wachtwoord Bewerken', array('id' => 'wachtwoord_bewerken')); ?>        
    <?=$this->form->end(); ?>