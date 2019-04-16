<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user-id" content="{{ Auth::user()->id }}">
  <meta name="user-name" content="{{ utf8_encode(Auth::user()->name) }}">
  <meta name="user-email" content="{{ Auth::user()->email }}">
  <meta name="user-branch_id" content="{{ Auth::user()->branch_id }}">
  <meta name="user-role" content="{{ Auth::user()->role }}">
  <link rel="stylesheet" href="{{asset('admin/bootstrap/css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/app.css')}}">


  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/font-awesome/css/font-awesome.min.css') }}">
  {{-- datetimepicker --}}
  <link rel="stylesheet" type="text/css" href="{{ asset('admin/datetimepicker/jquery.datetimepicker.css') }}">
  <!-- Datatable bootstrap -->
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <!-- Select2 -->
  {{--  <link rel="stylesheet" href="{{ asset('admin/select2/dist/css/select2.min.css') }}">  --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.css" rel="stylesheet" />

  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin/css/AdminLTE.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset('admin/css/_all-skins.css')}}">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  @yield('header')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>ICR</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img style="width:100px" src="http://www.icanread.asia/public/theme_icr/images/logo.png"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('admin/image/user.jpg') }}" class="user-image" alt="User Image">
              <span class="hidden-xs">Xin chào, {{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{ asset('admin/image/user.jpg') }}" class="img-circle" alt="User Image">
                <p>
                  {{ Auth::user()->name }}
                </p>
              </li>
              <li class="user-footer">
                {{--  <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>  --}}
                <div class="pull-right">
                  <a href="/logout" class="btn btn-default btn-flat">Thoát</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- =============================================== -->
  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      {{--  <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset('admin/image/user.jpg') }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>  --}}
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        {{--  <li class="header">MAIN NAVIGATION</li>  --}}
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
       
        
        {{--  <li class="">
          <a href="{{ asset('course') }}">
            <i class="fa fa-book" aria-hidden="true"></i>
            <span>Quản lý khóa học</span>
          </a>
        </li>  --}}
        <li class="">
          <a href="{{ asset('class') }}">
            <i class="fa fa-university" aria-hidden="true"></i>
            <span>Quản lý lớp</span>
          </a>
        </li>
        <li class="">
          <a href="{{ asset('student') }}">
            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
            <span>Quản lý tuyển sinh</span>
          </a>
        </li>
        <li class="">
          <a href="{{ asset('exam') }}">
            <i class="fa fa-trophy" aria-hidden="true"></i>
            <span>Quản lý đào tạo</span>
          </a>
        </li>
        <li class="">
          <a href="{{ asset('invoice') }}">
            <i class="fa fa-credit-card" aria-hidden="true"></i>
            <span>Lập hoá đơn</span>
          </a>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-female" aria-hidden="true"></i>
            <span>Quản lý giáo viên</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{asset('teacher-list')}}"><i class="fa fa-circle-o"></i> Danh sách giáo viên</a></li>
            <li><a href="{{asset('teacher-weekly-schedule')}}"><i class="fa fa-circle-o"></i> Lịch hàng tuần</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user" aria-hidden="true"></i>
            <span>Quản lý nhân viên</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{asset('staff-list')}}"><i class="fa fa-circle-o"></i> Danh sách nhân viên</a></li>
            <li><a href="{{asset('staff-add')}}"><i class="fa fa-circle-o"></i> Thêm nhân viên</a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-cog" aria-hidden="true"></i>
            <span>Thiết đặt hệ thống</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{asset('branch')}}"><i class="fa fa-circle-o"></i> Chi nhánh</a></li>
            <li>
                <a href="{{ asset('holiday') }}">
                  <i class="fa fa-calendar" aria-hidden="true"></i>
                  <span>Lịch nghỉ lễ</span>
                </a>
            </li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- =============================================== -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('title')
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ asset('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        @yield('breadcrumb')
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <div class="box-body">
          @yield('content')
        </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- jQuery 3 -->
{{--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  --}}
{{--  <script src="{{asset('admin/js/jquery.min.js')}}"></script>  --}}
{{-- momentjs --}}
<script>
  var asset = "{{asset('')}}";
  var img = "{{ asset('storage') }}/";
</script>
<script src="{{ asset('admin/js/moment.js') }}"></script>

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('admin/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>

<script src="{{ asset('admin/js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('admin/js/jquery.bootpag.min.js') }}"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<!-- Select2 -->
{{--  <script src="{{ asset('admin/select2/dist/js/select2.full.min.js') }}"></script>  --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="{{asset('admin/js/adminlte.js')}}"></script>
@yield('js')
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree();
  })

  var datatable_language = {
                "paginate": {
                  "previous": "Trước",
                  "next": "Sau",
                  "first": "Đầu tiên",
                  "last": "Cuối cùng"
                },
                "emptyTable" : "Không có bản ghi nào!",
                "info" : "Hiển thị từ _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
                "infoEmpty": "Hiển thị 0 bản ghi",
                "search": "Tìm kiếm:",
                "zeroRecords": "Không tìm thấy bản ghi nào phù hợp!",
                "lengthMenu":     "Hiển thị _MENU_ bản ghi"
            };

  var daterange_locale =  {
              "format": "YYYY/MM/DD",
              "separator": " - ",
              "applyLabel": "Chọn",
              "cancelLabel": "Huỷ",
              "fromLabel": "Từ",
              "toLabel": "Đến",
              "customRangeLabel": "Tuỳ chọn",
              "weekLabel": "W",
              "daysOfWeek": [
                  "CN",
                  "T2",
                  "T3",
                  "T4",
                  "T5",
                  "T6",
                  "T7"
              ],
              "monthNames": [
                  "Th.1",
                  "Th.2",
                  "Th.3",
                  "Th.4",
                  "Th.5",
                  "Th.6",
                  "Th.7",
                  "Th.8",
                  "Th.9",
                  "Th.10",
                  "Th.11",
                  "Th.12"
              ],
              "firstDay": 1
          };
</script>
@yield('footer')
</body>
</html>