@extends('admin.layouts.design')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>نمایش سفارشات موجود   </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('ad.dashboard') }}">خانه</a></li>
                            <li class="breadcrumb-item active">نمایش سفارشات موجود</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- general form elements -->
                <!-- /.card-header -->
                <!-- form start -->
                @include('layouts.errors')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">نمایش سفارشات  </h3>
                                <button id="PdfMake" class="btn btn-info">PDF</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table id="orderTable" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>

                                        <th>نام کاربر </th>
                                        <th>شماره سبد خرید </th>
                                        <th>آدرس </th>
                                        <th>نوع پرداخت </th>
                                        <th>نوع حمل و نقل </th>
                                        <th>مبلغ کل </th>
                                        <th>وضعیت سفارش</th>
                                        <th>تاریخ بروز رسانی</th>
                                        <th>عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($orders as $key => $row)
                                        <tr>
                                            <th>{{ $key+=1 }}</th>
                                            <th>{{ $row->user->username??'' }} </th>
                                            <th>{{ $row->cart_id }} </th>
                                            <th>{{ $row->address->address??'' }} </th>
                                            <th>{{ $row->payment_type }} </th>
                                            <th>
                                                @if($row->post_string=="code")
                                                <span>حضوری</span>
                                                @elseif($row->post_string=="payk")
                                                    <span>پیک</span>
                                                @elseif($row->post_string=="tipax")
                                                    <span>tipax</span>
                                                @elseif($row->post_string=="baar")
                                                    <span>باربری</span>
                                                @elseif($row->post_string=="bus")
                                                    <span>اتوبوس رانی</span>
                                                @else
                                                    <span>نامشخص </span>
                                                @endif
                                            </th>
                                            <th>{{ $row->total }} قیمت تمام شده </th>
                                            <th>
                                                {{ $row->order_status }}
                                            </th>
                                            <th>{{ Verta($row->updated_at)->format('%d %B %Y H:i') }}</th>
                                            <th>
                                                <div class="tools">
                                                    <div class="row">
                                                        <?php
                                                        $count = \App\OrderStatus::count();
                                                        ?>
                                                        @if($count > 0)
                                                        <a href="{{ url('/ad/orders/change/'.$row->id) }}"
                                                           style="margin-right: 10px;margin-left: 10px;">
                                                            <i class="fa fa-check-circle"></i>
                                                        </a>
                                                        @endif
                                                        <a id="delRole" rel="{{ $row->id }}"
                                                           rel1="orders/delete"
                                                           href="javascript:"
                                                           class="deleteRecord">
                                                            <i class="fa fa-trash-o"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    @empty
                                        <p>سفارشات موجود نیست</p>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>

                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(document).on('click', '.deleteRecord', function (e) {
            var id = $(this).attr('rel');
            var deleteFunction = $(this).attr('rel1');
            swal({
                    title: "شما مطمئن هستید؟",
                    text: "شما نمیتوانید این رکورد را دوباره بازیابی کنید!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "بله، آن را حذف کنید!",
                    closeOnConfirm: false
                },
                function () {
                    window.location.href = "/ad/" + deleteFunction + "/" + id;
                });

        });
        $('#PdfMake').on('click', function () {
            window.print();

        })

    </script>
    <script>
        $(function () {
            var table = $("#orderTable").DataTable({
                paging: true,
                ordering: false,
                info: false,
                stateSave: true,
                searching: true,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Persian.json"
                },
                "responsive": true, "lengthChange": true, "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });
            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });

    </script>
@endsection
