<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Vacation - Details</h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Title</label>
        <input type="text" class="form-control has-feedback-right"  required name="vacation_title" placeholder="Vacation Title...">
        <span class="fa fa-tag form-control-feedback right" aria-hidden="true"></span>
      </div>     
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Type</label>
        <input type="text" class="form-control has-feedback-right"  required name="vacation_type" placeholder="Vacation Type...">
        <span class="fa fa-tag form-control-feedback right" aria-hidden="true"></span>
      </div>   
       <div class="col-md-6 col-sm-6 col-xs-12">
        <label>Start Date</label>
        <div class="form-group">
          <div class="input-group" id="myDatepicker0">
              <input type="text" class="form-control date" name="start_date" required placeholder="Start Date" required>
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <label>Due Date</label>
        <div class="form-group">
          <div class="input-group" id="myDatepicker1">
              <input type="text" class="form-control date" name="end_date" required placeholder="End Date" required>
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
        <label>Description</label>
        <textarea name="description" rows="3" class="form-control" name="vacation_description" required placeholder="Vacation Description..."></textarea>
      </div>   
  </div>  
  <div class="col-xs-12" style="text-align: center">              
    <button class="btn btn-primary btn-sm" type="submit" name="send_request">Send Request</button>
  </div>              
</div>
</div>