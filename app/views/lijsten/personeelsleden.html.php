
<div style="clear:both"></div>
<div class="container-fluid" style="width: 970px; margin: 0 auto;">
    <div class="row">            
        <div class="col-md-12"> 
            <form class="form-inline">
                <div class="form-group">
                    <label>Filter</label>
                    <input placeholder="naam" type="text" class="form-control" id="naamFilter">
                </div>
                <div class="form-group">
                    <input placeholder="GSM" type="text" class="form-control" id="GSMFilter">
                </div>
                <?php if (($login['rol'] === 'administrator') || ($login['rol'] === 'personeel')){ ?>
                <div class="form-group">
                    <?=$this->form->field('', array('class' => 'form-control', 'required' => true, 'id' => 'selectDistrict', 'label' => '', 'list' => $locaties, 'type' => 'select'));?>
                </div>
                 <?php } ?>
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
                <?php echo $this->_view->render(
                       array('element' => 'personeel_table'), compact('personeelsleden')
                   ) ?>
                </tbody>
            </table>
        </div>
    </div>                
</div>
<div id="myModal_personeel_bewerken" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>Persoon bewerken</h3>
                    </div>
                    <div class="modal-body">
                        <p>
                        <form id="wn_edit_form" action="personeel/save/">
                            <input id="hidden_id" type="hidden">
                            <div class="form-group">
                                <label for="naam">Vlimpersnummer</label>
                                <input type="text" id="vlimpersnummer_bewerken"  class="form-control" placeholder="Vlimpersnummer"required="true" name="vlimpersnummer" value="">
                                <input type="hidden" id="vlimpersnummer_old" name="vlimpersnummer_old">
                            </div>
                            <div class="form-group">
                                <label for="naam">Naam</label>
                                <input type="text" id="personeel_bewerken"  class="form-control" placeholder="Naam"required="true" name="naam" value="">
                                <input type="hidden" id="personeel_old" name="personeel_old">
                            </div>
                            <div class="form-group">
                                <label for="gsmnummer">GSM</label>
                                <input type="text" id="GSM_bewerken"  class="form-control" placeholder="GSM nummer" required="true" name="GSM" value="">
                                <input type="hidden" id="GSM_old" name="GSM_old">
                            </div>
                            <?php
                                if (($login['rol'] == 'administrator') || ($login['rol'] == 'personeel')){
                                    $hidden = '';
                                } else {
                                    $hidden = ' hidden';
                                }?>
                            <div class="form-group<?=$hidden?>">
                                
                                <?=$this->form->field('districtscode', array('class' => 'form-control'.$hidden, 'label' => 'District', 'required' => true, 'list' => $locaties, 'type' => 'select'));?>
                            </div>
                           
                        </form>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save_modal_personeel">Bewaren</button>
                    </div>
                </div>
            </div>
        </div>