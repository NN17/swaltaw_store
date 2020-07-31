<h3><?=$this->lang->line('edit_warehouse')?> ( <?=$warehouse['warehouseName']?> )</h3>
<div class="ui divider"></div>

<div class="ui three column centered grid">
<div class="column">
<?=form_open('ignite/updateWarehouse/'.$warehouse['warehouseId'], 'class="ui form"')?>
    <div class="field">
        <?=form_label($this->lang->line('name'))?>
        <?=form_input('name', $warehouse['warehouseName'],'placeholder="'.$this->lang->line('warehouse_name').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('serial'))?>
        <?=form_number('serial',$warehouse['serial'],'placeholder="'.$this->lang->line('warehouse_serial').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('remark'))?>
        <?=form_textarea('remark',$warehouse['remark'],'placeholder="'.$this->lang->line('warehouse_remark').'"')?>
    </div>

    <div class="field">
        <label><?=$this->lang->line('status')?></label>
        <div class="inline">
            <div class="ui toggle checkbox">
            <input name="active" <?=$warehouse['activeState']?'checked':''?> type="checkbox" tabindex="0" class="hidden">
            <label><?=$this->lang->line('active')?></label>
            </div>
        </div>
    </div>

    <h4 class="ui orange dividing header">Use as Shop</h4>
    <div class="field">
        <label><?=$this->lang->line('shops')?></label>
        <div class="inline">
            <div class="ui toggle checkbox">
            <input name="shop" type="checkbox" <?=$warehouse['shop']?'checked':''?> tabindex="0" class="hidden">
            <label><?=$this->lang->line('active')?></label>
            </div>
        </div>
    </div>

    <div class="ui divider"></div>
    <div class="field text-center">
        <?=form_submit('save',$this->lang->line('update'),'class="ui button blue"')?>
        <?=anchor('warehouse',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
<?=form_close()?>
</div>
</div>