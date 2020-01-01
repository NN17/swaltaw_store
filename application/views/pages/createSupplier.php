<h3><?=$this->lang->line('new_supplier')?></h3>
<div class="ui divider"></div>

<div class="ui two column grid">
<div class="column">
<?=form_open('ignite/addSupplier', 'class="ui form"')?>
    <div class="field">
        <?=form_label($this->lang->line('name'))?>
        <?=form_input('name', '','placeholder="'.$this->lang->line('supplier_name').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('email').' (Optional)')?>
        <?=form_email('email', '','placeholder="'.$this->lang->line('email').'"')?>
    </div>

   	<div class="field">
        <?=form_label($this->lang->line('contact_person'))?>
        <?=form_input('contactPerson', '','placeholder="'.$this->lang->line('contact_person').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_phone1'))?>
        <?=form_input('phone1', '','placeholder="'.$this->lang->line('contact_phone1').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_phone2').' (Optional)')?>
        <?=form_input('phone2', '','placeholder="'.$this->lang->line('contact_phone2').'"')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_address1'))?>
        <?=form_textarea('address1','','placeholder="'.$this->lang->line('contact_address1').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_address2').' (Optional)')?>
        <?=form_textarea('address2','','placeholder="'.$this->lang->line('contact_address2').'"')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('remark').' <span class="default text">(Optional)</span>')?>
        <?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
    </div>

    <div class="field">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
        <?=anchor('supplier',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
<?=form_close()?>
</div>
</div>