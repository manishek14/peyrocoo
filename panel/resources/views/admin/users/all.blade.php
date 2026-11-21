@extends('layouts.admin.admin')
@section('title',' مدیریت کاربران  ')
@section('pageTitle',' فهرست کاربران ')
@section('content')
    <div class="d-flex flex-end mb-5">
        <button type="button" class="btn  btn-primary px-4 py-2 ml-4" data-bs-toggle="collapse"
                href="#student_filters" role="button"
                aria-expanded="false" aria-controls="collapseExample" style="margin-left: 5px!important;">
            فیلتر
            <i class="fa fa-filter p-0 m-0"></i>
        </button>
    </div>
    <div class="card mb-5 pb-3 mb-xl-8 collapse @if(request()->has('family')||request()->has('phone')) show @else @endif" id="student_filters">
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
                            <label class="form-label fs-6 fw-bolder text-dark">جستجو بر اساس نام خانوادگی
                            </label>
                            <input class="form-control form-control-lg form-control-solid" name="family"
                                   placeholder=""
                                   value="{{ request()->has('family') ? request()->get('family') : null }}"/>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            <label class="form-label fs-6 fw-bolder text-dark">جستجو بر اساس  تلفن همراه
                            </label>
                            <input class="form-control form-control-lg form-control-solid" name="phone"
                                   placeholder=""
                                   value="{{ request()->has('phone') ? request()->get('phone') : null }}"/>
                        </div>
                    </div>

                </div>
                <br/>
                <div class="d-flex  flex-end">
                    <a href="{{route('admin.users.all')}}" class="btn btn-sm btn-light-danger mx-1">
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
                <span class="card-label fw-bolder fs-3 mb-1"> فهرست کاربران </span>
            </h3>
            <div class="card-toolbar">


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
                                aria-label="Customer Name: activate to sort column ascending">نام و نام خانوادگی
                            </th>
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="IP Address: activate to sort column ascending"> شماره موبایل
                            </th>
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width:162.9px;"
                                aria-label="Status: activate to sort column ascending">کد ملی
                            </th>

                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                        @if($users->count() > 0)
                            @foreach($users as $user)
                                <tr class="odd">

                                    <td>
                                        <a href="#"
                                           class="text-gray-600 text-hover-primary mb-1">  {{$user->webPresent()->fullName}}</a>
                                    </td>
                                    <td>

                                    {!! $user->phone !!}

                                    </td>
                                    <td>

                                        {!! $user->code_mli !!}

                                    </td>
                                    <!--end::Date=-->
                                    <!--begin::Action=-->

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
            {{$users->links('vendor.pagination.bootstrap-4') }}
        </div>
        <!--end::Card body-->
    </div>
@endsection
