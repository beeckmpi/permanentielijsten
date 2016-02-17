
<?php
$calamiteiten_leidinggevenden = array();
$calamiteiten_medewerkers = array();
$winterdienst = array();
$weeknr = (int) date('W', time());
foreach ($lijsten as $key => $value) {
    if ($lijsten[$key]['type'] == 'calamiteiten') {
        if ($lijsten[$key]['subtype'] == 'leidinggevenden') {
            $calamiteiten_provinciaal[] = $lijsten[$key];
        } else if ($lijsten[$key]['subtype'] == 'medewerkers') {
            $calamiteiten_medewerkers[] = $lijsten[$key];
        }

    } else if ($lijsten[$key]['type'] == 'winterdienst') {
        if ($lijsten[$key]['subtype'] == 'provinciaal') {
            $winterdienst_provinciaal[] = $lijsten[$key];
        } else if ($lijsten[$key]['subtype'] == 'leidinggevenden') {
            $winterdienst_leidinggevenden[] = $lijsten[$key];
        } else if ($lijsten[$key]['subtype'] == 'medewerkers') {
            $winterdienst_medewerkers[] = $lijsten[$key];   
        }
    }
}
?>
<div class="container-fluid" style="width: 970px; margin: 0 auto;">
    <div class="row-fluid">
        <div class="span12">
            <h3>Overzicht</h3>
            <h4>Calamiteitenlijsten voor week <?=$weeknr ?></h4>
                <h5>Beurtrollen Provinciaal Coördinator Permanentie</h5>            
                <table id="permanentie_tabel" class=" table table-striped">
                	<thead>
                		<tr>
                			<th width="30%">Locatie</th>
                			<th width="2%">Van</th>
                			<th width="2%">Tot</th>
                			<th width="35%">Naam leidinggevende</th>
                			<th width="31%">GSM nummer</th>
                		</tr>
                	</thead>
                	<tbody>
                	    <?php
                        foreach ($calamiteiten_provinciaal as $lijst) {
                            $personeel = array();
                            foreach ($lijst['personeel'] as $personeelslid) {
                                $personeel[$personeelslid['naam']] = $personeelslid['GSM'];
                            }
                            foreach ($lijst['permanentie']['week_'.$weeknr] as $key => $value) {
                                echo '<tr id="week_' . $weeknr . '_0">';
                                if ($key == 0) {
                                    echo '<td class="week">' . str_replace('Alle districten ', '', $lijst['district']) . '</td>';
                                } else {
                                    echo '<td class="week"></td>';
                                }
                                if (is_object($lijst['permanentie']['week_' . $weeknr][$key]['startdatum'])) {
                                    echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum'] -> sec) . '</td>';
                                    echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum'] -> sec) . '</td>';
                                } else {
                                    echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum']) . '</td>';
                                    echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum']) . '</td>';
                                }

                                if ($lijst['subtype'] == "leidinggevenden" || $lijst['subtype'] == "provinciaal" || $lijst['subtype'] == "EM") {
                                    if (array_key_exists('personeelslid', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                        $list_data = array('naam' => '', 'GSM' => '');
                                        foreach ($lijst['permanentie']['week_'.$weeknr][$key]['personeelslid'] as $number) {
                                            if (isset($number['naam'])) {
                                                $list_data['naam'] .= '<div><span class="naam">' . $number['naam'] . '</span><div class="hidden GSM">' . $number['GSM'] . '</div></div>';
                                                $list_data['GSM'] .= '<div>' . $number['GSM'] . '</div>';
                                            }
                                        }
                                        echo '<td>' . $list_data['naam'] . '</td><td>' . $list_data['GSM'] . '</td></tr>';
                                    } else {
                                        echo '<td></td><td></td></tr>';
                                    }
                                }
                            }
                        }
                   ?>
                   </tbody>
                </table>
               
                <h5>Beurtrol Districtsmedewerkers</h5>
               
                <table id="permanentie_tabel" class="table table-striped" data-url="<?=$base_url ?>/lijsten/add_permanentie/<?=$lijsten -> _id ?>" data-deleteurl="<?=$base_url ?>/lijsten/delete_permanentie/<?=$lijsten -> _id ?>" data-type="<?=$lijsten -> type ?>" data-subtype="<?=$lijsten -> subtype ?>">
                    <thead>
                        <tr>
                            <th width="30%">Locatie</th>
                            <th width="2%">Van</th>
                            <th width="2%">Tot</th>
                            <th width="35%">Wegentoezichters</th>
                            <th width="31%">GSM nummer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($calamiteiten_medewerkers as $lijst) {
                            $personeel = array();
                            foreach ($lijst['personeel'] as $personeelslid) {
                                $personeel[$personeelslid['naam']] = $personeelslid['GSM'];
                            }
                            foreach ($lijst['permanentie']['week_'.$weeknr] as $key => $value) {
                                echo '<tr id="week_' . $weeknr . '_0">';
                                if ($key == 0) {
                                    echo '<td class="week">' .$lijst['provincie'].' - '. $lijst['district'] . '</td>';
                                } else {
                                    echo '<td class="week"></td>';
                                }
                                if (is_object($lijst['permanentie']['week_' . $weeknr][$key]['startdatum'])) {
                                    echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum'] -> sec) . '</td>';
                                    echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum'] -> sec) . '</td>';
                                } else {
                                    echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum']) . '</td>';
                                    echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum']) . '</td>';
                                }
                                $list_data = array('medewerker' => '', 'GSM' => '');
                                if (array_key_exists('medewerker', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                    foreach ($lijst['permanentie']['week_'.$weeknr][$key]['medewerker'] as $number) {
                                       
                                        if (isset($number['naam'])) {
                                            if ($list_data['medewerker'] == '') {
                                                $list_data['medewerker'] = '<div>';
                                            }
                                            $list_data['medewerker'] .= '<span class="naam">' . $number['naam'] . '</span></div>';
                                            $list_data['GSM'] .= '<div>' . $personeel[$number['naam']] . '</div>';
                                        }
                                        if (isset($number['naam'])) {
                                            $list_data['medewerker'] .= '</div>';
                                            
                                        }
                                    }
                                }
                                echo '<td>' . $list_data['medewerker'] . '</td><td>' . $list_data['GSM'] . '</td></tr>';
                            }
                        }
                    ?>
                   </tbody>
                </table>
        		<h4>Winterdienstlijsten voor week <?=$weeknr ?></h4>
        		
                <h5>Beurtrollen Provinciaal Coördinator Winterdienst</h5>
                <table id="permanentie_tabel" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="30%">Locatie</th>
                            <th width="2%">Van</th>
                            <th width="2%">Tot</th>
                            <th width="35%">Naam leidinggevende</th>
                            <th width="31%">GSM nummer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($winterdienst_provinciaal as $lijst) {
                            $personeel = array();
                            foreach ($lijst['personeel'] as $personeelslid) {
                                $personeel[$personeelslid['naam']] = $personeelslid['GSM'];
                            }
                            foreach ($lijst['permanentie']['week_'.$weeknr] as $key => $value) {
                                echo '<tr id="week_' . $weeknr . '_0">';
                                if ($key == 0) {
                                    echo '<td class="week">' . str_replace('Alle districten ', '', $lijst['district']) . '</td>';
                                } else {
                                    echo '<td class="week"></td>';
                                }
                                if (is_object($lijst['permanentie']['week_' . $weeknr][$key]['startdatum'])) {
                                    echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum'] -> sec) . '</td>';
                                    echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum'] -> sec) . '</td>';
                                } else {
                                    echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum']) . '</td>';
                                    echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum']) . '</td>';
                                }
    
                                if ($lijst['subtype'] == "leidinggevenden" || $lijst['subtype'] == "provinciaal" || $lijst['subtype'] == "EM") {
                                    if (array_key_exists('personeelslid', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                        $list_data = array('naam' => '', 'GSM' => '');
                                        foreach ($lijst['permanentie']['week_'.$weeknr][$key]['personeelslid'] as $number) {
                                            if (isset($number['naam'])) {
                                                $list_data['naam'] .= '<div><span class="naam">' . $number['naam'] . '</span><div class="hidden GSM">' . $number['GSM'] . '</div></div>';
                                                $list_data['GSM'] .= '<div>' . $number['GSM'] . '</div>';
                                            }
                                        }
                                        echo '<td>' . $list_data['naam'] . '</td><td>' . $list_data['GSM'] . '</td></tr>';
                                    } else {
                                        echo '<td></td><td></td></tr>';
                                    }
                                } 
                            }
                        }
                    ?>
                   </tbody>
                </table>
                <h5>Beurtrollen Leidinggevenden Winterdienst</h5>
                <table id="permanentie_tabel" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="30%">Locatie</th>
                            <th width="2%">Van</th>
                            <th width="2%">Tot</th>
                            <th width="35%">Naam leidinggevende</th>
                            <th width="31%">GSM nummer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($winterdienst_leidinggevenden as $lijst) {
                            $personeel = array();
                            foreach ($lijst['personeel'] as $personeelslid) {
                                $personeel[$personeelslid['naam']] = $personeelslid['GSM'];
                            }
                        foreach ($lijst['permanentie']['week_'.$weeknr] as $key => $value) {
                            echo '<tr id="week_' . $weeknr . '_0">';
                            if ($key == 0) {
                                echo '<td class="week">' .$lijst['provincie'].' - '. $lijst['district'] . '</td>';
                            } else {
                                echo '<td class="week"></td>';
                            }
                            if (is_object($lijst['permanentie']['week_' . $weeknr][$key]['startdatum'])) {
                                echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum'] -> sec) . '</td>';
                                echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum'] -> sec) . '</td>';
                            } else {
                                echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum']) . '</td>';
                                echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum']) . '</td>';
                            }

                            if ($lijst['subtype'] == "leidinggevenden" || $lijst['subtype'] == "provinciaal" || $lijst['subtype'] == "EM") {
                                if (array_key_exists('personeelslid', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                    $list_data = array('naam' => '', 'GSM' => '');
                                    foreach ($lijst['permanentie']['week_'.$weeknr][$key]['personeelslid'] as $number) {
                                        if (isset($number['naam'])) {
                                            $list_data['naam'] .= '<div><span class="naam">' . $number['naam'] . '</span><div class="hidden GSM">' . $number['GSM'] . '</div></div>';
                                            $list_data['GSM'] .= '<div>' . $number['GSM'] . '</div>';
                                        }
                                    }
                                    echo '<td>' . $list_data['naam'] . '</td><td>' . $list_data['GSM'] . '</td></tr>';
                                } else {
                                    echo '<td></td><td></td></tr>';
                                }
                            } 
                        }}
                    ?>
                   </tbody>
                </table>
                <h5>Beurtrollen Districtsmedewerkers Winterdienst</h5>
                <table id="permanentie_tabel" class="table table-striped">
                    <thead>
                        <tr>                   
                            <th>Locatie</th>
                            <th colSpan=2>Datum Van - Tot</th>
                            <th colSpan=2>Wegentoezichters</th>
                            <th colSpan=2>Lader/Technisch assistent</th>
                                
                            </tr>
                            <tr>
                                <th></th>
                                <th width="2%">Van</th>
                                <th width="2%">Tot</th>
                                <th>08.00u - 20.00u</th>
                                <th>20.00u - 08.00u</th>
                                <th>08.00u - 20.00u</th>
                                <th>20.00u - 08.00u</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($winterdienst_medewerkers as $lijst) {
                            $personeel = array();
                            foreach ($lijst['personeel'] as $personeelslid) {
                                $personeel[$personeelslid['naam']] = $personeelslid['GSM'];
                            }
                        foreach ($lijst['permanentie']['week_'.$weeknr] as $key => $value) {
                            echo '<tr id="week_' . $weeknr . '_0">';
                            if ($key == 0) {
                                echo '<td class="week">'.$lijst['provincie'].' - '. $lijst['district'] . '</td>';
                            } else {
                                echo '<td class="week"></td>';
                            }
                            if (is_object($lijst['permanentie']['week_' . $weeknr][$key]['startdatum'])) {
                                echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum'] -> sec) . '</td>';
                                echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum'] -> sec) . '</td>';
                            } else {
                                echo '<td class="van">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['startdatum']) . '</td>';
                                echo '<td class="tot">' . date('d/m/Y', $lijst['permanentie']['week_' . $weeknr][$key]['einddatum']) . '</td>';
                            }

                            $list_data = array('wegentoezichters-vroeg' => '', 'wegentoezichters-laat' => '', 'arbeiders-vroeg' => '', 'arbeiders-laat' => '');
                                if (array_key_exists('arbeiders-vroeg', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                    foreach ($lijst['permanentie']['week_'.$weeknr][$key]['arbeiders-vroeg'] as $number) {
                                        if (isset($personeel[$number['naam']]) && $personeel[$number['naam']] != '') {
                                            $list_data['arbeiders-vroeg'] .= '<div><a class="icon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="' . $personeel[$number['naam']] . '"></a><div class="hidden GSM">' . $personeel[$number['naam']] . '</div>&nbsp;&nbsp;';
                                        }
                                        if (isset($number['naam'])) {
                                            if ($list_data['arbeiders-vroeg'] == '') {
                                                $list_data['arbeiders-vroeg'] = '<div>';
                                            }
                                            $list_data['arbeiders-vroeg'] .= '<span class="naam">' . $number['naam'] . '</span>';
                                        }
                                        if (isset($number['naam'])) {
                                            $list_data['arbeiders-vroeg'] .= '</div>';
                                        }
                                    }
                                }
                                if (array_key_exists('arbeiders-laat', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                    foreach ($lijst['permanentie']['week_'.$weeknr][$key]['arbeiders-laat'] as $number) {
                                        if (isset($personeel[$number['naam']]) && $personeel[$number['naam']] != '') {
                                            $list_data['arbeiders-laat'] .= '<div><a class="icon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="' . $personeel[$number['naam']] . '"></a><div class="hidden GSM">' . $personeel[$number['naam']] . '</div>&nbsp;&nbsp;';
                                        }
                                        if (isset($number['naam'])) {
                                            if ($list_data['arbeiders-laat'] == '') {
                                                $list_data['arbeiders-laat'] = '<div>';
                                            }
                                            $list_data['arbeiders-laat'] .= '<span class="naam">' . $number['naam'] . '</span>';
                                        }
                                        if (isset($number['naam'])) {
                                            $list_data['arbeiders-laat'] .= '</div>';
                                        }
                                    }
                                }
                                if (array_key_exists('wegentoezichters-vroeg', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                    foreach ($lijst['permanentie']['week_'.$weeknr][$key]['wegentoezichters-vroeg'] as $number) {
                                        if (isset($personeel[$number['naam']]) && $personeel[$number['naam']] != '') {
                                            $list_data['wegentoezichters-vroeg'] .= '<div><a class="icon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="' . $personeel[$number['naam']] . '"></a><div class="hidden GSM">' . $personeel[$number['naam']] . '</div>&nbsp;&nbsp;';
                                        }
                                        if (isset($number['naam'])) {
                                            if ($list_data['wegentoezichters-vroeg'] == '') {
                                                $list_data['wegentoezichters-vroeg'] = '<div>';
                                            }
                                            $list_data['wegentoezichters-vroeg'] .= '<span class="naam">' . $number['naam'] . '</span>';
                                        }
                                        if (isset($number['naam'])) {
                                            $list_data['wegentoezichters-vroeg'] .= '</div>';
                                        }
                                    }
                                }
                                if (array_key_exists('wegentoezichters-laat', $lijst['permanentie']['week_' . $weeknr][$key])) {
                                    foreach ($lijst['permanentie']['week_'.$weeknr][$key]['wegentoezichters-laat'] as $number) {
                                        if (isset($personeel[$number['naam']]) && $personeel[$number['naam']] != '') {
                                            $list_data['wegentoezichters-laat'] .= '<div><a class="icon-info-sign" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="' . $personeel[$number['naam']] . '"></a><div class="hidden GSM">' . $personeel[$number['naam']] . '</div>&nbsp;&nbsp;';
                                        }
                                        if (isset($number['naam'])) {
                                            if ($list_data['wegentoezichters-laat'] == '') {
                                                $list_data['wegentoezichters-laat'] = '<div>';
                                            }
                                            $list_data['wegentoezichters-laat'] .= '<span class="naam">' . $number['naam'] . '</span>';
                                        }
                                        if (isset($number['naam'])) {
                                            $list_data['wegentoezichters-laat'] .= '</div>';
                                        }
                                    }
                                }
                                echo '<td>' . $list_data['wegentoezichters-vroeg'] . '</td><td>' . $list_data['wegentoezichters-laat'] . '</td><td>' . $list_data['arbeiders-vroeg'] . '</td><td>' . $list_data['arbeiders-laat'] . '</td></tr>';
                            }
                        }
                    ?>
                   </tbody>
                </table>
            </div>
        </div>
   </div>
