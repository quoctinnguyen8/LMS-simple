@if (Session::has('success'))
    <x-notify type="success" message="{{ session('success') }}" show="true" />
@endif

@if (Session::has('error'))
    <x-notify type="error" message="{{ session('error') }}" show="true" />
@endif

@if (Session::has('warning'))
    <x-notify type="warning" message="{{ session('warning') }}" show="true" />
@endif

@if ($errors->any())
    <x-notify type="error" message="Vui lòng kiểm tra lại thông tin đã nhập." show="true" />
@endif