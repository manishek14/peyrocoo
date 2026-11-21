@extends('layouts.app.auth')

@section('content')
    <div class="w-lg-500px p-10 p-lg-15 mx-auto" dir="rtl">
        <!--begin::Form-->
        <form action="{{route('auth.app.login')}}" class="form w-100" method="post">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">ورود به پنل پرسنل</h1>
                <!--end::Title-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark text-right">شماره موبایل (انگلیسی وارد کنید) </label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="text" name="phone"
                       autocomplete="off" placeholder="شماره موبایل "/>
                @error('phone')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">رمز عبور (انگلیسی وارد کنید)</label>


                </div>
                <!--end::Wrapper-->
                <!--begin::Input-->
                <input id="password-field" type="password" class="form-control" name="password">
              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" style="    float: left;
    margin-top: -25px;
    margin-left: 12px;"></span>
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
                    <span class="indicator-label">ورود</span>
                </button>
                <a href="{{route('auth.app.register.form')}}" class="text-danger">اگر حساب کاربری ندارید ایجاد کنید </a>
                <!--end::Submit button-->

            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>

@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>
    <script>
        $(document).ready(function () {
            Swal.fire({
                confirmButtonText: 'متوجه شدم',
                title: 'راهنما ! ',
                text: 'کاربر گرامی برای وارد شدن به پنل ابتدا وارد حساب کاربری خودشوید اگر حساب کاربری ندارید از لینک زیر برای ایجاد حساب کاربری استفاده کنید '
            });
        });
    </script>
@endsection


