<div class="ui clearing green segment">
	<h3 class="ui left floated header"><?=$this->lang->line('service')?></h3>
	<a href="newService" class="ui right floated button green <?=$this->auth->checkModify($this->session->userdata('Id'), 'services')?'':'disabled'?>">
	    <i class="icon plus circle"></i> <?=$this->lang->line('new')?>
	</a>
</div>

<table class="ui table" id="dataTable">

</table>