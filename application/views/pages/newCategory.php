<h3><?=$this->lang->line('add_category')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/addCategory', 'class="ui form" id="catSubmit"')?>
	    <div class="field">
	    	<div class="ui grid">
	    		<div class="twelve wide column">
			    	<label><?=$this->lang->line('name')?></label>
			    	<?=form_input('name','','placeholder="'.$this->lang->line('category_name').'" required')?>
			    </div>
			</div>
	    </div>
	    <div class="field">
			<label><?=$this->lang->line('cat_code')?></label>
	    	<div class="ui grid">
	    		<div class="twelve wide column">
			    	<?=form_input('l_code','','placeholder="'.$this->lang->line('cat_code').'" maxlength="2" id="LC_check" onkeypress="return /[a-z]/i.test(event.key)" required')?>
			    </div>
			    <div class="four wide column" id="LC_err">
			    	<span ></span>
			    </div>
			</div>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
		<div class="field">
		    <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
	        <?=anchor('categories',$this->lang->line('cancel'),'class="ui button"')?>
		</div>
	<?=form_close()?>
	</div>
</div>