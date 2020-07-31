<h3 class="text-center"><?=$this->lang->line('reset_password')?></h3>
<div class="ui three column centered grid">
  	<div class="column"> 
  		<?=form_open('ignite/updatePassword/'.$acc->accId,'class="ui form"')?>
		  	<div class="field">
			    <label><?=$this->lang->line('user_name')?></label>
			    <input type="text" name="name" value="<?=$acc->username?>" placeholder="Username Name" required readonly>
		  	</div>
		  	<div class="field">
			    <label><?=$this->lang->line('new_password')?></label>
			    <input type="password" name="psw" placeholder="New Password" required>
		  	</div>
		  
		  	<div class="ui divider"></div>
		  	<div class="field text-center">
			  	<a href="users" class="ui button negative"><?=$this->lang->line('cancel')?></a>
			  	<button class="ui button positive" type="submit"><?=$this->lang->line('update')?></button>
		  	</div>
		<?=form_close()?>
  	</div>
</div>

