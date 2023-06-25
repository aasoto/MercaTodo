<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MercaTodo - Export products</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <style>
            h1{
                font-size: 1.5rem; /* 24px */
                line-height: 2rem; /* 32px */
                font-weight: 700;
            }
            .card{
                background-color: rgb(203 213 225);
                max-width: 80rem; /* 1280px */
                margin-left: 1.75rem; /* 28px */
                margin-right: 1.75rem; /* 28px */
                padding-left: 2rem; /* 32px */
                padding-right: 2rem; /* 32px */
                padding-top: 2rem; /* 32px */
                padding-bottom: 2rem; /* 32px */
            }
            .card-inner{
                background-color: rgb(255 255 255);
                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                padding-left: 2.5rem; /* 40px */
                padding-right: 2.5rem; /* 40px */
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="card">
            <div class="card-inner">
                <h1>Export products file</h1>
                <p>Please be carefull with this information, all the data included in this file is confidential</p>
            </div>
        </div>
    </body>
</html>
