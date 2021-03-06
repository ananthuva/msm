<form role="form bor-rad" enctype="multipart/form-data" action="<?php echo base_url() . 'user/add_edit' ?>" method="post">
    <div class="box-body">
        <div class="row">
            <?php if (validation_errors()) { ?>
                <div class="alert danger">
                    <?php echo validation_errors(); ?>
                </div>
             <?php } ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="status"> Status</label>
                    <select name="status" id="" class="form-control">
                        <option value="active" <?php echo (isset($userData->status) && $userData->status == 'active') ? 'selected' : ''; ?> >Active</option>

                        <option value="deleted" <?php echo (isset($userData->status) && $userData->status == 'deleted') ? 'selected' : ''; ?> >Deleted</option>

                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">First Name</label>
                    <input required type="text" name="name" value="<?php echo isset($userData->name) ? $userData->name : ''; ?>" class="form-control" placeholder="First Name">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Last Name</label>
                    <input type="text" name="lname" value="<?php echo isset($userData->lname) ? $userData->lname : ''; ?>" class="form-control" placeholder="Last Name">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Email</label>
                    <input required type="text" name="email" value="<?php echo isset($userData->email) ? $userData->email : ''; ?>" class="form-control" placeholder="Email">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Land Line Number</label>
                    <input type="text" name="phone_no" value="<?php echo isset($userData->phone_no) ? $userData->phone_no : ''; ?>" class="form-control" placeholder="Phone Number">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Mobile Number</label>
                    <input type="text" pattern="\d*" maxlength="10" name="mobile_no" value="<?php echo isset($userData->mobile_no) ? str_replace('+91','',$userData->mobile_no) : ''; ?>" class="form-control" placeholder="Mobile Number">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">User Type</label>
                    <?php
                    $u_type = isset($userData->user_type) ? $userData->user_type : '';
                    $user_type = getAllDataByTable('permission');
                    ?>
                    <select name="user_type" class="form-control" required>  
                        <?php
                        foreach ($user_type as $option) {
                            $sel = '';
                            if (strtolower($option->user_type) == strtolower($u_type)) {
                                $sel = "selected";
                            }
                            if (strtolower($option->user_type) != 'admin') {
                                ?>
                                <option  value="<?php echo $option->user_type; ?>" <?php echo $sel; ?> ><?php echo ucfirst($option->user_type); ?> </option>

                        <?php }
                    } ?>                   
                    </select>
                </div> 
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Date of Birth</label>
                    <input style="cursor: pointer" readonly='true' required type="text" id="dob" name="dob" value="<?php echo isset($userData->dob) ? date('d-m-Y',strtotime($userData->dob)) : ''; ?>" class="form-control" placeholder="Dob">
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Address</label>
                    <textarea required name="address" class="form-control" rows="3" placeholder="Address"><?php echo isset($userData->address) ? $userData->address : ''; ?></textarea>
                </div>
            </div>
            
            <?php if (isset($userData)) { ?>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Current Password</label>
                        <input type="text" style="display: none">
                        <input type="Password" name="currentpassword" class="form-control" value="" placeholder="Password">
                    </div>
                </div>
            <?php } ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">New Password</label>
                        <input type="Password" name="password" class="form-control" value="" placeholder="Password">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="Password" name="confirmPassword" class="form-control" value="" placeholder="Password">
                    </div>
                </div>
            
                <div class="col-md-12"> 
                    <div class="form-group imsize">
                        <label for="exampleInputFile">Image Upload</label>
                        <div class="pic_size" id="image-holder"> 
                <?php
                if (isset($userData->profile_pic) && file_exists('assets/images/' . $userData->profile_pic)) {
                    $profile_pic = $userData->profile_pic;
                } else {
                    $profile_pic = 'user.png';
                }
                ?> 
                        <left> <img class="thumb-image setpropileam" src="<?php echo base_url(); ?>/assets/images/<?php echo isset($profile_pic) ? $profile_pic : 'user.png'; ?>" alt="User profile picture"></left>
                        </div> <input type="file" name="profile_pic" id="exampleInputFile">
                    </div>
                </div>                
        </div>
        <?php if (!empty($userData->user_id)) { ?>
            <input type="hidden"  name="user_id" value="<?php echo isset($userData->user_id) ? $userData->user_id : ''; ?>">
            <input type="hidden" name="fileOld" value="<?php echo isset($userData->profile_pic) ? $userData->profile_pic : ''; ?>">
            <div class="box-footer sub-btn-wdt">
                <button type="submit" name="edit" value="edit" class="btn btn-success wdt-bg">Update</button>
            </div>
            <!-- /.box-body -->
        <?php } else { ?>
            <div class="box-footer sub-btn-wdt">
                <button type="submit" name="submit" value="add" class="btn btn-success wdt-bg">Add</button>
            </div>
        <?php } ?>
    </div>
</form>
<script>
    $( "#dob" ).datepicker({
        changeMonth: true,
        changeYear: true,
        autoSize: true,
        dateFormat: 'dd-mm-yy',
        yearRange: "-70:+0",
        maxDate: "+0D"
    });
</script>
<style>
    /* For Firefox */
input[type='number'] {
    -moz-appearance:textfield;
}
/* Webkit browsers like Safari and Chrome */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>