<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>HMB Projects Pvt Ltd - Admin Panel</title>
    <link rel="icon" type="image/x-icon" href="{{ URL::asset('assets/admin/img/fav-icon.png') }}">
    <link href="{{ URL::asset('assets/admin') }}/css/validationEngine.jquery.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

    <link href="{{ URL::asset('assets/admin') }}/css/animate.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/css/select2.min.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/css/style.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/css/toastr.min.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
    <link href="{{ URL::asset('assets/admin') }}/css/plugins/switchery/switchery.css" rel="stylesheet">

    <script src="{{ URL::asset('assets/admin') }}/js/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css">

    <style type="text/css">
    .formErrorArrowBottom {
            box-shadow: none;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
            margin: 0px 0 0 12px;
            top: 12px;
        }
        .formErrorContent11 {
        position: absolute;
        top: 34px !important;
        width: auto !important;
        left: 35px !important;
        z-index: 11 !important;
        text-transform: none !important;
        background-color: #f2dede !important;
        color: #a94442!important;
        padding: 4px 10px !important;
        / font-size: 15px !important; /
        font-weight: 500 !important;
        border-radius: 3px !important;
    
    }
        .formErrorContent {
               position: absolute;
                top: 40px !important;
                width: auto !important;
                left: 177px !important;
                z-index: 11 !important;
                text-transform: none !important;
                background-color: #f2dede !important;
                color: #a94442!important;
                padding: 4px 10px !important;
                font-size: 15px !important;
                font-weight: 500 !important;
                border-radius: 3px !important;
        }
        .formError .formErrorArrowBottom {
            /* box-shadow: none;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
            margin: 0px 0 0 12px;
            top: 12px; */
            display: none;
        }
        .formErrorArrowBottom:after {
             content: '' !important;
            position: absolute !important;
            bottom: 10px !important;
            left: 15px !important;
            width: 0 !important;
            height: 0 !important;
            border: 10px solid transparent !important;
            border-top-color: #f2dede!important;
            border-bottom: 0 !important;
            margin-bottom: 18px !important;
            transform: rotate(180deg) !important;
    }
      .success_cls {
         border: 1px solid green !important; 
    }
    .error_cls {
        border: 1px solid red !important; 
    }
    .formError .formErrorContent {
        position: relative !important;
        color: red ;
        font-size: inherit;
        border: 0px solid #ddd;
        line-height: inherit;
        margin-top: 17px !important;
        margin-left: -176px !important;
        float: left;
    }
    .formError .formErrorContent:after{
        content: '' !important;
        position: absolute !important;
        bottom: 10px !important;
        left: 15px !important;
        width: 0 !important;
        height: 0 !important;
        border: 10px solid transparent !important;
        border-top-color: #f2dede!important;
        border-bottom: 0 !important;
        margin-bottom: 19px !important;
        transform: rotate(180deg) !important;
    }
    .formError .formErrorContent1 {
        position: relative !important;
        color: red ;
        font-size: inherit;
        border: 0px solid #ddd;
        line-height: inherit;
        margin-top: -29px !important;
        margin-left: -176px !important;
    }
    .formError .formErrorArrowBottom {
        box-shadow: none !important;
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        margin: 0px 0 0 12px !important;
        top: 40px !important;
    }
    .showicon{
    height: 0;
    position: absolute;
    margin-top: 5px;
    right: 23px;
    font-size: 20px;
    cursor: pointer;
    }
    .showicon2{
    height: 0;
    position: absolute;
    margin-top: 6px;
    right: 19px;
    font-size: 20px;
    cursor: pointer;
    }
    .heading_text:first-letter{
    font-size:150%;
    }
    .already-have{
    margin-top: 110px;
    }
    .formErrorContent111 {
    position: absolute;
    top: 95px !important;
    width: auto !important;
    left: 30px !important;
    z-index: 11 !important;
    text-transform: none !important;
    background-color: #f2dede !important;
    color: #a94442!important;
    padding: 4px 10px !important;
    / font-size: 15px !important; /
    font-weight: 500 !important;
    border-radius: 3px !important;
    }

    .custom-option .formError
    {
        top: 25px !important;
    }

    @media (max-width: 1199px){
      .remove_left_border,.remove_right_border{
        border : 0px  !important;
      }
      .device_image{
        width : 50% !important;
      }

    body{
         / The image used /
      /*background-image: url("admin_file/img/background.png");*/

    /*  filter: blur(8px);
      -webkit-filter: blur(8px);*/

      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }

    .form-group.g-recaptcha iframe{
      width: 100% !important;
      height: auto !important;
    }
    .rc-anchor-normal .rc-anchor-content {
        height: auto !important;
        width: 60% !important;
    }
    @media (max-width: 420px) {

    div.form-group.g-recaptcha{
      margin-left: -30px !important;
    }
    }

    </style>

</head>

<body>
    <input type="hidden" id="current_url" value="{{Request::url()}}">
    <input type="hidden" id="url" value="{{url('')}}">
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}"/>
 
    @if (Session::has('success'))
    @include('admin/msg/success')
    @endif
    @if (Session::has('error'))
    @include('admin/msg/error')
    @endif
    @if (Session::has('info'))
    @include('admin/msg/info')
    @endif
    <div id="wrapper">
        @include('admin.layouts.navigation.sidebar')
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                @include('admin.layouts.navigation.topnav')
            </div>
            <div class="wrapper wrapper-content">
                @yield('content')
            </div>
            <div class="footer">
                @include('admin.layouts.navigation.footer')
            </div>

        </div>
    </div>






    <!-- Mainly scripts -->
    <script src="{{ URL::asset('assets/admin') }}/js/jquery.validationEngine-en.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/jquery.validationEngine.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/popper.min.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/bootstrap.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/switchery/switchery.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/dataTables/datatables.min.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

  

    <!-- Custom and plugin javascript -->
    <script src="{{ URL::asset('assets/admin') }}/js/inspinia.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- Jvectormap -->
    <!-- <script src="{{ URL::asset('assets/admin') }}/js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script> -->
    <!-- <script src="{{ URL::asset('assets/admin') }}/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->

    <!-- EayPIE -->
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/easypiechart/jquery.easypiechart.js"></script>

    <!-- Sparkline -->
    <!-- <script src="{{ URL::asset('assets/admin') }}/js/plugins/sparkline/jquery.sparkline.min.js"></script> -->

    <!-- Sparkline demo data  -->
    <!-- <script src="{{ URL::asset('assets/admin') }}/js/demo/sparkline-demo.js"></script> -->
    <script src="{{ URL::asset('assets/admin') }}/js/toastr.min.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/plugins/summernote/summernote-bs4.js"></script>
    <script src="//cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>
    <script src="{{ URL::asset('assets/admin') }}/js/common.js"></script>
</body>

</html>