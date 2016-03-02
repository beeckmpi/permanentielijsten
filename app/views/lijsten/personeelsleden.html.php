<div style="clear:both"></div>
<div class="container-fluid" style="width: 970px; margin: 0 auto;">
    <div class="row">            
        <div class="col-md-12"> 
            <form class="form-inline">
                <div class="form-group">
                    <input placeholder="naam" type="text" class="form-control" id="naam">
                </div>
                <div class="form-group">
                    <input placeholder="GSM" type="text" class="form-control" id="GSM">
                </div>
                <div class="form-group">
                    <?=$this->form->field('', array('class' => 'form-control', 'required' => true, 'list' => $locaties, 'type' => 'select'));?>
                </div>
            </form>
            <table id="personeelsleden_tabel" class="table table-striped">
                <thead>
                    <tr>
                        <th>Vlimpersnummer</th>
                        <th>Naam</th>
                        <th>GSM</th>
                        <th>Provincie</th>
                        <th>District</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personeelsleden as $key => $value) {?>
                        <tr id="<?=$personeelsleden[$key]['_id']?>">
                            <td><?=$personeelsleden[$key]['vlimpersnummer']?></td>
                            <td><?=$personeelsleden[$key]['naam']?></td>
                            <td><?=$personeelsleden[$key]['GSM']?></td>
                            <td><?=$personeelsleden[$key]['provincie']?></td>
                            <td><?=$personeelsleden[$key]['district']?></td>
                            <td>
                                <?=$this->html->link('<i class="glyphicon glyphicon-edit"></i>', 'lijsten/personeel/edit/'.$personeelsleden[$key]['_id'], array('escape' => false, 'class' => 'btn btn-default  btn-xs', 'style'=>'margin-right:5px'));?>
                                <?=$this->html->link('<i class="glyphicon glyphicon-trash"></i>', 'lijsten/personeel/remove/'.$personeelsleden[$key]['_id'], array('escape' => false, 'class' => 'btn btn-default  btn-xs'));?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>                
</div>