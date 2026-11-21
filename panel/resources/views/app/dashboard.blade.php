@extends('layouts.app.app')

@section('content')



@endsection
@section('scripts')

<script>
    $(document).ready(function () {
        Swal.fire({
            confirmButtonText: 'متوجه شدم',
            title: 'راهنما ! ',
            text: 'کاربر گرامی برای درخواست فیش حقوقی در قسمت سایدبار روی گزینه درخواست فیش حقوقی کلیک کنید برای درخواست های دیگر روی گزینه ثبت درخواست کلیک کنید'
        });
    });
</script>
@endsection
