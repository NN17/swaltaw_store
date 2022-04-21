<h3><?=$this->lang->line('new_charges')?></h3>
<div class="ui divider"></div>

<div class="ui three column centered grid">
<div class="column">
<?=form_open('ignite/addCharges', 'class="ui form"')?>
    <input type="hidden" name="referer" value="<?=$_SERVER['HTTP_REFERER']?>" />
    <div class="field">
        <?=form_label($this->lang->line('title'))?>
        <?=form_input('title', '','placeholder="'.$this->lang->line('title').'" required')?>
    </div>

    <div class="field" id="dRate">
        <?=form_label($this->lang->line('charge_amount','','class="disabled"'))?>
        <?=form_number('chargeAmt','','placeholder="'.$this->lang->line('discount_rate').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('remark'))?>
        <?=form_textarea('chrgeRemark','','placeholder="'.$this->lang->line('remark').'"')?>
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

    <div class="ui divider"></div>
    <div class="field text-center">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
        <?=anchor('extra-charges',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
<?=form_close()?>
</div>
</div>