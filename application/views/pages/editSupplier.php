<h3><?=$this->lang->line('edit_supplier')?> ( <?=$supplier['supplierName']?> )</h3>
<div class="ui divider"></div>

<div class="ui two column grid">
<div class="column">
<?=form_open('ignite/updateSupplier/'.$supplier['supplierId'], 'class="ui form"')?>
    <div class="field">
        <?=form_label($this->lang->line('name'))?>
        <?=form_input('name',$supplier['supplierName'],'placeholder="'.$this->lang->line('supplier_name').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('email').' (Optional)')?>
        <?=form_email('email',$supplier['emailAddress'],'placeholder="'.$this->lang->line('email').'"')?>
    </div>

   	<div class="field">
        <?=form_label($this->lang->line('contact_person'))?>
        <?=form_input('contactPerson',$supplier['contactPerson'],'placeholder="'.$this->lang->line('contact_person').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_phone1'))?>
        <?=form_input('phone1',$supplier['contactPhone1'],'placeholder="'.$this->lang->line('contact_phone1').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_phone2').' (Optional)')?>
        <?=form_input('phone2',$supplier['contactPhone2'],'placeholder="'.$this->lang->line('contact_phone2').'"')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_address1'))?>
        <?=form_textarea('address1',$supplier['contactAddress1'],'placeholder="'.$this->lang->line('contact_address1').'" required')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('contact_address2').' (Optional)')?>
        <?=form_textarea('address2',$supplier['contactAddress2'],'placeholder="'.$this->lang->line('contact_address2').'"')?>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('remark').' <span class="default text">(Optional)</span>')?>
        <?=form_textarea('remark',$supplier['remark'],'placeholder="'.$this->lang->line('remark').'"')?>
    </div>

    <div class="field">
        <?=form_submit('update',$this->lang->line('update'),'class="ui button blue"')?>
        <?=anchor('supplier',$this->lang->line('cancel'),'class="ui button"')?>
    </div>
<?=form_close()?>
</div>
</div>