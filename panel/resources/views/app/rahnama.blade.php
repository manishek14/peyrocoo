@extends('layouts.app.app')

@section('content')

<div id="kt_app_content" class="app-content flex-column-fluid">
                        <!--begin::Content container-->
                        <div id="kt_app_content_container" class="app-container container">

                            
    <div class="card" data-select2-id="select2-data-131-rhmf">
        <div class="card-header border-0 pt-5">
           
            <div class="card-toolbar">


            </div>
        </div>
        <!--begin::Card body-->
        <div class="card-body pt-0">

            <!--begin::Table-->
            <div id="kt_customers_table_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
               <img src="{{asset('1.png')}}" class = "w-100" />
               <img src="{{asset('2.png')}}" class = "w-100" />
                <img src="{{asset('3.png')}}" class = "w-100" />
               <img src="{{asset('4.png')}}" class = "w-100" />
            </div>
            <!--end::Table-->
            
        </div>
        <!--end::Card body-->
    </div>

                        </div>
                        <!--end::Content container-->
                    </div>

@endsection
@section('scripts')

@endsection
