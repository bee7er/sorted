<!DOCTYPE html>
<!--
Design     : Number 9 Software
Name       : Get sorted
Description: Validate your bank sort code and accont numbers before you try to use them
Version    : 1.0
Released   : 20200501
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
    <link href="/css/default.css" rel="stylesheet" />
    <link href="/css/fonts.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://bulma.io/css/bulma-docs.min.css?v=202004111236">
    <link href={{asset('css/app.css')}} rel="stylesheet" />

    @yield('custom-head-section')

</head>
<body>
    <div id="header-wrapper">
        <div id="header" class="container">
            <div id="logo">
                <h1><a href="/">Get Sorted</a></h1>
            </div>
            <div id="menu">
                <ul>
                    <li class="{{ Request::is('/') ? 'current_page_item' : ''}}"><a href="/" accesskey="2"
                                                                                    title="">Home</a></li>
                    <li class="{{ Request::is('about*') ? 'current_page_item' : ''}}"><a href="/about/" accesskey="3"
                                                                                         title="">About</a></li>
                    <li class="{{ Request::is('admin*') ? 'current_page_item' : ''}}"><a href="/admin" accesskey="4"
                                                                                         title="">Admin</a></li>
                </ul>
            </div>
        </div>
    </div>

    @yield('content')

    <div id="copyright" class="container">
        <p>&copy; GetSorted</p>
    </div>

    <script type="application/javascript" src="/js/app.js?t=1"></script>
</body>
</html>
