<div class="container-fluid" style="width: 470px; margin: 0 auto;">
        <div class="row">
            <div class="col-md-12">
                <h2>Wachtwoord Bewerken</h2>
                    <?=$this->form->create($user); ?>
                	    <div class="form-group"> 		
                		   <?=$this->form->field('username', array('type' => 'hidden', 'value' => $user->username)); ?>
                		</div>  
                		<div class="form-group"> 
            	           <?=$this->form->field('password', array('label' => 'Oud wachtwoord', 'placeholder' => 'Wachtwoord', 'type' => 'password', 'size' => 50, 'class' => "form-control")); ?>
            	        </div>  
                        <div class="form-group"> 
            	           <?=$this->form->field('new_password', array('label' => 'Nieuw wachtwoord', 'placeholder' => 'Nieuw wachtwoord', 'type' => 'password', 'size' => 50, 'class' => "form-control")); ?>
            	        </div>  
                        <div class="form-group"> 
            	           <?=$this->form->field('repeat_password', array('label' => 'Nieuw wachtwoord herhalen', 'placeholder' => 'Nieuw wachtwoord herhalen', 'type' => 'password', 'size' => 50, 'class' => "form-control")); ?>
            	           <div id="passwordOkText" class="error-msg"></div>
            	        </div>
            	        <div class="form-group"> 
            	           <?=$this->form->field('password_ok', array('type' => 'hidden', 'value' => '1'));?>
                           <?=$this->form->submit('Wachtwoord Bewerken', array('id' => 'wachtwoord_bewerken', 'class' => 'btn btn-info ')); ?>    
                        </div>    
                    <?=$this->form->end(); ?>
                </div>
         </div>
</div>