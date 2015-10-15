<h2>Beheren</h2>
<div class="row">	
	<div class="span4 well">
		<div class="nav-header" style="font-size:larger">Gebruikers</div>
		<ul>
			<li><?=$this->html->link('Gebruikers lijst', 'users')?></li>
			<li><?=$this->html->link('Gebruikers toevoegen', 'users/add')?></li>
			<li><?=$this->html->link('Gebruikers toegangsrechten', 'users/roles')?></li>
		</ul>
	</div>
	<div class="span4 well">
		<div class="nav-header" style="font-size:larger">Lijsten</div>
		<ul>
			<li><?=$this->html->link('Lijst toevoegen', 'lijsten/add')?></li>
			<li><?=$this->html->link('Lijsten Beheren', 'lijsten/beheren')?></li>
			
		</ul>
	</div>
</div>