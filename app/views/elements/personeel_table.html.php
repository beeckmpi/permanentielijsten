<?php foreach ($personeelsleden as $key => $value) {?>
    <tr id="<?=$personeelsleden[$key]['_id'] ?>" style="transition: background 0.5s linear;">
        <td id="<?=$personeelsleden[$key]['_id'] ?>_vlimpers"><?=$personeelsleden[$key]['vlimpersnummer'] ?></td>
        <td id="<?=$personeelsleden[$key]['_id'] ?>_naam"><?=$personeelsleden[$key]['naam'] ?></td>
        <td id="<?=$personeelsleden[$key]['_id'] ?>_GSM"><?=$personeelsleden[$key]['GSM'] ?></td>
        <td id="<?=$personeelsleden[$key]['_id'] ?>_provincie"><?=$personeelsleden[$key]['provincie'] ?></td>
        <td id="<?=$personeelsleden[$key]['_id'] ?>_district"><?=$personeelsleden[$key]['district'] ?></td>
        <td>
            <?=$this->html->link('<i class="glyphicon glyphicon-edit"></i>', 'lijsten/personeel/edit/' . $personeelsleden[$key]['_id'], array('escape' => false, 'class' => 'bewerk_personeel btn btn-default  btn-xs', 'style' => 'margin-right:5px')); ?>
            <?=$this->html->link('<i class="glyphicon glyphicon-trash"></i>', 'lijsten/personeel/remove/' . $personeelsleden[$key]['_id'], array('escape' => false, 'class' => 'remove_personeel btn btn-default  btn-xs')); ?>
        </td>
   </tr>
<?php } ?>
                