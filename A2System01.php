<?
$PgID = "A0201";
$Local="System02";
 include "../connect_db.php";
$LocalFile="A2System01";
//test only

CheckLog($_SESSION["Manager"],$PgID);

	if ((@$_POST["AddBtn"])&&($IsAdd)){

		for ($index=1;$index<=12;$index++){
			$MList.=",M".(strlen($index)==1?"0".$index:$index);
			$MValue .=",'".($_POST["ChkMonth".$index]==0?0:1)."'";
		}

		for ($ink=1;$ink<=2;$ink++){
			$KList.=",k".(strlen($ink)==1?"0".$ink:$ink);
			$KValue .=",'".($_POST["k".$ink]==0?0:1)."'";
		}

		//$sql = "insert into ASubject (Subject,Remark,SValue,IsCount,IsHealth2,IsCount2,amount,k1,k2,k3,k4,k5,IsCount2Day ".$MList.") values
		//('".$_POST["Subject"]."','".$_POST["Remark"]."','".$_POST["SValue"]."','".$_POST["IsCount"]."','".$_POST["IsHealth2"]."','".$_POST["IsCount2"]."','".$_POST["amount"]."','".$_POST["k1"]."','".$_POST["k2"]."','".$_POST["k3"]."','".$_POST["k4"]."','".$_POST["k5"]."','".$_POST["IsCount2Day"]."' ".$MValue.")";

		//$sql = "insert into ASubject (Subject,Remark,SValue,IsHealth2,amount ".KList.$MList.") values
		//('".$_POST["Subject"]."','".$_POST["Remark"]."','".$_POST["SValue"]."','".$_POST["IsHealth2"]."','".$_POST["amount"]."','".$_POST["k1"]."','".$_POST["k2"]."','".$_POST["k3"]."','".$_POST["k4"]."','".$_POST["k5"]."' ".$MValue.")";

		$sql = "insert into ASubject (Subject,Remark,SValue,IsHealth2,amount ".$KList.$MList.") values
		('".$_POST["Subject"]."','".$_POST["Remark"]."','".$_POST["SValue"]."','".$_POST["IsHealth2"]."','".$_POST["amount"]."' ".$KValue.$MValue.")";

		$rs = mysqli_query($mysql_link,$sql);
		if ($rs){
			echo "<script>location.href='".$LocalFile.".php';</script>";
		}else{
			echo "<script>alert('".$KList.$MList."');history.back(-1);</script>";
		}
		exit();
	}

	if (($_POST["SnoBtn"])&&($IsMod)){
		for ($index=0;$index<count($_POST["Seq"]);$index++){
			$sql="update ASubject set Sno='".$_POST["Sno"][$index]."' where Seq='".$_POST["Seq"][$index]."'";
			mysqli_query($mysql_link,$sql);
		}
		echo "<script>location.href='".$LocalFile.".php?SValue=".$_POST["SValue"]."&Keyword=".$_POST["Keyword"]."';</script>";
		exit();
	}


	if (($_POST["Subject"]=="勞保費")||($_POST["Subject"]=="健保費")){
			$IsMod=0;$IsDel=0;
	}
	if ((@$_POST["ModBtn"])&&($IsMod)){

		for ($index=1;$index<=12;$index++){
			$MValue .=",M".(strlen($index)==1?"0".$index:$index)."='".($_POST["ChkMonth".$index]==0?0:1)."'";
		}





		$sql = "Update ASubject set Subject='".$_POST["Subject"]."',Remark='".$_POST["Remark"]."',SValue='".$_POST["SValue"]."',IsCount='".$_POST["IsCount"]."',IsHealth2='".$_POST["IsHealth2"]."',IsCount2='".$_POST["IsCount2"]."',IsCount2Day='".$_POST["IsCount2Day"]."' ".$MValue.", amount=22000, k1=1 where Seq='".$_POST["Seq"]."'";
		$rs = mysqli_query($mysql_link,$sql);
		if ($rs){
			echo "<script>location.href='".$LocalFile.".php?page=".$_POST["page"]."&Keyword=".urlencode($_POST["Keyword"])."';</script>";
		}else{
			echo "<script>alert('Error');history.back(-1);</script>";
		}
		exit();
	}
	//刪除
	if ((@$_POST["DelBtn"])&&($IsDel)){
		mysqli_query($mysql_link,"delete from ASubject where Seq='".$_POST["Seq"]."'");
		echo "<script>location.href='".$LocalFile.".php';</script>";
	exit();
	}



	$Archive = "../ImportFile/";

	if ((@$_POST["SendBtn"]=="確定匯入")){

	include "../Excel/reader.php";
				$FileName = $_POST["FileName"];
				if (is_file($Archive.$FileName)){
						//建立讀取物件
					$data = new Spreadsheet_Excel_Reader();
					//設定呈現資料編碼為中文
					$data->setOutputEncoding('UTF-8');
					//設定使用的編碼函式為何
					$data->setUTFEncoder('iconv');
					//利用函式庫中的讀取函式將excel讀進去data
					$data->read($Archive.$FileName);
					//顯現錯誤報告
					error_reporting(E_ALL ^ E_NOTICE);
					//讀取文件中的sheet數目
					$LastPage = count($data->sheets);
					//檢查現在讀取excel的sheet是否為最後一個sheet
					if($Page>$LastPage){
					exit();
					}
					//設定要讀取excel的sheet Page
					if ( $Page < 1 ) $Page = 1;
					if ( $Page > $LastPage && $LastPage>=1) $Page = $LastPage;
					$s_Page=intval($Page)-1;


					//全清除



					//利用回圈讀取excel檔案中某一sheet中的每一格資料

					for($i = 2; $i <= $data->sheets[$s_Page]['numRows']; $i++)//讀取excel直行的迴圈
					{
								$ClassName="";$WeakName="";$WeakLevel="";$LevelName="";$ThreatName="";
						if ($data->sheets[$s_Page]['cells'][$i][1]){
							for($j = 1; $j <=21; $j++)//讀取excel橫列的迴圈
							{


								//Subject ,SValue ,IsCount ,IsHealth2,IsCount2 ,IsCount2Day ,Sno ,M01 ,M02 ,M03 ,M04 ,M05 ,M06 ,M07 ,M08 ,M09 ,M10 ,M11 ,M12 ,Remark

								if ($j==1){$Subject=  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==2){$SValue =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								//if ($j==3){$IsCount =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==3){$IsHealth2 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								//if ($j==5){$IsCount2 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								//if ($j==6){$IsCount2Day =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==4){$Sno =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==5){$M01 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==6){$M02 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==7){$M03 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==8){$M04 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==9){$M05 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==10){$M06 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==11){$M07 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==12){$M08 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==13){$M09 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==14){$M10 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==15){$M11 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==16){$M12 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==17){$amount =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==18){$k01 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==19){$k02 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
								if ($j==20){$k03 =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}
							    if ($j==21){$Remark =  trim($data->sheets[$s_Page]['cells'][$i][$j]);}

							}


								//新增設定
								$rs=mysqli_fetch_row(mysqli_query($mysql_link,"select * from ASubject where Subject='".$Subject."'  "));
								if ($rs){
									$k = $k +1;
									$ShowMes .= ($i-1)."筆 <新增失敗，已存在： ".$Subject." ><br>";
								}else{
									$sqlss = "insert into ASubject (Subject ,SValue ,IsHealth2 ,Sno ,M01 ,M02 ,M03 ,M04 ,M05 ,M06 ,M07 ,M08 ,M09 ,M10 ,M11 ,M12 ,amount, k01, k02, k03, Remark) values(
									'".$Subject."','".$SValue."','".$IsHealth2."','".$Sno."',
									'".$M01."','".$M02."','".$M03."','".$M04."','".$M05."','".$M06."',
									'".$M07."','".$M08."','".$M09."','".$M10."','".$M11."','".$M12."','".$amount."','".$k01."','".$k02."','".$k03."','".$Remark."'
									)";
									$add = mysqli_query($mysql_link,$sqlss);
									if ($add){
										$k = $k +1;$ShowMes .= ($i-1)."筆 <新增成功><br>";
									}else{
											echo $sqlss;exit();
											$ShowMes .= ($i-1)."筆 <新增失敗><br>".$sqlss;
									}
								}


						}
					}

					}

	}
	if (($_GET["delfile"])){
		if (is_file($Archive.$_GET["delfile"])){
			unlink($Archive.$_GET["delfile"]);
		}

		echo "<script>location.href='".$LocalFile.".php?Ftype=Import';</script>";
		exit();
    }




?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?=$WebTitle?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">


    <!-- CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet">
   <link href="../style.css" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="../assets/ico/favicon.png">
  </head>

  <body>


    <!-- Part 1: Wrap all page content here -->
    <div id="wrap">
      <!-- Fixed navbar -->
      <? include "../Top.php";?>

<!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h3><?=$PgName?></h3>
        </div>
         <? switch ($_GET["Ftype"]){
	 case "Import";
	 ?>
<ol><li><b>屬性</b>
<p>0:加項、1:減項
<!-- <li><b>科目計算</b>
<p>0:★是 (該科目因請假影響，須扣除當日薪資；例：事假扣1日薪資、病假扣0.5日薪資)
<p>1:★是 (該科目因請假影響，須扣除1日的薪資或津貼；例：伙食津貼)
<p>2:★否 (該科目不受請假影響) -->
<li><b>二代健保屬性</b>
<p>0:否 、1:四倍扣除額、2:兼職薪資所得、3:執行業務收入
<li><b>金額</b>
<p>整數
<li><b>是否複製到下個月</b>
<p>1:是、0:否
<li><b>是否計稅</b>
<p>1:是、0:否
<li><b>是否實報實銷</b>
<p>1:是、0:否
</ol><hr />
<form action="<?=$LocalFile?>.php?Ftype=Import" method="post" enctype="multipart/form-data">
<table width="100%" border="0" align="center">
<tr>
<td style="font-size:12px">
    <? if (@$_POST["SendBtn"]=="") {?>
  上傳文件：
  <input name="UploadFile" type="file">
  <input type="submit" name="SendBtn" id="SendBtn" value="確定送出">
  <a href="DataSample2.xls" target="_blank">預設匯入格式 </a><hr />
<?
if (is_dir($Archive)==true){
$dir=opendir($Archive);
echo "<ul >";
while ($file=readdir($dir)) {
if (is_file($Archive.$file)){
	echo "<li style=\"line-height:30px\">暫存檔".$file." <a href=\"".$LocalFile.".php?delfile=".$file."\">刪除</a> <a href=\"".$LocalFile.".php?Ftype=Import&file=".$file."\">預覽</a>";
}}
echo "</ul>";
}
?>
  <? }?>

  </td></tr></table>
<?
  if ($ShowMes){ echo $k."筆成功<br>".$ShowMes;}
if ((@$_POST["SendBtn"]=="確定送出")||($_GET["file"])) {
	if (@$_POST["SendBtn"]=="確定送出"){
		$fileTyp = explode(".",strtolower($_FILES['UploadFile']['name']));
		$FileType = $fileTyp[count($fileTyp)-1];
		$FileName = date("YmdHis").".".$FileType;
		$rs = copy($_FILES['UploadFile']['tmp_name'],$Archive.$FileName);
	}else if ($_GET["file"]){
		$FileName = $_GET["file"];
	}
	if ($rs==true){
			if (is_file($Archive.$FileName)){
	include "../Excel/reader.php";

				//建立讀取物件
					$data = new Spreadsheet_Excel_Reader();
					//設定呈現資料編碼為中文
					$data->setOutputEncoding('UTF-8');
					//設定使用的編碼函式為何
					$data->setUTFEncoder('iconv');
					//利用函式庫中的讀取函式將excel讀進去data
					$data->read($Archive.$FileName);
					//顯現錯誤報告
					error_reporting(E_ALL ^ E_NOTICE);
					//讀取文件中的sheet數目
					$LastPage = count($data->sheets);
					//檢查現在讀取excel的sheet是否為最後一個sheet
					if($Page>$LastPage){
					exit();
					}
					//設定要讀取excel的sheet Page
					if ( $Page < 1 ) $Page = 1;
					if ( $Page > $LastPage && $LastPage>=1) $Page = $LastPage;
					$s_Page=intval($Page)-1;



					//利用回圈讀取excel檔案中某一sheet中的每一格資料
					echo "匯入資料如下：
					<input type=\"hidden\" name=\"FileName\" value=\"".$FileName."\">
					<input name=\"SendBtn\" type=\"submit\" value=\"確定匯入\"> <input type=\"button\" name=\"SendBtn\" id=\"SendBtn\" onClick=\"location.href='".$LocalFile.".php';\" value=\"取消\">";

					echo "<hr /><table border=1 style=\"font-size:12px;\">";

					echo "<tr><td>&nbsp;</td>
					<td>科目名稱</td><td>屬性</td><td>二代健保屬性</td>
					<td>排序</td>
					<td>1月份</td><td>2月份</td><td>3月份</td><td>4月份</td><td>5月份</td><td>6月份</td>
					<td>7月份</td><td>8月份</td><td>9月份</td><td>10月份</td><td>11月份</td><td>12月份</td>
					<td>金額</td><td>是否複製到下個月</td><td>是否加總</td><td>是否免稅</td><td>不包含於月薪內</td>
					<td>備註</td>
					</tr>";

					for($i = 2; $i <= $data->sheets[$s_Page]['numRows']; $i++)//讀取excel直行的迴圈
					{


						if ($data->sheets[$s_Page]['cells'][$i][1]){
							echo "<tr id=\"tr_".$i."\"><td>".($i-1)."</td>";

							for($j = 1; $j <=21; $j++)//讀取excel橫列的迴圈
							{
								//呈現讀取資料
								echo "<td>".trim($data->sheets[$s_Page]['cells'][$i][$j])."</td>\r\n";
							}

							echo "</tr>";
						}
					}
					echo "</table>";
					}


			}
	}
?></form>
	 <?
	 break;
	 case "";
	 ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>

<? if ($IsAdd){?> <a href="<?=$LocalFile?>.php?Ftype=AddNew" class="btn btn-xs  btn-warning">新增</a><? }?>
<? if ($IsAdd){?> <a href="<?=$LocalFile?>.php?Ftype=Import" class="btn btn-xs  btn-warning">科目匯入</a><? }?>
<a id="exp" class="btn btn-xs  btn-warning">匯出</a>
<a href="import.xls" download="薪資科目.xls"  class="btn btn-xs btn-success">下載匯入檔</a>
</td>
<form name="form1" method="get" action="" class="form-search"><td align="right">
<input type="button" name="btn" value="加項" onClick="location.href='<?=$LocalFile?>.php?SValue=0&Keyword='+encodeURI(document.getElementById('Keyword').value);">
<input type="button" name="btn" value="減項" onClick="location.href='<?=$LocalFile?>.php?SValue=1&Keyword='+encodeURI(document.getElementById('Keyword').value);">

<input type="text" size="45" name="Keyword" id="Keyword" placeholder="輸入all = 顯示全部資料" value="<?=$_GET["Keyword"]?>">
<input name="button2" type="submit" class="btn-primary" id="button2" value="查詢">
</td></form>
</tr>
</table>
<form action="" name="SnoForm" id="SnoForm" method="post">
<input type="hidden" name="SnoBtn" id="SnoBtn" value="<?=$_GET["SValue"]?>">
<input type="hidden" name="Keyword" id="Keyword" value="<?=$_GET["Keyword"]?>">
<table id="main_table" class="table table-bordered mtable">
			<thead>
					<tr>
						<th>..</th>
					    <th>排序</th>
						<th>科目名稱</th>
						<th>屬性</th>
						<th>月份</th>
						<th>二代健保屬性</th>
						<th>金額</th>
						<th>是否加總</th>
						<th>是否免稅</th>
						<th>不包含於月薪內</th>
						<th>顯示於</th>
 						<th>備註</th>
                       <th class="mcheck">刪除</th>
					</tr>
				</thead>
				<tbody>
					<?
	   $where =" where 1=1 ";


	 if ($_GET["Keyword"]){
	   	if ($_GET["Keyword"] !== 'all'){
   		    if ($_GET["SValue"]){
   			   $where .=" and (SValue='1' )  ";
   			}else{
   			   $where .=" and (SValue='0' )  ";
   			}
		   $where .=" and (Subject like '%".$_GET["Keyword"]."%' )  ";
		}
	} else {
		    if ($_GET["SValue"]){
			   $where .=" and (SValue='1' )  ";
			}else{
			   $where .=" and (SValue='0' )  ";
			}
	}

	   $sql="select count(*) from ASubject ".$where.$order;
		$rs = mysqli_fetch_row(mysqli_query($mysql_link,$sql));
		if ($rs){$TotalNum = $rs[0];}
			$Num = 20; //每頁10筆
			if (@$_GET["page"]==NULL){
				$page=1;
				$StartP=0;
				$EndP = $Num;
			}else{
				$page=@$_GET["page"];
				$StartP = ($page-1)*($Num);
				$EndP =$Num;
			}
		$sql="select Seq,Subject,Remark,SValue,IsCount,IsHealth2,IsCount2,Sno,M01,M02,M03,M04,M05,M06,M07,M08,M09,M10,M11,M12,amount,k01,k02,k03,location from ASubject ".$where.$order."   order by Sno,M01  desc,M02  desc,M03 desc,M04  desc,M05  desc,M06 desc,M07 desc,M08 desc,M09 desc,M10 desc,M11 desc,M12 desc limit ".$StartP.",".$EndP."";

		$show_page = '';

		if ($_GET["Keyword"] == 'all'){
			$sql="select Seq,Subject,Remark,SValue,IsCount,IsHealth2,IsCount2,Sno,M01,M02,M03,M04,M05,M06,M07,M08,M09,M10,M11,M12,amount,k01,k02,k03,location  from ASubject ".$where.$order."   order by Sno,M01  desc,M02  desc,M03 desc,M04  desc,M05  desc,M06 desc,M07 desc,M08 desc,M09 desc,M10 desc,M11 desc,M12 desc";

			$show_page = 'style="display:none"';
		}

		 $result =mysqli_query($mysql_link,$sql);
		 $total = mysqli_num_rows($result);
		if ($total){
			for ($index=0;$index<$total;$index++){
				$arr[$index] = mysqli_fetch_row($result);
				}

			for ($index=0;$index<$total;$index++){
				$Seq = $arr[$index][0];
				$Subject = $arr[$index][1];
				$Remark = $arr[$index][2];
				$SValue = $arr[$index][3];
				$IsCount = $arr[$index][4];
				$IsHealth2 = $arr[$index][5];
				$IsCount2 = $arr[$index][6];
				$Sno = $arr[$index][7];
				$amount = $arr[$index][20];
				$k01 = $arr[$index][21];
				$k02 = $arr[$index][22];
				$k03 = $arr[$index][23];
				$location = $arr[$index][24];

				for ($indexs=0;$indexs<12;$indexs++){
					$M[$indexs] = $arr[$index][$indexs+8];
				}

					?>

                    <tr class="drow" data-pid="<?=$Seq?>"  onMouseOver="this.style.backgroundColor='#E5E5E5';this.style.cursor='pointer';" onMouseOut="this.style.backgroundColor='';">

                        <? if ($IsMod){?>
						<td>
							 <input class="chkbox" data-pid="<?=$Seq?>" type="checkbox">
						</td>
                        	<td>
                       
                        	<input type="number" class="value_edit Sno" value="<?=$Sno?>" style="width:30px;text-align:center;display:inline">
                        <span class="tlabel"><?=$Sno?></span>
                        <input type="hidden" class="Seq" name="Seq" value="<?=$Seq?>">
                        </td><? }?>
                        <td>
                        	<input type="text" name="Subject" class="subject value_edit" value="<?=$Subject?>" style="width:100px;">
                        	<span class="tlabel"><?=$Subject?></span>
                        </td>

						<td class="align-center"><?=($SValue==0?" ( + ) ":" ( - ) ")?></td>
						<td>



							<? $month = " ";
							    	for ($indexs=1;$indexs<=12;$indexs++){
							    		if ($M[$indexs-1]==1) {
							    			$month .= $indexs."月,";
							    		}

							    		?>
							    		<span class="mcheck">
							    			<input type="checkbox" class="vbox mbox<?=$indexs?>" name="ChkMonth<?=$indexs?>”  id="ChkMonth<?=$indexs?>” <?=($M[$indexs-1]==1?"checked":"")?> value="1">
							    					<?=$indexs?>
							    		月 </span>

							    	<? } ?>

							    	<span class="tlabel"><?=$month?></span>




							</td>
						<td>
							<select name="IsHealth2" class="hselect mcheck">
							  <option value="0" <? if ($IsHealth2 === '0') echo 'selected' ?>>否</option>
							  <option value="1" <? if ($IsHealth2 === '1') echo 'selected' ?>>四倍扣除額</option>
							  <option value="2" <? if ($IsHealth2 === '2') echo 'selected' ?>>兼職薪資所得</option>
							  <option value="3" <? if ($IsHealth2 === '3') echo 'selected' ?>>執行業務收入</option>
							</select>
							<?
								if ($IsHealth2 === '0') $tx = '否';
								if ($IsHealth2 === '1') $tx = '四倍扣除額';
								if ($IsHealth2 === '2') $tx = '兼職薪資所得';
								if ($IsHealth2 === '3') $tx = '執行業務收入';
							?>

							<span class="tlabel"><?=$tx?></span>
                        </td>
                        <td>
                        	<input type="number" class="form-control amount value_edit" value="<?=$amount?>">
                        	<span class="tlabel"><?=$amount?></span>
                        </td>
                        <td class="align-center">
                        	<input class="form-check-input vbox k01" type="checkbox" <? if ($k01 === '1') echo 'checked' ?>>
                        	<span class="tlabel"><?=$k01?></span>
                        </td>
                        <td class="align-center">
                        	<input class="form-check-input vbox k02" type="checkbox" <? if ($k02 === '1') echo 'checked' ?>>
                        	<span class="tlabel"><?=$k02?></span>
                        </td>
                        <td class="align-center">
                        	<input class="form-check-input vbox k03" type="checkbox" <? if ($k03 === '1') echo 'checked' ?>>
                        	<span class="tlabel"><?=$k03?></span>
                        </td>

						<td>
							<select name="location" class="location mcheck">
							  <option value="3" <? if ($location === '3') echo 'selected' ?>>全部顯視</option>
							  <option value="0" <? if ($location === '0') echo 'selected' ?>>月薪</option>
							  <option value="1" <? if ($location === '1') echo 'selected' ?>>日薪</option>
							  <option value="2" <? if ($location === '2') echo 'selected' ?>>時薪</option>
							  <option value="4" <? if ($location === '4') echo 'selected' ?>>不顯示</option>
							</select>
							<?
								if ($location === '3') $tx = '全部顯視';
								if ($location === '0') $tx = '月薪';
								if ($location === '1') $tx = '日薪';
								if ($location === '2') $tx = '時薪';
							?>

							<span class="tlabel"><?=$tx?></span>
                        </td>


						<td>
							<input type="text" class="value_edit Remark" value="<?=$Remark?>" style="width:100px">
							<span class="tlabel"><?=$Remark?></span>
						</td>

						<td class="mcheck">
							<a class="btn btn-danger btn-xs del">刪除</a>
						</td>

					</tr>

                    <?
			}}
		?>
				</tbody>
		</table>

		<a id="selectall" class="btn btn-xs  btn-warning">全選</a>
		<a id="deleteall" class="btn btn-xs  btn-danger">刪除</a>
</form>

			<?
  if (($TotalNum)&&(ceil($TotalNum/$Num)>1)){
	  ?>  <div <?=$show_page?> class="pagination"> <ul><?
				$pagecount = (ceil($TotalNum/$Num));
				page_count($LocalFile.".php?orderby=".$_GET["orderby"]."&SValue=".$_GET["SValue"]."&Keyword=".urlencode($_GET["Keyword"])."&page=",$pagecount,$page);
	?> </ul></div><?		} ?>
    <?
	break;
	case "Detail";
$sql="select Seq,Subject,Remark,SValue,IsCount,IsHealth2,IsCount2,IsCount2Day,M01,M02,M03,M04,M05,M06,M07,M08,M09,M10,M11,M12 from ASubject  where Seq='".$_GET["Seq"]."'";
		 $result =mysqli_query($mysql_link,$sql);
		 $rs = mysqli_fetch_row($result);
		 if ($rs){

				$Seq =$rs[0];$Subject =$rs[1];
				$Remark=$rs[2];$SValue=$rs[3];$IsCount=$rs[4];$IsHealth2=$rs[5];$IsCount2 = $rs[6];$IsCount2Day= $rs[7];
				for ($indexs=0;$indexs<12;$indexs++){
					$M[$indexs] = $rs[$indexs+8];
				}

			}
	?>

<table  class="table table-bordered">
<tbody>
<tr>
  <th width="15%">科目</th>
  <td width="85%"><?=$Subject?></td>
</tr>
<tr>
  <th width="15%">屬性</th>
  <td width="85%"><?=($SValue==0?" ( + ) ":" ( - ) ")?></td>
</tr>
 <tr>
   <th>二代健保屬性</th>
   <td><? switch($IsHealth2){
							case 0;echo "否";break;
							case 1;echo "四倍扣除額";break;
							case 2;echo "兼職薪資所得";break;
							case 3;echo "執行業務收入";break;
							}?></td>
 </tr>
 <tr>
    <th>科目核算</th>
    <td>

	<?=($IsCount==1?"★是 (該科目因請假影響，須扣除當日薪資；例：事假扣1日薪資、病假扣0.5日薪資)":($IsCount==2?"是 (該科目因請假影響，須扣除1日的薪資或津貼；例：伙食津貼)":($IsCount==0?"★否 (該科目不受請假影響)":"")))?></td>
  </tr>
   <tr>
   <th>是否因請假取消</th>
    <td><?=($IsCount2==1?"是":($IsCount2==0?"否":""))?>、天數限制：<?=($IsCount2Day)?>  </td>
  </tr>

<tr>
  <th>設定月份 </th>
  <td><?
    	for ($indexs=1;$indexs<=12;$indexs++){

      	if ($M[$indexs-1]==1){
	  	?>
       <div style="width:60px; float:left"><i class="fa fa-check-square-o fa-lg" aria-hidden="true"></i>
        <?=$indexs?>
        月 </span></div>
      <?
		}else{
		?>
         <div style="width:60px; float:left"><i class="fa fa-square-o fa-lg" aria-hidden="true"></i>

        <?=$indexs?>
        月 </span></div>
      <?

		}
		}
	?></td>
</tr>
<tr>
  <th>備註</th>
  <td><?=$Remark?></td>
</tr>

</tbody>
</table>
<input type="button" name="button" id="button"  class="btn btn-primary" value="<?=$CancelBtn?>" onClick="location.href='<?=$LocalFile?>.php?page=<?=$_GET["page"]?>&Keyword=<?=urlencode($_GET["Keyword"])?>';">
	<?
	break;
	case "Modify";
	$sql="select Seq,Subject,Remark,SValue,IsCount,IsHealth2,IsCount2,IsCount2Day,M01,M02,M03,M04,M05,M06,M07,M08,M09,M10,M11,M12 from ASubject  where Seq='".$_GET["Seq"]."'";
		 $result =mysqli_query($mysql_link,$sql);
		 $rs = mysqli_fetch_row($result);
		 if ($rs){

				$Seq =$rs[0];$Subject =$rs[1];

				if (($Subject=="勞保費")||($Subject=="健保費")){
					$IsMod=0;$IsDel=0;$ShowDesc="系統預設，不可修改";
				}


				$Remark=$rs[2];$SValue=$rs[3];$IsCount=$rs[4];$IsHealth2=$rs[5];$IsCount2 = $rs[6];$IsCount2Day= $rs[7];
				for ($index=0;$index<12;$index++){
					$M[$index] = $rs[$index+8];
				}


			 }
	?>
	<form action="" method="post"  name="ModForm">
    <input name="Seq" type="hidden" value="<?=$Seq?>">
    <input name="page" type="hidden" value="<?=$_GET["page"]?>">
    <input name="Keyword" type="hidden" value="<?=$_GET["Keyword"]?>">

   <table  class="table table-bordered">
   <tbody>
  <tr>
    <th width="15%">科目</th>
    <td width="85%"><input type="text" size="45" name="Subject" id="Subject" value="<?=$Subject?>"></td>
  </tr>
  <tr>
  <th width="15%">屬性</th>
  <td width="85%"><input type="radio" name="SValue" id="SValue" value="0"<?=($SValue==0?" checked":"")?> /> ( + ) <input type="radio" name="SValue" id="SValue" value="1"<?=($SValue==1?" checked":"")?> /> ( - )</td>
  </tr>

  <tr>
   <th>二代健保屬性</th>
   <td>
   <input type="radio" name="IsHealth2" id="IsHealth2" value="0"<?=($IsHealth2==0?" checked":"")?>>否、
   <input type="radio" name="IsHealth2" id="IsHealth2" value="1"<?=($IsHealth2==1?" checked":"")?>>四倍扣除額、
   <input type="radio" name="IsHealth2" id="IsHealth2" value="2"<?=($IsHealth2==2?" checked":"")?>>兼職薪資所得、
   <input type="radio" name="IsHealth2" id="IsHealth2" value="3"<?=($IsHealth2==3?" checked":"")?>>執行業務收入
   &nbsp;</td>
 </tr>
  <tr>
    <th>設定月份 <span class="label" style="margin:1px;">
      <input type="checkbox" name="Chk" id="Chk" onClick="if (this.checked){for (index=1;index<=12;index++){document.getElementById('ChkMonth'+index).checked=true;}}else{for (index=1;index<=12;index++){document.getElementById('ChkMonth'+index).checked=false;}}">
      全選 </span></th>
    <td><?
    	for ($index=1;$index<=12;$index++){
			?>
      <span class="label" style="margin:1px;">
        <input type="checkbox" name="ChkMonth<?=$index?>"  id="ChkMonth<?=$index?>" <?=($M[$index-1]==1?"checked":"")?> value="1">
        <?=$index?>
        月 </span>
      <?
		}
	?></td>
  </tr>
  <tr>
    <th>科目計算</th>
    <td>
    <input type="radio" name="IsCount" id="IsCount" value="1" <?=($IsCount==1?"checked":"")?>>★是 (該科目因請假影響，須扣除當日薪資；例：事假扣1日薪資、病假扣0.5日薪資)<br />
    <input type="radio" name="IsCount" id="IsCount" value="2" <?=($IsCount==2?"checked":"")?>>★是 (該科目因請假影響，須扣除1日的薪資或津貼；例：伙食津貼)<br />
    <input type="radio" name="IsCount" id="IsCount" value="0" <?=($IsCount==0?"checked":"")?>>★否 (該科目不受請假影響) </td>
  </tr>
  <tr>
   <th>是否因請假取消</th>
    <td>是
      <input type="radio" name="IsCount2" id="IsCount2" value="1" <?=($IsCount2==1?"checked":"")?>>
      否
      <input type="radio" name="IsCount2" id="IsCount2" value="0" <?=($IsCount2==0?"checked":"")?>>、天數限制：<input type="text" name="IsCount2Day" id="IsCount2Day" value="<?=$IsCount2Day?>">
      </td>
  </tr>
  <tr>
  <th>備註</th>
  <td><input type="text" size="45" name="Remark" id="Remark" value="<?=$Remark?>"></td>
</tr>

</tbody>
</table>
<input type="submit" name="ModBtn" id="ModBtn" class="btn btn-primary" value="<?=$ModBtn?>" <?=($IsMod==0?"disabled":"")?>>
<input type="submit" name="DelBtn" id="DelBtn"  class="btn btn-warning" value="<?=$DelBtn?>" <?=($IsDel==0?"disabled":"")?> onClick="if (confirm('確定要刪除嗎?')==0){return false}">
<input type="button" name="button" id="button"  class="btn btn-primary" value="<?=$CancelBtn?>" onClick="location.href='<?=$LocalFile?>.php?page=<?=$_GET["page"]?>&Keyword=<?=urlencode($_GET["Keyword"])?>';">
</form>
	<?
	break;
		case "AddNew";
		?>
	  <form action="" method="post"  name="ModForm">

   <table  class="table table-bordered">
   <tbody>
  <tr>
<th>科目</th>
<td><input type="text" size="45" name="Subject" id="Subject"></td>
</tr>
<tr>
  <th width="15%">屬性</th>
  <td width="85%"><input type="radio" name="SValue" id="SValue" checked value="0" /> ( + ) <input type="radio" name="SValue" id="SValue" value="1" /> ( - )</td>
  </tr><tr>
    <th>設定月份 <span class="label" style="margin:1px;"><input type="checkbox" name="Chk" id="Chk" onClick="if (this.checked){for (index=1;index<=12;index++){document.getElementById('ChkMonth'+index).checked=true;}}else{for (index=1;index<=12;index++){document.getElementById('ChkMonth'+index).checked=false;}}"> 全選 </span></th>
    <td>
    <?
    	for ($index=1;$index<=12;$index++){
			?><span class="label" style="margin:1px;"> <input type="checkbox" name="ChkMonth<?=$index?>"  id="ChkMonth<?=$index?>" value="1"> <?=$index?> 月 </span> <?
		}
	?>
    </td>
  </tr>
  <tr>
   <th>二代健保屬性</th>
   <td>
   <input type="radio" name="IsHealth2" id="IsHealth2" value="0" checked>否、
   <input type="radio" name="IsHealth2" id="IsHealth2" value="1">四倍扣除額、
   <input type="radio" name="IsHealth2" id="IsHealth2" value="2">兼職薪資所得、
   <input type="radio" name="IsHealth2" id="IsHealth2" value="3">執行業務收入
   &nbsp;</td>
 </tr>

<!--   <tr>
    <th>科目核算</th>
    <td><input type="radio" name="IsCount" id="IsCount" value="1">★是 (該科目因請假影響，須扣除當日薪資；例：事假扣1日薪資、病假扣0.5日薪資)<br />
      <input type="radio" name="IsCount" id="IsCount" value="2">★是 (該科目因請假影響，須扣除1日的薪資或津貼；例：伙食津貼)<br />
      <input name="IsCount" type="radio" id="IsCount" value="0" checked="CHECKED">★否 (該科目不受請假影響) </td>

  </tr> -->
<!--   <tr>
   <th>是否因請假取消</th>
    <td>是
      <input type="radio" name="IsCount2" id="IsCount2" value="1">
      否
      <input name="IsCount2" type="radio" id="IsCount2" value="0" checked="CHECKED">
      、天數限制：
      <input type="text" name="IsCount2Day" id="IsCount2Day" value="0">
      </td>
  </tr> -->

<tr>
<th>金額</th>
<td><input type="number" name="amount" class="form-control"></td>
</tr>

<!-- <tr>
<th>是否攤提</th>
<td><input class="form-check-input" value="1" name="k1" type="checkbox"></td>
</tr>

<tr>
<th>包含在月薪</th>
<td><input class="form-check-input" value="1" name="k2" type="checkbox"></td>
</tr>
 -->


<tr>
<th>是否計稅</th>
<td><input class="form-check-input" value="1" name="k1" type="checkbox"></td>
</tr>

<tr>
<th>是否實報實銷</th>
<td><input class="form-check-input" value="1" name="k2" type="checkbox"></td>
</tr>

<tr>
<th>備註</th>
<td><input type="text" size="45" name="Remark" id="Remark"></td>
</tr>
  </tbody>
</table>
<input type="submit" name="AddBtn" id="AddBtn" class="btn btn-primary" value="<?=$AddBtn?>" <?=($IsAdd==0?"disabled":"")?>>
<input type="button" name="button" id="button"  class="btn btn-primary" value="<?=$CancelBtn?>" onClick="location.href='<?=$LocalFile?>.php?page=<?=$_GET["page"]?>&Keyword=<?=urlencode($_GET["Keyword"])?>';">
</form>
		<?
		break;
		}?>
      </div>


  </div>

   <? include "../Bottom.php";?>;

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script src="../js/xlsx.core.min.js"></script>
   <script src="../js/Blob.min.js"></script>
   <script src="../js/FileSaver.min.js"></script>
   <script src="../js/tableexport.min.js"></script>


   <script>
		$('.value_edit').on('blur', function(){
			//console.log($(this).closest('.drow').html());
			updateDate($(this).closest('.drow'));
		});

		$('.hselect, .location, .vbox').on('change', function(){
			updateDate($(this).closest('.drow'));
		})

		function updateDate(drow){
			var Subject = $('.subject', drow).val();
			var IsHealth2 = $('.hselect', drow).val();
			var amount = $('.amount', drow).val();
			var Seq= $('.Seq', drow).val();
			var Remark = $('.Remark', drow).val();
			var Sno = $('.Sno', drow).val();
			var location = $('.location', drow).val();

			var fd = new FormData();
			fd.append('Subject', Subject);
			fd.append('IsHealth2', IsHealth2);
			fd.append('amount', amount);
			fd.append('Seq', Seq);
			fd.append('Remark', Remark);
			fd.append('Sno', Sno);
			fd.append('location', location);

			for (i=1; i<=12; i=i+1){
				if ($('.mbox'+i, drow).is(':checked')) fd.append('ChkMonth'+i, '1');
			}

			for (i=1; i<=3; i=i+1){
				if ($('.k0'+i, drow).is(':checked')) fd.append('k0'+i, '1');
			}

			$.ajax({
			    url: 'update_subject.php',
			    type: "POST",
			    data: fd,
			    contentType: false,
			    cache: false,
			    processData: false,
			    success: function(data) {
			        data = $.trim(data);
			        console.log(data);
			        if (data === '-1') {
			            alert("時間閑置過久，或你尚未登入!請重新登入系統");
			            window.parent.location.href="../Login.php";
			        }

			        if (data === '-2') {
			            alert("資料庫更新錯誤!");
			        }

			    }
			});
		}

		$('.del').on('click', function(){
			if (confirm('確定要刪除嗎?')){
				var drow = $(this).closest('.drow');
				var pid = drow.data('pid');
				$.post('del_subject.php', {Seq: pid}, function(result){
					data = $.trim(result);
					console.log(data);
					if (data === '-1') {
					    alert("時間閑置過久，或你尚未登入!請重新登入系統");
					    window.parent.location.href="../Login.php";
					}

					if (data === '-2') {
					    alert("資料庫更新錯誤!");
					    return;
					}

					$(drow).remove();
				})

			}
		})





		$('#exp').on('click', function(){

			var mtable = $('#main_table').clone(true);

			$('.mcheck', mtable).remove();
			$(mtable).prop('id', 'mtable').css('display', 'none').appendTo($('body'));

			var myTable = $('#mtable').tableExport({
					formats: ["xlsx"],
					filename: "薪資科目",
					trimWhitespace: true,
					bootstrap: true
				});

			$('.xlsx').trigger('click');
			$('#mtable').remove();

		})


		$('#selectall').on('click', function(){
			if ($('.chkbox').prop('checked')){
				$('.chkbox').prop('checked', false);
			} else {
				$('.chkbox').prop('checked', true);
			}
		})

		$('#deleteall').on('click', function(){
			var msg = "確定要刪除選取的項目?"; 
			 if (confirm(msg)==true){ 
			  var dlist = [];
			  $('.chkbox').each(function(){
			  	if ($(this).prop('checked')){
			  		var pid = $(this).data('pid');
			  		dlist.push("'"+pid+"'");
			  	}
			  });

			  if (dlist.length === 0){
			  	alert('沒有選擇項目!');
			  	return;
			  }

			  $.post('../delete_items.php', {'tbname': 'ASubject', 'fname': 'Seq', 'dlist': dlist}, function(result){
			  	result = $.trim(result);
			  	if (result === '-1'){
			  		window.location = '../Login.php';
			  		return;
			  	}
			  	//console.log(result);
			  	location.reload();
			  })

			 }
		})
   </script>

  </body>
</html>
