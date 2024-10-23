<footer class="main-footer text-sm">
    Developed by: <em>Khennethlp</em> 
    <div class="float-right d-none d-sm-inline-block">
      <strong>Copyright &copy;
        <script>   
        var currentYear = new Date().getFullYear();
        if (currentYear !== 2024) {
          document.write("2024 - " + currentYear);
        } else {
          document.write(currentYear);
        };</script>. 
        </strong>
      All rights reserved.
    </div>
  </footer>
<?php
//MODALS
include '../../modals/import_masterlist.php';
include '../../modals/logout_modal.php';
include '../../modals/timeout.php';

?>
<!-- jquery -->
<script src="../../plugins/jquery/dist/jquery.min.js"></script>
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- datatable -->
<script src="../../plugins/DataTables/datatables.min.js"></script>

<script src="../../dist/js/chart.js"></script>
<script src="../../dist/js/chart.umd.min.js"></script>

<script type="text/javascript" src="../../plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="../../dist/js/adminlte.js"></script>
<script src="../../dist/js/popup_center.js"></script>
<!-- <script src="../../dist/js/session.js"></script> -->
</body>
</html>