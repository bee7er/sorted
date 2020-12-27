
@extends('template')

@section('content')

    <div id="wrapper">
        <div id="page" class="container">
            <div>
                <div class="title">About Get Sorted</div>
                <p>This simple web site is a front end testing tool for an API, which validates a bank sort code
                    and account number combination.</p>
                <p>Validating a sort code and account number is not straightforward.  This is because the very many
                    banks in the UK have introduced their own subtle differences in the way they format their bank
                    account numbers, such as them being from 6 to 10 digits in length.</p>
                <p>The validation relies on a modulus calculation on the account number, however, there are two
                    fundamentally different calculations and then the modulus may be on 10 or 11.  Which calculation,
                    how many calculations and other differences in the way the numbers are prepared and used in the
                    calculations is based on the sort code.</p>
                <p>The sort code identifies a weights record, which comes from a database table provided by
                    Mastercard, and the values are used in the modulus calculation.</p>
                <p>There are many other fiddly adjustments that must be made and the rules have grown into a bit of
                    a monster.  It was great fun implementing the algorithm.</p>
            </div>
        </div>
    </div>

@endsection