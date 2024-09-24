<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404</title>

    <style id="" media="all">
        /* cyrillic-ext */
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: 900;
            font-display: swap;
            src: url(/fonts.gstatic.com/s/montserrat/v25/JTUHjIg1_i6t8kCHKm4532VJOt5-QNFgpCvC73w0aXpsog.woff2) format('woff2');
            unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
        }

        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        body {
            padding: 0;
            margin: 0
        }

        #notfound {
            position: relative;
            height: 100vh
        }

        #notfound .notfound {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%)
        }

        .notfound {
            max-width: 520px;
            width: 100%;
            line-height: 1.4;
            text-align: center
        }

        .notfound .notfound-404 {
            position: relative;
            height: 240px
        }

        .notfound .notfound-404 h1 {
            font-family: montserrat, sans-serif;
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            font-size: 252px;
            font-weight: 900;
            margin: 0;
            color: #262626;
            text-transform: uppercase;
            letter-spacing: -40px;
            margin-left: -20px
        }

        .notfound .notfound-404 h1>span {
            text-shadow: -8px 0 0 #fff
        }

        .notfound .notfound-404 h3 {
            font-family: cabin, sans-serif;
            position: relative;
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            color: #262626;
            margin: 0;
            letter-spacing: 3px;
            padding-left: 6px
        }

        .notfound h2 {
            font-family: cabin, sans-serif;
            font-size: 20px;
            font-weight: 400;
            text-transform: uppercase;
            color: #000;
            margin-top: 0;
            margin-bottom: 25px
        }

        /* Basic button styles */
        .custom-button {
            font-family: cabin, sans-serif;
            font-weight: 600;
            display: inline-block;
            padding: 20px 30px;
            font-size: 24px;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            background-color: #3498db;
            /* Change the background color */
            color: white;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        /* Hover effect - change color on hover */
        .custom-button:hover {
            background-color: #2980b9;
            /* Change the hover background color */
        }

        @media only screen and (max-width: 767px) {
            .notfound .notfound-404 {
                height: 200px
            }

            .notfound .notfound-404 h1 {
                font-size: 200px
            }

            .custom-button {
                padding: 15px 25px;
                font-size: 20px;
            }
        }

        @media only screen and (max-width: 480px) {
            .notfound .notfound-404 {
                height: 162px
            }

            .notfound .notfound-404 h1 {
                font-size: 162px;
                height: 150px;
                line-height: 162px
            }

            .notfound h2 {
                font-size: 16px
            }

            .custom-button {
                padding: 12px 20px;
                font-size: 16px;
            }
        }
    </style>
    <meta name="robots" content="noindex, follow">
</head>

<body>
    <div id="notfound">
        <div class="notfound">
            <div class="notfound-404">
                <h3>Oops! Page not found</h3>
                <h1><span>4</span><span>0</span><span>4</span></h1>
            </div>
            <h2>we are sorry, but the page you requested was not found</h2>
            <a class="custom-button" href="{{ route('dashboard.index') }}">Return to Dashboard</a>
        </div>
    </div>
</body>

</html>
