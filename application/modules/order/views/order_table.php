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
                        <h3 id="table_name" class="box-title">ORDERS</h3>
                    </div>
                    <!-- /.box-header -->
                    <div style="padding: 5px 0px 0px 10px;" class="row">
                        <div class="col-sm-3" style="width: 21% !important; padding-right: 0px;">
                            <label> Filter:
                                <select name="order_type" id="user_type" class="form-control inline-77 input-sm" onchange="filterByOption()" style="padding-right: 16px;">
                                    <option  value=""> No Filter </option>
                                    <option  value="new_order"> New Orders </option>
                                    <option  value="assigned_order"> Assigned Orders </option>
                                    <option  value="acc_order"> Accepted Orders </option>
                                    <option  value="rej_order"> Rejected Orders </option>
                                    <option  value="proc_order"> Processed Orders </option>
                                    <option  value="cod"> COD Deliveries </option>
                                    <option  value="online_pay"> Online Payments Orders </option>
                                    <option  value="out_delivery"> Out for Delivery Orders </option>
                                    <option  value="delivered"> Delivered Orders </option>
                                </select>
                            </label>
                        </div>
                        <div class="col-sm-6" style="width: 37% !important; padding-left: 0px;">
                            <div id="range_div" class="hidden">
                                <div class="col-md-6">
                                    <input style="height: 30px !important;" type="text" name="start" id="start" class="form-control" placeholder="Orders From">
                                    <span style="right: 10px;" class="glyphicon glyphicon-calendar form-control-feedback pointer"></span>
                                    <span id="clear_start" style="right: -13px;pointer-events: all;" title="clear" class="glyphicon glyphicon-remove form-control-feedback pointer"></span>
                                </div>
                                <div class="col-md-6">
                                    <input style="height: 30px !important;" type="text" name="end" id="end" class="form-control" placeholder="Orders To">
                                    <span style="right: 10px;" class="glyphicon glyphicon-calendar form-control-feedback pointer"></span>
                                    <span id="clear_end" style="right: -13px;pointer-events: all;" title="clear" class="glyphicon glyphicon-remove form-control-feedback pointer"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3"></div>
                    </div>
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