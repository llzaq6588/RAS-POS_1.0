﻿<html><!--result_price.php에서 넘어오는 코드는 총 3가지가 있다.
1. 일반 상품 바코드.
2. 상품 계산 바코드. (code = 100)
3. 재계산 바코드 (code = 100000000000001)
참고로 이 코드도 바코드기계로 찍는데 코드에 관한 데이터는 별첨파일에 있음으로 참고하기 바란다.
-->

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>상품 가격</title>

</head>

<body onload="document.form1.code.focus();">
<p>
<?php


$code = $_POST['code']; //reslut_price.php로부터 바코드 정보를 밭아서 마찬가지로 code라는 변수로 선언한다.


if ($code < 101) {
	/* 
	계산을 완료하였을 경우. 즉 손님이 돈을 지불하고 물건을 사 간 뒤에 입력되는 코드를 말한다. 이때 입력되는 코드는 "100"이다.
	
	이럴 경우 다음과 같은 절차가 진행된다.
	1. table sell의 price의 총 합과 판매 시간과 날짜가 table sold_book에 입력된다.
	2. table sell은 이자리에서 바로 drop된다.
	3. index.html로 바로 되돌아간다.
	*/

	$year = date("Y"); //date와 time이 변수로 선언된다. sold_book.php의 검색시스템을 위해 년도, 월, 시간은 분리되었다.
	$month = date("m");
	$day = date("d");
	$time = date("H:i:s");

	$hostname = "localhost"; //DB 연결
	$username = "raspos";
	$password = "raspospw";
	$dbname = "raspos";
 
	$connect = mysql_connect($hostname, $username, $password) or die("MySQL Server 연결에 실패했습니다");
	mysql_select_db($dbname,$connect);
 
	$sql1="SELECT sum(price) FROM sell"; //price값을 전체 더하는 식이다.
 
	$result=mysql_query($sql1);
	
 
	while($totalprice=mysql_result($result,0,0))
	{ //sell의 총 합은 totalprice라는 변수로 선언된다.

		$sql2="INSERT INTO sold_book (year, month, day, time, totalprice) " .
				"VALUES ('$year', '$month', '$day', '$time', '$totalprice')"; //변수로 선언된 날짜, 시간, totalprice는 sold_book 매상장부 테이블로 전송된다.
 
			while($result=mysql_query($sql2))
			{

				$sql3="DROP TABLE sell"; //그리고 마지막으로 쓸모없어진 장바구니(sell)을 버린다.

				$result=mysql_query($sql3);

				mysql_close($connect);

				echo '<meta http-equiv="refresh" content="0; url=index.html" />'; //모든것이 끝마쳤음으로 가차없이 처음 화면으로 되덜아간다.

			}
	}
}

elseif ($code > 100000000000000) {  //계산이 실패하였을 경우를 말한다. 편의점에 몇몇 POS기는 이럴 경우 점장이 열쇠를 지고 달려오는 등 난리 부르스가 나는데 이 경우는 아니다. 이런 경우는 손님이 물건을 다시 계산하기를 원하는 경우이거나 사용자가 물건을 잘못 찍었을 경우이다.

	$hostname = "localhost";
	$username = "raspos";
	$password = "raspospw";
	$dbname = "raspos";
 
	$connect = mysql_connect($hostname, $username, $password) or die("MySQL Server 연결에 실패했습니다");
	mysql_select_db($dbname,$connect);
 
	$sql="DROP TABLE sell"; //잘못된 장바구니는 가차없이 drop시켜버린다.
 
	$result=mysql_query($sql);

	mysql_close($connect);

	
	echo '<meta http-equiv="refresh" content="0; url=index.html" />'; //마찬가지로 처음부터 시작하기 때문에 index.html로 돌아간다.

}

else { //이 경우는 보통의 평범한 바코드가 들어왔을 경우이다. 이 경우 code 변수를 통해 table "price"에서 상품을 검색하고 상품 이름과 가격을 table "sell"에 입력한다.
	$hostname = "localhost";
	$username = "raspos";
	$password = "raspospw";
	$dbname = "raspos";
 
	$connect = mysql_connect($hostname, $username, $password) or die("MySQL Server 연결에 실패했습니다");
	mysql_select_db($dbname,$connect);
 
	$sql1="SELECT * FROM price WHERE code = '$code'"; //code변수로 상품을 찾는다.
 
	$result=mysql_query($sql1);
 
	while($array=mysql_fetch_array($result))
		{ 
 

		$name = $array[name];
		$price = $array[price];

		$sql2="INSERT INTO sell (name, price) " .
				"VALUES ('$name', '$price')"; //찾아낸 정보를 table "sell"로 전송
 
		$result=mysql_query($sql2);


		mysql_close($connect);

		}


}
?>
</p><!--이후는 result_price.php와 같다. 다만 다른점은 여기에서 입력된 코드는 다시 result_price_2.php로 return된다. 자세한 정보는 result_price.php의 주석을 보라.-->
<table border="1" width="1000" align="center">
    <tr>
        <td width="60%" height="50">
            <p align="center"><b><span style="font-size:26pt;">상품명</span></b></p>
</td>
        <td width="40%" height="50">
            <p align="center"><b><span style="font-size:26pt;">가격</span></b></p>
</td>
    </tr>
    <tr>
        <td width="60%">
            <p align="center"><span style="font-size:36pt;"><b>
			
<?php 

$hostname = "localhost"; 
$username = "raspos";
$password = "raspospw";
$dbname = "raspos";
 
$connect = mysql_connect($hostname, $username, $password) or die("MySQL Server 연결에 실패했습니다");
mysql_select_db($dbname,$connect);
 
$sql="SELECT * FROM sell"; 
 
$result=mysql_query($sql);
 
while($array=mysql_fetch_array($result)){ 
 

echo $array[name] . '<br/>'; 

 

mysql_close($connect);

}
?>
			
			
			
			
			</b></span></p>
</td>
        <td width="40%">
            <p align="center"><span style="font-size:36pt;"><b>
			<?php 

$hostname = "localhost"; 
$username = "raspos";
$password = "raspospw";
$dbname = "raspos";
 
$connect = mysql_connect($hostname, $username, $password) or die("MySQL Server 연결에 실패했습니다");
mysql_select_db($dbname,$connect);
 
$sql="SELECT * FROM sell"; 
 
$result=mysql_query($sql);
 
while($array=mysql_fetch_array($result)){ 
 

echo $array[price] . '<br/>'; 
 

mysql_close($connect);

}
?>
			
			</b></span></p>
</td>
    </tr>
    <tr>
        <td width="60%">
            <p align="center"><b><span style="font-size:26pt;">총합</span></b></p>
</td>
        <td width="40%">
            <p align="center"><span style="font-size:36pt;"><b>
			


<?php

$hostname = "localhost";
$username = "raspos";
$password = "raspospw";
$dbname = "raspos";
 
$connect = mysql_connect($hostname, $username, $password) or die("MySQL Server 연결에 실패했습니다");
mysql_select_db($dbname,$connect);
 
$sql="SELECT sum(price) FROM sell";
 
$result=mysql_query($sql);
 
$totalprice=mysql_result($result,0,0);
 
 
echo $totalprice . ' 원'; 
 

mysql_close($connect);


?>


			</b></span></p>
</td>
    </tr>
</table>


<p>&nbsp;</p>
<table border="1" width="1000" align="center">
    <tr>
        <td width="1235" height="166" bordercolor="white">
            <form name="form1" method="post" action="result_price_2.php">
                <p align="center"><!--바코드가 입력되면 그 정보는 다시 result_price_2.php로 전송된다. -->
<input type="text" name="code" maxlength="15" size="5" style="font-family:돋움; font-weight:bolder; font-size:80;"> &nbsp;&nbsp;&nbsp;<input type="submit" name="post" value="입력" style="font-family:돋움; font-weight:bolder; font-size:60; border-width:2;"></p>
            </form>

</body>

</html><!--builded Web-Application by KAERIUS-->