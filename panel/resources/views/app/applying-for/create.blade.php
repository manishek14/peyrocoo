@extends('layouts.app.app')
@section('title',' مدیریت درخواست ها  ')
@section('pageTitle',' ثبت  درخواست جدید')
@section('content')
    <div class="">
        <form class="form" action="{{route('app.create-applying.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!--begin::Main column-->
                <div class="col-md-12">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 mt-2">
                            <!--begin::Input group-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-10 fv-row fv-plugins-icon-container">
                                        @include('admin.__components.label',['title' => '  عنوان درخواست  '])
                                        <div class="mb-5">
                                            @include('admin.__components.input-text', [
                                                    'name' => 'title',
                                                    ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-10 fv-row fv-plugins-icon-container">
                                        <input type="file" id="img" name="file" accept="image/*">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!--end::Card header-->

                        <div class="card-footer d-flex flex-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                <span class="indicator-label">ثبت  درخواست </span>
                            </button>
                        </div>
                    </div>
                    <!--end::General options-->
                </div>
                <!--end::Main column-->
                <!--begin::Aside column-->
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    @include('admin.message.alert')
    <script>
        $(document).ready(function () {
            Swal.fire({
                confirmButtonText: 'متوجه شدم',
                title: 'راهنما ! ',
                text: 'کاربر گرامی برای درخواست  ابتدا موضوع خودرا وارد نمایید سپس عکس درخواست خودرا اپلود  سپس دکمه ثبت در خواست را ثبت نمایید'
            });
        });
    </script>
@endsection



