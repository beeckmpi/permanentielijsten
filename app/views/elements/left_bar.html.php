
<ul class="nav nav-list">	
	<?php if ($login) { ?>
	
	<li class="nav-header">Administratie</li>
	<li><?=$this->html->link('Gebruikers', '/users');?>
	<li><?=$this->html->link('Gebruiker toevoegen', '/users/add');?></li>
	<?php }?>
</ul>
