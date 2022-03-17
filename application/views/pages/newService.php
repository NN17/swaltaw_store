<h3><?=$this->lang->line('new_service')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="eight wide column">
	<?=form_open('ignite/addPurchase', 'class="ui form"')?>

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
    
	<?=form_close()?>
	</div>
</div>


</div>