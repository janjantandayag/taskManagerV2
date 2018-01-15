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