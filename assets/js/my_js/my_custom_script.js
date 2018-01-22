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

    BootstrapDialog.confirm({
        title : 'MODIFY STATUS',
        message : 'Are you sure to ' +$action+ ' <b>' + $name + '</b> ?',
        type : BootstrapDialog.TYPE_PRIMARY,
        closable: true, // <-- Default value is false
        draggable: true, // <-- Default value is false
        btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
        btnOKLabel: $action.toUpperCase(), // <-- Default value is 'OK',
        btnOKClass: 'btn-primary', // <-- If you didn't specify it, dialog type will be used,
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

// $('#task_myDatepicker1,#task_myDatepicker0').datetimepicker({
//     format: 'YYYY-MM-DD',
//     minDate: new Date()
// });


