<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Daniel Steiner">
    <title>Apple / Google Wallet Pass Generator für den Grünen Pass</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-fork-ribbon-css/0.2.3/gh-fork-ribbon.min.css" />
</head>
<body class="text-center">
<a class="github-fork-ribbon" href="https://github.com/danielsteiner/grueneswallet" data-ribbon="Fork me on GitHub" title="Fork me on GitHub">Fork me on GitHub</a>
    <form class="form-signin" method="post" action="/process_certificate.php" enctype="multipart/form-data">
        <h1 class="h3 mb-3 font-weight-normal">Wallet Pass generator für den Grünen Pass</h1>
        <p>Mit diesem Tool können Sie sich selbst ein ".pkpass" file generieren, welches in Apple Wallet importiert werden kann.</p>
        <p>Zum derzeitigen Stand kann das Tool folgende Zertifikate umwandeln:
            <ul>
                <li>Impfzertifikate</li>
                <li>Testzertifikate ausgestellt von: 
                    <ul>
                        <li>MA15 - Gesundheitsservice der Stadt Wien</li>
                        <li>Lifebrain</li>
                        <li>Rotes Kreuz LifeBrain Labor GmbH</li>
                        <li>Niederösterreich Testet</li>
                    </ul>
                </li>
            </ul>
            Dazu müssen sie nur das PDF auswählen und auf "Passfile generieren" klicken.
        </p>
        <label for="certificate">Test-, Impf- oder Genesungszertifikat</label>
        <input type="file" id="certificate" name="certificate" class="form-control" required autofocus>
        <button class="btn btn-lg btn-niu btn-block" type="submit">Passfile generieren</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2021, Daniel Steiner - <a href="/disclaimer.html">Disclaimer</a></p>
    </form>
</body>
</html>