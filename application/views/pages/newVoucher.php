<h3><?=$this->lang->line('new_voucher')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/addVoucher', 'class="ui form"')?>
		<input type="hidden" name="referer" value="vouchers" />
	    <div class="field">
	    	<label><?=$this->lang->line('voucher_date')?></label>
	    	<?=form_input('vDate','','placeholder="'.$this->lang->line('voucher_date').'" required id="datepicker"')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('voucher_serial')?></label>
	    	<?=form_input('vSerial','','placeholder="'.$this->lang->line('voucher_serial').'" required')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('extra_charges')?></label>
	    	<select name="extCharge" class="ui dropdown">
	    		<option> -- Select Extra Charges -- </option>
	    		<?php foreach($extCharges as $ext): ?>
	    			<option value="<?=$ext->chargeId?>"><?=$ext->chargeTitle?> ( <?=$ext->chargeAmount?> )</option>
	    		<?php endforeach; ?>
	    	</select>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('supplier')?></label>
	    	<select name="supplier" class="ui search dropdown" required>
	    		<option>-- Select Supplier --</option>
	    		<?php foreach($suppliers as $supplier): ?>
	    			<option value="<?=$supplier->supplierId?>"><?=$supplier->supplierName?></option>
	    		<?php endforeach; ?>
	    	</select>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
		<div class="field">
	        <?=anchor('vouchers',$this->lang->line('cancel'),'class="ui button"')?>
		    <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
		</div>
	<?=form_close()?>
	</div>
</div>