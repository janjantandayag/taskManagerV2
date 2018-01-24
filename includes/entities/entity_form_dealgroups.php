<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Entity-Deal Groups Assignment</h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Deal Group</label>
        <span class="fa fa-users form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
        <select class="form-control has-feedback-right" name="deal_group" required id="taskadd_dealgroup">         
          <option value="" disabled <?= $isUpdate ? '':'selected' ?>>Select deal group...</option>
        </select>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Type</label>
        <input type="text" class="form-control has-feedback-right"  name="task_type" placeholder="Type" required value="<?= $isUpdate ? $task['task_type'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" aria-hidden="true"></span>
      </div>
    </div>
  </div>
</div>
