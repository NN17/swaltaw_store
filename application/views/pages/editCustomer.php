<h3><?=$this->lang->line('new_customer')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="six wide column">
	<?=form_open('ignite/updateCustomer/'.$customer->customerId, 'class="ui form"')?>
	    <div class="field">
	    	<label><?=$this->lang->line('name')?></label>
	    	<?=form_input('name',$customer->customerName,'placeholder="'.$this->lang->line('name').'" required')?>
	    </div>
	    <div class="field">
	        <?=form_label($this->lang->line('email').' (Optional)')?>
	        <?=form_email('email',$customer->email,'placeholder="'.$this->lang->line('email').'"')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('contact_phone1')?></label>
	    	<?=form_input('phone1',$customer->phone1,'placeholder="'.$this->lang->line('contact_phone1').'" required')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('contact_phone2')?> (Optional)</label>
	    	<?=form_input('phone2',$customer->phone2,'placeholder="'.$this->lang->line('contact_phone2').'"')?>
	    </div>
	    <div class="field">
	        <?=form_label($this->lang->line('contact_address1'))?>
	        <?=form_textarea('address1',$customer->address1,'placeholder="'.$this->lang->line('contact_address1').'" required')?>
	    </div>

	    <div class="field">
	        <?=form_label($this->lang->line('contact_address2').' (Optional)')?>
	        <?=form_textarea('address2',$customer->address2,'placeholder="'.$this->lang->line('contact_address2').'"')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark',$customer->remark,'placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
		<div class="field text-center">
		    <?=form_submit('update',$this->lang->line('update'),'class="ui button blue"')?>
	        <?=anchor('customers',$this->lang->line('cancel'),'class="ui button"')?>
		</div>
	<?=form_close()?>
	</div>
</div>