<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Entities Assignment</h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content" id="dealGroupContainer">
      <?php
        $count_assigned_entity = $isUpdate ? getAssocEntities($dealgroup['dealgroup_id']) : 0;
        if($isUpdate && $count_assigned_entity->num_rows > 0) {
          $entity_query = getAssocEntities($dealgroup['dealgroup_id']);
          while($entity_assigned = mysqli_fetch_assoc($entity_query)) {
       ?>       
       <div class="dealgroupInitial">
       <div class="row">
        <div class="col-md-3 col-sm-3 col-xs-12 form-group">
          <label>Entity Name</label>
          <span class="fa fa-users form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
          <select class="form-control has-feedback-right taskadd_dealgroup" name="entity[]" id="taskadd_dealgroup">         
            <option value="" >Select entity...</option>
            <?php
              $entities_query = getEntities();
              while($entity = mysqli_fetch_assoc($entities_query)) {
                $selected = $entity['entity_id'] === $entity_assigned['entity_id'] ? 'selected' : '';
            ?>
            <option value="<?= $entity['entity_id'] ?>" <?= $selected ?>><?= ucwords($entity['entity_legal_name']) . ' ( ' . ucwords($entity['entity_nickname']) .' )' ?></option>
            <?php } ?>
          </select>
        </div>
           <div class="col-md-3 col-sm-3 col-xs-12">
          <label>Start Date</label>
          <div class="form-group">
              <div class="input-group date" id="myDatepicker1">
                  <input type="text" class="form-control"  name="start_date[]" id="start_date" value="<?= $entity_assigned['start_date'] ?>">
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
                  <input type="text" class="form-control" name="end_date[]" id="end_date" value="<?= $entity_assigned['end_date'] ?>">
                  <span class="input-group-addon">
                     <span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div>
        </div>   
        <div class="col-md-2 col-sm-2 col-xs-12 form-group has-feedback">
          <label>Type</label>
          <input type="text" class="form-control has-feedback-right entity_type"  id="type" name="type[]" placeholder="Type" value="<?= ucwords($entity_assigned['type']) ?>" >
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
          <label>Entity Name</label>
          <span class="fa fa-users form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
          <select class="form-control has-feedback-right taskadd_dealgroup" name="entity[]" id="taskadd_dealgroup">         
            <option value="" >Select entity...</option>
            <?php
              $entities_query = getEntities();
              while($entity = mysqli_fetch_assoc($entities_query)) {
            ?>
            <option value="<?= $entity['entity_id'] ?>" ><?= ucwords($entity['entity_legal_name']) . ' ( ' . ucwords($entity['entity_nickname']) .' )' ?></option>
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
          <input type="text" class="form-control has-feedback-right entity_type"  name="type[]" id="type" placeholder="Type" >
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
