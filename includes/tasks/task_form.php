<?php
  if($isUpdate){
    $task_id = $_GET['task_id'];
    $task = getTaskDetails($task_id);
  }
?>

<div class="row">
  <div class="x_panel">
    <div class="x_title">
      <h2>Task <?= $isUpdate ? 'Update' : 'Details' ?></h2>
      <ul class="nav navbar-right panel_toolbox">
      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Task Title</label>
        <input type="text" class="form-control has-feedback-left"  required name="task_title" value="<?= $isUpdate ? $task['title'] : ''?>" placeholder="Task Title">
        <span class="fa fa-tag form-control-feedback left" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <label>Due Date</label>
        <div class="form-group">
          <div class="input-group date" id="myDatepicker0">
              <input type="text" class="form-control" name="due_date" placeholder="Due Date" value="<?= $isUpdate ? $task['due_date'] : ''?>" required>
              <span class="input-group-addon">
                 <span class="glyphicon glyphicon-calendar"></span>
              </span>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Deal Group</label>
        <span class="fa fa-users form-control-feedback left" style="margin-top: 30px" aria-hidden="true"></span>
        <select class="form-control has-feedback-left" name="deal_group" required >         
          <?php $dealgroup_query = getDealGroups();  ?>
          <option value="" disabled <?= $isUpdate ? '':'selected' ?>>Select deal group...</option>
          <?php while($deal_group = mysqli_fetch_assoc($dealgroup_query)) : ?>
          <option value="<?= $deal_group['dealgroup_id']; ?>" <?= ($isUpdate && $deal_group['dealgroup_id'] === $task['dealgroup_id']) ? 'selected' : ''?>><?= $deal_group['group_name'] . ' (' .$deal_group['code_name']. ')' ?></option>
          <?php endwhile;?>
        </select>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group">
        <label>Document</label>
        <span class="fa fa-file form-control-feedback right" style="margin-top: 30px;margin-right: 10px" aria-hidden="true"></span>
        <select class="form-control has-feedback-right" name="document" required >           
          <?php 
            $document_query = getAllDocuments();
          ?>
          <option value="" disabled <?= $isUpdate ? '':'selected' ?>>Select document...</option>
          <?php while($document = mysqli_fetch_assoc($document_query)) : ?>
          <option value="<?=$document['document_id'];?>" <?= ($isUpdate && $document['document_id'] === $task['document_id']) ? 'selected' : ''?>><?=$document['document_name'];?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Reference</label>
        <input type="text" class="form-control has-feedback-left" required name="task_ref" placeholder="Reference" value="<?= $isUpdate ? $task['reference'] : ''?>" >
        <span class="fa fa-search form-control-feedback left" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Link To Support</label>
        <input type="text" class="form-control has-feedback-right"  required name="link_to_support" placeholder="Link To Support" value="<?= $isUpdate ? $task['link_to_support'] : ''?>">
        <span class="fa fa-file form-control-feedback right" aria-hidden="true"></span>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Type</label>
        <input type="text" class="form-control has-feedback-left"  name="task_type" placeholder="Type" required value="<?= $isUpdate ? $task['task_type'] : ''?>">
        <span class="fa fa-tags form-control-feedback left" aria-hidden="true"></span>
      </div>
      <?php if($isUpdate) : ?>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Status</label>        
        <span class="fa fa-file form-control-feedback right" style="margin-right: 10px;" aria-hidden="true"></span>
        <select class="form-control has-feedback-right" name="task_status" required >           
          <option value="IN PROGRESS" <?= $task['status'] == 'IN PROGRESS' ? 'selected' : ''  ?>>In Progress</option>
          <option value="FINISHED" <?= $task['status'] == 'FINISHED' ? 'selected' : ''  ?>>Finished</option>
        </select>
      </div>
      <?php endif; ?>
    </div>  
    <div class="row" style="padding:0px 12px 0 12px">          
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Language</label>
        <textarea class="form-control" rows="10" style="resize: none" name="task_language" placeholder="Language" required ><?= $isUpdate ? str_replace("<br />","",$task['language']) : ''?></textarea>
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
        <label>Note</label>
        <textarea class="form-control" rows="10" style="resize: none" name="task_note" placeholder="Note" required><?= $isUpdate ? str_replace("<br />","",$task['note']) : ''?></textarea>
      </div>
    </div>
  </div>  
  <div class="col-xs-12" style="text-align: center">              
    <input type="hidden" value="<?= $isUpdate ? $task_id : '' ?>" name="task_id">
    <button class="btn btn-primary btn-xs" name="<?= $isUpdate ? 'edit_task' : 'add_task'?>" type="submit"><?= $isUpdate ? 'EDIT' : 'ADD' ?> TASK</button>
  </div>              
</div>