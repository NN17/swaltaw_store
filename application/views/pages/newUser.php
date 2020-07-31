<h3 class="text-center"><?=$this->lang->line('new_user')?></h3>

<div class="ui three column centered grid">
  	<div class="column"> 
  		<?=form_open('ignite/addUser','class="ui form"')?>
		  	<div class="field">
			    <label><?=$this->lang->line('user_name')?></label>
			    <input type="text" name="name" placeholder="Username Name" required>
		  	</div>
		  	<div class="field">
			    <label><?=$this->lang->line('password')?></label>
			    <input type="password" name="psw" placeholder="Password" required>
		  	</div>
		  
		  	<div class="ui divider"></div>
		  	<div class="field text-center">
			  	<a href="users" class="ui button negative"><?=$this->lang->line('cancel')?></a>
			  	<button class="ui button positive" type="submit"><?=$this->lang->line('save')?></button>
		  	</div>
		<?=form_close()?>
  	</div>
</div>

