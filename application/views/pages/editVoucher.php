<h3><?=$this->lang->line('edit_voucher')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('update-vouchers/'.$voucher->voucherId, 'class="ui form"')?>
	    <div class="field">
	    	<label><?=$this->lang->line('voucher_date')?></label>
	    	<?=form_input('vDate',date('Y-m-d', strtotime($voucher->vDate)),'placeholder="'.$this->lang->line('voucher_date').'" required id="datepicker"')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('voucher_serial')?></label>
	    	<?=form_input('vSerial',$voucher->vSerial,'placeholder="'.$this->lang->line('voucher_serial').'" required')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('extra_charges')?></label>
	    	<select name="extCharge" class="ui dropdown" required>
	    		<option> -- Select Extra Charges -- </option>
	    		<?php foreach($extCharges as $ext): ?>
	    			<option value="<?=$ext->chargeId?>" <?=$voucher->extCharge == $ext->chargeId?'selected':''?>><?=$ext->chargeTitle?> ( <?=$ext->chargeAmount?> )</option>
	    		<?php endforeach; ?>
	    	</select>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('supplier')?></label>
	    	<select name="supplier" class="ui search dropdown">
	    		<option>-- Select Supplier --</option>
	    		<?php foreach($suppliers as $supplier): ?>
	    			<option value="<?=$supplier->supplierId?>" <?=$voucher->supplier == $supplier->supplierId?'selected':''?>><?=$supplier->supplierName?></option>
	    		<?php endforeach; ?>
	    	</select>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark',$voucher->remark,'placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
		<div class="field">
	        <?=anchor('vouchers',$this->lang->line('cancel'),'class="ui button"')?>
		    <?=form_submit('update',$this->lang->line('update'),'class="ui button blue"')?>
		</div>
	<?=form_close()?>
	</div>
</div>