<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Vacation - <?= $isUpdate ? 'Update' : 'Details'?></h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Title</label>
        <input type="text" class="form-control has-feedback-right"  required name="vacation_title" value="<?= $isUpdate ? ucwords($vacation['title']) : '' ?>" placeholder="Vacation Title...">
        <span class="fa fa-tag form-control-feedback right" aria-hidden="true"></span>
      </div>     
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Type</label>
        <input type="text" class="form-control has-feedback-right"  required name="vacation_type" value="<?= $isUpdate ? ucfirst($vacation['type']) : '' ?>" placeholder="Vacation Type...">
        <span class="fa fa-tag form-control-feedback right" aria-hidden="true"></span>
      </div>   
       <div class="col-md-6 col-sm-6 col-xs-12">
        <label>Start Date</label>
        <div class="form-group">
          <div class="input-group" id="myDatepicker0">
              <input type="text" class="form-control date" name="start_date" required placeholder="Start Date" value="<?= $isUpdate ? $vacation['start_date'] : ''  ?>" required>
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <label>End Date</label>
        <div class="form-group">
          <div class="input-group" id="myDatepicker1">
              <input type="text" class="form-control date" name="end_date" required placeholder="End Date" value="<?= $isUpdate ? $vacation['end_date'] : ''?>" required>
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
        <label>Description</label>
        <textarea name="description" rows="3" class="form-control" name="vacation_description" required placeholder="Vacation Description..."><?= $isUpdate ? ucfirst($vacation['description']) : '' ?></textarea>
      </div>   
  </div>  
  <div class="col-xs-12" style="text-align: center">              
    <?php if($isUpdate) : ?>
    <input type="hidden" name="vacation_id" value="<?=$vacation['vacation_id']?>">
    <?php endif; ?>
    <button class="btn btn-primary btn-sm" type="submit" name="<?= $isUpdate ? 'update_request' : 'send_request'?>"><?= $isUpdate ? 'Update' : 'Send' ?> Request</button>
  </div>              
</div>
</div>