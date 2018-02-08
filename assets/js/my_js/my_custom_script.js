$('#myDatepicker1').datetimepicker({
    format: 'YYYY-MM-DD'
});
$('#myDatepicker2').datetimepicker({
    format: 'YYYY-MM-DD'
});

$(".date").each(function(index){
    $(this).attr('id','myDatepicker'+index);
    $('#myDatepicker'+index).datetimepicker({
        format: 'YYYY-MM-DD'
    });
});

$('.addPositionButton').click(function(){  
    $copy = $( ".initialInput:first").clone();
    $copy.find('.date input').val(''); 
    $copy.find('.position_title').val('');
    $copy.appendTo("#position_container");
    $( ".initialInput").find('button').css("display","block");

    $(".date").each(function(index){
        $(this).attr('id','myDatepicker'+index);
        $('#myDatepicker'+index).datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
});

function removeRow(clicked){      
    $rowLength = $(".initialInput").length;
    if($rowLength != 1) {
        $(clicked).parents(".initialInput").remove();
    } 
    if($rowLength == 2){
        $( ".initialInput").find('button').css("display","none");
    }
}

// IMAGE CROPPED
//set image coordinates
function updateCoords(im,obj){
    $('#x').val(obj.x1);
    $('#y').val(obj.y1);
    $('#w').val(obj.width);
    $('#h').val(obj.height);
}

//check coordinates
// function checkCoords(){
//     if(parseInt($('#w').val())) return true;
//     alert('Please select a crop region then press submit.');
//     return false;
// }

$(document).ready(function(){
    //prepare instant image preview
        var p = $("#filePreview");
        $("#fileInput").change(function(){
        var fileInput = document.getElementById('fileInput');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        if(!allowedExtensions.exec(filePath)){
            alert('Please upload a valid photo!');
            fileInput.value = '';
            p.attr('src','database/attachment/images/profile/user-default.png');
            return false;
        }else{
            //fadeOut or hide preview
            p.fadeOut();
            $('[class^="imgareaselect"]').show();
            //prepare HTML5 FileReader
            var oFReader = new FileReader();
            if(document.getElementById("fileInput").files.length !=0 ){
                oFReader.readAsDataURL(document.getElementById("fileInput").files[0]);
            }

            oFReader.onload = function (oFREvent) {
                p.attr('src', oFREvent.target.result).fadeIn();
            };
        }
    });

    //implement imgAreaSelect plugin
    $('img#filePreview').imgAreaSelect({
        aspectRatio: '4:4',
        handles: true,
        onSelectEnd: updateCoords,
        show: true
    });
});

    // PASSWORD RESET
function resetPassword(selected){
    var inputHidden = $(selected).val();
    var id = $("#"+inputHidden).val();
    var $user = $(".row-"+id).text();

    BootstrapDialog.confirm({
        title : 'PASSWORD RESET',
        message : 'Are you sure you want to reset the password of <b>' + $user + '</b> ?',
        type : BootstrapDialog.TYPE_PRIMARY,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Reset', // <-- Default value is 'OK',
        btnOKClass: 'btn-primary', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/user_functions.php', {'user_id':id,'action' : 'reset_password'}, function(data){
                    if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS
                        });
                    } else {
                        BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json')
            } else {

            }
        }
    });
}

function updateStatus(selected){
    $uid = $(selected).data('id');
    $name = $(selected).data('name');
    $action = ($(selected).text() === ' Activate') ? 'activate' : 'deactivate';
    $modal_type = ($(selected).text() === ' Activate') ? BootstrapDialog.TYPE_PRIMARY : BootstrapDialog.TYPE_DANGER;
    $modal_btn_type = ($(selected).text() === ' Activate') ? 'btn-primary' : 'btn-danger'

    BootstrapDialog.confirm({
        title : 'MODIFY STATUS',
        message : 'Are you sure to ' +$action+ ' <b>' + $name + '</b> ?',
        type : $modal_type,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: $action.toUpperCase(), // <-- Default value is 'OK',
        btnOKClass: $modal_btn_type, // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/user_functions.php', {'user_id':$uid,'modify_status' : $action}, function(data){
                    location.reload();
                });
            }
        }
    });
}

function postComment(id,type){
    comment = $('#comment').val();

    $.post('database/comment_functions.php', {'id':id,'add_comment' : 'add_comment','comment' : comment, 'type' : type}, function(data){
         if(data.status == 'success'){
                 BootstrapDialog.alert({
                    title: 'SUCCESS',
                    message: data.message,
                    type: BootstrapDialog.TYPE_SUCCESS,
                    callback: function(result) {                        
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    }
                });
            } else {
                BootstrapDialog.alert({
                    title: 'ERROR',
                    message: data.message,
                    type: BootstrapDialog.TYPE_DANGER
                });
            }
        }, 'json');
}

function deleteTask(id,task_title){
    BootstrapDialog.confirm({
        title : 'DELETE TASK',
        message : 'Are you sure to delete <b style="color:red">'+ task_title.toUpperCase() +'</b>?',
        type : BootstrapDialog.TYPE_DANGER,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Delete', // <-- Default value is 'OK',
        btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/task_functions.php', {'task_id':id,'delete_task' : 'delete_task'}, function(data){
                    if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            callback: function(result) {                        
                                setTimeout(function(){
                                    location.reload();
                                }, 1000);
                            }
                        });
                    } else {
                        BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json')
            }
        }
    });
}

function deleteEntity(id,entity_title){
    BootstrapDialog.confirm({
        title : 'DELETE ENTITY',
        message : 'Are you sure to delete <b style="color:red">'+ entity_title.toUpperCase() +'</b>?',
        type : BootstrapDialog.TYPE_DANGER,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Delete', // <-- Default value is 'OK',
        btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/entity_functions.php', {'entity_id':id,'delete_entity' : 'delete_entity'}, function(data){
                    if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            callback : function (result) { if(result) updateFragment(data.after_action);}
                        });
                    } else {
                        BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json')
            }
        }
    });
}

$("[name='checkbox-taskcomplete']").bootstrapSwitch({
    onText : 'FINISHED',
    offText : 'PENDING',
    onColor : 'success',
    offColor : 'danger'
});

function setToComplete(task_id,switched){
    $value = $(switched).val();
    $action = ($value === '') ? 'set' : 'unset';
     $.post('database/task_functions.php', {'task_id':task_id,'action_task_status' : $action}, function(data){
        if(data.status == 'success'){
             BootstrapDialog.alert({
                title: 'SUCCESS',
                message: data.message,
                type: BootstrapDialog.TYPE_SUCCESS,
                callback: function(result) {                        
                    setTimeout(function(){
                        location.reload();
                    }, 500);
                }
            });
        } else {
            BootstrapDialog.alert({
                title: 'ERROR',
                message: data.message,
                type: BootstrapDialog.TYPE_DANGER
            });
        }
    }, 'json');
}

$('#datatable-responsive').DataTable( {
    "lengthMenu":  [ 1,5,10,15,20, 25, 50, 75, 100, 150, 200 ],
    "pageLength" : 10
} );

$('#taskadd_dealgroup').on('change',function(){
    $documentField = $('#taskadd_document');
    $dealGroupId = $(this).val();
    if($dealGroupId !== ''){
        $documentField.empty();
        $documentField.removeAttr('disabled');
        $.post('database/task_functions.php', {'dealgroup_id':$dealGroupId,'action_update_document' : ''}, function(data){
            if($.isEmptyObject(data)){
                $documentField.attr('disabled','disabled');
            } else {
                $options = '<option value="" > Select document ... </option>';
                for(i=0;i<data.length;i++){
                    $options += '<option value="' + data[i].document_id +'">'+ data[i].document_name +'</option>';
                }
                $documentField.append($options);
            }
        }, 'json');

    }
});

$(document).ready(function() {
    $('#position_select').select2({
        placeholder: 'Select positions...',
        theme: 'bootstrap',
        allowClear: true,
        multiple: true,
    });
});

$('.addDealGroup').click(function(){  
    $copy = $( ".dealgroupInitial:first").clone();
    $copy.appendTo("#dealGroupContainer");
    $copy.find("#taskadd_dealgroup,#start_date,#end_date,#type").val("");
    
    $(".date").each(function(index){
        $(this).attr('id','myDatepicker'+index);
        $('#myDatepicker'+index).datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });

    
    $rowLength = $(".dealgroupInitial").length;
    if($rowLength != 1) {
        $copy.find('button').css('display','block');
    }
});

function removeDealGroup(clicked){
    $rowLength = $(".dealgroupInitial").length;

    if($rowLength != 1) {
        $(clicked).parents(".dealgroupInitial").remove();
    } 

    if($rowLength == 2){
        $( ".dealgroupInitial").find('button').css("display","none");
    }
}

$('.show_form_pos_assign').click(function(){  
    $val = $(this).data('value');
    $target = $('.container'+$val);

    if($target.hasClass('displayed')){        
        $($target).removeClass('displayed');
        $($target).slideUp(400);
        $(this).html('show form <span class="fa fa-chevron-down"></span>');
    } else {
        $($target).slideDown(400);
        $($target).addClass(' displayed');
        $(this).html('hide form <span class="fa fa-chevron-up"></span>');
    }
});

$('.delete_assigned_position_btn').on('click',function(){
    $id = $(this).data('id');
    $name = $(this).data('user');
    $title = $(this).data('position');
    $data_row = $(this).data('tr');

    $message = "Remove <strong>" + $name + "</strong> as <strong>" + $title + "</strong> ?";
    BootstrapDialog.confirm({
        title : 'REMOVE POSITION',
        message : $message,
        type : BootstrapDialog.TYPE_DANGER,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Delete', // <-- Default value is 'OK',
        btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/dealgroup_staffing.php', {'dealgroup_staff_id':$id,'dealgroup_staff_remove' : 'dealgroup_staff_remove','target_row': $data_row}, function(data){
                     if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            callback : function (result) { if(result) updateFragment(data.after_action);}
                        });                            
                    } else {
                        BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json')
            }
        }
    });
});

$('.dealstaff_add_position').on('click',function(){
    $entity_id = $(this).data('entityid');
    $dealgroup_id = $(this).data('dealgroupid');

    $.post('database/dealgroup_staffing.php', {'dealgroup_id':$dealgroup_id, 'entity_id' : $entity_id, 'dealgroup_assigned_position' : 'dealgroup_assigned_position'}, function(data){
        if(data.status == 'success'){
            BootstrapDialog.show({
                title: 'ASSIGN POSITION',
                message: data.message,
                cssClass: 'login-dialog',
                buttons: [{
                    label: 'ASSIGN POSITION',
                    cssClass: 'btn-primary',
                    type: 'submit',
                    action: function(dialog){
                        $noError = true;
                        $( "#update_staffing_form .input_required" ).each(function() {           
                            if( !$(this).val() ) {
                                  $noError = false;
                            }
                        });  
                        if($noError) {
                            $values = $('#update_staffing_form').serialize();
                            $.post('database/dealgroup_staffing.php' , $values , function(data){
                                if(data.status == 'success'){
                                     BootstrapDialog.alert({
                                        title: 'SUCCESS',
                                        message: data.message,
                                        type: BootstrapDialog.TYPE_SUCCESS,
                                        callback : function (result) { if(result) updateFragment(data.after_action);}
                                    });        
                                } else {
                                    BootstrapDialog.alert({
                                        title: 'ERROR',
                                        message: data.message,
                                        type: BootstrapDialog.TYPE_DANGER
                                    });
                                }
                                dialog.close();
                            }, 'json');
                        } else {
                            alert('Please specify required fields!');
                            return false;
                        }
                    }
                }]
            });           
        } else {
            BootstrapDialog.alert({
                title: 'ERROR',
                message: data.message,
                type: BootstrapDialog.TYPE_DANGER
            });
        }
    }, 'json');

    setTimeout(function(){
        $('form#update_staffing_form').find('br').remove();
    }, 300);
});

$('.update_assigned_position_btn').on('click',function(){
    $id = $(this).data('id');

    $.post('database/dealgroup_staffing.php', {'id':$id, 'updateDealStaffingForm' : 'updateDealStaffingForm'}, function(data){
        if(data.status == 'success'){
            BootstrapDialog.show({
                title: 'UPDATE POSITION',
                message: data.message,
                type: BootstrapDialog.TYPE_WARNING,
                buttons: [{
                    label: 'UPDATE',
                    cssClass: 'btn-warning',
                    type: 'submit',
                    action: function(dialog){
                        $values = $('#update_staffing_form').serialize();
                        $.get('database/dealgroup_staffing.php' , $values , function(data){
                            if(data.status == 'success'){
                                 BootstrapDialog.alert({
                                    title: 'SUCCESS',
                                    message: data.message,
                                    type: BootstrapDialog.TYPE_SUCCESS,
                                    callback : function (result) { if(result) updateFragment(data.after_action);}
                                });        
                            } else {
                                BootstrapDialog.alert({
                                    title: 'ERROR',
                                    message: data.message,
                                    type: BootstrapDialog.TYPE_DANGER
                                });
                            }
                            dialog.close();
                        }, 'json');
                    }
                }]
            });           
        } else {
            BootstrapDialog.alert({
                title: 'ERROR',
                message: data.message,
                type: BootstrapDialog.TYPE_DANGER
            });
        }
    }, 'json');

    setTimeout(function(){
        $('form#update_staffing_form').find('br').remove();
    }, 300);
});

function updateFragment(data){
    if(data.action == 'replace'){
        for(var tar_e in data.fragments){
            $(data.target + ' ' + tar_e).fadeOut(1000);
            $(data.target + ' ' + tar_e).text(data.fragments[tar_e]);            
            $(data.target + ' ' + tar_e).fadeIn(1000);
        }   
    }

    if(data.action == 'delete'){
        $(data.target).fadeOut(1500);
    }

    if(data.action == 'append') {
        for(i=0;i<data.fragments.length;i++){
            $(data.target).append(data.fragments[i]);
        }
    }
}

$('#document_btn_label').on('change',function(){
    if(document.getElementById('document_btn').files.length > 0){
        $('#file_to_upload_container').css("display","block");
        $('#file_container_tb').html('');
        for(i=0;i<document.getElementById('document_btn').files.length;i++){
            $file_row = '<tr style="border-bottom: 1px solid"><td style="padding-bottom:5px">'+ document.getElementById('document_btn').files[i].name +'</td></tr>';
            $('#file_container_tb').append($file_row);
        }
    } else {        
        $('#file_container_tb').html('');
        $('#file_to_upload_container').css("display","none");
    }

});



