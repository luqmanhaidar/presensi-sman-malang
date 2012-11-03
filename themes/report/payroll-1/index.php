<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table style="margin:0 auto;width:80%;border-collapse:collapse;background:#ecf3eb">
    <caption>
    <h3><?=$title?></h3>
    <h5><?=$this->session->userdata('eat_start').' s/d '.$this->session->userdata('eat_finish')?></h5>
	<br/>
    </caption>
	<thead>
		<tr>
			<th class="mini" width="5%">No</th>
			<th class="mini">ID User</th>
            <th class="mini">Nama</th>
			<th class="mini">Jabatan</th>
            <th class="mini">Hari</th>
			<th class="mini">Jumlah</th>
			<th class="mini">Paraf</th>
		</tr>
    </thead>
	<tbody>
	<?php 
        $i=1;
        $grand_total=0;
        foreach($checks as $row):?>
			<tr>
				<td class="mini"><?=$i;?></td>
				<td class="mini"><?=$row['UserID'];?></td>
                <td class="mini"><?=$row['Name'];?></td>
				<td class="mini"><?=$row['GroupName'];?></td>
                <td class="mini align-right hide-on-mobile"><?=$row['Total'];?></td>
				<td class="mini align-right hide-on-mobile"><?=number_format($total=$row['Total'] * $var,0);?></td>
				<td class="mini"><img style="width:20px;height:auto;" src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg')?>" /></td>
			</tr>
     <?php 
         $i++;
         $grand_total = $grand_total + $total;
         endforeach;?>
    </tbody>
    
    <tfoot>
		<tr>
            <th class="mini align-right" colspan="5">Total</th>
			<th class="mini align-right"><?=number_format($grand_total,0);?></th>
			<th class="mini"></th>
		</tr>
    </tfoot>
 </table>
</body>
</html>