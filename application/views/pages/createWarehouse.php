<h3><?=$this->lang->line('new_warehouse')?></h3>
<div class="ui divider"></div>

<div class="ui two column grid">
<div class="column">
<?=form_open('ignite/addWarehouse', 'class="ui form"')?>
    <input type="hidden" name="referer" value="<?=$_SERVER['HTTP_REFERER']?>" />
    <div class="field">
        <?=form_label($this->lang->line('name'))?>
        <?=form_input('name', '','placeholder="'.$this->lang->line('warehouse_name').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('serial'))?>
        <?=form_number('serial','','placeholder="'.$this->lang->line('warehouse_serial').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('remark'))?>
        <?=form_textarea('remark','','placeholder="'.$this->lang->line('warehouse_remark').'"')?>
    </div>

    <div class="field">
        <label><?=$this->lang->line('status')?></label>
        <div class="inline">
            <div class="ui toggle checkbox">
            <input name="active" checked="TRUE" type="checkbox" tabindex="0" class="hidden">
            <label><?=$this->lang->line('active')?></label>
            </div>
        </div>
    </div>

    <div class="field">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
        <?=anchor('warehouse',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
<?=form_close()?>
</div>
</div>