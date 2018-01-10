 <!-- footer content -->
 <?php if($_SERVER['PHP_SELF'] != '/taskManagerV2/index.php') : ?>
           <footer>
          <div class="pull-right">
            Task Manager Application
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>
  <?php endif; ?>
   <!-- jQuery -->
    <script src="assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="assets/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="assets/vendors/moment/min/moment.min.js"></script>
    <script src="assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-datetimepicker -->    
    <script src="assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Bootstrap Colorpicker -->
    <script src="assets/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <!-- jquery.inputmask -->
    <script src="assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jQuery Knob -->
    <script src="assets/vendors/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="assets/build/js/custom.min.js"></script>	

    <script>
        $('#myDatepicker1').datetimepicker({
            format: 'DD.MM.YYYY'
        });
        $('#myDatepicker2').datetimepicker({
            format: 'DD.MM.YYYY'
        });

        $('.addPositionButton').click(function(){            
            $( ".initialInput:first").clone().appendTo("#position_container");
            $( ".initialInput").find('button').css("display","block");

            $(".date").each(function(index){
                $(this).attr('id','myDatepicker'+index);
                $('#myDatepicker'+index).datetimepicker({
                    format: 'DD.MM.YYYY'
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
    </script>
  </body>
</html>