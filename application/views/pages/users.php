<div class="ui clearing yellow segment">
  <h3 class="ui left floated header"><?=$this->lang->line('accounts')?></h3>
  <a href="new-user" class="ui button right floated primary"><i class="icon plus circle"></i> <?=$this->lang->line('new')?></a>
</div>


<table class="ui definition table" id="dataTable">
  	<thead>
	    <tr>
	    	<th></th>
	    	<th><?=$this->lang->line('account_state')?></th>
	    	<th><?=$this->lang->line('created_date')?></th>
	    	<th><?=$this->lang->line('permission')?></th>
	    	<th></th>
	  	</tr>
  	</thead>
  	<tbody>
  		<?php $i = 1; ?>
  		<?php foreach($users as $user): ?>
  			<?php
  				$arr = explode(',', $user->link_accept);
  				$pCount = count($arr);
  			 ?>
  		<tr>
  			<td><?=$user->username?></td>
  			<td>
          <?=$user->accountState == true?'<i class="icon green check circle"></i>':'<i class="icon red exclamation circle"></i>'?>
          <?=$user->accountState == true?'Active':'Disabled'?></td>
  			<td><?=$user->created_at?></td>
  			<td><?=$pCount?> Permission has allowed.</td>
  			<td>
  				<a href="modify-permission/<?=$user->permissionId?>" class="ui button icon teal" id="popup_link" data-content="Change Permission for user"><i class="user secret icon"></i></a>
  				<a href="reset-password/<?=$user->accId?>" class="ui button icon yellow" id="popup_link" data-content="Reset Password"><i class="key icon"></i></a>
  				<?php if($user->accountState): ?>
  				<a href="disable-user/<?=$user->accId?>" class="ui button icon negative" id="popup_link" data-content="Disable User"><i class="trash alternate icon"></i></a>
  				<?php else: ?>
				  <a href="enable-user/<?=$user->accId?>" class="ui button icon green" id="popup_link" data-content="Enable User"><i class="user circle icon"></i></a>
  				<?php endif; ?>
  			</td>
  		</tr>
  		<?php $i++; ?>
  		<?php endforeach; ?>
    
  	</tbody>
</table>