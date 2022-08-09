@if ($message = Session::get('success'))

<script>
    iziToast.success({
        title: '{{ $message }}',
        position: "topRight",
   });
   
</script>

@endif

@if ($message = Session::get('error'))

<script>
    iziToast.error({
        title: '{{ $message }}',
        position: "topRight",
    });   
</script>

@endif

@if($errors->any())
<script>
    @foreach ($errors->all() as $error)
    iziToast.error({
        title: '{{ $error }}',
        position: "topRight",
    });
    @endforeach        
</script>
@endif
