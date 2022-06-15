<!DOCTYPE html>
<html class="loading" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="author" content="MYPCOTINFOTECH">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../public/backend/img/ico/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900%7CMontserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/mypcot.min.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/fonts/feather/style.min.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/vendors/css/perfect-scrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/vendors/css/prism.min.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/vendors/css/switchery.min.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/colors.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/components.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/themes/layout-dark.css">
    <link rel="stylesheet" href="../public/backend/css/plugins/switchery.css">
    <link rel="stylesheet" href="../public/backend/vendors/css/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/style.css">
    <link rel="stylesheet" type="text/css" href="../public/backend/css/select2.min.css">
    <script src="../public/backend/js/jquery-3.2.1.min.js"></script>
    <script src="../public/backend/vendors/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="../public/backend/vendors/js/vendors.min.js"></script>
    <script src="../public/backend/vendors/js/datatable/jquery.dataTables.min.js"></script>
    <script src="../public/backend/vendors/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="../public/backend/js/bootbox.min.js"></script>
</head>
<body class="vertical-layout vertical-menu 2-columns" data-menu="vertical-menu" data-col="2-columns" id="container">
    <nav class="navbar navbar-expand-lg navbar-light header-navbar navbar-fixed mt-2">
        <div class="container-fluid navbar-wrapper">
            <div class="navbar-header d-flex pull-left">
                <div class="navbar-toggle menu-toggle d-xl-none d-block float-left align-items-center justify-content-center" data-toggle="collapse"><i class="ft-menu font-medium-3"></i></div>
                <li class="nav-item mr-2 d-none d-lg-block">
                    {{-- <a class="nav-link apptogglefullscreen" id="navbar-fullscreen" href="javascript:;">
                        <i class="ft-maximize font-medium-3" style="color:black !important"></i>
                    </a> --}}
                </li>
                   
                <h5 class="translateLable padding-top-sm padding-left-sm pt-1"  data-translate="welcome_to_admin_panel">Welcome {{session('data')['name']}}</h5>
            </div>
            <div class="navbar-container pull-right">
                <div class="collapse navbar-collapse d-block" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <div class="d-none d-xl-block">
                            <div class="col-sm-12">   
                                <a href="profile" class="mr-1"><span class="mr-1" style="font-size: 24px; color: #aaa;">|</span><i title="Edit Profile" class="fa fa-user-circle-o fa-lg" style="color:brown;"></i></a>

                                <a href="updatePassword"><span class="mr-1" style="font-size: 24px; color: #aaa;">|</span><i title="Change Password" class="fa fa-key fa-lg" style="color:brown;"></i></a>

                                <a href="logout"><span class="mr-1" style="font-size: 24px; color: #aaa;">|</span><i title="Logout" class="fa fa-power-off fa-lg" style="color:brown;"></i></a>
                            </div>
                        </div>
                        <li class="dropdown nav-item d-xl-none d-block"><a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-user font-medium-3 blue-grey darken-4"></i>
                            <div class="dropdown-menu text-left dropdown-menu-right m-0 pb-0 dropdownBasic3Content" aria-labelledby="dropdownBasic2">
                                <a class="dropdown-item" href="profile">
                                    <div class="d-flex align-items-center"><i class="ft-edit mr-2"></i><span>Edit Profile</span></div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="updatePassword">
                                    <div class="d-flex align-items-center"><i class="ft-edit mr-2"></i><span>Update Password</span></div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout">
                                    <div class="d-flex align-items-center"><i class="ft-power mr-2"></i><span>Logout</span></div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <div class="app-sidebar menu-fixed" data-background-color="man-of-steel" data-image="../public/backend/img/sidebar-bg/01.jpg" data-scroll-to-active="true">
            <div class="sidebar-header">
                <div class="logo clearfix">
                    <a class="logo-text float-left" href="dashboard">
                        <div class="logo-img" style="">
                            <img src="../public/backend/img/Home_logo.png" alt="Logo"/><span class="text-white text bold" style="font-size: smaller;">&nbsp;Packarma</span>
                        </div>
                    </a>
                    <a class="nav-toggle d-none d-lg-none d-xl-block is-active" id="sidebarToggle" href="javascript:;"><i class="toggle-icon ft-toggle-right" data-toggle="collapsed"></i></a>
                    <a class="nav-close d-block d-lg-block d-xl-none" id="sidebarClose" href="javascript:;"><i class="ft-x"></i></a>
                </div>
            </div>
            <div class="sidebar-content main-menu-content scroll">
                @php
                //$lastParam =  last(request()->segments());
                //GET OATH :: Request::path()
                    $lastParam =  Request::segment(2);
                    $permissions = Session::get('permissions');
                    $count = count($permissions);
                    $permission_array = array();
                @endphp
                @for($i=0; $i<$count; $i++)
                    @php
                        $permission_array[$i] = $permissions[$i]->codename;
                    @endphp
                @endfor
                <div class="nav-container">
                    <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                        <li class="nav-item {{ Request::path() ==  'dashboard' ? 'active' : ''  }}">
                            <a href="dashboard"><i class="ft-home"></i><span class="menu-title" data-i18n="Documentation">Dashboard</span></a>
                        </li>
                        @if(session('data')['role_id'] == 1  ||
                            in_array('category', $permission_array) || in_array('company', $permission_array) ||
                            in_array('city', $permission_array) 
                        )
                            <li class="has-sub nav-item 
                                {{-- {{ $lastParam ==  'company' ? 'open' : ''  }} --}}
                                {{ $lastParam ==  'city' ? 'open' : ''  }}
                                {{ $lastParam ==  'state' ? 'open' : ''  }}                                
                                {{-- {{ $lastParam ==  'country' ? 'open' : ''  }}
                                {{ $lastParam ==  'currency' ? 'open' : ''  }} --}}
                                {{ $lastParam ==  'subscription' ? 'open' : ''  }}
                                {{ $lastParam ==  'banners' ? 'open' : ''  }}
                            ">
                                <a href="javascript:;" class="dropdown-parent"><i class="ft-grid"></i><span data-i18n="" class="menu-title">Master</span></a>
                                <ul class="menu-content">
                                    {{-- @if(in_array('company', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'company' ? 'active' : ''  }}">
                                            <a href="company" class="menu-item"><i class="fa fa-circle fs_i"></i>Company</a>
                                        </li>
                                    @endif --}}
                                    @if(in_array('city', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'city' ? 'active' : ''  }}">
                                            <a href="city" class="menu-item"><i class="fa fa-circle fs_i"></i>City</a>
                                        </li>
                                    @endif
                                    @if(in_array('state', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'state' ? 'active' : ''  }}">
                                            <a href="state" class="menu-item"><i class="fa fa-circle fs_i"></i>State</a>
                                        </li>
                                    @endif
                                    {{-- @if(in_array('country', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'country' ? 'active' : ''  }}">
                                            <a href="country" class="menu-item"><i class="fa fa-circle fs_i"></i>Country</a>
                                        </li>
                                    @endif
                                    @if(in_array('currency', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'currency' ? 'active' : ''  }}">
                                            <a href="currency" class="menu-item"><i class="fa fa-circle fs_i"></i>Currency</a>
                                        </li>
                                    @endif --}}
                                    @if(in_array('subscription', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'subscription' ? 'active' : ''  }}">
                                            <a href="subscription" class="menu-item"><i class="fa fa-circle fs_i"></i>Subscription</a>
                                        </li>
                                    @endif
                                    @if(in_array('banners', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'banners' ? 'active' : ''  }}">
                                            <a href="banners" class="menu-item"><i class="fa fa-circle fs_i"></i>Banner</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if(session('data')['role_id'] == 1  ||
                                in_array('category', $permission_array) || in_array('company', $permission_array) ||
                                in_array('city', $permission_array) 
                            )
                            <li class="has-sub nav-item 
                                {{ $lastParam ==  'category' ? 'open' : ''  }} 
                                {{ $lastParam ==  'sub_category' ? 'open' : ''  }}
                                {{ $lastParam ==  'product_form' ? 'open' : ''  }}
                                {{ $lastParam ==  'packing_type' ? 'open' : ''  }}
                                {{ $lastParam ==  'packaging_machine' ? 'open' : ''  }}
                                {{ $lastParam ==  'packaging_treatment' ? 'open' : ''  }}
                                {{ $lastParam ==  'product' ? 'open' : ''  }}
                                {{ $lastParam ==  'packaging_material' ? 'open' : ''  }}
                                {{ $lastParam ==  'packaging_solution' ? 'open' : ''  }}
                                {{ $lastParam ==  'storage_condition' ? 'open' : ''  }}
                                {{ $lastParam ==  'measurement_unit' ? 'open' : ''  }}
                            ">
                                <a href="javascript:;" class="dropdown-parent"><i class="ft-grid"></i><span data-i18n="" class="menu-title">Product Master</span></a>
                                <ul class="menu-content">
                                    @if(in_array('category', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'category' ? 'active' : ''  }}">
                                            <a href="category" class="menu-item"><i class="fa fa-circle fs_i"></i>Category</a>
                                        </li>
                                    @endif
                                    @if(in_array('sub_category', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'sub_category' ? 'active' : ''  }}">
                                            <a href="sub_category" class="menu-item"><i class="fa fa-circle fs_i"></i>Sub Category</a>
                                        </li>
									@endif
									@if(in_array('product_form', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'product_form' ? 'active' : ''  }}">
                                            <a href="product_form" class="menu-item"><i class="fa fa-circle fs_i"></i>Product Form</a>
                                        </li>
                                    @endif
                                    @if(in_array('packing_type', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'packing_type' ? 'active' : ''  }}">
                                            <a href="packing_type" class="menu-item"><i class="fa fa-circle fs_i"></i>Packing Type</a>
                                        </li>
                                    @endif
                                    @if(in_array('packaging_machine', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'packaging_machine' ? 'active' : ''  }}">
                                            <a href="packaging_machine" class="menu-item"><i class="fa fa-circle fs_i"></i>Packaging Machine</a>
                                        </li>
                                    @endif
                                    @if(in_array('packaging_treatment', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'packaging_treatment' ? 'active' : ''  }}">
                                            <a href="packaging_treatment" class="menu-item"><i class="fa fa-circle fs_i"></i>Packaging Treatment</a>
                                        </li>
                                    @endif
                                    @if(in_array('storage_condition', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'storage_condition' ? 'active' : ''  }}">
                                            <a href="storage_condition" class="menu-item"><i class="fa fa-circle fs_i"></i>Storage Condition</a>
                                        </li>
                                    @endif
                                    @if(in_array('measurement_unit', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'measurement_unit' ? 'active' : ''  }}">
                                            <a href="measurement_unit" class="menu-item"><i class="fa fa-circle fs_i"></i>Measurement Unit</a>
                                        </li>
                                    @endif
                                    @if(in_array('product', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'product' ? 'active' : ''  }}">
                                            <a href="product" class="menu-item"><i class="fa fa-circle fs_i"></i>Product</a>
                                        </li>
                                    @endif
                                    @if(in_array('packaging_material', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'packaging_material' ? 'active' : ''  }}">
                                            <a href="packaging_material" class="menu-item"><i class="fa fa-circle fs_i"></i>Packaging Material</a>
                                        </li>
                                    @endif
                                    @if(in_array('packaging_solution', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'packaging_solution' ? 'active' : ''  }}">
                                            <a href="packaging_solution" class="menu-item"><i class="fa fa-circle fs_i"></i>Packaging Solution</a>
                                        </li>
                                    @endif
                                </ul>
                        @endif

                        <li class="has-sub nav-item {{ $lastParam ==  'customer_section' ? 'open' : ''  }} {{ $lastParam ==  'customer_section' ? 'open' : ''  }}">
                            <a href="javascript:;" class="dropdown-parent"><i class="ft-users"></i><span data-i18n="" class="menu-title">Customer Section</span></a>
                            <ul class="menu-content">
                                @if(in_array('user_approval_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/user_approval_list' ? 'active' : ''  }}">
                                        <a href="user_approval_list" class="menu-item"><i class="fa fa-circle fs_i"></i>Approval List</a>
                                    </li>
                                @endif
                                @if(in_array('user_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/user_list' ? 'active' : ''  }}">
                                        <a href="user_list" class="menu-item"><i class="fa fa-circle fs_i"></i>User List</a>
                                    </li>
                                @endif
                                @if(in_array('user_address_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/user_address_list' ? 'active' : ''  }}">
                                        <a href="user_address_list" class="menu-item"><i class="fa fa-circle fs_i"></i>User Address List</a>
                                    </li>
                                @endif
                                @if(in_array('user_subscription_payment', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/user_subscription_payment' ? 'active' : ''  }}">
                                        <a href="user_subscription_payment" class="menu-item"><i class="fa fa-circle fs_i"></i>User Subscription Payment</a>
                                    </li>
                                @endif
                                @if(in_array('customer_enquiry', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ $lastParam ==  'customer_enquiry' ? 'active' : ''  }}">
                                            <a href="customer_enquiry" class="menu-item"><i class="fa fa-circle fs_i"></i>Customer Enquiry</a>
                                        </li>
                                @endif
                            </ul>
                        </li>
                        <li class="has-sub nav-item {{ $lastParam ==  'vendors' ? 'open' : ''  }} {{ $lastParam ==  'vendors' ? 'open' : ''  }}">
                            <a href="javascript:;" class="dropdown-parent"><i class="icon-user-following"></i><span data-i18n="" class="menu-title">Vendors</span></a>
                            <ul class="menu-content">
                                @if(in_array('vendor_approval_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/vendor_approval_list' ? 'active' : ''  }}">
                                        <a href="vendor_approval_list" class="menu-item"><i class="fa fa-circle fs_i"></i>Vendor Approval List</a>
                                    </li>
                                @endif
                                @if(in_array('vendor_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/vendor_list' ? 'active' : ''  }}">
                                        <a href="vendor_list" class="menu-item"><i class="fa fa-circle fs_i"></i>Vendor List</a>
                                    </li>
                                @endif
                                @if(in_array('vendor_material_map', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/vendor_material_map' ? 'active' : ''  }}">
                                        <a href="vendor_material_map" class="menu-item"><i class="fa fa-circle fs_i"></i>Vendor Material Map</a>
                                    </li>
                                @endif
                                {{-- @if(in_array('vendor_warehouse', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/vendor_warehouse' ? 'active' : ''  }}">
                                        <a href="vendor_warehouse" class="menu-item"><i class="fa fa-circle fs_i"></i>Vendor Warehouse</a>
                                    </li>
                                @endif --}}
                                @if(in_array('vendor_payment_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/vendor_payment_list' ? 'active' : ''  }}">
                                        <a href="vendor_payment_list" class="menu-item"><i class="fa fa-circle fs_i"></i>Vendor Payment</a>
                                    </li>
                                @endif
                                @if(in_array('vendor_quotation_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/vendor_quotation_list' ? 'active' : ''  }}">
                                        <a href="vendor_quotation_list" class="menu-item"><i class="fa fa-circle fs_i"></i>Vendor Quotation</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        <li class="has-sub nav-item {{ $lastParam ==  'orders' ? 'open' : ''  }} {{ $lastParam ==  'orders' ? 'open' : ''  }}">
                            <a href="javascript:;" class="dropdown-parent"><i class="icon-handbag"></i><span data-i18n="" class="menu-title">Orders</span></a>
                            <ul class="menu-content">
                                @if(in_array('order_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/order_list' ? 'active' : ''  }}">
                                        <a href="order_list" class="menu-item"><i class="fa fa-circle fs_i"></i>Order List</a>
                                    </li>
                                @endif
                                {{-- @if(in_array('order_payment_list', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/order_payment_list' ? 'active' : ''  }}">
                                        <a href="order_payment_list" class="menu-item"><i class="fa fa-circle fs_i"></i>Order Payment List</a>
                                    </li>
                                @endif --}}
                            </ul>
                        </li>
                        @if(session('data')['role_id'] == 1  ||
                            in_array('role', $permission_array) || in_array('staff', $permission_array) 
                        )
                            <li class="has-sub nav-item {{ $lastParam ==  'roles' ? 'open' : ''  }} {{ $lastParam ==  'staff' ? 'open' : ''  }}">
                                <a href="javascript:;" class="dropdown-parent"><i class="icon-users"></i><span data-i18n="" class="menu-title">Staff</span></a>
                                <ul class="menu-content">
                                    @if(in_array('role', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ Request::path() ==  'webadmin/roles' ? 'active' : ''  }}">
                                            <a href="roles" class="menu-item"><i class="fa fa-circle fs_i"></i>Manage Roles</a>
                                        </li>
                                    @endif
                                    @if(in_array('staff', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ Request::path() ==  'webadmin/staff' ? 'active' : ''  }}">
                                            <a href="staff" class="menu-item"><i class="fa fa-circle fs_i"></i>Manage Staff</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                        {{-- @if(session('data')['role_id'] == 1  ||
                            in_array('role', $permission_array) || in_array('messaging', $permission_array) 
                        )
                            <li class="has-sub nav-item {{ $lastParam ==  'roles' ? 'open' : ''  }} {{ $lastParam ==  'messaging' ? 'open' : ''  }}">
                                <a href="javascript:;" class="dropdown-parent"><i class="icon-envelope"></i><span data-i18n="" class="menu-title">Messaging</span></a>
                                <ul class="menu-content">
                                    @if(in_array('sms', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ Request::path() ==  'webadmin/sms' ? 'active' : ''  }}">
                                            <a href="sms" class="menu-item"><i class="fa fa-circle fs_i"></i>SMS</a>
                                        </li>
                                    @endif
                                    @if(in_array('email', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ Request::path() ==  'webadmin/email' ? 'active' : ''  }}">
                                            <a href="email" class="menu-item"><i class="fa fa-circle fs_i"></i>Email</a>
                                        </li>
                                    @endif
                                    @if(in_array('whatsapp', $permission_array) || session('data')['role_id'] == 1)
                                        <li class="{{ Request::path() ==  'webadmin/whatsapp' ? 'active' : ''  }}">
                                            <a href="whatsapp" class="menu-item"><i class="fa fa-circle fs_i"></i>Whatsapp</a>
                                        </li>
                                    @endif
                                    @if(in_array('notification', $permission_array) || session('data')['role_id'] == 1)
                                    <li class="{{ Request::path() ==  'webadmin/notification' ? 'active' : ''  }}">
                                        <a href="notification" class="menu-item"><i class="fa fa-circle fs_i"></i>Notification</a>
                                    </li>
                                @endif
                                </ul>
                            </li>
                        @endif --}}
                        <li class="nav-item {{ $lastParam ==  'review' ? 'active' : ''  }}">
                            <a href="review"><i class="ft-star"></i><span class="menu-title" >Reviews</span></a>
                        </li>
                        <li class="nav-item {{$lastParam ==  'contactus' ? 'active' : ''  }}">
                            <a href="contactus"><i class="ft-phone"></i><span class="menu-title" data-i18n="Documentation">Contact us</span></a>
                        </li>

                        <li class="has-sub nav-item {{ $lastParam ==  'generalSetting' ? 'open' : ''  }} {{ $lastParam ==  'vendorGeneralSetting' ? 'open' : ''  }}">
                                <a href="javascript:;" class="dropdown-parent"><i class="icon-settings"></i><span data-i18n="" class="menu-title">General Settings</span></a>
                                <ul class="menu-content">
                                    <li class="nav-item {{ $lastParam ==  'generalSetting' ? 'active' : ''  }}">
                            <a href="generalSetting"><i class="fa fa-circle fs_i"></i><span class="menu-title" >Customer</span></a>
                        </li>
                                     <li class="nav-item {{ $lastParam ==  'vendorGeneralSetting' ? 'active' : ''  }}">
                            <a href="vendorGeneralSetting"><i class="fa fa-circle fs_i"></i><span class="menu-title" >Vendor</span></a>
                        </li>
                                </ul>
                            </li>


                      
                        
                       
                        <li class="nav-item {{ $lastParam ==  'logout' ? 'active' : ''  }}">
                            <a href="logout"><i class="fa fa-power-off"></i><span class="menu-title" >Logout</span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="sidebar-background"></div>
        </div>
        <div class="main-panel">
            @yield('content')
            <footer class="footer">
                <p class="clearfix text-muted m-0"><span>Copyright &copy; 2022 &nbsp;</span><span class="d-none d-sm-inline-block"> All rights reserved.</span></p>
            </footer>
            <button class="btn btn-primary scroll-top" type="button"><i class="ft-arrow-up"></i></button>
        </div>
        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>
    </div>
</body>
<script src="../public/backend/vendors/js/switchery.min.js"></script>
<script src="../public/backend/js/core/app-menu.js"></script>
<script src="../public/backend/js/core/app.js"></script>
<script src="../public/backend/js/notification-sidebar.js"></script>
<script src="../public/backend/js/customizer.js"></script>
<script src="../public/backend/js/scroll-top.js"></script>
<script src="../public/backend/js/scripts.js"></script>
<script src="../public/backend/js/ajax-custom.js"></script>
<script src="../public/backend/js/mypcot.min.js"></script>
<script src="../public/backend/js/select2.min.js"></script>
</html>