<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metismenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/toastr/toastr.min.js') }}"></script>

@yield('script')
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script>
    @if (Session::has('success'))
        toastr.options = { 'closeButton': true, 'progressBar': true }
        toastr.success("{{ session('success') }}");
    @endif
    @if (Session::has('error'))
        toastr.options = { 'closeButton': true, 'progressBar': true }
        toastr.error("{{ session('error') }}");
    @endif
</script>
@yield('script-bottom')
