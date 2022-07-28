<h3><?=$this->lang->line('new_item')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
<div class="seven wide column">
<?=form_open_multipart('ignite/addItem', 'class="ui form"')?>
	<h4 class="ui teal dividing header">Basic Information</h4>
	<input type="hidden" name="referer" value="<?=$referer?>" />

	<div class="field">
		<?=form_label($this->lang->line('category'))?>
		<div class="ui grid">
			<div class="twelve wide column">
				<select name="category" class="ui search dropdown" id="cat" required>
					<option value="">Select</option>
					<?php foreach($categories as $cat):?>
					<option value="<?=$cat['categoryId']?>"><?=$cat['categoryName']?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="four wide column">
				<a href="javascript:void(0)" class="ui icon circular button green" onclick="category_modal()"><i class="icon plus"></i></a>
			</div>
		</div>
	</div>

	

   	<div class="field">
        <?=form_label($this->lang->line('item_code'))?>
        <div class="ui grid">
        	<div class="twelve wide column">
        		<?=form_input('code','','placeholder="'.$this->lang->line('item_code').'" id="code" data-itemid="'.($code['itemId']+1).'" readonly required')?>
        	</div>
        	<div class="four wide column">
        		<div class="ui toggle checkbox">
				  	<input type="checkbox" name="scanner" id="scanner">
				  	<label><i class="ui icon blue flaticon-barcode-scanner"></i></label>
				</div>
        	</div>
        </div>
    </div>

    <div class="field">
        <?=form_label($this->lang->line('name') . " ( Item Name must not include ' or \" )")?>
        <?=form_input('name',set_value('name'),'placeholder="'.$this->lang->line('item_name').'" pattern="[^\'\x22]+" required')?>  
    </div>


    <div class="field">
    	<label><?=$this->lang->line('currency')?></label>
    	<select name="currency" class="ui dropdown" required="required" readonly>
    		<option value="">Select</option>
    		<?php foreach($currencies as $currency): ?>
    			<option value="<?=$currency['currencyId']?>" <?=$currency['default']?'selected':''?>><?=$currency['currency']?></option>
    		<?php endforeach; ?>
    	</select>
    </div>

    <!-- image upload -->
    <div class="field">
		<?=form_label('Select Image')?>
		<div class="file-upload px-2 py-2 upload-demo">
			<div class="uploadBtn">
				<div class="fileUpload ui button teal">
					<span class="fa fa-image"> Browse</span>
					<?=form_upload('itemImage','','class="upload" onchange="readURL(this)" accept=".jpg,.png,.gif"')?>
				</div>
			</div>
			<div class="preview">
				<?php if(!empty($item['imgPath'])): ?>
					<img src="<?=$item['imgPath']?>" id="previewImg" class="ui centered image rounded" />
				<?php else: ?>
					<img src="assets/imgs/preview.png" id="previewImg" class="ui centered image rounded" />
				<?php endif; ?>
			</div>
		</div>
	</div>

    <h4 class="ui blue dividing header">Other Information</h4>

    <div class="field">
        <?=form_label($this->lang->line('remark').' <span class="default text">(Optional)</span>')?>
        <?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
    </div>

    <div class="field">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button blue"')?>
        <a href="<?=$_SERVER['HTTP_REFERER']?>" class="ui button"><?=$this->lang->line('cancel')?></a>
    </div>
<?=form_close()?>
</div>
</div>

<!-- Category Modal -->
<div class="ui tiny modal category">
  	<div class="header">
    	<?=$this->lang->line('add_category')?>
  	</div>
  	<div class="content">
    <?=form_open('ignite/addCategory/new-item/'.$referer, 'class="ui form" id="catSubmit"')?>
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
			    	
			    </div>
			</div>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
  	</div>
    <div class="actions">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button green"')?>
    </div>
    <?=form_close()?>
</div>

<!-- Brand Modal -->
<div class="ui tiny modal brand">
	<div class="header">
	    <?=$this->lang->line('add_brand')?>
	</div>
	<div class="content">
	    <?=form_open('ignite/addBrand/new-item/'.$referer, 'class="ui form"')?>
	    <div class="field">
	    	<label><?=$this->lang->line('name')?></label>
	    	<?=form_input('name','','placeholder="'.$this->lang->line('brand_name').'" required')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('remark')?> ( Optional )</label>
	    	<?=form_textarea('remark','','placeholder="'.$this->lang->line('remark').'"')?>
	    </div>
	</div>
	<div class="actions">
	    <?=form_submit('save',$this->lang->line('save'),'class="ui button yellow"')?>
	</div>
    <?=form_close()?>
</div>

<!-- Supplier Modal -->
<div class="ui tiny modal supplier">
	<div class="header">
	    <?=$this->lang->line('new_supplier')?>
	</div>
    <?=form_open('ignite/addSupplier/new-item/'.$referer, 'class="ui form"')?>
  	<div class="scrolling content">
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

	</div>
	<div class="actions">
        <?=form_submit('save',$this->lang->line('save'),'class="ui button pink"')?>
	</div>
	<?=form_close()?>
</div>

<!-- Image Crop Modal -->
<!-- <div class="ui modal crop">
	<div class="header">
	    Crop Image
	</div>
	<div class="content centered">
	    <div id="img_prev" style="width:400px;"></div>
        <button class="ui button olive crop_my_image">Store</button>
	</div>
</div> -->