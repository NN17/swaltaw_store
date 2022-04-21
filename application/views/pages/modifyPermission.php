<h3>User Permission</h3>
<div class="ui divider"></div>

<?=form_open('ignite/updatePermission/'.$user->accId) ?>
<table class="ui definition table">
  <thead>
    <tr><th></th>
    <th>Modify</th>
    <th>Description</th>
  </tr></thead>
  <tbody>
    <?php foreach($links as $link): ?>
    <tr>
      <td>
        <div class="ui toggle checkbox">
          <input type="checkbox" <?=$this->libigniter->is_in_array($link->linkId , json_decode($user->link))?'checked="checked"':''?> name="permission<?=$link->linkId?>">
          <label></label>
        </div>

        <i class="icon <?=$link->color?> <?=$link->icon_class?>"></i>
        <?=$link->linkName?>
      </td>
      <td>
        <div class="ui toggle checkbox">
          <input type="checkbox" <?=$this->libigniter->is_in_array($link->linkId , json_decode($user->modify))?'checked="checked"':''?> name="modify<?=$link->linkId?>">
          <label></label>
        </div>
      </td>
      <td><?=$link->description?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div class="text-center field">
  <a href="users" class="ui button">Cancel</a>
  <?=form_submit('Save','Apply', 'class="ui button green"')?>
</div>
<?=form_close()?>