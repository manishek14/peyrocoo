@extends('layouts.app.app')
@section('title',' مدیریت درخواست ها  ')
@section('pageTitle',' فهرست درخواست های ثبت شده من ')
@section('content')

    <div class="card" data-select2-id="select2-data-131-rhmf">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1"> فهرست درخواست های ثبت شده </span>
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
                                aria-label="Customer Name: activate to sort column ascending">تصویر
                            </th>
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="IP Address: activate to sort column ascending"> موضوع
                            </th>
                              <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="IP Address: activate to sort column ascending"> تاریخ ثبت درخواست 
                            </th>
                             <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="IP Address: activate to sort column ascending"> جواب پاسخ 
                            </th>
                            
                            <th class="min-w-125px sorting" tabindex="0" aria-controls="kt_customers_table" rowspan="1"
                                colspan="1" style="width: 162.9px;"
                                aria-label="IP Address: activate to sort column ascending"> مشاهده پاسخ
                            </th>

                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                        @if($data->count() > 0)
                            @foreach($data as $item)
                                <tr class="odd">

                                    <td>
                                                                        <a href="{{asset($item->webPresent()->image)}}">نمایش</a>

                                    </td>
                                    <td>

                                    {!! $item->title !!}

                                    </td>
                                      <td>
                                         {!! $item->webPresent()->date !!}
                                    </td>
                                      <td>

                                         @if(!is_null($item->description))
                                                                          {!!$item->description !!}

                                       @else
                                       <div class='badge badge-light-danger'>
                                            پاسخ داده نشده    
                                        </di>
                                       @endif
                                    </td>
                                    <td>
                                        <a href="{{route('app.applying.answer',$item)}}">نمایش پاسخ</a>
                                    </td>
                                    <!--end::Date=-->
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
            {{$data->links('vendor.pagination.bootstrap-4') }}
        </div>
        <!--end::Card body-->
    </div>
@endsection
