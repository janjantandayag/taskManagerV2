<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Entity Details</h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Legal Name</label>
        <input type="text" class="form-control has-feedback-right"  required name="legal_name" placeholder="Legal Name" value="<?= $isUpdate ? $entity_details['entity_legal_name'] : ''?>">
        <span class="fa fa-building form-control-feedback right" aria-hidden="true"></span>
      </div>      
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Nickname</label>
        <input type="text" class="form-control has-feedback-right"  name="nick_name" placeholder="Nickname" required value="<?= $isUpdate ? $entity_details['entity_nickname'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Street Address</label>
        <input type="text" class="form-control has-feedback-right"  name="street_address" placeholder="Street Address" required value="<?= $isUpdate ? $entity_details['street_address'] : ''?>">
        <span class="fa fa-home form-control-feedback right" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>City</label>
        <input type="text" class="form-control has-feedback-right"  name="city" placeholder="City" required value="<?= $isUpdate ? $entity_details['city'] : ''?>"
        <span class="fa fa-map-marker form-control-feedback right" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>State</label>
        <input type="text" class="form-control has-feedback-right"  name="state" placeholder="State" required value="<?= $isUpdate ? $entity_details['state'] : ''?>">
        <span class="fa fa-map form-control-feedback right" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Zip Code</label>
        <input type="text" class="form-control has-feedback-right"  name="zip_code" placeholder="Zip Code" required value="<?= $isUpdate ? $entity_details['zipcode'] : ''?>">
        <span class="fa fa-sort-numeric-asc form-control-feedback right" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Country</label>
        <input type="text" class="form-control has-feedback-right"  name="country" placeholder="Country" required value="<?= $isUpdate ? $entity_details['country'] : ''?>">
        <span class="fa fa-map-pin form-control-feedback right" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Incorporation State</label>
        <input type="text" class="form-control has-feedback-right"  name="incorporation_state" placeholder="Incorporation State" required value="<?= $isUpdate ? $entity_details['incorporation_state'] : ''?>">
        <span class="fa fa-map-o form-control-feedback right" aria-hidden="true"></span>
      </div>          
    </div>
  </div>
</div>
