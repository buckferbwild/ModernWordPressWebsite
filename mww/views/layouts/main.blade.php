<!DOCTYPE html>
<html {{ language_attributes() }}>

<head>
    <meta charset="{{ bloginfo('charset') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! wp_head() !!}
</head>

<body {{ body_class() }}>

<main>
    @yield('content')
</main>

{!! wp_footer() !!}
</body>

</html>
