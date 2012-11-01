<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <center></center>
 <table class="vmonth">
    <caption>
		<h3><?=$title?></h3>
		<h4><?='Group:' .$position['Name'];?></h4>
	</caption>
	<thead>		
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Paraf</th>
			<th colspan="<?=days_in_month($this->session->userdata('month_search'))?>">Tanggal</th>
			<th colspan="3">Keterangan</th>
		</tr>
		<tr>
			<?php for($i=1;$i<=days_in_month($this->session->userdata('month_search'));$i++):?>
			<th><?=$i;?></th>
			<?php endfor;?>
			<th>S</th>
			<th>I</th>
			<th>T</th>
		</tr>
    </thead>
	<tbody>
	<?php 
        $x=1;
        foreach($checks as $row):?>
			<tr>
				<td rowspan="4"><?=$x;?></td>
				<td><?=$row['Name']?></td>
				<td>Paraf</td>
				<?php for($i=1;$i<=days_in_month($this->session->userdata('month_search'));$i++):?>
				  <td>
                    <?php if(strlen($this->authlog->getUserTime($i,$row['UserID'],1))>2): ?>
                        <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
                    <?php else: ?>   
                        
                    <?php endif;?>        
                  </td>
				<?php endfor;?>
				<td rowspan="4">-</td>
				<td rowspan="4">-</td>
				<td rowspan="4">-</td>
			</tr>
            
            <tr>
				<td>-</td>
				<td>Dtg.PK</td>
				<?php for($i=1;$i<=days_in_month($this->session->userdata('month_search'));$i++):?>
				  <td><?=substr($this->authlog->getUserTime($i,$row['UserID'],1),0,5);?></td>
				<?php endfor;?>
			</tr>
            
            <tr>
				<td>-</td>
				<td>Paraf</td>
				<?php for($i=1;$i<=days_in_month($this->session->userdata('month_search'));$i++):?>
				  <td>
                    <?php if(strlen($this->authlog->getUserTime($i,$row['UserID'],2))>2): ?>
                        <img src="<?=base_url('assets/signature/'.$row['UserID'].'.jpg');?>" />
                    <?php else: ?>   
                    <?php endif;?>
                  </td>
				<?php endfor;?>
			</tr>
            
			<tr>
                <td><?=$row['Department']?></td>
				<td>Plg.PK</td>
				<?php for($i=1;$i<=days_in_month($this->session->userdata('month_search'));$i++):?>
				  <td><?=substr($this->authlog->getUserTime($i,$row['UserID'],2),0,5);?></td>
				<?php endfor;?>
			</tr>		
     <?php 
         $x++;
         endforeach;?>
    </tbody>
 </table>
</body>
</html>