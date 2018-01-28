<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Deal Groups Assignment</h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content" id="dealGroupContainer">

      <?php
        if($isUpdate) {
          $dealgroup_query = getAssignedDealGroups($entity_details['entity_id']);
          while($dealgroup_assigned = mysqli_fetch_assoc($dealgroup_query)) {
       ?>
       <div class="dealgroupInitial">
        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
          <label>Deal Group</label>
          <span class="fa fa-users form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
          <select class="form-control has-feedback-right taskadd_dealgroup" name="deal_group[]" required id="taskadd_dealgroup">         
            <option value="" >Select deal group...</option>
            <?php
              $dealgroups_query = getDealGroups();
              while($dealgroup = mysqli_fetch_assoc($dealgroups_query)) {
                $selected = $dealgroup['dealgroup_id'] === $dealgroup_assigned['dealgroup_id'] ? 'selected' : '';
            ?>
            <option value="<?= $dealgroup['dealgroup_id'] ?> " <?= $selected ?> ><?= $dealgroup['group_name'] . ' ( ' . $dealgroup['code_name'] .' )' ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-5 col-sm-5 col-xs-12 form-group has-feedback">
          <label>Type</label>
          <input type="text" class="form-control has-feedback-right entity_type"  name="entity_type[]" placeholder="Type" value="<?= $dealgroup_assigned['type'] ?>" required >
          <span class="fa fa-tags form-control-feedback right" aria-hidden="true"></span>
        </div>
        <div class="col-xs-1">             
          <button class="btn btn-danger btn-xs" onclick="removeDealGroup(this);" type="button" style="margin-top:28px;"><span class="fa fa-minus"></span></button> 
        </div>      
      </div>

      <?php } } else { ?>
      <div class="dealgroupInitial">
        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
          <label>Deal Group</label>
          <span class="fa fa-users form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
          <select class="form-control has-feedback-right taskadd_dealgroup" name="deal_group[]" required id="taskadd_dealgroup">         
            <option value="" >Select deal group...</option>
            <?php
              $dealgroup_query = getDealGroups();
              while($dealgroup = mysqli_fetch_assoc($dealgroup_query)) {
            ?>
            <option value="<?= $dealgroup['dealgroup_id'] ?>" ><?= $dealgroup['group_name'] . ' ( ' . $dealgroup['code_name'] .' )' ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-5 col-sm-5 col-xs-12 form-group has-feedback">
          <label>Type</label>
          <input type="text" class="form-control has-feedback-right entity_type"  name="entity_type[]" placeholder="Type" required >
          <span class="fa fa-tags form-control-feedback right" aria-hidden="true"></span>
        </div>
        <div class="col-xs-1">             
          <button class="btn btn-danger btn-xs" onclick="removeDealGroup(this);" type="button" style="margin-top:28px;display: none;"><span class="fa fa-minus"></span></button> 
        </div>      
      </div>
    <?php  } ?>
    </div>

  <button class="btn btn-info btn-xs addDealGroup" type="button" ><span class="fa fa-plus"></span> Add Deal Group</button>         
  </div>
</div>
