<h3>User Permission</h3>
<div class="ui divider"></div>

<?=form_open('ignite/updatePermission/'.$user->accId) ?>
<table class="ui definition table">
  <thead>
    <tr><th></th>
    <th>Action</th>
    <th>Description</th>
  </tr></thead>
  <tbody>
    <?php foreach($links as $link): ?>
    <tr>
      <td>
        <i class="icon <?=$link->color?> <?=$link->icon_class?>"></i>
        <?=$link->linkName?>
      </td>
      <td>
        <div class="ui toggle checkbox">
          <input type="checkbox" <?=in_array($link->linkId, explode(',', $user->link_accept))?'checked="checked"':''?> name="permission<?=$link->linkId?>">
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
  <?=form_submit('Save','Save', 'class="ui button green"')?>
</div>
<?=form_close()?>