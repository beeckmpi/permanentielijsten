<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html>
<head>
	<?php echo $this->html->charset();?>
	<title>Permanentie > <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('debug', 'lithium', 'bootstrap', 'bootstrap-theme', '/js/jquery-ui-n/jquery-ui.min.css', '/js/jquery-ui-n/jquery-ui.theme.min.css', '/js/jquery-ui-n/jquery-ui.structure.min.css')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->script('jquery-ui/js/jquery-1.9.1.js')?>
	<?php echo $this->html->script('jquery-ui-n/jquery-ui.min.js')?>
	<?php echo $this->html->script('jquery.tinyscrollbar.min.js')?>
	<?php echo $this->html->script('default.js')?>
	<?php echo $this->html->script('lijsten.js')?>
	
	<?php echo $this->html->script('b3/bootstrap.js')?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>

<body class="app">
	<nav class="navbar navbar-default">
		<div class="container-fluid">
		    <div class="navbar-header">
		        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
			    <a class="navbar-brand" href="#">Permanentie</a>	
			</div>	
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    			<ul class="nav navbar-nav">
                    <li class="<?php echo $actief['overzicht']?>"><?=$this->html->link('Overzicht', 'lijsten/overzicht')?></li>
    				<li class="<?php echo $actief['lijsten']?>"><?=$this->html->link('Lijsten', 'lijsten')?></li>
    				<?php if ($login['rol'] == 'administrator') {?>
    				<li class="<?php echo $actief['beheren']?> dropdown">
    					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Beheren<b class="caret"></b></a>
    					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
    						<li><?=$this->html->link('Gebruikers lijst', 'users')?></li>
    						<li><?=$this->html->link('Gebruikers toevoegen', 'users/add')?></li>
    						<!--<li><?=$this->html->link('Gebruikers toegangsrechten', 'users/roles')?></li>-->
    						<li role="presentation" class="divider"></li>
    						<li><?=$this->html->link('Lijst toevoegen', 'lijsten/add')?></li>
    						<!--<li><?=$this->html->link('Lijsten Beheren', 'lijsten/beheren')?></li>-->
    						<li role="presentation" class="divider"></li>
    						<li><?=$this->html->link('Feestdagen beheren', 'lijsten/feestdagen')?></li>
    						<li role="presentation" class="divider"></li>
                            
    					</ul>
    				</li>    				
    				<?php } ?>
    				<?php if (($login['rol'] == 'administrator') || ($login['rol'] == 'personeel') || ($login['rol'] == 'gebruiker') || (strpos($login['location'], 'Alle districten') !== false && $login['provincie'] == $lijsten->provincie)){?>
    				<li  class="<?php echo $actief['personeel']?>"><?=$this->html->link('Personeel', 'lijsten/personeelsleden')?></li>
    				<?php } ?>
    			</ul>
    			<ul class="nav navbar-nav navbar-right">
    			    <?php if($login) { 
                        if(array_key_exists('voornaam', $login)){   
                    ?>              
                    <div class="btn-group" style="margin-top: 8px">
                        <button class="btn dropdown-toggle btn-info"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?=$login['voornaam'].' '.$login['achternaam'];?>
                            <span class="caret"></span>
                        </button>
                    <?php } else { ?>
                        <button class="btn"><?=$this->html->link($login['username'], '/users/view/'.$login['username'])?></button>
                    <?php }} else { ?>
                        <?=$this->html->link('AANMELDEN', '/login/', array('class' => 'btn'))?>
                    <?php } ?>
                    
                    <ul class="dropdown-menu">
                    <?php if($login) { ?>
                        <li><?=$this->html->link('Wachtwoord wijzigen', '/users/password'); ?></li> 
                        <li><?=$this->html->link('Afmelden', '/logout/'); ?></li> 
                    <?php } ?>
                    </ul>   
    			</ul>
		</div>
	</nav>
	<ol class="breadcrumb">
		<?php foreach ($breadcrumb as $key){ ?>
			<?php if (array_key_exists('url', $key)) { ?>
		  	  <li><a href="<?=$key['url']?>"><?=$key['naam']?></a> <span class="divider"></span></li>
		    <?php } else { ?>
		    <li class="active"><?=$key['naam']?></li>
	    <?php }}?>
    </ol>
	
	<div id="container">
		
		<?php echo $this->content(); ?>		
	</div>
</body>
</html>