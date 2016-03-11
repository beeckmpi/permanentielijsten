<div class="container-fluid" style="width: 470px; margin: 0 auto;">
    <div class="row">
        <div class="col-md-12">
            <h2>Gebruiker: <?=$user->username?></h2>
            <div class="edit-user" style="padding-top: 12px"><?=$this->html->link('bewerken', '/users/edit/'.$user->username, array('escape' => false, 'class' => 'btn btn-info btn-xs'));?></div>
            
            <section id="user-view" class="shadow" style="display:block; margin-top:10px;">
            	<div>
            		<div class="view-label">Gebruikersnaam</div>
            		<div class="view-data"><?=$user->username;?></div>
            	</div>
            	<?php if($user->voornaam){?>
            	<div>
            		<div class="view-label">Naam</div>
            		<div class="view-data"><?=$user->voornaam;?> <?=$user->achternaam;?></div>
            	</div>
            	<?php }?>
            	<div>
            		<div class="view-label">Locatie</div>
            		<div class="view-data"><?=$user->location;?></div>
            	</div>		
            	<?php if($user->rol){?>
            	<div>
            		<div class="view-label">Rol</div>
            		<div class="view-data"><?=$user->rol;?></div>
            	</div>
            	<?php }?>
            </section>
        </div>
   </div>
</div>