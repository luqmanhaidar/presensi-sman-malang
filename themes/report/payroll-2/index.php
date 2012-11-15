<html>
<head>
<title><?=$title?></title>
<link type="text/css" rel="stylesheet" href="<?=base_url('themes/report/css/style.css');?>" />
</head>
<body>
 <table style="margin:0 auto;width:80%;border-collapse:collapse;background:#ecf3eb">
    <caption>
    <h3><?=$title?></h3>
    <h3><?=$group['Name']?></h3>
    <h3><?=indonesian_monthName(substr($this->session->userdata('eat_start'),0,2)).' - '.indonesian_monthName(substr($this->session->userdata('eat_finish'),0,2))?></h3>
	<br/>
    </caption>
	<thead>
		<tr>
			<th class="mini">No</th>
            <th class="mini">Nama</th>
			<th class="mini">Jabatan</th>
            <th class="mini">Kehadiran</th>
			<th class="mini">Operasional <br /> Kehadiran</th>
			<th class="mini">Paraf</th>
		</tr>
    </thead>
	<tbody>
	<?php 
        $i=1;
        //$sub_eat=0;
        $sub_trp=0;
           
        foreach($checks as $row):
            if($i%2==0)
                $align = "align-left";
            else
                $align = "center"; 
        ?>
			<tr>
				<td class="mini center"><?=$i;?></td>
                <td class="mini"><?=$row['Name'];?></td>
				<td class="mini center"><?=$row['GroupName'];?></td>
                <td class="mini center hide-on-mobile"><?=$absen = $row['Total']-$holidays;?></td>
				<td class="mini align-right hide-on-mobile"><?=number_format($trp_total=$absen * $trp,0);?></td>
                <td style="width:17%;" class="mini <?=$align?>"><?=$i?></td>
			</tr>
     <?php 
            $i++;
            //$sub_eat = $sub_eat + $eat_total;
            $sub_trp = $sub_trp + $trp_total;
         endforeach;?>
    </tbody>
    
    <tfoot>
		<tr>
            <th class="mini align-right" colspan="4">Total</th>
            <th class="mini align-right"><?=number_format($sub_trp,0);?></th>
			<th class="mini"></th>
		</tr>
        <tr>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-right white no-border"></th>
			<th class="mini align-right white no-border"></th>
            <th class="mini white no-border"></th>
            <th class="mini align-left white no-border">Malang,...........</th>
		</tr>
        <tr>
            <th style="padding-bottom:100px;"class="mini align-right white no-border"></th>
            <th style="padding-bottom:100px;" class="mini align-left white no-border">Kepala Madrasah</th>
            <th style="padding-bottom:100px;" class="mini align-right white no-border"></th>
			<th style="padding-bottom:100px;" class="mini align-right white no-border"></th>
            <th style="padding-bottom:100px;" class="mini align-left white no-border"></th>
			<th style="padding-bottom:100px;" class="mini align-left white no-border">Bendahara</th>
		</tr>
        
        <tr>
            <th class="mini align-right white no-border"></th>
            <th class="mini align-left white no-border"><p style="border-bottom:1px solid #222;width:170px;">Drs.H.Ahmad Hidayatullah, M.Pd</p><p>NIP. 19680622 200012 1 002</p></th>
            <th class="mini align-right white no-border"></th>
			<th class="mini align-right white no-border"></th>
            <th class="mini white no-border"></th>
            <th class="mini align-left white no-border"><p style="border-bottom:1px solid #222;width:170px;">Drs. Suwito</p><p>NIP. 19601010 199503 1 001</p></th>
		</tr>
        
    </tfoot>
 </table>
</body>
</html>