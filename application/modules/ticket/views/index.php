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
                        <h3 class="box-title">Support Tickets</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">           
                        <table id="example1" class="cell-border example1 table table-striped table1 delSelTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="selAll"></th>
                                    <th>Ticket Number</th>
                                    <th>Subject</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    <th>Closed By</th>
                                    <th>Closed On</th>
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
<!-- Modal Crud Start-->
<div class="modal fade" id="nameModal_ticket" role="dialog">
    <div class="modal-dialog">
        <div class="box box-primary popup" >
            <div class="box-header with-border formsize">
                <h3 class="box-title">Ticket Details</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <!-- /.box-header -->
            <div class="modal-body" style="padding: 0px 0px 0px 0px;"></div>
        </div>
    </div>
</div><!--End Modal Crud --> 

<div id="cnfrm_close" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content col-md-6" style="width: 60%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Do you really want to Close the Ticket?</p>
                <p>Once its closed you will not be able to re-open it.</p>
            </div>
            <div class="modal-footer">
                <button id="close_ticket" type="button" class="btn btn-danger" data-id="" data-dismiss="modal">yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">no</button>
            </div>
        </div> 
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        loadTable();
        $("button.closeTest, button.close").on("click", function () {});
        $("#close_ticket").on("click", function () {
            if ($(this).attr("data-id") != '') {
                ajaxLoad($(this).attr("data-id"),'closed');
            }
        });
    });

    function setTicketId(id) {
        $("#cnfrm_close").find("#close_ticket").attr("data-id", id);
    }

    function holdTicket(id) {
        ajaxLoad(id,'hold');
    }
    
    function openTicket(id) {
        ajaxLoad(id,'open');
    }

    function ajaxLoad(id,condition) {
        var url = '<?php echo base_url(); ?>';
        
        $.ajax({
            url: url + 'ticket/changeStatus',
            method: 'post',
            data: {
                id: id,
                status : condition
            }
        }).done(function (data) {
            $('#example1').DataTable().ajax.reload();
        });
    }

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
                "url": url + "ticket/dataTable"
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