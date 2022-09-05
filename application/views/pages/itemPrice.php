<div class="ui clearing segment teal">
	<h3 class="ui teal left floated header"><?=$this->lang->line('itemPrice')?></h3>
	<a href="new-item/~" class="ui right floated button teal <?=$this->auth->checkModify($this->session->userdata('Id'), 'items-price/0')?'':'disabled'?>">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>

<div class="ui large icon input">
  <input type="text" placeholder="Search..." onkeyup="igniteAjax.searchItem()" id="searchItems">
  <i class="search icon"></i>
</div>

<table class="ui teal table" id="">
	<thead>
		<tr>
			<th>#</th>
			<th><?=$this->lang->line('item_name')?></th>
			<th><?=$this->lang->line('image')?></th>
			<th class="ui right aligned"><?=$this->lang->line('purchase_price')?></th>
			<th class="ui right aligned"><?=$this->lang->line('sell_price')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody id="itemPriceBody">
		<?php 
			$cat = "";
			foreach($items as $item):
				$p_countType = $this->ignite_model->get_limit_datas('count_type_tbl', ['related_item_id' => $item['itemId'], 'type' => 'P'])->row();
				$s_countType = $this->ignite_model->get_limit_datas('count_type_tbl', ['related_item_id' => $item['itemId'], 'type' => 'R'])->result();
				$w_countType = $this->ignite_model->get_limit_datas('count_type_tbl', ['related_item_id' => $item['itemId'], 'type' => 'W'])->result();
		?>
			
			<tr>
				<td><?=$item['codeNumber']?></td>
				<td><?=$item['itemName']?></td>
				<td><button class="ui basic button tiny icon olive <?=!empty($item['imgPath'])?'':'disabled'?>" onclick="viewImg('<?=$item['imgPath']?>')"><i class="ui icon eye"></i></button></td>
				<td class="ui right aligned"><strong><?=$p_countType?round($p_countType->price/$p_countType->qty , 2).' / <span class="text-grey">( '.number_format($p_countType->price).' )</span>':'-'?></strong></td>
				<td class="ui right aligned"><strong>
					<?php
						if(count($s_countType) > 0):
						$count = 1;
						foreach($s_countType as $s_type): 
					?>
						<?=number_format($s_type->price)?>
						<?=$count < count($s_countType)?' / ': ''?>
					<?php
						$count++; 
						endforeach; 
						else:
					?>
						
					<?php endif; ?>

					<?php
						if(count($w_countType) > 0):
						$count = 1;
						foreach($w_countType as $w_type): 
					?>
						<?=' / '.number_format($w_type->price)?>
						<?=$count < count($w_countType)?' / ': ''?>
					<?php
						$count++; 
						endforeach; 
						else:
					?>
						
					<?php endif; ?>
					</strong></td>
				<td>
					<?php 
						$checkPrice = $this->ignite_model->checkPrice($item['itemId']);
					 ?>
					<a href="define-price/<?=$item['itemId']?>/~" class="ui button icon tiny circular <?=$checkPrice ? 'green' : 'yellow'?> <?=$this->auth->checkModify($this->session->userdata('Id'), 'items-price/0')?'':'disabled'?>" data-content="Update Price"><i class="ui icon money bill alternate outline"></i></a>
					<a href="edit-item/<?=$item['itemId']?>" class="ui icon button tiny orange circular <?=$this->auth->checkModify($this->session->userdata('Id'), 'items-price/0')?'':'disabled'?>"><i class="ui icon cog"></i></a>
					<a href="javascript:void(0)" class="ui icon button tiny red circular <?=$this->auth->checkModify($this->session->userdata('Id'), 'items-price/0')?'':'disabled'?>" id="delete" data-url="ignite/deleteItem/<?=$item['itemId']?>"><i class="ui icon remove"></i></a>
				</td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>

<div class="ui pagination center aligned">
	<?=$this->pagination->create_links();?>
</div>


<!-- Image Modal -->
<div class="ui tiny modal imgPreview">
	<div class="header">
	    Preview Image
	</div>
	<div class="content centered" id="imgContent">
	    
	</div>
</div>

