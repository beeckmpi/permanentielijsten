<h2>Gebruiker: <?=$user->username?></h2>
<div class="edit-user"><?=$this->html->link($this->html->image('icon-edit.png'), '/users/edit/'.$user->username, array('escape' => false));?></div>

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