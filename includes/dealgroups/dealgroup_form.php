<?php
  if($isUpdate){
    $dealgroup_id = $_GET['dealgroup_id'];
    $dealgroup = getDealGroupDetails($dealgroup_id);
  }
?>


<div class="x_panel">
  <div class="x_title">
    <h2>Deal Group <?= $isUpdate ? 'Update' : 'Details' ?></h2>
    <ul class="nav navbar-right panel_toolbox">
    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
    </ul>
    <div class="clearfix"></div>
  </div>
  <div class="x_content">
    <div class="row">
      <div class="col-md-6 col-sm-6 col-xs-12 form-group ">
        <label>Main Contact</label>
        <span class="fa fa-users form-control-feedback right" aria-hidden="true" style="margin-top:30px"></span>
        <select class="form-control has-feedback-right" name="dealgroup_main_contact">         
          <option value="" disabled selected>Select main contact...</option>
          <?php
            $users_query = getActiveUsers();
            while($user = mysqli_fetch_assoc($users_query)) {
          ?>
          <option value="<?=$user['user_id']; ?>"><?= ucwords($user['first_name']) . ' ' . ucwords($user['last_name']) ?></option>
          <?php } ?>
        </select>
      </div>      
      <div class="col-md-6 col-sm-6 col-xs-12 form-group ">
        <label>Group Name</label>
        <input type="text" class="form-control has-feedback-right"  name="group_name" placeholder="Group Name"  value="<?= $isUpdate ? $dealgroup['group_name'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" aria-hidden="true" style="margin-top:30px"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Code Name</label>        
        <input type="text" class="form-control has-feedback-right"  name="code_name" placeholder="Code Name"  value="<?= $isUpdate ? $dealgroup['code_name'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" aria-hidden="true" style="margin-top: 30px"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Sector</label>
        <input type="text" class="form-control has-feedback-right"  name="sector" placeholder="Sector"  value="<?= $isUpdate ? $dealgroup['sector'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" aria-hidden="true" style="margin-top: 30px"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Deal Type</label>
        <input type="text" class="form-control has-feedback-right"  name="deal_type" placeholder="Deal Type"  value="<?= $isUpdate ? $dealgroup['deal_type'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" aria-hidden="true" style="margin-top: 30px"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group ">
        <label>Club Syndicate</label>
        <input type="text" class="form-control has-feedback-right"  name="club_syndicate" placeholder="Club Syndicate" value="<?= $isUpdate ? $dealgroup['club_syndicate'] : ''?>" >
        <span class="fa fa-search form-control-feedback right" aria-hidden="true" style="margin-top:30px"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group ">
        <label>Source</label>
        <input type="text" class="form-control has-feedback-right"   name="source" placeholder="Source" value="<?= $isUpdate ? $dealgroup['source'] : ''?>">
        <span class="fa fa-file form-control-feedback right" aria-hidden="true" style="margin-top: 30px"></span>
      </div>  
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Business Description</label>
        <textarea name="business_description" rows="1" style="width: 100%;resize: none;overflow-x: hidden" class="form-control" placeholder="Business description ..."></textarea>
      </div>    
    </div>
<!--     <div class="row">      
      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
        <label>Documents</label>
        <div>          
          <label class="btn btn-success btn-xs" id="document_btn_label">
            Browse documents ... <input type="file"  id="document_btn" name="documents[]" class="form-control" multiple="multiple" hidden />
         </label>       

         <div id="file_to_upload_container" style="display: none;margin-top:30px">
            <label>Selected Files</label>
            <div id="file_container">
              <table id="file_container_tb">
              </table>
            </div>
          </div>
        </div> 
      </div>
    </div> -->
    <div class="row" style="margin-top:30px">
      <div class="col-xs-12" style="text-align: center">              
        <input type="hidden" value="<?= $isUpdate ? $dealgroup_id : '' ?>" name="dealgroup_id">
        <button class="btn btn-primary btn-sm" name="<?= $isUpdate ? 'edit_dealgroup' : 'add_dealgroup'?>" type="submit"><?= $isUpdate ? 'EDIT' : 'ADD' ?> DEAL GROUP</button>
      </div>             
    </div>
  </div>          
</div>