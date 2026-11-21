@extends('layouts.app.auth')

@section('content')
    <div class="w-lg-500px p-10 p-lg-15 mx-auto" dir="rtl">
        <!--begin::Form-->
        <form action="{{route('auth.app.register')}}" class="form w-100" method="post">
        @csrf
        <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">  ایجاد حساب پرسنل</h1>
                <!--end::Title-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark text-right">نام خودرا وارد نمایید  </label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="text" name="name"
                       autocomplete="off" placeholder=" نام "/>
                @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!--end::Input-->
            </div>
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark text-right"> نام خوانوادگی خودرا وارد نمایید </label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="text" name="family"
                       autocomplete="off" placeholder="نام خانوادگی   "/>
                @error('family')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <!--end::Input-->
            </div>
            <div class="fv-row mb-10">
                 <label class="form-label fs-6 fw-bolder text-dark text-right">شماره موبایل (انگلیسی وارد کنید) </label>
                <!--end::Label-->
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="text" name="phone"
                       autocomplete="off" placeholder="شماره موبایل "/>
                @error('phone')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <!--end::Input-->
            </div>
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark text-right">کد ملی خودرا وارد نمایید  </label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="text" name="code_mli"
                       autocomplete="off" placeholder="کد ملی  "/>
                @error('code_mli')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <!--end::Input-->
            </div>


            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                  
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">رمز عبور (انگلیسی وارد کنید)</label>

                </div>
                <!--end::Wrapper-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="password" name="password"
                       autocomplete="off" placeholder="رمز عبور"/>
                @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">ایجاد حساب کاربری</span>
                </button>
                <a href="{{route('auth.app.login.form')}}" class="text-danger">حساب کاربری دارید وارد حساب کاربری خود شوید ! </a>
                <!--end::Submit button-->

            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>

@endsection
