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
                        <h3 class="box-title">User</h3>
                        <div class="box-tools">
                            <?php if (CheckPermission("users", "own_create")) { ?>
                                <button type="button" class="btn-sm  btn btn-success modalButtonUser" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Add User</button>
                            <?php } if (setting_all('email_invitation') == 1) { ?>
                                <button type="button" class="btn-sm  btn btn-success InviteUser" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Invite People</button>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div style="padding: 5px 0px 0px 10px;" class="row">
                        <div class="col-sm-3" style="width: 21% !important; padding-right: 0px;">
                            <label> Filter:
                                <select name="user_type" id="user_type" class="form-control inline-77 input-sm" onchange="filterByOption()" style="padding-right: 16px;">
                                    <option  value=""> No Filter </option>
                                    <option  value="new_usr"> New Users </option>
                                    <option  value="new_reg"> New Registrations </option>
                                    <option  value="all_reg"> All Registered Users </option>
                                    <option  value="pur_usr"> Purchasing users </option>
                                    <option  value="fre_pur_usr"> Frequently Purchased users </option>
                                </select>
                                <!--<input type="text" id="dob" name="dob" class="form-control" data-validation="required" placeholder="Date of Birth">-->
                            </label>
                        </div>
                        <div class="col-sm-6" style="width: 37% !important; padding-left: 0px;">
                            <div id="range_div" class="hidden">
                                <div class="col-md-6">
                                    <input style="height: 30px !important;" type="text" name="start" id="start" class="form-control" placeholder="Purchased From">
                                    <span style="right: 10px;" class="glyphicon glyphicon-calendar form-control-feedback pointer"></span>
                                    <span id="clear_start" style="right: -13px;pointer-events: all;" title="clear" class="glyphicon glyphicon-remove form-control-feedback pointer"></span>
                                </div>
                                <div class="col-md-6">
                                    <input style="height: 30px !important;" type="text" name="end" id="end" class="form-control" placeholder="Purchased To">
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
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Email</th>
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
<div class="modal fade" id="nameModal_user" role="dialog">
    <div class="modal-dialog">
        <div class="box box-primary popup" >
            <div class="box-header with-border formsize">
                <h3 class="box-title">User Form</h3>
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
        setTimeout(function () {
            var add_width = $('.dataTables_filter').width() + $('.box-body .dt-buttons').width() + 10;
            $('.table-date-range').css('right', add_width + 'px');

            $('.dataTables_info').before('<button data-base-url="<?php echo base_url() . 'user/delete/'; ?>" rel="delSelTable" class="btn btn-default btn-sm delSelected pull-left btn-blk-del"> <i class="fa fa-trash"></i> </button><br><br>');
        }, 300);
        $("button.closeTest, button.close").on("click", function () {});
        
        $("#clear_start").on("click", function () {
            if($('#start').val() != "") {
                $('#start').val("");
                $('#example1').DataTable().ajax.reload();
            }
        });
        
        $("#clear_end").on("click", function () {
            if($('#end').val() != "") {
                $('#end').val("");
                $('#example1').DataTable().ajax.reload();
            }
        });
        
    });

    $(function () {
        var dateFormat = "mm/dd/yy",
                from = $("#start")
                .datepicker({
                    changeMonth: true,
                    changeYear: true,
                    autoSize: true,
                    yearRange: "-70:+0",
                    maxDate: "+0D"
                })
                .on("change", function () {
                    to.datepicker("option", "minDate", getDate(this));
                    $('#example1').DataTable().ajax.reload();
                }),
                to = $("#end").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    autoSize: true,
                    yearRange: "-70:+0",
                    maxDate: "+0D"
                })
                .on("change", function () {
                    from.datepicker("option", "maxDate", getDate(this));
                    $('#example1').DataTable().ajax.reload();
                });

        function getDate(element) {
            var date;
            try {
                date = $.datepicker.parseDate(dateFormat, element.value);
            } catch (error) {
                date = null;
            }

            return date;
        }
    });

    function filterByOption() {
        $('#example1').DataTable().ajax.reload();
        if ($('#user_type').val() == 'fre_pur_usr') {
            $('#range_div').removeClass('hidden');
        } else {
            $('#range_div').addClass('hidden');
        }
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
                "url": url + "user/dataTable",
                "data": function (d) {
                    d.filter_option = $('#user_type').val();
                    d.filter_start = $('#start').val();
                    d.filter_end = $('#end').val();
                }
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