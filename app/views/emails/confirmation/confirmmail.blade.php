<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Completion of registration</h2>

        <div>
            To finish registration, follow this link: <a href="{{ URL::to('user/confirm', $code) }}">{{ URL::to('user/confirm', $code) }}</a>
        </div>
    </body>
</html>
