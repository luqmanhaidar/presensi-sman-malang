<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Two Column CSS Layout</title>
<link type="text/css" media="screen" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
<style type="text/css">
body{margin:0;font-family: Arial;font-size:10px;}
div.my_wrapper{
    width: 730px;
    height:400px;
}
.clear{clear: both;}
div.my1{
    float: left;
    padding: 5px;
    width: 350px;
    border: 1px solid gray;
    margin-bottom:10px;
}

div.my0{
    float: right;
    padding: 5px;
    width: 350px;
    border: 1px solid gray;
    margin-bottom:10px;
}
.row{width:100%;height:20px;}
.col{float:left;width:45px;text-align:center;border:1px solid #222;padding: 1px;}

table {margin:0 auto;width:100%;border-collapse:collapse;}
th,td{border:1px solid #222;font-size:12px;margin:0;font-size:9px;}
</style>

</head>

<body>

<div class="my_wrapper">
    
<?php 
$x=1;
foreach($users as $user):
?>    	          
    <div class="my<?=$x%2?>">
        
        <table>
            <tr>
                <td>Test</td>
            </tr>
        </table>
    
    </div>
    
    <?php if($x%2==0): ?>
    <div class="clear"></div>
    <?php endif;?>

<?php
 $x++;
 endforeach;
 ?>
</div>

</body>
</html>
