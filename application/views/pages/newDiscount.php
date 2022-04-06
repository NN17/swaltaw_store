<h3><?=$this->lang->line('new_discount')?></h3>
<div class="ui divider"></div>

<div class="ui three column centered grid">
<div class="column">
<?=form_open('ignite/addDiscount', 'class="ui form"')?>
    <input type="hidden" name="referer" value="<?=$_SERVER['HTTP_REFERER']?>" />
    <div class="field">
        <?=form_label($this->lang->line('title'))?>
        <?=form_input('title', '','placeholder="'.$this->lang->line('title').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('discount_type'))?>
        <select name="discountType" class="ui dropdown" id="dType">
            <option value="">Select Discount Type</option>
            <option value="DP">Discount With Percent</option>
            <option value="DA">Discount With Fix Amount</option>
            <option value="DF">Discount With Free Amount</option>
        </select>
    </div>

    <div class="field disabled" id="dRate">
        <?=form_label($this->lang->line('discount_rate','','class="disabled"'))?>
        <?=form_number('discountRate','','placeholder="'.$this->lang->line('discount_rate').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('remark'))?>
        <?=form_textarea('discountRemark','','placeholder="'.$this->lang->line('remark').'"')?>
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
        <?=anchor('discounts',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
<?=form_close()?>
</div>
</div>