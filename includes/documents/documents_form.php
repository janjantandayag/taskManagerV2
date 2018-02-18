<?php
  if($isUpdate){
    $document_id = $_GET['document_id'];
    $document = getDocumentDetails($document_id);
  }
?>

<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Document <?= $isUpdate ? 'Update' : 'Details' ?></h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Document Name</label>
        <input type="text" class="form-control has-feedback-right"  required name="document_name" value="<?= $isUpdate ? $document['document_name'] : ''?>" placeholder="Document name">
        <span class="fa fa-file form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
      </div>      
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Document Link</label>
        <input type="text" class="form-control has-feedback-right"  name="document_link" id="document_link_input" placeholder="Document link" required value="<?= $isUpdate ? $document['document_link'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" aria-hidden="true" style="margin-top: 30px"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Effective Date</label>
        <div class="input-group" id="myDatepicker0">
            <input type="text" class="form-control date" name="effective_date" placeholder="Effective date" value="<?= $isUpdate ? $document['effective_date'] : ''?>" required>
            <span class="input-group-addon">
               <span class="glyphicon glyphicon-calendar"></span>
            </span>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Obscelence Date</label>
          <div class="input-group" id="myDatepicker1">
              <input type="text" class="form-control date" name="obscelence_date" placeholder="Obscelence date" value="<?= $isUpdate ? $document['obscelence_date'] : ''?>" required>
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Type</label>
        <input type="text" class="form-control has-feedback-right"  name="type" placeholder="Type" required value="<?= $isUpdate ? $document['type'] : ''?>">
        <span class="fa fa-tags form-control-feedback right" style="margin-top: 30px" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Date Created</label>
        <div class="form-group">
          <div class="input-group" id="myDatepicker2">
              <input type="text" class="form-control date" name="date_created" placeholder="Date created" value="<?= $isUpdate ? $document['date_created'] : ''?>" required>
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar" ></span>
              </span>
          </div>
        </div>
      </div>
      <?php if($isUpdate) : ?>
        <div class="col-md-6 col-sm-6 col-xs-12 form-group">
          <label>Created By</label>
          <span class="fa fa-file form-control-feedback right" style="margin-top: 30px;margin-right: 10px" aria-hidden="true"></span>
          <select class="form-control has-feedback-right" name="created_by" required>  
            <option value="" disabled <?= $isUpdate ? '':'selected' ?>>Select created by...</option>
            <?php
              $getActiveUsers = getActiveUsers();
              while($user = mysqli_fetch_assoc($getActiveUsers)) { 
                $selected = $document['created_by'] === $user['user_id'] ? 'selected' : '';
            ?>            
            <option value="<?=$user['user_id']?>" <?= $selected ?> > <?= ucwords($user['first_name'] . ' ' . $user['last_name']) ?> </option>
            <?php
              }
            ?>
          </select>
        </div>
      <?php endif; ?>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Document Description</label>
        <div class="form-group">
          <textarea name="document_description" id="" class="form-control" rows="4"> <?= $isUpdate ? $document['document_description'] : '' ?> </textarea>
        </div>
      </div>
  </div>  
  <div class="col-md-12" style="text-align: center">              
    <input type="hidden" value="<?= $isUpdate ? $document_id : '' ?>" name="document_id">
    <button class="btn btn-primary btn-sm" name="<?= $isUpdate ? 'edit_document' : 'add_document'?>" type="submit"><?= $isUpdate ? 'UPDATE' : 'ADD' ?> DOCUMENT</button>
  </div>              
</div>
</div>