<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Completion of registration</h2>

        <div>
            To finish registration, follow this link: {{ URL::to('user/confirm', array($data)) }}.
        </div>
    </body>
</html>
