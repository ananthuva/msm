<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Main content -->
    <div class="col-md-12 form f-label">
        <?php
        if ($this->session->flashdata("messagePr")) {
            ?>
            <div class="alert alert-info">      
            <?php echo $this->session->flashdata("messagePr") ?>
            </div>
<?php } ?>
        <!-- Profile Image -->
        <div class="box box-success pad-profile">
            <div class="box-header with-border">
                <h3 class="box-title">Order Details</h3>
                <span class="fright txt-u"><b><?php echo isset($order['order_bill_id']) ? $order['order_bill_id'] : ''; ?></b></span>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Order Bill Number</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['order_bill_id']) ? $order['order_bill_id'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Store Name</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['store_name']) ? $order['store_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6"></div>
                        <div class="col-md-6"> 
                            <div id="editButtons">
                                <?php if ($order['store_name'] == '') { ?>
                                <button type="button" id="storeAdd" class="btn store-edit btn-success">Assign Store</button>
                                <?php } else if ($order['order_status_id'] < 6) { ?>
                                <button type="button" id="storeEdit" class="btn store-edit btn-success">Change Store</button>
                                <?php } ?>
                            </div>
                            <div id="editInput" class="hidden">
                                <div class="col-xs-6 w-48">
                                    <div id="stores"></div> 
                                </div>
                                <div class="col-xs-6 w-48">
                                    <button type="button" id="storeEditSave" class="btn store-edit-action btn-success">Save</button>
                                    <button type="button" id="storeEditCancel" class="btn store-edit-action btn-warning">Cancel</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>User Name</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['user_name']) ? $order['user_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>User Notes</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['note']) ? $order['note'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Status</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['order_status_name']) ? $order['order_status_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Date of Order</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['order_date']) ? $order['order_date'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Payment Type</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['payment_type']) ? $order['payment_type'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Payment Status</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($order['payment_status']) ? $order['payment_status'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-12 line2 section-hidden"></div>
                    <div class="col-md-12">
                        <div class="col-md-6 w-48">
                            <div style="font-weight: 500;font-size: 16px;">Billing Details</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Name</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['full_name']) ? $billing['full_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Contact Number</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['mobile']) ? $billing['mobile'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>House Name</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['house_name']) ? $billing['house_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Street</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['street']) ? $billing['street'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Post Office</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['postoffice']) ? $billing['postoffice'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>PIN</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['pin']) ? $billing['pin'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>State</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['state_name']) ? $billing['state_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-12 line2 section-hidden"></div>
                    <div class="col-md-12">
                        <div class="col-md-6 w-48">
                            <div style="font-weight: 500;font-size: 16px;">Delivery Details</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Name</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['full_name']) ? $billing['full_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Contact Number</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['mobile']) ? $billing['mobile'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>House Name</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['house_name']) ? $billing['house_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Street</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['street']) ? $billing['street'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>Post Office</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['postoffice']) ? $billing['postoffice'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>PIN</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['pin']) ? $billing['pin'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-xs-6 w-48">
                            <label>State</label>
                        </div>
                        <div class="col-xs-6 w-48">
                            <label>: <?php echo isset($billing['state_name']) ? $billing['state_name'] : ''; ?></label>
                        </div>
                    </div>
                    <div class="col-md-12 line2 section-hidden"></div>
                    <?php if (isset($attachments) && !empty($attachments)) { ?>
                        <div class="section-hidden">
                            <div class="col-md-12">
                                <div class="col-md-6 w-48">
                                    <div style="font-weight: 500;font-size: 16px;">Attachments</div>
                                </div>
                            </div>
                            <?php $i = 0;
                            foreach ($attachments as $attachment) {
                                ++$i; ?>
                                <div class="col-md-12">
                                    <div class="col-md-6 w-48">
                                        <a target="_blank" href="<?php
                                            echo isset($attachment['attachment']) ? base_url() . $attachment['attachment'] : '#';
                                            ?>"> Attachment-<?php echo $i; ?></a>
                                    </div>
                                </div>
                        <?php } ?>
                            <div class="col-md-12 line2 section-hidden"></div>
                        </div>
                    <?php } ?>
                </div>
                <!-- /.box-body -->
                <div class="pad-box-custom section-hidden">
                    <div style="font-size: 16px;">Track Order</div>
                    <div class="order-status">Current Status : Order <?php echo $order_status; ?></div>
                    <div class="row bs-wizard" style="border-bottom:0;">
                        <?php 
                            if(isset($history) && !empty($history)) { 
                                $numItems = count($history); $i = 0; $delivered = false;
                                foreach ($history as $historyItem) {
                                    if(++$i === $numItems) {
                                        $className = 'active';
                                        if($historyItem['order_status_name'] == 'Delivered') {
                                            $delivered = true;
                                        }
                                    } else {
                                        $className = 'complete';
                                    }
                                ?>
                                    <div class="col-xs-3 bs-wizard-step <?php echo $className; ?>">
                                        <div class="text-center bs-wizard-stepnum">Order <?php echo $historyItem['status']; ?></div>
                                        <div class="progress"><div class="progress-bar"></div></div>
                                        <a href="#" class="bs-wizard-dot"></a>
                                        <div class="bs-wizard-info text-center">
                                            <?php echo (!empty($historyItem['created_on'])) ? date('g:ia \o\n l jS F Y', strtotime($historyItem['created_on'])) : ''; ?>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        <?php if(isset($delivered) && !$delivered) {?>
                            <div class="col-xs-3 bs-wizard-step disabled">
                                <div class="text-center bs-wizard-stepnum">Not yet Delivered</div>
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#" class="bs-wizard-dot"></a>
                                <div class="bs-wizard-info text-center"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<script>
    $(document).ready(function () {
        $('.store-edit').on('click', function() {
            $('#editButtons,#editInput').toggleClass('hidden');
        });
        $('.store-edit-action').on('click', function() {
            $('#editButtons,#editInput').toggleClass('hidden');
            if($(this).attr('id') == "storeEditCancel") {
                $('#stores_input').val('');
            } else {
                $.ajax({
                    url: '<?php echo base_url('order/changeStore'); ?>',
                    type: 'POST',
                    data: "store_id=" + $("#stores_hidden").val() + "&order_id=<?php echo $order['id']; ?>",
                    success: function(data) {
                        if(data == 'true') {
                            location.reload();
                        }
                    }
                });
            }
        });
        $("#stores").flexbox('<?php echo base_url(); ?>store/getStores/<?php echo $order['store_id']; ?>', {
            allowInput: true,
            inputClass: 'xxwide text input stores-input',
            allowInputClick: false,
            width: null,
//            paging: {
//                style: 'links', // or 'links'
//                cssClass: 'paging', // prefix with containerClass (e.g. .ffb .paging)
//                pageSize: 5 // acts as a threshold.
//            }
//            onSelect: function () {
//                var sel_store = $("#stores_hidden").val();
//            }
        });
    });

</script>
<script src="<?php echo base_url('assets/js/jquery.flexbox.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery.flexbox.css'); ?>" />
<style>
    .bs-wizard {margin-top: 40px;}

    /*Form Wizard*/
    .bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
    .bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
    .bs-wizard > .bs-wizard-step + .bs-wizard-step {}
    .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px;}
    .bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
    .bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 16px; height: 16px; display: block; background: #09B4AC; top: 45px; left: 50%; margin-top: -7px; margin-left: -8px; border-radius: 50%;} 
    .bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0; background-color: #e5e5e5; overflow: hidden;}
    .bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #09B4AC;}
    .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
    .bs-wizard > .bs-wizard-step.active > .bs-wizard-stepnum {color:#09B4AC;}
    .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
    .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
    .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
    .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #e5e5e5;}
    .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
    .bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
    .bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
    .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
    .store-edit { margin-bottom: 15px; }
    .stores-input { width: 100%; }
    @media print {
        .section-hidden, .section-hidden * {
            visibility: hidden;
        }
    }
</style>
