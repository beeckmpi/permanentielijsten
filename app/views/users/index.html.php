<h2>Gebruikers</h2>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table id="users_table" class='table table-striped'>
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
        </div>
    </div>
    