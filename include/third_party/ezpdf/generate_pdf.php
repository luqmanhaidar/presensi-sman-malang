<?php
     include_once ('class.ezpdf.php');
     //ezpdf: from http://www.ros.co.nz/pdf/?
     //docs: http://www.ros.co.nz/pdf/readme.pdf
     //note: xy origin is at the bottom left

     //data
     $colw = array(      80 ,    40,   220,    80,     40  );//column widths
     $rows = array(
         array('company','size','desc','cost','instock'),
         array("WD", "80GB","WD800AAJS SATA2 7200rpm 8mb"        ,"$36.90","Y"),
         array("WD","160GB","WD1600AAJS SATA300 8mb 7200rpm"     ,"$39.87","Y"),
         array("WD", "80GB","800jd SATA2 7200rpm 8mb"            ,"$41.90","Y"),
         array("WD","250GB","WD2500AAKS SATA300 16mb 7200rpm"    ,"$49.88","Y"),
         array("WD","320GB","WD3200AAKS SATA300 16mb 7200rpm"    ,"$49.90","Y"),
         array("WD","160GB","1600YS SATA raid 16mb 7200rpm"      ,"$59.90","Y"),
         array("WD","500GB","500gb WD5000AAKS SATA2 16mb 7200rpm","$64.90","Y"),
         array("WD","250GB","2500ys SATA raid 7200rpm 16mb"      ,"$69.90","Y"),
     );

     //x is 0-600, y is 0-780 (origin is at bottom left corner)
     $pdf =& new Cezpdf('LETTER');

     $image = imagecreatefrompng("background.png");
     $pdf->addImage($image,0,0,611);

     $pdf->selectFont("fonts/Helvetica.afm");
     $pdf->setColor(0/255,0/255,0/255);
     $pdf->addText(80,620,10,"List of Hard Drives");

     $pdf->setLineStyle(0.5);
     $pdf->line(80,615,540,615);
     $pdf->setStrokeColor(0,0,0);

     $pdf->setColor(0/255,0/255,0/255);
     $pdf->addText(30,16,8,"<b>Created ".date("m/d/Y"));

     $total=0;
     $curr_x=80;
     $curr_y=600;
     foreach($rows as $r)
     {
         $xoffset = $curr_x;
         foreach($r as $i=>$data)
         {
             $pdf->setColor(0/255,0/255,0/255);
             $pdf->addText( $xoffset, $curr_y , 10, $data );
             $xoffset+=$colw[$i];
         }
         $curr_y-=20;
     }

     $pdf->ezStream(); 

//-----------------------------------------------------------------------------
?>
