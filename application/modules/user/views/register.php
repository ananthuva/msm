<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<?php if ($this->session->flashdata("alert_msg")) { ?>
    <div class="alert alert-danger">      
        <?php echo $this->session->flashdata("alert_msg") ?>
    </div>
<?php } ?>
<body class="hold-transition register-page" style="margin-top: -50px;">
    <div class="register-box">
        <div class="register-logo">
            <a href="<?php echo base_url(); ?>"><b>User Registration</b></a>
        </div>
        <div class="register-box-body">
            <p class="login-box-msg">Register a new membership</p>
            <?php if ($this->session->flashdata("messagePr")) { ?>
                <div class="alert alert-info">      
                    <?php echo $this->session->flashdata("messagePr") ?>
                </div>
            <?php } ?>
            <form action="<?php echo base_url() . 'user/registration'; ?>" method="post">

                <div class="form-group has-feedback">
                    <input type="text" name="name" class="form-control" data-validation="required" placeholder="Name">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                
                <div class="form-group has-feedback">
                    <input type="text" name="lname" class="form-control" placeholder="Last Name">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                
                <div class="form-group has-feedback">
                    <input type="text" id="dob" name="dob" class="form-control" data-validation="required" placeholder="Date of Birth">
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                </div>
                
                <div class="form-group has-feedback">
                    <input type="text" name="email" class="form-control" data-validation="required" placeholder="Email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                
                <div class="form-group has-feedback">
                    <input type="text" id ="mobile_no" name="mobile_no" data-validation="required" class="form-control" placeholder="Mobile Number">
                    <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password_confirmation" placeholder="Password" data-validation="required">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="Retype password" data-validation="confirmation">
                    <span class="glyphicon glyphicon-repeat form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <!--<?php /* $type = json_decode(setting_all('user_type')); */ ?>
                    <select name="user_type" id="" class="form-control">
                    <?php /*
                      foreach ($type as $key => $value) {
                      if($value != 'admin') {
                      echo '<option value="'.$value.'">'.ucfirst($value).'</option>';
                      }
                      } */
                    ?>
                    </select>-->
                    <input type="hidden" name="user_type" class="form-control" value="Member"> 
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                     <!--  <input type="hidden" name="user_type" value="<?php //echo setting_all('user_type'); ?>"> -->
                        <input type="hidden" name="call_from" value="reg_page">
                        <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat btn-color">Register</button>
                    </div>
                </div>
            </form>
            <br>
            <a href="<?php echo base_url('user/login'); ?>" class="text-center">I already have a membership</a>
        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.register-box -->
</body>
<script>
    $(document).ready(function () {
<?php if ($this->input->get('invited') && $this->input->get('invited') != '') { ?>
            $burl = '<?php echo base_url() ?>';
            $.ajax({
                url: $burl + 'user/chekInvitation',
                method: 'post',
                data: {
                    code: '<?php echo $this->input->get('invited'); ?>'
                },
                dataType: 'json'
            }).done(function (data) {
                console.log(data);
                if (data.result == 'success') {
                    $('[name="email"]').val(data.email);
                    $('form').attr('action', $burl + 'user/register_invited/' + data.users_id);
                } else {
                    window.location.href = $burl + 'user/login';
                }
            });
<?php } ?>
        $( "#dob" ).datepicker({
            changeMonth: true,
            changeYear: true,
            autoSize: true,
            yearRange: "-70:+0",
            maxDate: "+0D"
        });
    });
</script>
  