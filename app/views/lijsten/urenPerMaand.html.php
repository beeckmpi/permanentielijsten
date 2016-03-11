<?php if (($login['rol'] == 'administrator' || ($login['rol'] == 'personeel' && $login['provincie'] == $lijsten->provincie)) || (strpos($login['location'], 'Alle districten') !== false && $login['provincie'] == $lijsten->provincie)){ 
    $last_day = date('t', strtotime($year.'-'.$maand.'-1'));
    setlocale(LC_ALL, 'nld_NLD');
    $feestdagen_vol = array(
        "Nieuwjaar"=> "Nieuwjaar",
        "Paasmaandag"=> "Paasmaandag",
        "Feest_van_de_arbeid"=> "Feest van de arbeid",
        "olhh"=> "Hemelvaart",
        "Pinkstermaandag"=> "Pinkstermaandag",
        "Feest_van_de_vlaamse_gemeenschap"=> "Feest van de vlaamse gemeenschap",
        "nationale_feestdag"=> "Nationale feestdag",
        "olvh"=> "Onze lieve vrouw hemelvaart",
        "Allerheiligen"=> "Allerheiligen",
        "Allerzielen"=> "Allerzielen",
        "Wapenstilstand"=> "Wapenstilstand",
        "Koningsdag"=> "Koningsdag",
        "Kerstdag"=> "Kerstdag",
        "Kerstverlof1"=> "Kerstverlof",
        "Kerstverlof2"=> "Kerstverlof",
        "Kerstverlof3"=> "Kerstverlof",
        "Kerstverlof4"=> "Kerstverlof",
        "Kerstverlof5"=> "Kerstverlof",
        "Kerstverlof6"=> "Kerstverlof"
    );
    $personeelsleden = array();
    $vlimpersnummer = array();
    foreach ($lijsten->personeel as $key => $value){
        $personeelsleden[$value['naam']] = (int) 0;
        $vlimpersnummer[$value['naam']] = $value['vlimpersnummer'];
    }
    $maanden_lijst = array();
    $first_month = (int) date('n', $lijsten->Startdatum->sec);
    $first_year = (int) date('Y', $lijsten->Startdatum->sec);
    $date_loop = $lijsten->Startdatum->sec;
    $maanden_lijst[$first_month.'-'.$first_year] = date('F Y', strtotime('01-'.$first_month.'-'.$first_year));
    while ($date_loop<$lijsten->Einddatum->sec){
        $first_month++;
        $date_loop = strtotime('01-'.$first_month.'-'.$first_year);
        $maanden_lijst[$first_month.'-'.$first_year] = strftime('%B %Y', strtotime('01-'.$first_month.'-'.$first_year));
        if ($first_month==12){
            $first_year++;
            $first_month = 0;
        }        
    }
    $tr = array();
    for($i=1;$i < $last_day;$i++) {
        $tr[$i] = array(
            'feestdag' => '',
            'naam' => '',
            'speciaal' => false,
            'speciaal_class' => '',
            'uren' => (int) 16,
            'datetime' => strtotime($year.'-'.$maand.'-'.$i)
        );
        
        $day = strftime('%A', $tr[$i]['datetime']);
        if ($day == 'zaterdag' || $day == 'zondag'){
            $tr[$i]['feestdag'] = $day;
            $tr[$i]['speciaal'] = true;
        }
        foreach($feestdagen['data'] as $key => $value) {
            if (date('Y-m-d', $tr[$i]['datetime']) === date('Y-m-d', $value->sec)){
                $tr[$i]['feestdag'] = $feestdagen_vol[$key];
                $tr[$i]['speciaal'] = true;
            }
        } 
        $week = intval(date('W', $tr[$i]['datetime']));
        if(array_key_exists('week_'.$week, $lijsten_arr['permanentie'])){
            foreach($lijsten_arr['permanentie']['week_'.$week] as $key => $value){
                if(is_object($lijsten_arr['permanentie']['week_'.$week][$key]['startdatum'])){
                    $startdatum = $lijsten_arr['permanentie']['week_'.$week][$key]['startdatum']->sec;
                } else {
                    $startdatum = $lijsten_arr['permanentie']['week_'.$week][$key]['startdatum'];
                }
                if(is_object($lijsten_arr['permanentie']['week_'.$week][$key]['einddatum'])){
                    $einddatum = $lijsten_arr['permanentie']['week_'.$week][$key]['einddatum']->sec;
                } else {
                    $einddatum = $lijsten_arr['permanentie']['week_'.$week][$key]['einddatum'];
                }
                if ($tr[$i]['datetime'] >= $startdatum && $tr[$i]['datetime'] <= $einddatum){
                    if ($lijsten->subtype == "leidinggevenden" || $lijsten->subtype == "provinciaal" || $lijsten->subtype == "EM"){
                        if (array_key_exists('personeelslid', $lijsten_arr['permanentie']['week_'.$week][$key])){
                            $tr[$i]['naam'] = $lijsten_arr['permanentie']['week_'.$week][$key]['personeelslid'][0]['naam'];
                        }
                    } else if ($lijsten->type == "calamiteiten") {
                        if (array_key_exists('medewerker', $lijsten_arr['permanentie']['week_'.$week][$key])){
                            $tr[$i]['naam'] = $lijsten_arr['permanentie']['week_'.$week][$key]['medewerker'][0]['naam'];
                        }
                    }
                }                        
            }
        }
        if ($tr[$i]['speciaal']) {
            $tr[$i]['uren'] = (int) 24;
            $tr[$i]['speciaal_class'] = 'special';
        }
        if($tr[$i]['naam']!=''){
            $personeelsleden[$tr[$i]['naam']] = $personeelsleden[$tr[$i]['naam']] + $tr[$i]['uren'];            
        } else {
            $tr[$i]['uren'] = '';
        }
    }                      
?>
    
<div class="container-fluid" style="width: 970px; margin: 0 auto;">
    <div class="row">
        <div class="col-md-12">
            <input type=hidden id=lijstID value=<?=$lijsten->_id?> />
            <?php if ($lijsten->type == "calamiteiten" && $lijsten->subtype == "leidinggevenden"){?>   
               <?php if (strstr($lijsten->district, 'Alle districten') !== FALSE){?>
                 <h4>Beurtrol <?=$lijsten->subtype?> <?=$lijsten->type?> wachtdienst (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
               <?php } else { ?>
                 <h4>Beurtrol <?=$lijsten->subtype?> <?=$lijsten->type?> wachtdienst - <?=$locatie->district?> - <?=$locatie->districtnummer?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
               <?php } ?>
            <?php } else if ($lijsten->subtype == 'provinciaal') {?>
                <h4>Tijdschema Beurtrol Provinciaal Coördinator  <?=str_replace('Alle districten', '', $lijsten->district)?> (<?=strftime('%B', strtotime('01-'.$maand.'-'.$year))?> <?=$year?>)</h4>
            <?php } else if ($lijsten->subtype == 'EM') {?>
                <h4>Beurtrol Permanentie Sectie EM (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
            <?php } else { ?>
                <h4>Permanentielijst - <?=$locatie->district?> - <?=$locatie->districtnummer?> - <?=$lijsten->subtype?> <?=$lijsten->type?> (<?=date('Y',$lijsten->Startdatum->sec)?>-<?=date('Y',$lijsten->Einddatum->sec)?>)</h4>
            <?php } ?>
            <div style="margin: 0px 0 10px 0; padding-bottom: 15px">
                <label for="UPMMaand">Maand:</label>
                <select id=UPMMaand name="UPMMaand" class="form-control" style="max-width:375px;">
                    <?php foreach ($maanden_lijst as $key => $value) { 
                        $data = explode('-', $key);
                        $selected = '';
                        if ($data[0]==$maand && $data[1]==$year){
                            $selected = 'selected=selected';
                        }
                        ?>
                        <option value="<?=$key?>" <?=$selected?>><?=$value?></option>
                    <?php } ?> 
                </select>
            </div>
            <div style="width:40%">
            <?php echo $this->html->link('CSV', 'lijsten/exportUrenPerMaand/'.$lijsten->_id.'/'.$maand.'/'.$year.'/totalen', array('escape' => false, 'class' => 'btn btn-info btn-xs', 'style'=>'float:right; margin-top:-5px; margin-left: 5px"'));?>
            <a id=toggleHidden class="btn btn-default btn-xs" style='float:right; margin-top:-5px'>Toon/Verberg 0 uren</a>            
            <h5>Totalen</h5>       
            </div>       
            <table class="table table-striped" id="Personeelstabel" style="width: 40%;">
                <thead>
                    <tr>
                        <th>Vlimpers</th>
                        <th>Naam</th>
                        <th>uren</th>
                        <th>Premie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $totaal_value = 0;
                    $totaal_geld = 0;
                    $className = '';
                    ksort($personeelsleden);
                    foreach($personeelsleden as $key => $value){
                        if($value==0){
                            $className='hiddenPersoneel';
                        } else {
                            $className='';
                        }
                            $geld = 0; 
                            $totaal_value += $value;
                            if ($value >= 21 && $value <=50){
                                $geld = (string) '75€';
                                $totaal_geld += 75;
                            } else if ($value >= 51 && $value <=100){
                                $geld = (string) '100€';
                                $totaal_geld += 100;
                            } else if ($value >= 101 && $value <=200){
                                $geld = (string) '125€';
                                $totaal_geld += 125;
                            } else if ($value >= 201){
                                $geld = (string) '140€';
                                $totaal_geld += 125;
                            }
                        ?>
                        <tr class=<?=$className?>>
                            <td><?=$vlimpersnummer[$key]?></td>
                            <td><?=$key?></td>
                            <td><?=$value?>u</td>
                            <td><?=$geld?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2"><b>Totalen</b></td>
                        <td><b><?=$totaal_value?>u</b></td>
                        <td><b><?=$totaal_geld?>€</b></td>
                    </tr>
                </tbody>
            </table>
            
            <div style="float:right">
                <?php echo $this->html->link('Exporteren naar CSV', 'lijsten/exportUrenPerMaand/'.$lijsten->_id.'/'.$maand.'/'.$year.'/overzicht', array('escape' => false, 'class' => 'btn btn-info btn-xs', 'style'=>'float:right; margin-top:-5px"'));?>
            </div>
            <h5>Maandoverzicht</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="max-width: 25%">Datum</th>
                        <th style="max-width: 25%">Speciaal</th>
                        <th width="33%">Naam</th>
                        <th>Uren</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=1;$i < $last_day;$i++) { ?>
                        <tr class="<?=$tr[$i]['speciaal_class']?>">
                            <td><?=strftime('%A %d %B %Y', $tr[$i]['datetime'])?></td>
                            <td><?=$tr[$i]['feestdag']?></td>
                            <td><?=$tr[$i]['naam']?></td>
                            <td><?=$tr[$i]['uren']?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>    
</div>    
<?php } ?>