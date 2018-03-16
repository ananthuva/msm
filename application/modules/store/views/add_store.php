<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Main content -->
    <div class="col-md-12 form f-label">
        <?php if ($this->session->flashdata("messagePr")) { ?>
            <div class="alert alert-info">      
                <?php echo $this->session->flashdata("messagePr") ?>
            </div>
        <?php } ?>
        <!-- Profile Image -->
        <div class="box box-success pad-profile">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo (isset($storeData->id))? 'Edit Store' : 'Create Store' ?></h3>
            </div>
            <form role="form bor-rad" enctype="multipart/form-data" action="<?php echo base_url() . 'store/add_edit' ?>" method="post">
                <div class="box-body">
                    <div class="row">
                        <?php if (validation_errors()) { ?>
                            <div class="alert danger">
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php } ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active"> Status</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" <?php echo (isset($storeData->is_active) && $storeData->is_active == '1') ? 'selected' : ''; ?> >Active</option>
                                    <option value="0" <?php echo (isset($storeData->is_active) && $storeData->is_active == '0') ? 'selected' : ''; ?> >Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Store Name</label>
                                <input required type="text"id="name" name="name" value="<?php echo isset($storeData->name) ? $storeData->name : ''; ?>" class="form-control" placeholder="First Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Owner</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value=""> Select Owner </option>
                                    <?php
                                    if (isset($users)) {
                                        foreach ($users as $user) {
                                            ?>
                                            <option value="<?php echo $user->user_id; ?>" <?php echo (isset($storeData->user_id) && ($storeData->user_id == $user->user_id)) ? 'selected' : ''; ?>>
                                            <?php echo $user->user_name; ?>
                                            </option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="license_no">License Number</label>
                                <input required type="text" name="license_no" value="<?php echo isset($storeData->license_no) ? $storeData->license_no : ''; ?>" class="form-control" placeholder="License Number">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Address</label>
                                <textarea required name="address" class="form-control" rows="3" placeholder="Address"><?php echo isset($storeData->address) ? $storeData->address : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">State</label>
                                <select name="state_id" id="state_id" class="form-control">
                                    <?php
                                    if (isset($states)) {
                                        foreach ($states as $state) {
                                            ?>
                                            <option value="<?php echo $state->id; ?>" <?php echo (isset($storeData->state_id) && ($storeData->state_id == $state->id)) ? 'selected' : ''; ?>>
                                            <?php echo $state->name; ?>
                                            </option>
                                        <?php }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">District</label>
                                <select required name="district_id" id="district_id" class="form-control">
                                    <option value="">-Select District-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div id="dvMap" style="width: inherit; height: 300px"> </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Percent of Commission</label>
                                <input required type="text" name="poc" value="<?php echo isset($storeData->poc) ? $storeData->poc : ''; ?>" class="form-control" placeholder="Percent of Commission">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Latitude</label>
                                        <input id="latitude" name="latitude" type="text" value="<?php echo isset($storeData->latitude) ? $storeData->latitude : ''; ?>" class="form-control" placeholder="Latitude">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Longitude</label>
                                        <input id="longitude" name="longitude" type="text" value="<?php echo isset($storeData->longitude) ? $storeData->longitude : ''; ?>" class="form-control" placeholder="Longitude">
                                    </div>
                                </div>
                                <input id="id" name="id" type="hidden" value="<?php echo isset($storeData->id) ? $storeData->id : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="form-group imsize">
                                <label for="agreement">Agreement Upload</label>
                                <div class="pic_size" id="agreement-holder"> 
                                    <?php
                                    if (isset($storeData->agreement) && !empty($storeData->agreement)) {
                                        $agreements = explode(",", $storeData->agreement);
                                        foreach($agreements as $agreement) {
                                            if(file_exists('uploads/agreement/' . $agreement)) { ?>
                                    <a target="_blank" download="<?php echo base_url().'uploads/agreement/'.$agreement?>" href="<?php echo base_url().'uploads/agreement/'.$agreement?>"><span class="no-wrap"><?php echo $agreement; ?></span></a> &nbsp;
                                    <a style="cursor:pointer;" class="mClass" data-toggle="modal" onclick="deleteAttachment('<?php echo $agreement; ?>','<?php echo $storeData->id; ?>')" data-target="#cnfrm_delete" title="delete"><i class="fa fa-trash-o" ></i></a><br>
                                    <?php   }
                                        }
                                    } 
                                    ?> 
                                </div>
                                <input type="file" name="agreement[]" id="agreement" multiple>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($storeData->id)) { ?>
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    <button type="submit" name="edit" value="edit" class="btn btn-success wdt-bg">Update</button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    <button type="submit" name="submit" value="add" class="btn btn-success wdt-bg">Submit</button>
                                </div>
                                <div class="clear-pad"></div>
                                <div class="col-md-4">
                                    <button type="reset" name="reset" class="btn btn-danger wdt-bg">Reset</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </form>
            <!-- /.box -->
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<script>
    $( document ).ready(function() {
        $("#state_id").change(function() {
            listDistricts();
        });
        $("#district_id").change(function() {
            getLatitudeLongitude();
        });
        listDistricts();
        <?php if(isset($storeData->id)) { ?>
                loadMap();
        <?php } else { ?>
            getLatitudeLongitude();
        <?php } ?>
    });
    //Adding marker on click
    var marker;
    function placeMarker(location, map) {
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                title: jQuery('#name').val()
            });
        }
    }
    
    function loadMap() {
        var lat= $('#latitude').val();
        var lng= $('#longitude').val();
        var mapOptions = {
            center: new google.maps.LatLng(lat, lng),
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var infoWindow = new google.maps.InfoWindow();
        var latlngbounds = new google.maps.LatLngBounds();

        //This will load your map with default location co-ordinates.
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        marker = new google.maps.Marker({
          position: mapOptions.center,
          map: map,
          title: jQuery('#name').val()
        });
        marker.setMap(map);
        //To capture click event.
        google.maps.event.addListener(map, 'click', function (e) {
            $("#latitude").val(e.latLng.lat());
            $("#longitude").val(e.latLng.lng());
            placeMarker(e.latLng, map);
        });
    }
    
    function listDistricts() {
        $.ajax({
            type: "POST",
            url: base_url + "store/getDistricts",
            data: "state_id=" + $("#state_id").val(),
            success: function(response) {
                var response = $.parseJSON(response);
                $('#district_id').find('option').remove()
                        .end().append('<option value="">-Select District-</option>');
                var selected = false;
                $(response).each(function(e,item){
                    <?php if(isset($storeData->district_id)) { ?>
                     selected = (item.id == '<?php echo $storeData->district_id; ?>')?true:false;
                    <?php } ?>
                    $('#district_id').append($('<option>', { 
                        value: item.id,
                        text : item.name,
                        selected : selected
                    }));
                });
            },
            error: function() {

            }
        });
    }
    
    function getLatitudeLongitude() {
        var state = $("#state_id option:selected").text();
        var district = $("#district_id option:selected").text();
        if(district == '-Select District-'){
            district = 'Thiruvananthapuram';
        }
        var address = district+','+state;
        // Initialize the Geocoder
        var geocoder = new google.maps.Geocoder();
        if (geocoder) {
            geocoder.geocode({
                'address': address
            }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    $('#latitude').val(results[0].geometry.location.lat());
                    $("#longitude").val(results[0].geometry.location.lng());
                    loadMap()
                }
            });
        }
    }
    
    function deleteAttachment(name,id) {
        var url =  $('body').attr('data-base-url');
        $("#cnfrm_delete").find("a.yes-btn").attr("href",base_url+"/store/deleteAgreement/"+id+"/"+name);
    }
</script>
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyB9DLk0VCJClmscd0bORll_mQuXk_R7HKo" type="text/javascript"></script>