<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <style type="text/css">
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 0px solid grey;
            padding-top: 14px;
        }

        table, th {
            padding-left: 15px;
        }

        #bg_ket {
            border-radius: 10px;
        }

        #txt_center {
            text-align: center;
        }

        .money:before{
            content:"Rp";
        }

        center > strong::before{
            content: "@";
        }

        .button {
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            background-color: #7868e6;
            border-radius: 4px;
        }

        /*.centered{
            position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -40%);
        }*/
    </style>
</head>
<body style="display:block;width:600px;margin-left:auto;margin-right:auto;color: #000000">
<div style="line-height: 1.5em;">
    <center><img src="{{ asset('image/sims_sangar.png')}}" style="width: 30%; height: 30%"></center>
</div>
<div style="line-height: 1.5em;padding-left: 13px;">
    <div style="font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
        <p style="font-size: 20px">
            <b>Hi {{$receiver->name}}</b>
        </p>
        <p style="font-size: 16px">
            {{$sender}} telah memberikan ide bisnis baru, dengan detail berikut:
        </p>
        <div id="bg_ket" style="background-color: #ececec; padding: 10px">
            <center><b></b></center>
            <table style="text-align: left;margin: 5px; font-size: 16px" class="tableLead">
                <tr>
                    <th class="tb_ket">Ide</th>
                    <th> : </th>
                    <td>{{$detail->ide}}</td>
                </tr>
                <tr>
                    <th>Konsep Bisnis</th>
                    <th> : </th>
                    <td>{{$detail->konsep_bisnis}}</td>
                </tr>
            </table>
        </div>
        <p style="font-size: 16px">
            <b>Untuk melihat lebih detail dari Ide tersebut dapat dilihat dengan mengunjungi halaman dibawah ini</b>
        </p>
        <center><a href="{{url('/idea_hub/detail/'.$detail->id)}}" target="_blank"><button class="button"> Detail Idea </button></a></center>
        <p style="font-size: 16px">
            Mohon periksa kembali jika ada kesalahan atau pertanyaan silahkan hubungi Team Developer (Ext: 384) atau email ke development@sinergy.co.id
        </p>
        <p style="font-size: 16px">
            Best Regard,
        </p><br>
        <p style="font-size: 16px">
            Application Development
        </p>
    </div>
</div>
</body>
<footer style="display:block;width:600px;margin-left:auto;margin-right:auto;">
    <div style="background-color: #7868e6; padding: 20px; color: #ffffff; font-size: 12px; font-family: 'Montserrat','Helvetica Neue',Helvetica,Arial,sans-serif;">
        <p>
        <center>PT. Sinergy Informasi Pratama</center>
        </p>
        <p>
        <center>Jakarta International Tower, Jl. Letjen S. Parman Nomor 1AA, Kemanggisan - Pal Merah, Jakarta 11480 - Indonesia</center>
        </p>
        <p>
        <center><i class="fa fa-phone"></i>021 - 58355599</center>
        </p>
    </div>
</footer>
</html>