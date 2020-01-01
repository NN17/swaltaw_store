<h3><?=$this->lang->line('edit_brand')?> ( <?=$brand['brandName']?> )</h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/updateBrand/'.$brand['brandId'], 'class="ui form"')?>
	    <div class="field">
	    	<label><?=$this->lang->line('name')?></label>
	    	<?=form_input('name',$brand['brandName'],'placeholder="'.$this->lang->line('brand_name').'" required')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark',$brand['remark'],'placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
		<div class="field">
		    <?=form_submit('update',$this->lang->line('update'),'class="ui button blue"')?>
	        <?=anchor('brands',$this->lang->line('cancel'),'class="ui button"')?>
		</div>
	<?=form_close()?>
	</div>
</div>