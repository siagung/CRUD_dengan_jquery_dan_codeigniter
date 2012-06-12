<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<?php
$attributes = array('id' => 'formbarang', 'class' => 'form');
echo form_open('', $attributes);
?>
<div class="formdata">
    <fieldset>
        <div class="formRow">
            <label>PLU * :</label>

            <div class="formRight">
                <?php if ($type == 'update') {
                echo '<input disabled type="text" name="plu" id="plu" value="' . $query['plu'] . '" />';
            }
            else
            {
                echo '<input type="text" name="plu" id="plu" value="" />';
            }
                ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="formRow">
            <label for="namabarang">Nama Barang * :</label>

            <div class="formRight">
                <input type="text" name="namabarang" id="namabarang" value="<?php echo $query['descp'];?>" style="width: 350px;">
            </div>
            <div class="clear"></div>
        </div>
        <div class="formRow">
            <label>Satuan :</label>
            <?php $opsatuan = array(
            '' => '--pilih satuan--',
            'BKS' => 'BKS',
            'BH' => 'BH',
            'PCS' => 'PCS',
            'ROLL' => 'ROLL'
            );
            echo form_dropdown('satuan', $opsatuan, set_value('satuan', $query['satuan']), 'id ="satuan" style="width :125px"') . '&nbsp; ';
            ?>
            <div class="clear"></div>
        </div>
        <div class="formRow">
            <label>Harga :</label>

            <div class="formRight"><input type="text" name="harga" id="harga" value="<?php echo $query['harga'];?>"></div>
            <div class="clear"></div>
        </div>
    </fieldset>
</div>

<?php echo form_close(); ?>
<div style="display: none;">
    <input type="hidden" id="hide_plu" value="<?php echo $query['plu']?>">
    <input type="hidden" id="hide_type" value="<?php echo  $type ?>">
</div>
