<h2>Gebruikers</h2>

    <table id="users_table" style="max-width: 1100px; min-width: 900px; width: auto; margin: 0 auto 40px;" class='table table-striped'>
    	<thead>
    		<tr>
	    		<th>Gebruiker</th>
	    		<th>Naam</th>
	    		<th>Locatie</th>
	    		<th>gebruikersrol</th>
    		</tr>
    	</thead>
    	<tbody>
    		 <?php foreach ($users as $user) { ?>
	            <tr><td><?=$this->html->link($user->username, '/users/view/'.$user->username); ?></td><td><?=$this->html->link($user->voornaam.' '.$user->achternaam, '/users/view/'.$user->username);?></td><td><?=$this->html->link($user->location, '/users/view/'.$user->username);?></td><td><?=$this->html->link($user->rol, '/users/view/'.$user->username);?></td></tr>
	        <?php } ?>
    	</tbody>
    </table>