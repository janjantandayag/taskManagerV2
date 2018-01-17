<?php 
include('./database/position_functions.php');
?>
<div class="x_panel">
	<div class="x_title">
		<h2>Positions</h2>
		<ul class="nav navbar-right panel_toolbox">
			<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
		</ul>
		<div class="clearfix"></div>
	</div>
	<div class="x_content" id="position_container">
		<?php if(!$isUpdate) : ?>
		<div class="row initialInput" style="border-bottom:1px solid #f5eded;padding-top:5px">
			<div class="col-md-4 col-sm-4 col-xs-12 form-group has-feedback">
				<label>Position Name</label>
				<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
				<select class="form-control has-feedback-left position_title" name="position_id[]" required >					
					<option value=""></option>
					<?php 
						$query = getPositions();
						while($position = mysqli_fetch_assoc($query)) {
							$selected = $position['position_id'] === $row['position_id'] ? 'selected' : '';
					?>
					<option value="<?=$position['position_id']?>" <?= $selected ?> ><?=$position['position_title']?></option>
					<?php } ?>
				</select>
			</div>		
			<div class="col-md-4 col-sm-4 col-xs-12">
				<label>Start Date</label>
				<div class="form-group">
                    <div class="input-group date" id="myDatepicker1">
                        <input type="text" class="form-control"  name="start_date[]">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<label>End Date</label>								
				<div class="form-group">
                    <div class="input-group date" id="myDatepicker2">
                        <input type="text" class="form-control" name="end_date[]">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
			</div>	 
			<div class="col-xs-12">							
				<button class="btn btn-danger btn-xs" onclick="removeRow(this)" type="button" style="display: <?= $isUpdate ? '':'none' ?>"><span class="fa fa-minus"></span></button> 
			</div>                   									
    	</div>   
    	<?php endif; ?>
		<?php
			if($isUpdate){
				$position_query = getPositionHistory($user_id);
				if ($position_query->num_rows > 0 ) {
				while($row = mysqli_fetch_assoc($position_query)) {
		?>
		<div class="row initialInput" style="border-bottom:1px solid #f5eded;padding-top:5px">
			<div class="col-md-4 col-sm-4 col-xs-12 form-group has-feedback">
				<label>Position Name</label>
				<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
				<select class="form-control has-feedback-left position_title" name="position_id[]" required >					
					<option value=""></option>
					<?php 
						$query = getPositions();
						while($position = mysqli_fetch_assoc($query)) {
							$selected = $position['position_id'] === $row['position_id'] ? 'selected' : '';
					?>
					<option value="<?=$position['position_id']?>" <?= $selected ?> ><?=$position['position_title']?></option>
					<?php } ?>
				</select>
			</div>		
			<div class="col-md-4 col-sm-4 col-xs-12">
				<label>Start Date</label>
				<div class="form-group">
                    <div class="input-group date" id="myDatepicker1">
                        <input type="text" class="form-control" value="<?= $isUpdate ? $row['start_date'] : '' ?>" name="start_date[]">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<label>End Date</label>								
				<div class="form-group">
                    <div class="input-group date" id="myDatepicker2">
                        <input type="text" class="form-control" value="<?= $isUpdate ? $row['end_date'] : '' ?>" name="end_date[]">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
			</div>	 
			<div class="col-xs-12">							
				<button class="btn btn-danger btn-xs" onclick="removeRow(this)" type="button" style="display: <?= $isUpdate ? '':'none' ?>"><span class="fa fa-minus"></span></button> 
			</div>                   									
    	</div>                           	         
    	<?php } } else { ?>
		<div class="row initialInput" style="border-bottom:1px solid #f5eded;padding-top:5px">
			<div class="col-md-4 col-sm-4 col-xs-12 form-group has-feedback">
				<label>Position Name</label>
				<span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
				<select class="form-control has-feedback-left position_title" name="position_id[]" required >					
					<option value=""></option>
					<?php 
						$query = getPositions();
						while($position = mysqli_fetch_assoc($query)) {
							$selected = $position['position_id'] === $row['position_id'] ? 'selected' : '';
					?>
					<option value="<?=$position['position_id']?>" <?= $selected ?> ><?=$position['position_title']?></option>
					<?php } ?>
				</select>
			</div>		
			<div class="col-md-4 col-sm-4 col-xs-12">
				<label>Start Date</label>
				<div class="form-group">
                    <div class="input-group date" id="myDatepicker1">
                        <input type="text" class="form-control"  name="start_date[]">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<label>End Date</label>								
				<div class="form-group">
                    <div class="input-group date" id="myDatepicker2">
                        <input type="text" class="form-control" name="end_date[]">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
			</div>	 
			<div class="col-xs-12">							
				<button class="btn btn-danger btn-xs" onclick="removeRow(this)" type="button" style="display: none"><span class="fa fa-minus"></span></button> 
			</div>                   									
    	</div>   
	    <?php } } ?>    	     
	</div>
	<button class="btn btn-info btn-xs addPositionButton" type="button" ><span class="fa fa-plus"></span> Add Position</button>        	
</div>			