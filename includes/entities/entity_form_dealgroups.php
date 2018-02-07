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
        $count_assigned_dealgroups = $isUpdate ? getAssignedDealGroups($entity_details['entity_id']) : 0;
        if($isUpdate && $count_assigned_dealgroups->num_rows > 0) {
          $dealgroup_query = getAssignedDealGroups($entity_details['entity_id']);
          while($dealgroup_assigned = mysqli_fetch_assoc($dealgroup_query)) {
       ?>       
       <div class="dealgroupInitial">
       <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
          <label>Deal Group</label>
          <span class="fa fa-users form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
          <select class="form-control has-feedback-right taskadd_dealgroup" name="deal_group[]" id="taskadd_dealgroup">         
            <option value="" >Select deal group...</option>
            <?php
              $dealgroups_query = getDealGroups();
              while($dealgroup = mysqli_fetch_assoc($dealgroups_query)) {
                $selected = $dealgroup['dealgroup_id'] === $dealgroup_assigned['dealgroup_id'] ? 'selected' : '';
            ?>
            <option value="<?= $dealgroup['dealgroup_id'] ?> " <?= $selected ?> ><?= ucwords($dealgroup['group_name']) . ' ( ' . ucwords($dealgroup['code_name']) .' )' ?></option>
            <?php } ?>
          </select>
        </div>
           <div class="col-md-3 col-sm-3 col-xs-12">
          <label>Start Date</label>
          <div class="form-group">
              <div class="input-group date" id="myDatepicker1">
                  <input type="text" class="form-control"  name="start_date[]" id="start_date" value="<?= $dealgroup_assigned['start_date'] ?>">
                  <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <label>End Date</label>               
          <div class="form-group">
              <div class="input-group date" id="myDatepicker2">
                  <input type="text" class="form-control" name="end_date[]" id="end_date" value="<?= $dealgroup_assigned['end_date'] ?>">
                  <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div>
        </div>   
        <div class="col-md-2 col-sm-2 col-xs-12 form-group has-feedback">
          <label>Type</label>
          <input type="text" class="form-control has-feedback-right entity_type"  id="type" name="entity_type[]" placeholder="Type" value="<?= ucwords($dealgroup_assigned['type']) ?>" >
          <span class="fa fa-tags form-control-feedback right" aria-hidden="true"></span>
        </div>
        <div class="col-xs-1">             
          <button class="btn btn-danger btn-xs" onclick="removeDealGroup(this);" type="button" style="margin-top:28px;"><span class="fa fa-minus"></span></button> 
        </div>      
      </div>
    </div>

      <?php } } else { ?>      
      <div class="dealgroupInitial">
      <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
          <label>Deal Group</label>
          <span class="fa fa-users form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
          <select class="form-control has-feedback-right taskadd_dealgroup" name="deal_group[]" id="taskadd_dealgroup">         
            <option value="" >Select deal group...</option>
            <?php
              $dealgroup_query = getDealGroups();
              while($dealgroup = mysqli_fetch_assoc($dealgroup_query)) {
            ?>
            <option value="<?= $dealgroup['dealgroup_id'] ?>" ><?= ucwords($dealgroup['group_name']) . ' ( ' . ucwords($dealgroup['code_name']) .' )' ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <label>Start Date</label>
          <div class="form-group">
              <div class="input-group date" id="myDatepicker1">
                  <input type="text" class="form-control"  name="start_date[]" id="start_date">
                  <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12">
          <label>End Date</label>               
          <div class="form-group">
              <div class="input-group date" id="myDatepicker2">
                  <input type="text" class="form-control" name="end_date[]" id="end_date">
                  <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div>
        </div>   
        <div class="col-md-2 col-sm-2 col-xs-12 form-group has-feedback">
          <label>Type</label>
          <input type="text" class="form-control has-feedback-right entity_type"  name="entity_type[]" id="type" placeholder="Type" >
          <span class="fa fa-tags form-control-feedback right" aria-hidden="true"></span>
        </div>
        <div class="col-xs-1">             
          <button class="btn btn-danger btn-xs" onclick="removeDealGroup(this);" type="button" style="margin-top:28px;display: none;"><span class="fa fa-minus"></span></button> 
        </div>      
      </div>
      </div>
    <?php  } ?>
    </div>

  <button class="btn btn-info btn-xs addDealGroup" type="button" ><span class="fa fa-plus"></span> Add Deal Group</button>         
  </div>
</div>
