<div class="content-wrapper">
<!-- Content Header (Page header) -->
<!-- Main content -->
  <section class="content">
  <?php if($this->session->flashdata("messagePr")){?>
    <div class="alert alert-info">      
      <?php echo $this->session->flashdata("messagePr")?>
    </div>
  <?php } ?>
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Stores List</h3>
            <div class="box-tools">
              <?php if(CheckPermission("stores", "own_create")){ ?>
              <button type="button" onclick="location.href='<?php echo base_url();?>store/createStores'" class="btn-sm  btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add Shop</button>
              <?php } ?>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">           
            <table id="stores" class="cell-border example1 table table-striped table1 delSelTable">
              <thead>
                <tr>
                  <th><input type="checkbox" class="selAll"></th>
                  <th>Status</th>
                  <th>Name</th>
		  <th>License No</th>
                  <th>Commission %</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody> 
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div> 
<div class="modal-body" style="padding: 0px 0px 0px 0px;"></div>
<script type="text/javascript">
  $(document).ready(function() {  
    var url = '<?php echo base_url();?>';//$('.content-header').attr('rel');
    var table = $('#stores').DataTable({ 
          dom: 'lfBrtip',
          buttons: [
              'copy', 'excel', 'pdf', 'print'
          ],
          "aoColumnDefs": [
            { 'bSortable': false, 'aTargets': [ 0 ] },
            {"mRender": function (data) {
                    if(data == 1)
                        return 'Active';
                    else
                        return 'Disabled';
                }, 'aTargets': [ 1 ]
            },
            {'bSortable': false, 'aTargets': [ 5 ]},
           ] ,
          "processing": true,
          "serverSide": true,
          "ajax": url+"store/getStoreList",
          "sPaginationType": "full_numbers",
          "language": {
            "search": "_INPUT_", 
            "searchPlaceholder": "Search",
            "paginate": {
                "next": '<i class="fa fa-angle-right"></i>',
                "previous": '<i class="fa fa-angle-left"></i>',
                "first": '<i class="fa fa-angle-double-left"></i>',
                "last": '<i class="fa fa-angle-double-right"></i>'
            }
          }, 
          "iDisplayLength": 10,
          "aLengthMenu": [[10, 25, 50, 100,500,-1], [10, 25, 50,100,500,"All"]]
      });
    
    setTimeout(function() {
      var add_width = $('.dataTables_filter').width()+$('.box-body .dt-buttons').width()+10;
      $('.table-date-range').css('right',add_width+'px');

        $('.dataTables_info').before('<button data-base-url="<?php echo base_url().'store/delete/'; ?>" rel="delSelTable" class="btn btn-default btn-sm delSelected pull-left btn-blk-del"> <i class="fa fa-trash"></i> </button><br><br>');  
    }, 300);
    $("button.closeTest, button.close").on("click", function (){});
  });
</script>            