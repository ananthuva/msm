<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/storeLocator/storelocator.css'); ?>" />
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper clearfix">
    <!-- Main content -->
    <div class="col-md-12 form f-label">
        <!-- box -->
        <div class="box">
            <div class="bh-sl-container">
                <div class="box-header with-border">
                    <h3 class="box-title">My Account <small></small></h3>
                </div>

                <!--                <div class="bh-sl-form-container">-->
                <form id="bh-sl-user-location" method="post" action="#">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bh-sl-search">Store name:</label>
                                <input class="w57" type="text" id="bh-sl-search" name="bh-sl-search" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bh-sl-address">Enter Location or Zip Code:</label>
                                <input class="w57" type="text" id="bh-sl-address" name="bh-sl-address" />
                            </div>
                        </div>
                        <div class="col-md-6" style="padding-left: 0px;">
                            <div class="col-md-4">
                                <button id="bh-sl-submit" class="btn btn-success w100" type="submit">Submit</button>
                            </div>
                            <div class="col-md-4">
                                <button id="bh-sl-reset" class="btn btn-success w100" 
                                        style="background-color: #dd4b39 !important;margin-left: 10px;" type="reset">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--</div>-->

                <div id="bh-sl-map-container" class="bh-sl-map-container">
                    <div id="bh-sl-map" class="bh-sl-map"></div>
                    <div class="bh-sl-loc-list">
                        <ul class="list"></ul>
                    </div>
                    <div class="bh-sl-pagination-container">
                        <ol class="bh-sl-pagination"></ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
</div>
<!-- /.content-wrapper -->
<script src="<?php echo base_url('assets/js/storeLocator/handlebars.min.js'); ?>"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyB9DLk0VCJClmscd0bORll_mQuXk_R7HKo"></script>
<script src="<?php echo base_url('assets/js/storeLocator/jquery.storelocator.js'); ?>"></script>
<script>
    $(function () {
        $('#bh-sl-map-container').storeLocator({'nameSearch': true});
    });
</script>
