<h3><?=$this->lang->line('edit_return')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="six wide column">
	<?=form_open('ignite/updateReturn/'.$return->returnId, 'class="ui form"')?>
		<div class="field">
			<select name="item" class="ui dropdown search" onchange="igniteAjax.checkItem(this)" required>
				<option> -- Select Item -- </option>
				<?php foreach($items as $item): ?>
					<option value="<?=$item->itemId?>" <?=$return->itemId == $item->itemId ? 'selected':''?>><?=$item->codeNumber.' [ '.$item->itemName.' ] '.$item->price?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="field">
			<label><?=$this->lang->line('quantity')?></label>
			<?=form_number('qty', $return->qty, 'placeholder="Quantity" onchange="igniteAjax.balanceCheck(this)" id="dQty" required')?>
		</div>
	    
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark', $return->remark,'placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
		<div class="field">
	        <?=anchor('purchase-return',$this->lang->line('cancel'),'class="ui button"')?>
		    <?=form_submit('save',$this->lang->line('update'),'class="ui button blue"')?>
		</div>
	<?=form_close()?>
	</div>

	<div class="ten wide column">
		<div class="ui centered aligned itemDetail">
			
		</div>
	</div>
</div>