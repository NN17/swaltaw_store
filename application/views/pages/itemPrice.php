<h3><?=$this->lang->line('itemPrice')?></h3>
<div class="ui divider"></div>

<a href="new-item/~" class="ui button violet">
    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
</a>

<table class="ui table">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('item_name')?></th>
			<th><?=$this->lang->line('brand')?></th>
			<th><?=$this->lang->line('currency')?></th>
			<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
			<th class="ui right aligned"><?=$this->lang->line('retail_price')?></th>
			<th class="ui right aligned"><?=$this->lang->line('wholesale_price')?></th>
			<th><?=$this->lang->line('supplier')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$cat = "";
			foreach($items as $item):
		?>
		<?php if($cat != $item['categoryName']):?>
			<tr>
				<td colspan="9" class="error"><?=$item['categoryName']?></td>
			</tr>
			<?php $cat = $item['categoryName'];?>
		<?php endif;?>
			<tr>
				<td><?=$item['codeNumber']?></td>
				<td><?=$item['itemName']?></td>
				<td><?=$item['brandName']?></td>
				<td><?=$item['currency']?></td>
				<td class="ui right aligned"><strong><?=number_format($item['purchasePrice'])?></strong></td>
				<td class="ui right aligned"><strong><?=number_format($item['retailPrice'])?></strong></td>
				<td class="ui right aligned"><strong><?=number_format($item['wholesalePrice'])?></strong></td>
				<td><?=$item['supplierName']?></td>
				<td>
					<button class="ui button icon tiny circular yellow" onclick="changePriceModal(<?=$item['itemId']?>)"><i class="ui icon money bill alternate outline"></i></button>
					<a href="edit-item/<?=$item['itemId']?>" class="ui icon button tiny orange circular"><i class="ui icon cog"></i></a>
					<a href="javascript:void(0)" class="ui icon button tiny red circular" id="delete" data-url="ignite/deleteItem/<?=$item['itemId']?>"><i class="ui icon remove"></i></a>
				</td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>

<div class="ui pagination center aligned">
	<?=$this->pagination->create_links();?>
</div>

<!-- Change Price Modal -->
<div class="ui tiny modal changePrice">
  	<div class="header">
    	<?=$this->lang->line('change_price')?>
		( <span id="itemName"></span> )
  	</div>
  	<div class="content">
    <?=form_open('', 'class="ui form" id="changePriceForm"')?>
	    <div class="field">
	    	<label><?=$this->lang->line('purchase_price')?></label>
	    	<?=form_number('p_price','','placeholder="'.$this->lang->line('purchase_price').'" id="purchase" required')?>
	    </div>
	    <div class="field">
	    	<label><?=$this->lang->line('retail_price')?></label>
	    	<?=form_number('r_price','','placeholder="'.$this->lang->line('retail_price').'" id="retail" required')?>
	    </div>
		<div class="field">
	    	<label><?=$this->lang->line('wholesale_price')?></label>
	    	<?=form_number('w_price','','placeholder="'.$this->lang->line('wholesale_price').'" id="wholesale" required')?>
	    </div>
  	</div>
    <div class="actions">
        <?=form_submit('update',$this->lang->line('update'),'class="ui button green"')?>
    </div>
    <?=form_close()?>
</div>
