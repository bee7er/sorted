@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    @if(session('fail'))
                        <div class="alert alert-danger">
                            {{ session('fail') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="links">
                        <div><a href="/substitutes/refresh">Refresh Sort Code Substitutes data</a></div>
                        <div><a href="/weights/refresh">Refresh Sort Code Weightings data</a></div>
                        <hr />
                        <div><a href="/">Go to the front end</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
