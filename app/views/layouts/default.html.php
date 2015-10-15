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
	<?php echo $this->html->style(array('debug', 'lithium', 'bootstrap', '/js/jquery-ui/css/eggplant/jquery-ui-1.10.1.custom.css')); ?>
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->script('jquery-ui/js/jquery-1.9.1.js')?>
	<?php echo $this->html->script('jquery-ui/js/jquery-ui-1.10.1.custom.js')?>
	<?php echo $this->html->script('modernizr.js')?>
	<?php echo $this->html->script('jquery.tinyscrollbar.min.js')?>
	<?php echo $this->html->script('jquery-ui/jquery.ui.datepicker-nl.js')?>
	<?php echo $this->html->script('default.js')?>
	<?php echo $this->html->script('lijsten.js')?>
	
	<?php echo $this->html->script('bootstrap.js')?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>

<body class="app">
	<div class="navbar">
		<div class="navbar-inner">
			<a class="brand" href="#">Permanentie</a>		
			<ul class="nav navbar-nav">

				<li class="<?php echo $actief['lijsten']?>"><?=$this->html->link('Lijsten', 'lijsten')?></li>
				<?php if ($login['rol'] == 'administrator') {?>
				<li class="<?php echo $actief['beheren']?> dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Beheren<b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
						<li><?=$this->html->link('Gebruikers lijst', 'users')?></li>
						<li><?=$this->html->link('Gebruikers toevoegen', 'users/add')?></li>
						<li><?=$this->html->link('Gebruikers toegangsrechten', 'users/roles')?></li>
						<li role="presentation" class="divider"></li>
						<li><?=$this->html->link('Lijst toevoegen', 'lijsten/add')?></li>
						<li><?=$this->html->link('Lijsten Beheren', 'lijsten/beheren')?></li>
					</ul>
				</li>
				
				<?php } ?>
			</ul>
		</div>
	</div>
	<ul class="breadcrumb">
		<?php foreach ($breadcrumb as $key){ ?>
			<?php if (array_key_exists('url', $key)) { ?>
		  	  <li><a href="<?=$key['url']?>"><?=$key['naam']?></a> <span class="divider">/</span></li>
		    <?php } else { ?>
		    <li class="active"><?=$key['naam']?></li>
	    <?php }}?>
    </ul>
	<div id="header">
		<div class="user_login">
			<div class="btn-group">
				<?php if($login) { 
					if(array_key_exists('voornaam', $login)){	
				?>				
					<button class="btn"><?=$login['voornaam'].' '.$login['achternaam'];?></button>
					<button class="btn dropdown-toggle" data-toggle="dropdown">
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
			</div>	
		</div>		
	</div>
	<div id="container">
		
		<?php echo $this->content(); ?>		
	</div>
</body>
</html>