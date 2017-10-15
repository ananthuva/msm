<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <?php if ($this->session->flashdata("messagePr")) { ?>
            <div class="alert alert-info">      
                <?php echo $this->session->flashdata("messagePr") ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Orders</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">           
                        <table id="example1" class="cell-border example1 table table-striped table1 delSelTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="selAll"></th>
                                    <th>Ordered by</th>
                                    <th>Store Name</th>
                                    <th>Date of Order</th>
                                    <th>Status</th>
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
<!-- Modal Crud Start-->
<div class="modal fade" id="nameModal_order" role="dialog">
    <div class="modal-dialog">
        <div class="box box-primary popup" >
            <div class="box-header with-border formsize">
                <h3 class="box-title">Order Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <!-- /.box-header -->
            <div class="modal-body" style="padding: 0px 0px 0px 0px;"></div>
        </div>
    </div>
</div><!--End Modal Crud --> 
<script type="text/javascript">
    $(document).ready(function () {
        loadTable();
        $("button.closeTest, button.close").on("click", function () {});     
        
    });

    function loadTable() {
        var url = '<?php echo base_url(); ?>';
        var table = $('#example1').DataTable({
            dom: 'lfBrtip',
            buttons: [
                'copy', 'excel', 'pdf', 'print'
            ],
            "aoColumnDefs": [
                {'bSortable': false, 'aTargets': [0]}
            ],
            "processing": true,
            "serverSide": true,
            "destroy": true,
            "ajax": {
                "url": url + "order/dataTable"
            },
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
            "aLengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]]
        });
    }
</script>            