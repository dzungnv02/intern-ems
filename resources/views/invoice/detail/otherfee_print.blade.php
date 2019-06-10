<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Print Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('admin/bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('admin/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('admin/css/AdminLTE.css')}}">

  <style>
    @if ($act === 'print')
    @media print {
      @page {
        size:35%; !important
        margin-bottom:1cm;
        border:none;
      }

      #invoice-detail tr td, #signature tr td{
        height: 20px;!important
        padding: 0 1px 0 1px; !important
        line-height:50%; !important
      }

      body {
        width: 100%;
        height: 100%;
      };
    }

    /* A4 Landscape*/
    @page {
      size: A5 landscape;
      margin:2%;
    }
    @endif

    #invoice-detail {
        width: 100%;
    }

    #invoice-detail tr td{
        height: 24px;!important
        padding: 0 1px 0 1px; !important
    }
  </style>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img style="width:100px" src="{{asset('images/logo.png')}}">
          <small class="pull-right">Ngày in: {{date('d/m/Y H:i')}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <p class="h4"><strong>I CAN READ SYSTEM IN VIETNAM</strong></p>

    <div class="row invoice-info">
      <div class="col-sm-2 invoice-col">
        <address>
          <strong>Yên Hoà</strong>: 0966 620 066<br>
          <strong>Kim Mã</strong>: 0963 010 033<br>
          <strong>Văn Quán</strong>: 0978 730 022
        </address>
      </div>
      <div class="col-sm-7 invoice-col">
            <address>
              <strong>Tô Hiến Thành</strong>: 0961 305 885<br>
              <strong>Nguyễn Trãi</strong>: 0914 698 000<br>
              <strong>Hoàng Quốc Việt</strong>: 0961 205 550
            </address>
          </div>
      <!-- /.col -->
      <div class="col-sm-3 invoice-col">
        <address>
            <b>Mẫu số: 04-KT-ICR</b><br>
            <b>Số phiếu thu:</b> {{$invoice_number}}<br>
            <b>Ngày lập phiếu:</b> {{$created_at}}<br>
        </address>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table-striped" id="invoice-detail">
          <tr>
            <td style="width:15px;">1. </td>
            <td style="width:200px;">Họ và tên học viên</td>
            <th>{{$student_name}}</th>
            <td style="width:15px;">3. </td>
            <td style="width:100px;">Lớp</td>
            <th>{{$class_name}}</th>
         </tr>
         <tr>
            <td>2. </td>
            <td>Mã học viên</td>
            <th colspan="4">{{$student_code}}</th>
        </tr>
        <tr> 
            <td>4. </td>
            <td>Lý do nộp</td>
            <th colspan="4">{{$reason}}</th>
        </tr>
        <tr>
            <td></td>
            <td>Viết bằng chữ</td>
            <th colspan="4"><span id="amount_text">{{$amount_text}}</span> đồng chẵn</th>
        </tr>
        <tr>
            <td>6. </td>
            <td>Phương thức thanh toán</td>
            <th colspan="4">{{$payment_method}}</th>
        </tr>
        <tr>
            <td>7. </td>
            <td>Ghi chú</td>
            <th colspan="4">{{$note}}</th>
        </tr>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- /.col -->
      <div class="col-xs-12">
        <div class="table-responsive border-0">
          <table class="table border-0" id="signature">
            <tr>
                <td class="text-center"><strong>Người nộp tiền</strong><br />(ký và họ tên)</td>
                <td class="text-center"><strong>Người lập phiếu</strong><br />(ký và họ tên)</td>
                <td class="text-center"><strong>Quản lý trung tâm</strong><br />(ký và họ tên)</td>
                <td class="text-center"><strong>Kế toán</strong><br />(ký và họ tên)</td>
                <td class="text-center"><strong>Giám đốc</strong><br />(ký và họ tên)</td>
            </tr>
            <tr>
                <td style="height:30px"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <th class="text-center">{{$payer}}</th>
              <th class="text-center">{{$created_by_name}}</th>
              <th class="text-center"></th>
              <th class="text-center"></th>
              <th class="text-center"></th>
          </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
