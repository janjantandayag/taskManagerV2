<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/document_functions.php');

  $documents_query = getAllDocuments();

?>

  <div class="right_col" role="main">
  	<?php
	    if(isset($_SESSION['action_success'])) {
	      $alert['class_type'] = 'success';
	      $alert['text'] = 'SUCCESS';
	      $message = $_SESSION['action_success'];
  	?>
	  <div class="row">
	    <div class="alert alert-<?= $alert['class_type'] ?> alert-dismissible fade in" role="alert" style="margin-top:70px">
	            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	            </button>
	            <strong><?= $alert['text'] ?></strong> <?= $message; ?>
	        </div>  
	  </div>
	  <?php } unset($_SESSION['action_success']); ?>
	<div class="row" style="margin-top:70px">
		<div class="page-title">
          <div class="title_left">
            <h3>All Documents</h3>
          </div>
        </div>
		<div class="col-md-12 col-sm-12 col-xs-12" style="background:#fff;border-top:5px solid #cecece">
			<div class="x_content">    
		        <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="0" width="100%">
		         <thead>
	                <tr>
	                  <th  style="cursor: pointer;">Document Name</th>
	                  <th style="cursor: pointer;">Description</th>
	                  <th  style="cursor: pointer;">Effective Date</th>
	                  <th  style="cursor: pointer;">Obscelence Date</th>
	                  <th  style="cursor: pointer;">Type</th>
	                  <th  style="cursor: pointer;">Created By</th>
	                  <th  style="cursor: pointer;">Action</th>
	                </tr>
	              </thead>
		          <tbody>		          
		          	<?php while($document = mysqli_fetch_assoc($documents_query)) : ?>	
                 	<tr id="row_document_<?= $document['document_id'] ?>">
                 		<td>
                 			<a href="//<?= $document['document_link'] ?>" title="<?= ucwords($document['document_name']) ?>" target="_new"> 
                 			<?php
                 				if(strlen($document['document_name']) > 20){
                 					echo substr(ucwords($document['document_name']), 0, 20) . ' ...';
                 				} else {
                 					echo ucwords($document['document_name']);
                 				}
                 			?>           
                 			</a>
	                	</td>
	                	<td title="<?= ucfirst($document['document_description']); ?>"> <?= substr(ucfirst($document['document_description']),0,20); ?> </td>
	                	<td><?= strtotime($document['effective_date']) ? date('F d, Y | l', strtotime($document['effective_date'])) : 'NOT SET' ?></td>
	                	<td><?= strtotime($document['obscelence_date']) ? date('F d, Y | l', strtotime($document['obscelence_date'])) : 'NOT SET' ?></td>
                 		<td><?= $document['type'] ? ucwords($document['type']) : 'NOT SET' ?></td>
                 		<td><a href="users_view.php?user_id=<?=$document['user_id']?>"> <?= ucwords($document['first_name'] . ' ' . $document['last_name']) ?> </a></td>
                 		<td>
                 			<a href="documents_update.php?document_id=<?=$document['document_id'];?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="deleteDocument(<?= $document['document_id'] ?>,'<?= $document['document_name']?>');"><i class="fa fa-trash-o"></i> Delete </a>
                 		</td>
	                </tr>
	            	<?php endwhile; ?>
		          </tbody>
		        </table>
		      </div>
		</div>
	</div>
  </div>

<?php
  include('includes/footer.php');



} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
