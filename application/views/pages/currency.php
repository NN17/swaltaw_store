<h3><?=$this->lang->line('currency')?></h3>
<div class="ui divider"></div>

<div class="ui grid">
	<div class="six wide column">
		
		<table class="ui table padded">
			<thead>
				<tr>
					<th>#</th>
					<th>Currency</th>
					<th>Default</th>
				</tr>
			</thead>
			<tbody>
				<div class="ui form">
				<?php
					$i = 1;
					foreach($currencies as $currency):
				?>
				<tr>
					<td><?=$i?></td>
					<td><?=$currency['currency']?></td>
					<td>
						<div class="field">
					      <div class="ui slider checkbox">
					        <input type="radio" id="curDefault" value="<?=$currency['currencyId']?>" name="throughput" <?=$currency['default']?'checked':''?>>
					      </div>
					    </div>
					</td>			
				</tr>
				<?php
					$i ++;
					endforeach;
				?>
				</div>
			</tbody>
		</table>
	</div>
</div>