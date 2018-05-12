@include('layouts.head')

@include('layouts.navbar')

<div class="container">
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
    

    <div class="row">
        @yield('content')
    </div>
</div>


@include('layouts.footer')
