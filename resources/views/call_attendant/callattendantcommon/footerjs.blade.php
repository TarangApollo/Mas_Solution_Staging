 {{-- <!-- plugins:js -->
 <script src="{{ asset('global/assets/vendors/js/vendor.bundle.base.js') }}"></script>
 <script src="{{ asset('global/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
 <script src="{{ asset('global/assets/js/settings.js') }}"></script>
 <script src="{{ asset('global/assets/js/custom.js') }}"></script>
 <script src="{{ asset('global/assets/js/off-canvas.js') }}"></script>
 <script src="{{ asset('global/assets/js/hoverable-collapse.js') }}"></script>
 <script src="{{ asset('global/assets/vendors/wizard/js/bootstrap.min.js') }}" type="text/javascript"></script>
 <!-- endinject -->
 <!-- Plugin js for this page -->
 <!--chart-->
 <script src="{{ asset('global/assets/vendors/charts/Chart.min.js') }}"></script>
 <script src="{{ asset('global/assets/vendors/charts/chart.js') }}"></script>





 <!-- Plugin js for this page -->
 <!--select 2 form-->
 <script src="{{ asset('global/assets/vendors/select2/select2.min.js') }}"></script>
 <script src="{{ asset('global/assets/js/select2.js') }}"></script>

 <!--form validation-->
 <script src="{{ asset('global/assets/vendors/wizard/js/jquery.validate.min.js') }}"></script>

 <!--date range picker-->
 <script type="text/javascript" src="{{ asset('global/assets/vendors/date-picker/moment.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('global/assets/vendors/date-picker/daterangepicker.min.js') }}') }}">
 </script>

 <!--table plugin-->
 <script type="text/javascript" src="{{ asset('global/assets/vendors/bootstrap-table/js/bootstrap-table.js') }}">
 </script>
 <script type="text/javascript">
     var $table = $('#fresh-table'),

         full_screen = false;

     $().ready(function() {
         $table.bootstrapTable({
             toolbar: ".toolbar",

             showDownload: true,
             showRefresh: true,
             search: true,
             showColumns: true,
             pagination: true,
             striped: true,
             pageSize: 8,
             pageList: [8, 10, 25, 50, 100],

             formatShowingRows: function(pageFrom, pageTo, totalRows) {
                 //do nothing here, we don't want to show the text "showing x of y from..."
             },
             formatRecordsPerPage: function(pageNumber) {
                 return pageNumber + " rows visible";
             },
             icons: {
                 download: 'mas-download',
                 refresh: 'mas-refresh',
                 toggle: 'fa fa-th-list',
                 columns: 'mas-columns',
                 detailOpen: 'fa fa-plus-circle',
                 detailClose: 'fa fa-minus-circle'
             }
         });

         $(window).resize(function() {
             $table.bootstrapTable('resetView');
         });

         window.operateEvents = {
             'click .like': function(e, value, row, index) {
                 alert('You click like icon, row: ' + JSON.stringify(row));
                 console.log(value, row, index);
             },
             'click .edit': function(e, value, row, index) {
                 alert('You click edit icon, row: ' + JSON.stringify(row));
                 console.log(value, row, index);
             },
             'click .remove': function(e, value, row, index) {
                 $table.bootstrapTable('remove', {
                     field: 'id',
                     values: [row.id]
                 });
             }
         };

         $alertBtn.click(function() {
             alert("You pressed on Alert");
         });

     });
 </script>

 <!--toggle button active/inactive-->
 <script src="{{ asset('global/assets/vendors/toggle-button/bootstrap4-toggle.min.js') }}"></script> --}}
