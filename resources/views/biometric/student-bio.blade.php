<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Bio-data</title>
</head>
<body>

    <style>
        h3,h2,h3,h6{
            margin:0px;
            vertical-align: middle;
        }
        .wrapper{
            width: 200px;
            height: 300px;
            background-color: white;
            border: 1px dotted black
        }
.contaner{
    display: flex;
   justify-content: center;
   padding: 10px; 
}

.profile-image{
    width: 50px;
    height: 50px;
}
.barcode{
    width: 95px;
    height: 80px;
}
.table{
    width: 100%;
}
.table td img{
    vertical-align:top;
    margin: 0;
    padding: 0;
    
}
.table tr{
    margin-top: 0
}
.table td,h6{
    vertical-align: top;
    font-size: 6px;
    
}
h6,h5{
    text-align: center
}
h5{
    font-size: 8px;
    margin-top: -5px
}
p{
    font-size: 6px!important; 
 
}
.address{
font-size: 4px
}
.td-img {
    display: flex;
    justify-content:center;
align-items: center
}
.name{
    font-size: 10px;
    text-transform: uppercase
}
.bank-details p{
    text-transform: uppercase;
    font-weight: bold
}
    </style>

<div class="wrapper">
    @php
    $realPath=public_path('/img/profile/'.$student->users->photo);
    $altPath=public_path('/img/profile/'.'profile.png');
    $imgPath=file_exists($realPath)?$realPath:$altPath;

@endphp 
<div class="container">
    <table class="table ">
                        <tr style="margin-bottom: 0px">
                        <td style="width: 10%"> 
                            <img src="{{ public_path('/img/schools/'.$school->logo) }}" alt="logo"  height="20" onerror="this.style.display='none'">
                        </td>
                             <td  style="width: 80%" >
                <h5 >{{$school->name}}
                <p>{{$school->contact_address}},</p>
                
                           </td>   
                        </tr>
                        <tr >
                            
                            <td colspan="2">
                                <center>
                                    <img src="{{$imgPath}}" alt="" class="profile-image">
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <center>

                                    <p class="name">{{$student->first_name}} {{$student->surname}} ({{$student->gender}})</p>
                               <p>{{$student->levels->level_name}}</p>
                                </center>
                            </td>
                        </tr>
                    </table>
   <table class="table">
    <tr>
        <td colspan="2" class="bank-details">
            <p>FEE ACCOUNT NO: {{$accountNumber?$accountNumber:''}}</p>
            <p>FEE ACCOUNT NAME: {{$student->surname}}</p>
            <p>BANK  NAME: STANDBIC IBTC BANK</p>
            <hr>
        </td>
    </tr>
    <tr>
        <td>
            
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <center>
                <img src="data:image/png;base64,{!!$barcode!!}" alt="" width="80%">
                <p>STUDENT'S  IDENTIFICATION CARD</p>
            </center>
        </td>
    </tr>
   </table>
</div>

        </div>

</body>
</html>