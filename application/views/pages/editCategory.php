<h3><?=$this->lang->line('edit_category')?> ( <?=$catDetail['categoryName']?> )</h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/updateCategory/'.$catDetail['categoryId'], 'class="ui form"')?>
	    <div class="field">
	    	<label><?=$this->lang->line('name')?></label>
	    	<?=form_input('name',$catDetail['categoryName'],'placeholder="'.$this->lang->line('category_name').'" required')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark',$catDetail['remark'],'placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
		<div class="field">
		    <?=form_submit('update',$this->lang->line('update'),'class="ui button blue"')?>
	        <?=anchor('categories',$this->lang->line('cancel'),'class="ui button"')?>
		</div>
	<?=form_close()?>
	</div>
</div>