<html>
  <head>      
    <title>書籍管理_新增</title>
  <!-- </head>
   -->
  <body>
        
<FORM name ="form1" action="test.php" method="get">
	  書本價格: <INPUT type="text" name="price" size=40 maxlength=40>
   	<INPUT type="submit" value="新增">
	<INPUT type="reset" value="重新輸入">
</FORM>
//test0811-2120 2nd ttime
</body>
</html>
<?php
header("Content-type: text/html;charset=utf8");
@$price = $_GET['price']; 
if(!@$_GET['price'])
 { die("請輸入書本價格");}
elseif (!is_numeric($price))
 { die("請輸入數字"); }
else
  { echo "這是數字".$price; }  
   
?>
