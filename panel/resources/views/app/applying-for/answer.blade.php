@extends('layouts.app.app')
@section('title',' جواب  درخواست من  ')
@section('pageTitle',' جواب  درخواست من ')
@section('content')

    <div class="card" data-select2-id="select2-data-131-rhmf">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1"> جواب  درخواست من </span>
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
                                aria-label="IP Address: activate to sort column ascending"> توضیحات
                            </th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="fw-bold text-gray-600">
                                <tr class="odd">

                                    <td>
                                        <a href="{{asset($data->webPresent()->javab)}}">نمایش</a>
                                    </td>
                                    <td>

                                    {!! $data->description !!}

                                    </td>
                                    <!--end::Date=-->
                                </tr>
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
        </div>
        <!--end::Card body-->
    </div>
@endsection
