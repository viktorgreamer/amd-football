<?php
$colors = \app\models\Colors::find()->all();
?>

<div class="dropdown">
    <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Select Color
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dLabel">
        <table class="table-bordered">
            <tr> <td class="btn set-attr-value" data-attr = 'id_color' data-id = "<?= $id ?>" data-value="99" title="НЕТ ЦВЕТА">
                    НЕТ
                </td></tr>
            <tr>

                <?php foreach ($colors as $color) { $n++; ?>

                    <td class="btn set-attr-value" data-attr = 'id_color' data-id = "<?= $id ?>" data-value="<?= $color->id; ?>" bgcolor="<?= $color->code ?>" title="<?= $color->rus; ?>">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='padding-left:10px;'> </span>
                    </td>
                    <? if ($n % 5 == 0) echo "</tr><tr>"; ?>

                <?php } ?>

            </tr>
        </table>
    </ul>
</div>