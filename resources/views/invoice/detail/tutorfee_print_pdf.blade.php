<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Print Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  {{--  <style>
    {{$css}}
  </style>  --}}
  <!-- style_pdf -->
  <style>
    body {
      font-size: 13px;
      margin: 0px;
    }

    @media print {
      @page {
        size:25%; !important
        margin-bottom:0.5cm;
        border:none;
      }

      #invoice-detail tr td, #signature tr td{
        height: 20px;!important
        padding: 0 1px 0 1px; !important
        line-height:50%; !important
      }
    }

    /* A5 Landscape*/
    @page {
      size: A5 landscape;
      margin:2%;
    }

    #invoice-detail {
      width: 100%;
    }

    #invoice-detail tr td{
        height: 24px;!important
        padding: 0 1px 0 1px; !important
    }

    .table-striped > tbody > tr:nth-of-type(odd) {
      background-color: #f9f9f9;
    }

    .row {
      width: 100%;
    }

    .col-sm-1 {
      width: 17.5mm;
    }

    .col-sm-2 {
      width: 35mm;
    }

    .col-sm-3 {
      width: 52.5mm;
    }

    .col-sm-4 {
      width: 70mm;
    }
    .col-sm-5 {
      width: 87.5mm;
    }
    .col-sm-6 {
      width: 105mm;
    }
    .col-sm-7 {
      width: 122.5mm;
    }
    .col-sm-8 {
      width: 140mm;
    }
    .col-sm-9 {
      width: 157.5mm;
    }
    .col-sm-10 {
      width: 175mm;
    }
    .col-sm-11 {
      width: 192.5mm;
    }
    .col-sm-12, .col-xs-12 {
      width: 210mm;
    }

  </style>

  <!-- Google Font -->
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <table style="width:100%">
        <tr>
          <td><img style="width:100px" src="{{public_path('images/logo.png')}}"></td>
          {{--  <td><img style="width:100px" src="{{$logo}}"></td>  --}}
          <td style="text-align:right">Ngày in: {{date('d/m/Y H:i')}}</td>
        </tr>
    </table>
    <!-- info row -->
    <p class="h4"><strong>I CAN READ SYSTEM IN VIETNAM</strong></p>

    <div class="row invoice-info">
        <table style="width:100%">
            <tr>
              <td style="width:25%"><strong>Yên Hoà</strong>: 0966 620 066</td>
              <td style="width:45%"><strong>Tô Hiến Thành</strong>: 0961 305 885</td>
              <td style="width:30%"><b>Mẫu số: 04-KT-ICR</b></td>
            </tr>
            <tr>
                <td><strong>Kim Mã</strong>: 0963 010 033</td>
                <td><strong>Nguyễn Trãi</strong>: 0914 698 000</td>
                <td><b>Số phiếu thu:</b> {{$invoice_number}}</td>
            </tr>
            <tr>
                <td><strong>Văn Quán</strong>: 0978 730 022</td>
                <td><strong>Hoàng Quốc Việt</strong>: 0961 205 550</td>
                <td><b>Ngày lập phiếu:</b> {{$created_at}}</td>
            </tr>
          </table>
    </div>
    <!-- /.row -->
    <p></p>
    <!-- Table row -->
    <div class="row">
        <table style="width:100%" class="table-striped" id="invoice-detail">
          <tr>
            <td style="width:15px;">1. </td>
            <td style="width:200px;">Họ và tên học viên</td>
            <th colspan="4" style="align:left">{{$student_name}}</th>
         </tr>
         <tr>
            <td>2. </td>
            <td>Mã học viên</td>
            <th>{{$student_code}}</th>
            <td style="width:15px;">3. </td>
            <td style="width:100px;">Lớp</td>
            <th>{{$class_name}}</th>
        </tr>
        <tr>  
            <td></td>
            <td>Số buổi học</td>
            <th colspan="2">{{$duration}}</th>
            <td>Kỳ học phí</td>
            <th>Từ {{$start_date}} đến {{$end_date}}</th>
        </tr>
        <tr>
            <td></td>
            <td>Chiết khấu</td>
            <th colspan="2">{{$discount}}%</th>
            <td>Lý do chiết khấu</td>
            <th>{{$discount_desc}}</th>
        </tr>
        <tr> 
            <td>4. </td>
            <td>Lý do nộp</td>
            <th colspan="4">Đóng học phí</th>
        </tr>
        <tr>
            <td>5. </td>
            <td>Số tiền tạm ứng (nếu có)</td>
            <th style="width:250px;" colspan="2"><span id="prepaid">{{$prepaid}}</span> {{$currency}}</th>
            <td>Số tiền phải thu</td>
            <th><span id="amount_num">{{$amount}}</span> {{$currency}}</th>
        </tr>
        <tr>
            <td></td>
            <td>Viết bằng chữ</td>
            <th colspan="4" style="text-align:left;padding-left:15px"><span id="amount_text">{{$amount_text}}</span> đồng chẵn</th>
        </tr>
        {{--  <tr>
            <td></td>
            <td>Số tiền còn phải thu</td>
            <th colspan="4"></th>
        </tr>  --}}
        {{--  <tr>
            <td>6. </td>
            <td>Chứng từ kèm theo</td>
            <th colspan="4">Không có</th>
        </tr>  --}}
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
      
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <p></p>
    <div class="row">
      <!-- /.col -->
      <div class="col-xs-12">
          <table class="table border-0" id="signature" style="width:100%">
            <tr>
                <td style="text-align:center"><strong>Người nộp tiền</strong><br />(ký và họ tên)</td>
                <td style="text-align:center"><strong>Người lập phiếu</strong><br />(ký và họ tên)</td>
                <td style="text-align:center"><strong>Quản lý trung tâm</strong><br />(ký và họ tên)</td>
                <td style="text-align:center"><strong>Kế toán</strong><br />(ký và họ tên)</td>
                <td style="text-align:center"><strong>Giám đốc</strong><br />(ký và họ tên)</td>
            </tr>
            <tr>
                <td colspan="5" style="height:50px"></td>
            </tr>
            <tr>
              <td style="text-align:center">{{$payer}}</td>
              <td style="text-align:center">{{$created_by_name}}</td>
              <td style="text-align:center"></td>
              <td style="text-align:center"></td>
              <td style="text-align:center"></td>
          </tr>
          </table>
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
