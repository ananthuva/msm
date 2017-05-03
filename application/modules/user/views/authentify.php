<?php if ($this->session->flashdata("alert_msg")) { ?>
    <div class="alert alert-danger">      
        <?php echo $this->session->flashdata("alert_msg") ?>
    </div>
<?php } ?>
<body class="hold-transition register-page" style="margin-top: -50px;">
    <div class="register-box">
        <div class="register-logo">
            <a href="<?php echo base_url(); ?>"><b>Verify Mobile Number</b></a>
        </div>
        <div class="register-box-body">
            <p class="login-box-msg">Verify your mobile number to access our services</p>
            <?php if ($this->session->flashdata("messagePr")) { ?>
                <div class="alert alert-info">      
                    <?php echo $this->session->flashdata("messagePr") ?>
                </div>
            <?php } ?>
                <form action="<?php echo base_url() . 'user/verifyMobileNumber'; ?>" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" id="mobile_no" name="mobile_no" value ="<?php echo $mobile_no; ?>" data-validation="required" class="form-control" placeholder="Mobile Number" style="width: 65%;">
                        <span class="glyphicon glyphicon-earphone form-control-feedback" style="left: 52%;"></span>
                        <button type="button" style="width: 30%;" id="send_otp" class="btn btn-success form-control-element">Send OTP</button>
                    </div>
                    <div class="form-group has-feedback div_otp hidden">
                        <label>Please enter the verification code sent to your mobile</label>
                        <input type="password" class="form-control" id="otp_confirmation" name="otp_confirmation" placeholder="Enter OTP" data-validation="required">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?php echo $user_id; ?>"> 
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat btn-color">Verify</button>
                        </div>
                    </div>
                </form>
            <br>
            <p class="login-box-msg">
                <a href="<?php echo base_url('user/login'); ?>" class="text-center">I already have a membership</a>
            </p>
        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.register-box -->
</body>
<script>
    $(document).ready(function () {
        $('#send_otp').on('click',function(){
            var ph_number = $('#mobile_no').val();
            var user_id = $('#user_id').val();
            $('#otp_confirmation').val('');
            if (/^\d{10}$/.test(ph_number)) {
               $.ajax({
                    url: '<?php echo base_url('user/sendOTPtoMobile'); ?>',
                    type: 'POST',
                    data: "mobile_number=" + ph_number+ "&user_id=" + user_id,
                    success: function(data) {
                        $('.div_otp').removeClass('hidden');
                        $('#send_otp').html('Resend OTP');
                    }
                });
            } else {
                alert('Invalid Mobile Number');
            }
        });
    });
</script>
