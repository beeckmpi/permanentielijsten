<h2>Producten</h2>
<a class="btn" href="/permanentielijsten/producten/add/1" data-toggle="modal" data-target="#myModal" style="float:right">Product toevoegen</a>
<?=$this->form->create(); ?>
	<H4>Filter</H4>
	<div style="position:relative; display:inline-block;margin-right: 5px; vertical-align: top">
	   	<?=$this->form->field('tagnummer', array('label' => 'Tagnummer', 'required' => 'true', 'placeholder' => 'Tagnummer', 'size' => 10)); ?>
	</div>
	<div style="position:relative; display:inline-block; vertical-align: top;margin-right: 5px;">
 		<?php $types = array(
		        				'' 			=> '--Selecteer--', 
		        				'laptop' 	=> 'Laptop', 
		        				'desktop'	=> 'desktop', 
		        				'scherm' 	=> 'Scherm', 
		        				'printer' 	=> 'printer', 
							);
		?>
	   <?=$this->form->field('type', array('label' => 'Type', 'required' => true, 'list' => $types, 'type' => 'select', 'style' => 'width: 140px;'));?>
	</div>
	<div style="position:relative; display:inline-block;margin-right: 5px; vertical-align: top">
	   	<?=$this->form->field('component', array('label' => 'Component', 'required' => 'true', 'placeholder' => 'Component', 'size' => 30)); ?>
	</div>
	<div style="position:relative; display:inline-block;margin-right: 5px; vertical-align: top">
	   	<?=$this->form->field('In dienst name', array('label' => 'In dienst name', 'required' => 'true', 'placeholder' => 'In dienst name', 'size' => 10, 'class' => 'datepicker')); ?>
	</div>
	<div style="position:relative; display:inline-block;margin-right: 5px; vertical-align: top">
	   	<?=$this->form->field('locatie', array('label' => 'Locatie', 'required' => true, 'list' => $locaties, 'type' => 'select', 'style' => 'width: 220px;'));?>
	</div>
<?=$this->form->end(); ?>
<table id="users_table">
    	<thead>
    		<tr>
	    		<th width="10%">Tagnummer</th>
	    		<th width="25%">Type</th>
	    		<th width="25%">Component</th>
	    		<th width="15%">In dienst name</th>
	    		<th width="25%">Locatie</th>
    		</tr>
    	</thead>
    	<tbody>
    		 <?php foreach ($producten as $product) { ?>
	            <tr><td><?=$product->tagnummer; ?></td><td><?=$product->type;?></td><td><?=$product->component;?></td><td><?=$product->In_dienst_name?></td><td><?=$product->locatie?></td></tr>
	        <?php } ?>
    	</tbody>
    </table>
    
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
		 <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Product toevoegen</h3>
		</div>
	
		<div class="modal-body">
		<p>One fine body…</p>
		</div>
		<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Sluiten</button>
		<button class="btn" id="add_product">Product toevoegen</button>
	</div>
</div>