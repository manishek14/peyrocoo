 <script>
    @if(session()->has('store'))
    Swal.fire({
        icon: 'success',
        confirmButtonText :  'ok',
        title: 'success',
        text: '{{ session()->get('store') }}',
    });
    @endif
</script>
<script>
    @if(session()->has('delete'))
    Swal.fire({
        icon: 'success',
        confirmButtonText :  'ok',
        title: 'success ',
        text: '{{ session()->get('delete') }}',
    });
    @endif
</script>
<script>
    @if(session()->has('update'))
    Swal.fire({
        icon: 'success',
        confirmButtonText :  'ok',
        title: ' success',
        text: '{{ session()->get('update') }}',
    });
    @endif
</script>
 <script>
     @if(session()->has('error'))
     Swal.fire({
         icon: 'error',
         confirmButtonText :  'ok',
         title: 'خطا! ',
         text: '{{ session()->get('error') }}',
     });
     @endif
 </script>
 <script>
     @if(session()->has('updatePassword'))
     Swal.fire({
         icon: 'success',
         confirmButtonText :  'ok',
         title: 'عملیات موفق',
         text: '{{ session()->get('updatePassword') }}',
     });
     @endif
 </script>
 <script>
     @if(session()->has('success'))
     Swal.fire({
         icon: 'success',
         confirmButtonText :  'ok',
         title: 'عملیات موفق',
         text: '{{ session()->get('success') }}',
     });
     @endif
 </script>
