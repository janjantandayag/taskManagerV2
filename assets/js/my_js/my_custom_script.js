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

function deleteDealGroup(id,entity_title){
    BootstrapDialog.confirm({
        title : 'DELETE DEAL GROUP',
        message : 'Are you sure to delete <b style="color:red">'+ entity_title.toUpperCase() +'</b>?',
        type : BootstrapDialog.TYPE_DANGER,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Delete', // <-- Default value is 'OK',
        btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/dealgroup_functions.php', {'dealgroup_id':id,'delete_dealgroup' : 'delete_dealgroup'}, function(data){
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

function deleteDocument(id,task_title){
    BootstrapDialog.confirm({
        title : 'DELETE DOCUMENT',
        message : 'Are you sure to delete <b style="color:red">'+ task_title.toUpperCase() +'</b>?',
        type : BootstrapDialog.TYPE_DANGER,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Delete', // <-- Default value is 'OK',
        btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/document_functions.php', {'document_id':id,'delete_document' : 'delete_document'}, function(data){
                    if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            callback: function(result) {          
                                updateFragment(data.after_action);
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
    "pageLength" : 10,
    "aaSorting" : []
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

$('.assignment_position_btn').on('click',function(){
    $.post('database/position_functions.php', {'assign_position' : 'assign_position'}, function(data){
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
                        $( "#assign_position_form .input_required" ).each(function() {           
                            if( !$(this).val() ) {
                                  $noError = false;
                            }
                        });  

                        if($noError) {
                            $values = $('#assign_position_form').serialize();
                            $.post('database/position_functions.php' , $values , function(data){
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
                            BootstrapDialog.alert({
                                title: 'ERROR',
                                message: 'Please specify required fields!',
                                type: BootstrapDialog.TYPE_DANGER
                            });
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
        $('form#assign_position_form').find('br').remove();        
    }, 1000);

    setTimeout(function(){
        var entity_inputform = document.getElementById("entity_inputform");
        entity_inputform.addEventListener("change", function() {            
            entity_id = entity_inputform.value;
            if(entity_id != ''){
                $.post('database/entity_functions.php', {'id':entity_id, 'get_dealgroups_pos_page' : 'get_dealgroups_pos_page'}, function(data){
                    if(data.status == 'success'){
                        if(data.count === 0){
                            BootstrapDialog.alert({
                                title: 'ERROR',
                                message: 'Selected entity has no deal group/s assigned to it!!',
                                type: BootstrapDialog.TYPE_DANGER
                            });
                            target = $('.input_assign_dealgroup');

                            target.addClass('empty');

                            if(target.hasClass('empty')){
                                target.empty();
                                target.attr('disabled','disabled');
                            }
                        } else { 
                            $('.input_assign_dealgroup').removeAttr('disabled');
                            $('.input_assign_dealgroup').empty();
                            updateFragment(data.after_action);
                        }
                    } else {
                         BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json');
            } else {
               BootstrapDialog.alert({
                    title: 'ERROR',
                    message: 'Error processing your request',
                    type: BootstrapDialog.TYPE_DANGER
                });
            }
            
            
        });
    },3000);
});

$('.assigned_entity_deal_group_btn').on('click',function(){
    $.post('database/entity_functions.php', {'get_form_ed' : 'get_form_ed'}, function(data){
        if(data.status == 'success'){
            BootstrapDialog.show({
                title: 'ASSIGN ENTITY - DEAL GROUP',
                message: data.message,
                cssClass: 'login-dialog',
                buttons: [{
                    label: 'ASSIGN DEAL GROUP',
                    cssClass: 'btn-primary',
                    type: 'submit',
                    action: function(dialog){
                        $noError = true;
                        $( "#entity_dealgroups_assign_form .input_required" ).each(function() {           
                            if( !$(this).val() ) {
                                  $noError = false;
                            }
                        });  

                        if($noError) {
                            $values = $('#entity_dealgroups_assign_form').serialize();
                            $.post('database/entity_dealgroup_assignment.php' , $values , function(data){                                
                                var $i = 0;
                                if(data.status == 'success'){
                                    for(key in data.message){
                                        $message = "";                                                                       
                                        for(i=0;i<data.message[key].length;i++){
                                            $message += data.message[key][i];
                                        }

                                        if(key === 'success'){             
                                            BootstrapDialog.alert({
                                                title: 'SUCCESS',
                                                message: $message,
                                                type: BootstrapDialog.TYPE_SUCCESS,
                                                callback: function (){
                                                    if(data.last_prompt === 'success'){
                                                        updateFragment(data.after_action);
                                                    }
                                                }
                                            }); 
                                        } else {
                                             BootstrapDialog.alert({
                                                title: 'ERROR',
                                                message: $message,
                                                type: BootstrapDialog.TYPE_DANGER,  
                                                callback: function (){
                                                    if(data.last_prompt === 'error'){
                                                        updateFragment(data.after_action);
                                                    }
                                                }
                                            });     
                                        }
                                    }
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
                            BootstrapDialog.alert({
                                title: 'ERROR',
                                message: 'Please specify required fields',
                                type: BootstrapDialog.TYPE_DANGER
                            });
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
        $('#dealgroup_id').select2({
            placeholder: 'Select deal groups...',
        });
        $('form#entity_dealgroups_assign_form').find('br').remove();        
    }, 1000);

    setTimeout(function(){
        var entity_inputform = document.getElementById("entity_id");
        entity_inputform.addEventListener("change", function() {            
            entity_id = entity_inputform.value;
            if(entity_id != ''){
                $.post('database/entity_functions.php', {'id':entity_id, 'get_dealgroups' : 'get_dealgroups'}, function(data){
                    if(data.status == 'success'){
                        var target = $('#dealgroup_id');

                        target.removeAttr('disabled');
                        target.empty();

                        target.select2({
                            theme: 'bootstrap',
                            allowClear: true,
                            multiple: true,
                            data: data.options,
                        });

                        target.val(data.selectedValues).trigger('change');
                    } else {
                         BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json');
            } else {
               BootstrapDialog.alert({
                    title: 'ERROR',
                    message: 'Error processing your request',
                    type: BootstrapDialog.TYPE_DANGER
                });
            }
        });
    },2000);
});

$('.assigned_dealgroup_document').on('click',function(){
    $.post('database/dealgroup_document.php', {'get_form_dealgroup_document' : 'get_form_dealgroup_document'}, function(data){
        if(data.status == 'success'){
            BootstrapDialog.show({
                title: 'ASSIGN DEAL GROUP DOCUMENTS',
                message: data.message,
                cssClass: 'login-dialog',
                buttons: [{
                    label: 'ASSIGN DOCUMENTS',
                    cssClass: 'btn-primary',
                    type: 'submit',
                    action: function(dialog){
                        $noError = true;
                        $( "#dealgroup_documents_form .input_required" ).each(function() {           
                            if( !$(this).val() ) {
                                  $noError = false;
                            }
                        });  

                        if($noError) {
                            $values = $('#dealgroup_documents_form').serialize();
                            $.post('database/dealgroup_document.php' , $values , function(data){                                
                                var $i = 0;
                                if(data.status == 'success'){
                                    for(key in data.message){
                                        $message = "";                                                                       
                                        for(i=0;i<data.message[key].length;i++){
                                            $message += data.message[key][i];
                                        }

                                        if(key === 'success'){             
                                            BootstrapDialog.alert({
                                                title: 'SUCCESS',
                                                message: $message,
                                                type: BootstrapDialog.TYPE_SUCCESS,
                                                callback: function (){
                                                    if(data.last_prompt === 'success'){
                                                        updateFragment(data.after_action);
                                                    }
                                                }
                                            }); 
                                        } else {
                                             BootstrapDialog.alert({
                                                title: 'ERROR',
                                                message: $message,
                                                type: BootstrapDialog.TYPE_DANGER,  
                                                callback: function (){
                                                    if(data.last_prompt === 'error'){
                                                        updateFragment(data.after_action);
                                                    }
                                                }
                                            });     
                                        }
                                    }
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
                             BootstrapDialog.alert({
                                title: 'ERROR',
                                message: 'Please specify required fields!',
                                type: BootstrapDialog.TYPE_DANGER
                            });
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
        $('#document_ids').select2({
            placeholder: 'Select documents...',
        });

        $('form#dealgroup_documents_form').find('br').remove();        
    }, 1000);

    setTimeout(function(){
        var dealgroup_input = document.getElementById("dealgroup_id");
        dealgroup_input.addEventListener("change", function() {            
            dealgroup_id = dealgroup_input.value;
            if(dealgroup_id != ''){
                $.post('database/entity_dealgroup_assignment.php', {'id':dealgroup_id, 'get_documents' : 'get_documents'}, function(data){
                    if(data.status == 'success'){
                        var target = $('#document_ids');
                        target.empty();

                        target.select2({
                            theme: 'bootstrap',
                            allowClear: true,
                            multiple: true,
                            data: data.options,
                        });
                        target.removeAttr('disabled');

                        target.val(data.selectedValues).trigger('change');
                    } else {
                         BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json');
            } else {
               BootstrapDialog.alert({
                    title: 'ERROR',
                    message: 'Error processing your request',
                    type: BootstrapDialog.TYPE_DANGER
                });
            }
        });
    },2000);
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

    if(data.action == 'redirect') {
        window.location.href = data.ref;
    }
}

function is_url(str){
    regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
    
    if (regexp.test(str))  {
      return true;
    } else {
      return false;
    }
}

$('#document_link_input').on('change',function(){
    url_val = $(this).val();
    if(!is_url(url_val)){
        alert("Please provide a valid url! | google.com | https://google.com");
        $(this).val('');
    }
});

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
// APPROVE VACATION REQUEST
$('.approveVacationRequest').on('click',function(){
    $id = $(this).data('id');
    $title = $(this).data('title');

    BootstrapDialog.confirm({
        title : 'APPROVE VACATION REQUEST',
        message : 'Are you sure to approve <b style="color:#337ab7">'+ $title +'?</b>',
        type : BootstrapDialog.TYPE_PRIMARY,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Approve', // <-- Default value is 'OK',
        btnOKClass: 'btn-primary', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/vacation_functions.php', {'v_id':$id,'approveVacationRequest' : 'approveVacationRequest'}, function(data){
                    if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            callback: function(result) {               
                                updateFragment(data.after_action);
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
});
// REJECT VACATION REQUEST
$('.rejectVacationRequest').on('click',function(){
    $id = $(this).data('id');
    $title = $(this).data('title');

    BootstrapDialog.confirm({
        title : 'REJECT VACATION REQUEST',
        message : 'Are you sure to reject <b style="color:red">'+ $title +'?</b>',
        type : BootstrapDialog.TYPE_DANGER,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Reject', // <-- Default value is 'OK',
        btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/vacation_functions.php', {'v_id':$id,'rejectVacationRequest' : 'rejectVacationRequest'}, function(data){
                    if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            callback: function(result) {          
                                updateFragment(data.after_action);
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
});
// DELETE VACATION
function deleteVacation(id,vacation_title){
    BootstrapDialog.confirm({
        title : 'DELETE VACATION',
        message : 'Are you sure to delete <b style="color:red">'+ vacation_title.toUpperCase() +'</b>?',
        type : BootstrapDialog.TYPE_DANGER,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: 'Delete', // <-- Default value is 'OK',
        btnOKClass: 'btn-danger', // <-- If you didn't specify it, dialog type will be used,
        callback: function(result) {
            if(result) {               
                $.post('database/vacation_functions.php', {'vacation_id':id,'delete_vacation' : 'delete_vacation'}, function(data){
                    if(data.status == 'success'){
                         BootstrapDialog.alert({
                            title: 'SUCCESS',
                            message: data.message,
                            type: BootstrapDialog.TYPE_SUCCESS,
                            callback: function(result) {                        
                                updateFragment(data.after_action);
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
// ASSIGN POSITION TO USER
$('.userAssignPositionBtn').on('click',function () {
    var $name = $(this).data('user');
    var $user_id = $(this).data('id');

    $.post('database/position_functions.php', {'user_assign_position' : 'user_assign_position','user_id' : $user_id}, function(data){
        if(data.status == 'success'){
            BootstrapDialog.show({
                title: "ASSIGN POSITION <strong style='color:#000'>[" + $name.toUpperCase() + "]</strong>",
                message: data.message,
                cssClass: 'login-dialog',
                buttons: [{
                    label: 'ASSIGN POSITION',
                    cssClass: 'btn-primary',
                    type: 'submit',
                    action: function(dialog){
                        $noError = true;
                        $( "#assign_userposition_form .input_required" ).each(function() {           
                            if( !$(this).val() ) {
                                  $noError = false;
                            }
                        });  

                        if($noError) {
                            $values = $('#assign_userposition_form').serialize();
                            $.post('database/position_functions.php' , $values , function(data){
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
                            BootstrapDialog.alert({
                                title: 'ERROR',
                                message: 'Please specify required fields!',
                                type: BootstrapDialog.TYPE_DANGER
                            });
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
        $('form#assign_userposition_form').find('br').remove();        
    }, 1000);

    setTimeout(function(){
        var entity_inputform = document.getElementById("entity_inputform");
        entity_inputform.addEventListener("change", function() {            
            entity_id = entity_inputform.value;
            if(entity_id != ''){
                $.post('database/entity_functions.php', {'id':entity_id, 'get_dealgroups_pos_page' : 'get_dealgroups_pos_page'}, function(data){
                    if(data.status == 'success'){
                        if(data.count === 0){
                            BootstrapDialog.alert({
                                title: 'ERROR',
                                message: 'Selected entity has no deal group/s assigned to it!!',
                                type: BootstrapDialog.TYPE_DANGER
                            });
                            target = $('.input_assign_dealgroup');

                            target.addClass('empty');

                            if(target.hasClass('empty')){
                                target.empty();
                                target.attr('disabled','disabled');
                            }
                        } else { 
                            $('.input_assign_dealgroup').removeAttr('disabled');
                            $('.input_assign_dealgroup').empty();
                            updateFragment(data.after_action);
                        }
                    } else {
                         BootstrapDialog.alert({
                            title: 'ERROR',
                            message: data.message,
                            type: BootstrapDialog.TYPE_DANGER
                        });
                    }
                }, 'json');
            } else {
               BootstrapDialog.alert({
                    title: 'ERROR',
                    message: 'Error processing your request',
                    type: BootstrapDialog.TYPE_DANGER
                });
            }
        });
    },3000);
});







