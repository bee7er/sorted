
@extends('template')

@section('content')

    <div id="wrapper">
        <div id="page" class="container">
            <form name="bank" method="post" action="/">

                @csrf <!-- {{ csrf_field() }} -->

                <div>
                    <div class="title">Validating your bank sort code / account number combination</div>
                    <p>Enter the details of the sort code and account number and then press submit</p>
                    <div style="margin-top: 15px;">
                        <div class="field">
                            <div class="control">
                                <div class="columns">
                                    <div class="column is-one-fifth">
                                        Sort code:
                                    </div>
                                    <div class="column is-one-fifth">
                                        <input name="sortCode" id="sortCode" class="input is-primary is-small"
                                               type="text" placeholder="000000" value="{{ $sortCode }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <div class="columns">
                                    <div class="column is-one-fifth">
                                        Account number:
                                    </div>
                                    <div class="column is-one-fifth">
                                        <input name="accountNumber" id="accountNumber" class="input is-primary
                                        is-small" type="text" placeholder="00000000" value="{{ $accountNumber }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('fail'))
                            <div class="alert alert-danger">
                                {{ session('fail') }}
                            </div>
                        @endif

                        <div class="buttons">
                            <button class="button is-warning" onclick="this.form.reset(); return false;">Reset</button>
                            <button type="submit" class="button is-info">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection