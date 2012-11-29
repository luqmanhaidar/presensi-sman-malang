<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
<h3 style="text-align:center"><?=$title?></h3>
 <table style="margin:0 auto;width:100%;border-collapse:collapse;background:#ecf3eb">
	<thead>
		<tr>
			<th style="width=5%">No</th>
			<th style="width=35%">Nama</th>
			<th style="width=20%">Group</th>
			<th style="width=20%">Tanggal</th>
			<th style="width=20%">Function Key</th>
		</tr>
    </thead>
	<tbody>
	<?php 
        $i=1;
        foreach($checks as $row):?>
			<tr>
				<td><?=$i;?></td>
				<td style="width=35%"><?=$row['Name']?></td>
				<td><?=$row['GroupName']?></td>
				<td><?=$row['MyDate'];?></td>
				<td><?=$row['FunctionKey']?> (<?=functionKey($row['FunctionKey']);?>)</td>
			</tr>
     <?php 
         $i++;
         endforeach;?>
    </tbody>
 </table>

</body>
</html>