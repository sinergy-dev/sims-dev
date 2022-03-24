<!DOCTYPE html>
<html>
    <head>
        <title>Page Not Found</title>
        <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Mochiy+Pop+One&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic+Coding:wght@700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=ZCOOL+KuaiLe&display=swap" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

        <style type="text/css">
            /*img{
                width: 10%;
                height: 10%;
                
                float: left;
            }

            p{
                font-size: 40px;
                justify-content: center;
            }*/

            body{
                padding-top: 100px;
            }

            .container{
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            img{
                max-width: 20%;
                padding-left: 400px;
            }

            .img-col{
                flex-basis: 50%;
            }

            .text-col{
                display: flex;
                padding-right: 25%;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                text-align: center;
                float: left;
            }

            .text{
                justify-content: center;
                display: flex;
                align-items: center;
                font-size: 20px;
            }

            .content{
                justify-content: center;
                text-align: center;
                align-items: center;
                padding-top: 5%;
            }

            button{
                background:none;
                border:none;
                margin:0;
                padding:0;
                cursor: pointer;
                color: #fbb901;

            }

            .left-arrow{
                border: solid orange;
                border-width: 0 3px 3px 0;
                display: inline-block;
                padding: 3px;
                transform: rotate(135deg);
                -webkit-transform: rotate(135deg);
            }

        </style>

    </head>
    <body>
        <!-- <div class="container">
            <div class="content">
                    <a href="{{ URL::previous() }}"><button class="btn btn-primary-back pull-left"> <span>Back</span></button></a>
                    <p>error bos gada apa apa</p>
            </div>
        </div> -->
        <div class="container">
            <div class="img-col">
                <img src="{{ asset('img/warning.png')}}">
            </div>
            <div class="text-col" style="font-family: 'Lato', sans-serif; font-weight: bold">
                <h1>
                    404 PAGE NOT FOUND
                </h1>
            </div>
        </div>


        <!-- <div class="text">
            <h3>
                <p style="font-family: 'Sawarabi Gothic', sans-serif;">
                    このリクエストされたURLはこのサーバーで見つかりませんでした。 <br>
                    私たちが知っているのはそれだけです。
                </p>
                <p style="font-family: 'ZCOOL KuaiLe', cursive;">
                    在此服务器上未找到此请求的 URL。我们知道的就这些。
                </p>
                <p style="font-family: 'Nanum Gothic Coding', monospace;">
                    이 서버에서 요청한 URL을 찾을 수 없습니다. 그게 우리가 아는 전부 야.
                </p>
                <p style="font-family: 'Lato', sans-serif;">
                    This requested URL was not found on this server. Thats all we know.    
                </p>
                
            </h3>
        </div> -->

        <div class="text" style="font-family: 'Lato', sans-serif;">
            <p>
                
                    このリクエストされたURLはこのサーバーで見つかりませんでした。<br>
                    私たちが知っているのはそれだけです。<br><br>
                
                
                    在此服务器上未找到此请求的 URL。我们知道的就这些。<br><br>
                
                
                    이 서버에서 요청한 URL을 찾을 수 없습니다. 그게 우리가 아는 전부 야.<br><br>
                
                
                    This requested URL was not found on this server. Thats all we know.    
                
                
            </p>
        </div>

        <div class="content">
            
            <i class="left-arrow"></i>&nbsp<a href="{{ URL::previous() }}"><button class="btn"><span><u>Return To Homepage</u></span></button></a>

        </div>
    </body>
</html>