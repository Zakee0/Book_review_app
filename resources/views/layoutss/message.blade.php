@if (Session::has('Success'))
<div class="alert alert-success">
    {{ Session::get('Success') }}
</div>
@endif
@if (Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif
