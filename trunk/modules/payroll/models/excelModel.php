<?php

class ExcelModel extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function eat_excel($recs,$trp,$eat,$group){         						
		$objPHPExcel = new PHPExcel();
        $objDrawing  = new Worksheet_Drawing();
		$objPHPExcel->getProperties()->setTitle("title")
					->setDescription("description");
					 $objPHPExcel->setActiveSheetIndex(0);
		$styleArray = array( 'borders' => array( 'allborders' => array(
                             'style' => Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                                'rgb' => 'CCCCCC'
                        ),
                        'endcolor'   => array(
                                'argb' => 'CCCCCC'
                        )
                );       
	  	
		$fontArray = array(
			'font' => array(
			'bold' => true
			)
			);
		
        $row=1;
        $col=0;
        
        //Judul
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"DAFTAR OPERASIONAL KEHADIRAN DAN PENAMBAHAN GIZI ");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+6,$row);
        
		$row=$row+1;
        $col=0;
        
		//Judul
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$group['Name']);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+6,$row);
	   
        $row=$row+1;
        $col=0;
        
		//Judul
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,indonesian_monthName(substr($this->session->userdata('eat_start'),0,2)).' - '.indonesian_monthName(substr($this->session->userdata('eat_finish'),0,2)));
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+6,$row);
	   
       
        $row=$row+2;
        $col=0;
		
		//No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'No');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(5);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
		
		//Nama
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'Nama');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+1)->setWidth(25);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
		
		//Jabatan
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,'Jabatan');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+2)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,'Kehadiran');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+3)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        
        //Jumlah
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,'Operasional  Kehadiran');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+4)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
        
        //Jumlah
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,'Penambahan Gizi');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+5)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,'Paraf');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+6)->setWidth(25);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        $row=$row + 1;
		$i=1;
        $sub_eat=0;
        $sub_trp=0;
		foreach($recs as $rec):
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,$rec['Name']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,$rec['GroupName']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,$absen = $rec['Total']- $this->session->userdata('eat_holiday'));
             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,$trp_total=$absen * $trp);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,$eat_total=$absen * $eat);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,$i);
            if($i%2==0)
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_LEFT);
            else
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
            
            $i++;
            $row++;
            $sub_eat = $sub_eat + $eat_total;
            $sub_trp = $sub_trp + $trp_total;
        endforeach; 
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,"Total");
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,$sub_trp);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getNumberFormat()->setFormatCode("#,##0");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);  
                
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,$sub_eat);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getNumberFormat()->setFormatCode("#,##0");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);  
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray); 
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        $row=$row+2;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,"Malang, ................");
        
        $row=$row+1;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,"Kepala Madrasah");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,"Bendahara");
        
        
        $row=$row+5;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,"Drs.H.Ahmad Hidayatullah, M.Pd");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,"Drs. Suwito");
        
        $row=$row+1;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,"NIP. 19680622 200012 1 002");
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,"NIP. 19601010 199503 1 001");
        
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007");
        $file="Lap-Gaji-Format-1.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
    
    function transport_excel($recs,$var){         						
		$objPHPExcel = new PHPExcel();
        $objDrawing  = new Worksheet_Drawing();
		$objPHPExcel->getProperties()->setTitle("title")
					->setDescription("description");
					 $objPHPExcel->setActiveSheetIndex(0);
		$styleArray = array( 'borders' => array( 'allborders' => array(
                             'style' => Style_Border::BORDER_THIN )));
        $fill = array(
                        'type'       => Style_Fill::FILL_SOLID,
                        'rotation'   => 0,
                        'startcolor' => array(
                                'rgb' => 'CCCCCC'
                        ),
                        'endcolor'   => array(
                                'argb' => 'CCCCCC'
                        )
                );       
	  	
		$fontArray = array(
			'font' => array(
			'bold' => true
			)
			);
		
        $row=1;
        $col=0;
        
        //Judul
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Laporan Gaji Format 2");
       
		$row=$row+1;
        $col=0;
		
		//No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'No');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(5);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
		
		//ID User
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'ID User');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+1)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
		
		//Nama
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,'Nama');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+2)->setWidth(25);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
		
		//Jabatan
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,'Jabatan');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+3)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,'Hari');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+4)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
        
        //Jumlah
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,'Transport');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+5)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,'Paraf');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+6)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        $row=$row + 1;
		$i=1;
        $grandtotal = 0;
		foreach($recs as $rec):
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,$rec['UserID']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,$rec['Name']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,$rec['GroupName']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,$rec['Total']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,$total = $rec['Total']* $var );
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,"");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
            
            $i++;
            $row++;
            $grandtotal = $grandtotal + $total;
        endforeach; 
        
        $row++;
        $col=0;
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,"Total");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
                
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,$grandtotal);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getNumberFormat()->setFormatCode("#,##0");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);  
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray); 
        
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007");
        $file="Lap-Gaji-Format-2.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
}    