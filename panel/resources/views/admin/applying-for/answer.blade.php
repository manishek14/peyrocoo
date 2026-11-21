@extends('layouts.admin.admin')
@section('title',' مدیریت   جواب درخواست ها  ')
@section('pageTitle',' ثبت جواب درخواست ')
@section('content')
    <div class="">
        <form class="form" action="{{route('admin.create-applying.answer.store',$applyingFor)}}" method="post" enctype="multipart/form-data">
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
                                        @include('admin.__components.label',['title' => '  توضیحات   '])
                                        <div class="mb-5">
                                            @include('admin.__components.textarea', [
                                                    'name' => 'description',
                                                    ])
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-10 fv-row fv-plugins-icon-container">
                                        <input type="file" id="img" name="javab" accept="image/*">
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!--end::Card header-->

                        <div class="card-footer d-flex flex-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-plus"></i>
                                <span class="indicator-label">ثبت  جواب درخواست  </span>
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
@endsection



