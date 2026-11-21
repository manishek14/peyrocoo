@extends('layouts.admin.admin')
@section('title',' مدیریت کارمندان  ')
@section('pageTitle',' لیست مدیران ')
@section('content')

    <div class="d-flex flex-end mb-5">
        <button type="button" class="btn  btn-primary px-4 py-2 ml-4" data-bs-toggle="collapse"
                href="#student_filters" role="button"
                aria-expanded="false" aria-controls="collapseExample" style="margin-left: 5px!important;">
            فیلتر
            <i class="fa fa-filter p-0 m-0"></i>
        </button>
    </div>
    <div class="card mb-5 pb-3 mb-xl-8 collapse @if(request()->has('first_name')||request()->has('last_name')||request()->has('mobile')||request()->has('username')) show @else @endif" id="student_filters">
        <form action="" class="form remove-empty-values" method="get" id="remove-empty-values">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">
                        <i class="fa fa-search text-white pl-1"></i>
                        جستجوی پیشرفته
                    </span>
            </h3>
        </div>
        <div class="card-body py-3">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="form-label fs-6 fw-bolder text-dark">جستجو بر اساس نام
                        </label>
                        <input class="form-control form-control-lg form-control-solid" name="first_name"
                               placeholder=""
                               value="{{ request()->has('first_name') ? request()->get('first_name') : null }}"/>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="form-label fs-6 fw-bolder text-dark">جستجو بر اساس نام خانوادگی
                        </label>
                        <input class="form-control form-control-lg form-control-solid" name="last_name"
                               placeholder=""
                               value="{{ request()->has('last_name') ? request()->get('last_name') : null }}"/>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="form-label fs-6 fw-bolder text-dark">جستجو بر اساس شماره همراه
                        </label>
                        <input class="form-control form-control-lg form-control-solid" name="mobile" placeholder=""
                               value="{{ request()->has('mobile') ? request()->get('mobile') : null }}"/>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="form-label fs-6 fw-bolder text-dark">جستجو بر اساس  نام کاربری
                        </label>
                        <input class="form-control form-control-lg form-control-solid" name="username" placeholder=""
                               value="{{ request()->has('username') ? request()->get('username') : null }}"/>
                    </div>
                </div>
            </div>
            <br/>
            <div class="d-flex  flex-end">
                <a href="{{route('admin.admins.all')}}" class="btn btn-sm btn-light-danger mx-1">
                    <i class="fa fa-eraser p-0 m-0"></i>
                    حذف فیلترها
                </a>
                <button type="submit" class="btn btn-sm btn-light-primary mx-1">
                    <i class="fa fa-search"></i>
                    فیلتر
                </button>
            </div>
        </div>
        </form>
    </div>

    <div class="card" data-select2-id="select2-data-131-rhmf">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1"> لیست مدیران </span>
            </h3>
            <div class="card-toolbar">

                <a href="{{route('admin.admins.create')}}" class="btn btn-sm btn-light-success">
                   ثبت مدیر جدید
                </a>
            </div>
        </div>
        <!--begin::Card body-->
        <div class="card-body pt-0">

            <!--begin::Table-->
            <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer"
                           id="kt_customers_table">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="min-w-125px sorting">
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="Created Date: activate to sort column ascending">تصویر پروفایل
                            </th>
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="Customer Name: activate to sort column ascending">نام و نام خانوادگی
                            </th>
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="Customer Name: activate to sort column ascending">  نام کاربری
                            </th>
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="IP Address: activate to sort column ascending">تلفن همراه
                            </th>
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width:162.9px;"
                                aria-label="Status: activate to sort column ascending">وضعیت
                            </th>


                            <th class="min-w-125px sorting" rowspan="1" colspan="1"
                                style="width: 162.9px;" aria-label="Actions">تنظیمات
                            </th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                        @if($admins->count() > 0)
                            @foreach($admins as $admin)
                                <tr class="odd">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!--begin:: Avatar -->
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <a href="#">
                                                    <div class="symbol-label">
                                                        <img src="{{asset($admin->webPresent()->image)}}" alt="Ana Crown"
                                                             class="w-100">
                                                    </div>
                                                </a>
                                            </div>
                                            <!--end::Avatar-->

                                        </div>
                                    </td>
                                    <td>
                                        <a href="#"
                                           class="text-gray-600 text-hover-primary mb-1">  {{$admin->webPresent()->fullName}}</a>
                                    </td>
                                    <td>{!! $admin->username !!}</td>
                                    <td>{!! $admin->mobile !!}</td>
                                    <td>
                                        <!--begin::Badges-->
                                          {!! $admin->webPresent()->status !!}
                                        <!--end::Badges-->
                                    </td>
                                    <!--end::Date=-->
                                    <!--begin::Action=-->
                                    <td class="">


                                        <a href="{{ route('admin.admins.edit', $admin) }}"
                                           class="btn btn-icon btn-light-info btn-sm me-1">
                                             <span class="svg-icon svg-icon-3">
                                                <i class="fa fa-layer-group"></i>
                                            </span>
                                        </a>
                                        <a data-id={{ $admin->id }} href="javascript"
                                           class="btn btn-icon btn-light-danger btn-sm me-1 delete-confirm"
                                           data-kt-customer-table-filter="delete_row">
                                              <span class="svg-icon svg-icon-3">
                                                <i class="fa fa-trash"></i>
                                            </span>
                                        </a>
                                    </td>
                                    <!--end::Action=-->
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" style="text-align:center;color:red"><a class="btn btn-danger" href="">
                                       اطلاعات موجود نیست <i class="icon-warning2 mr-3 icon-1x"></i></a></td>
                            </tr>
                        @endif
                        </tbody>
                        <!--end::Table body-->
                    </table>
                </div>
                <div class="row">
                    <div
                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">

                    </div>
                </div>
            </div>
            <!--end::Table-->
            {{$admins->links('vendor.pagination.bootstrap-4') }}
        </div>
        <!--end::Card body-->
    </div>
@endsection
@section('scripts')
    @include('admin.message.alert')
    <script>
        $('#remove-empty-values').submit(function () {
            $(this).find(':input').filter(function () {
                return !this.value;
            }).attr('disabled', 'disabled');
            return true;
        });
    </script>
    <script>

        $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            let admin = $(this).data("id");
            let url = "{{ route('admin.admins.logicalDeletion',":admin") }}";
            url = url.replace(":admin",admin);

            Swal.fire({
                title: 'حذف  !',
                text: "آیا مطمئن هستید ؟",
                icon: "error",
                showCancelButton: true,
                confirmButtonColor: 'red',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'بله,حذف کن',
                cancelButtonText: 'خیر',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection
