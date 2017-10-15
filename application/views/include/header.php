<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <?php $setting = setting_all(); ?>

        <link rel="icon" href="<?php echo base_url((setting_all('favicon')) ? 'assets/images/' . setting_all('favicon') : 'assets/images/favicon.ico'); ?>">
        <title><?php echo (setting_all('website')) ? setting_all('website') : 'Dasboard'; ?></title>

        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/ionicons.min.css'); ?>">
        <!-- DataTables -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap.css'); ?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/AdminLTE.min.css'); ?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/skins/skin-black-light.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/skins/skin-black-light.css'); ?>">
        <!--  <link rel="stylesheet" href="<?php echo base_url('assets/css/blue.css'); ?>">-->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/buttons.dataTables.min.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/daterangepicker.css'); ?>" />
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script>
            var base_url = '<?php echo base_url(); ?>';
        </script>

    </head>
    <body class="hold-transition skin-black-light sidebar-mini sidebar-collapse" data-base-url="<?php echo base_url(); ?>">
        <div class="wrapper">

            <header class="main-header">
                <a href="<?php echo base_url(); ?>" class="logo">
                    <?php $logo = (setting_all('logo')) ? setting_all('logo') : 'logo.png'; ?>
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><img src="<?php echo base_url() . 'assets/images/' . $logo; ?>" id="logo"></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><img src="<?php echo base_url() . 'assets/images/' . $logo; ?>" id="logo"></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- Control Sidebar Toggle Button -->
                            <!-- <li>
                              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li> -->
                            <!-- User Account: style can be found in dropdown.less -->          

                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php
                                    $profile_pic = 'user.png';
                                    if (isset($this->session->userdata('user_details')[0]->profile_pic) && file_exists('assets/images/' . $this->session->userdata('user_details')[0]->profile_pic)) {
                                        $profile_pic = $this->session->userdata('user_details')[0]->profile_pic;
                                    }
                                    ?>
                                    <img src="<?php echo base_url() . 'assets/images/' . $profile_pic; ?>"  class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?php echo isset($this->session->userdata('user_details')[0]->name) ? $this->session->userdata('user_details')[0]->name : ''; ?></span>
                                </a>
                                <ul class="dropdown-menu" role="menu" style="width: 164px;">
                                    <li><a href="<?php echo base_url('user/profile'); ?>"><i class="fa fa-user mr10"></i>My Account</a></li>
                                    <li class="divider"></li>
                                    <li><a href="<?php echo base_url('user/logout'); ?>"><i class="fa fa-power-off mr10"></i> Sign Out</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <ul class="sidebar-menu tree">
                        <li class="header"><!-- MAIN NAVIGATION --></li>
                        <?php // echo '<pre>';print_r($this->router); die; ?>
                        <li class="<?= ($this->router->method === "profile") ? "active" : "not-active" ?>"> 
                            <a href="<?php echo base_url('user/profile'); ?>"> <i class="fa fa-user"></i> <span>My Account</span></a>
                        </li>                
                        <?php $this->load->view("include/menu"); ?> 


                        <?php if (CheckPermission("users", "own_read")) { ?>
                            <li class="<?= ($this->router->method === "userTable") ? "active" : "not-active" ?>"> 
                                <a href="<?php echo base_url(); ?>user/userTable"> <i class="fa fa-users"></i> <span>Users</span></a>
                            </li>    
                        <?php } if (isset($this->session->userdata('user_details')[0]->user_type) && $this->session->userdata('user_details')[0]->user_type == 'admin') { ?>    
                            <li class="<?= ($this->router->class === "setting") ? "active" : "not-active" ?>">
                                <a href="<?php echo base_url("setting"); ?>"><i class="fa fa-cogs"></i> <span>Settings</span></a>
                            </li>
                        <?php } ?>
                        <?php
                        if (isset($this->session->userdata('user_details')[0]->user_type) && ($this->session->userdata('user_details')[0]->user_type == 'member' ||
                                $this->session->userdata('user_details')[0]->user_type == 'admin' )) {
                            ?>
                            <li class="treeview <?php echo ($this->router->class === "store") ? "active" : "not-active" ?>">
                                <a href="#"><i class="fa fa-building-o"></i> <span>Medical Stores</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span></a>
                                <ul class="treeview-menu" style="display: block;">
                                    <li class="<?php echo ($this->router->method === "index") ? "active" : "" ?>">
                                        <a href="<?php echo base_url("store"); ?>"><i class="fa fa-sliders"></i>Manage Stores</a>
                                    </li>
                                    <li class="<?php echo ($this->router->method === "searchStoresMap") ? "active" : "" ?>">
                                        <a href="<?php echo base_url("store/searchStoresMap"); ?>"><i class="fa fa-map-marker"></i>Location Filter Stores</a>
                                    </li>
                                </ul>
                            </li>
                        <?php
                        }
                        if (isset($this->session->userdata('user_details')[0]->user_type) && ($this->session->userdata('user_details')[0]->user_type == 'member' ||
                                $this->session->userdata('user_details')[0]->user_type == 'admin' )) {
                            ?>
                            <li class="treeview <?php echo ($this->router->class === "order") ? "active" : "not-active" ?>">
                                <a href="<?php echo base_url(); ?>order"><i class="fa fa-book"></i> <span>Order History</span>
                            </li>
                        <?php } ?>

                        <li class="<?= ($this->router->class === "about") ? "active" : "not-active" ?>">
                            <a href="<?php echo base_url("about"); ?>"><i class="fa fa-info-circle"></i> <span>About Us</span></a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>          
            <style>
                .progress-main-div1 {
                    padding-top: 10px;
                    background-color: rgb(232, 82, 15);
                    position: fixed;
                    bottom: 21px;
                    z-index: 50;
                    width: 50%;
                    padding-bottom: 10px;
                    color: white;
                    text-align: center;
                    margin-left: 34%;
                    margin-bottom: 19px;
                }
                .progress-main-div1 a{
                    color:#04046e;;
                }
            </style>
