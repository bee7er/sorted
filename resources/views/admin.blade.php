@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <form name="bank" method="post" action="/admin">

                    @csrf <!-- {{ csrf_field() }} -->

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

                        <div style="font-weight: bold; margin-bottom: 20px;">
                            1. When notified of changes to the Sort code Weights or Substitutes data is received,
                            the latest data files should be downloaded.  It is normally essential to update all of
                            the data files.
                        </div>

                        <div class="field">
                            <div class="control">
                                <div class="columns">
                                    <div class="column">
                                        Download the latest version of sort code weights table:
                                    </div>
                                    <div class="column">
                                        <input name="weightsTableUrl" id="weightsTableUrl" class="input is-primary
                                        is-small" type="text" value="{{ $weightsTableUrl }}" required>
                                    </div>
                                    <div class="column">
                                        <span xclass="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary" name="downloadWeights" value="1">
                                                {{ __('Download') }}
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <div class="columns">
                                    <div class="column">
                                        Download the latest version of sort code substitutes table:
                                    </div>
                                    <div class="column">
                                        <input name="substitutesTableUrl" id="substitutesTableUrl" class="input is-primary
                                        is-small" type="text" value="{{ $substitutesTableUrl }}" required>
                                    </div>
                                    <div class="column">
                                        <span xclass="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary" name="downloadSubstitutes" value="1">
                                                {{ __('Download') }}
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="font-weight: bold; margin-bottom: 20px;">
                            2. Having obtained the latest version of the data files, use these options to
                            update the database.
                        </div>

                        <div class="field">
                            <div class="control">
                                <div class="columns">
                                    <div class="column">
                                        Refresh Sort Code Weights table
                                    </div>
                                    <div class="column">&nbsp;</div>
                                    <div class="column">
                                    <span xclass="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary" name="refreshWeights" value="1">
                                            {{ __('Refresh Weights Table') }}
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <div class="columns">
                                    <div class="column">
                                        Refresh Sort Code Substitutes table
                                    </div>
                                    <div class="column">&nbsp;</div>
                                    <div class="column">
                                    <span xclass="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary" name="refreshSubstitutes" value="1">
                                            {{ __('Refresh Substitutes Table') }}
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="links">
                            {{--<div><a href="/weights/refresh" onclick="if (confirm('Are you sure you want to refresh ' +--}}
                             {{--'sort code weights data?')) { return true; return false; }">Refresh Sort Code Weights--}}
                                    {{--data</a></div>--}}
                            {{--<div><a href="/substitutes/refresh" onclick="if (confirm('Are you sure you want to refresh ' +--}}
                             {{--'substitute sort code data?')) { return true; return false; }">Refresh Sort Code Substitutes--}}
                                    {{--data</a></div>--}}
                            <hr />
                            <div><a href="/">Go to the front end</a></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
