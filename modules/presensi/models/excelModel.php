<?php

class ExcelModel extends CI_Model
{
    function __construct()
    {
        parent::__construct(); // Call the Model constructor
    }
    
    function personal_excel($user,$name){
        //$this->user_logs->saveLog('Export Excell Admission Report');           						
		$objPHPExcel = new PHPExcel();
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
        
        //No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$user.'-'.$name);
       
		$row=$row+1;
        $col=0;
		
		//No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'No');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(5);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getFill()->applyFromArray($fill);
		
		//Tanggal
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'Tanggal');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+1)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getFill()->applyFromArray($fill);
		
		//Jenis
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,'Jenis');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+2)->setWidth(20);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->getFill()->applyFromArray($fill);
		
		//Waktu
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,'Waktu');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+3)->setWidth(15);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        
        $row=$row + 1;
        $data['checks']	= $this->authlog->getAllRecords('','',$this->session->userdata('personal_search'),$this->session->userdata('personal_key'),$this->session->userdata('personal_start'),$this->session->userdata('personal_finish'));
		$i=1;
		foreach($data['checks'] as $rec):
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,indonesian_shortDate($rec['TransactionTime']));
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,functionKey($rec['FunctionKey']));
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,substr($rec['TransactionTime'],11,8));
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            $i++;
            $row++;
        endforeach;    
		
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007");
		//$objWriter = IOFactory::createWriter($objPHPexcel,'PDF'); 
        $file="Personal.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
    
    function month_excel($recs,$start,$end,$group,$days,$pos,$desc){      						
		$objPHPExcel = new PHPExcel();
        $objDrawing = new Worksheet_Drawing();  // PHPExcel_Worksheet_MemoryDrawing
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Laporan Presensi Bulanan Periode ".$start." s/d ".$end); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
        
        $row++;
        $col=0;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Group ".$pos['Name']); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
		
        $row=$row+3;
        $col=0;
        //No
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row-1,'No');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row-1,$col,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(5);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row-1)->getFill()->applyFromArray($fill);
        
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row-1,'Nama');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row-1,$col+1,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+1)->setWidth(30);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row-1)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row-1,'Paraf');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+2,$row-1,$col+2,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+2)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row-1)->getFill()->applyFromArray($fill);
        
        $i=3;
        foreach($days as $rec):
            //Days
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i,$row,substr($rec['DAY'],0,2));
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i)->setWidth(7);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i,$row)->getFill()->applyFromArray($fill);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $i++;
        endforeach;
        
        //Tanggal
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row-1,$desc);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+3,$row-1,$col+($i-1),$row-1);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row-1)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row-1,$col+($i-1),$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row-1,$col+($i-1),$row-1)->getFill()->applyFromArray($fill);
        
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+0,$row-1,'Keterangan');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row-1)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$i+0,$row-1,$col+$i+2,$row-1);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+0)->setWidth(25);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row-1)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row-1)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+0,$row,'S');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+0)->setWidth(7);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+0,$row)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+1,$row,'I');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+1)->setWidth(7);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+1,$row)->getFill()->applyFromArray($fill);
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$i+2,$row,'T');
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$i+2)->setWidth(7);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+2,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$i+2,$row)->getFill()->applyFromArray($fill);
		
        $row=$row+1;
        $col=0;
        $i=1;
        foreach($recs as $var):
            
             /** Paraf Datang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$i);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,$var['Name']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+1);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Paraf");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,'');
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,$this->others->getUserTime($var['UserID'],$this->session->userdata('month_start'),$this->session->userdata('month_finish'),"Sakit"));
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$j+0,$row,$col+$j+0,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,$this->others->getUserTime($var['UserID'],$this->session->userdata('month_start'),$this->session->userdata('month_finish'),"Ijin"));
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$j+1,$row,$col+$j+1,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,$this->others->getUserTime($var['UserID'],$this->session->userdata('month_start'),$this->session->userdata('month_finish'),"Tugas"));
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$j+2,$row,$col+$j+2,$row+3);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $i++;
            $row++;
            
            /** Datang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Dtg.PK");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,substr($this->authlog->getUserTime($rec['DAY'],$var['UserID'],1),0,5));
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            
            $i++;
            $row++;
            
            /** Paraf Pulang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'Jab: '.$var['Department']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+1,$row,$col+1,$row+1);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->getAlignment()->setVertical(Style_Alignment::VERTICAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Paraf");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,'');
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            
            $i++;
            $row++;
            
            /** Pulang PK **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,'');
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"Plg.PK");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
            
            $j=3;
            foreach($days as $rec):
                //Days
        		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j,$row,substr($this->authlog->getUserTime($rec['DAY'],$var['UserID'],2),0,5));
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $j++;
            endforeach;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+0,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+0,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+1,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+1,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$j+2,$row,'');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$j+2,$row)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            
            
            $i++;
            $row++;
            $i=$i-3;
        endforeach;
        
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007"); 
        $file="Lap-Bulanan.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
    
    function week_excel($recs,$start,$end,$group,$days,$pos,$var){      						
		$objPHPExcel = new PHPExcel();
        $objDrawing = new Worksheet_Drawing();  // PHPExcel_Worksheet_MemoryDrawing
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
        /** Setup Page **/    
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setScale(95);
        /** Setup Page **/  
        
        $row=1;
        $col=0;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Laporan Mingguan Periode ".$start." s/d ".$end); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(5);
        
        $row=$row + 2;
        $col=0;
        $i=1;
        foreach($recs as $user):
            $c = $i % 2;
                
            if($c==1):
                $colx = 0;
                $rowx = 0 ;
                $j    = 0;
                $day=0;
            elseif($c==0):
                $colx = 8;
                $rowx = 0; //4
                $j    = 0;
                $day  = 0;
            endif; 
                    
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$j-$day,$i." DAFTAR CEK CLOCK"); // Title
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $row++;
            $col=0;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"Periode ".$start." s/d ".$end); // Periode
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            /** Nama **/
            $row++;
            $col=0;
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,$user['Name']);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            
            /** Column **/
            $row++;
            $col=0;
            /**Minggu **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,'M');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx)->setWidth(5);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Tanggal **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+1,$row-$rowx-$day,'Tanggal');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+1)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Datang **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+2,$row-$rowx-$day,'Datang');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+2)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**PUlang **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+3,$row-$rowx-$day,'Pulang');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+3)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Durasi **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+4,$row-$rowx-$day,'Durasi');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+4)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Telat **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+5,$row-$rowx-$day,'Dtg Telat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+5)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Pulang Cepat **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+6,$row-$rowx-$day,'Plg Cepat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+6)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $row++;
            $j=1;
            $w[1]=0;
            $l[1]=0;
            $e[1]=0;
            
            $w[2]=0;
            $l[2]=0;
            $e[2]=0;
            
            $w[3]=0;
            $l[3]=0;
            $e[3]=0;
            
            $w[4]=0;
            $l[4]=0;
            $e[4]=0;
            
            $w[5]=0;
            $l[5]=0;
            $e[5]=0;
            foreach($days as $rec):
                $val = $this->authprocess->getAllRecords('','',$user['ID'],'row',$rec['DAY']);
                 if($val):
                    $wx = $val['W'];
                    $date=$val['MyDate'];
                    $dstart=$val['MyTimeStart'];
                    $dend=$val['MyTimeEnd'];
                    
                    if(empty($val['MyTimeEnd']))
                        $endtime = 0;
                    else
                        $endtime = (substr($val['MyTimeEnd'],0,2) * 3600) + (substr($val['MyTimeEnd'],3,2)*60) + (substr($val['MyTimeEnd'],6,2));
                    
                    if(empty($val['MyTimeStart']))
                        $starttime = 0;
                    else
                        $starttime = (substr($val['MyTimeStart'],0,2) * 3600) + (substr($val['MyTimeStart'],3,2)*60) + (substr($val['MyTimeStart'],6,2));
                                        
                    $range = $endtime - $starttime; 
                    if($range<=0)
                        $range = 0;
                    else
                        $range=$range;        
                                
                    $hours = code(floor($range / 3600));
                    $mins  = code(floor(($range - ($hours*3600)) / 60));
                    $seconds = code($range % 60);
                    $duration  = $hours.':'.$mins.':'.$seconds; 
                    
                    $late = $val['ProcessDateLate'];
                    $early= $val['ProcessDateEarly'];
                    
                 else:
                    $range = 0;
                    $wx = '-';
                    $date='-';
                    $dstart="-";
                    $dend="-";
                    $duration="-";
                    $late = "";
                    $early="-";
                 endif;
                 
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,$wx);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1+$colx,$row-$rowx-$day,$date);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2+$colx,$row-$rowx-$day,$dstart);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                       
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3+$colx,$row-$rowx-$day,$dend);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4+$colx,$row-$rowx-$day,$duration);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5+$colx,$row-$rowx-$day,$late);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6+$colx,$row-$rowx-$day,$early);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                 
                 $lt = (substr($late,0,2) * 3600) + (substr($late,3,2)*60) + (substr($early,6,2));
                 $el = (substr($early,0,2) * 3600) + (substr($early,3,2)*60) + (substr($early,6,2));
                 if($wx==1):
                    $w[1] = $w[1] + $range;
                    $l[1] = $l[1]+ $lt;
                    $e[1] = $e[1]+ $el;
                 elseif($wx==2):
                    $w[2] = $w[2] + $range;
                    $l[2] = $l[2]+ $lt;
                    $e[2] = $e[2]+ $el;        
                 elseif($wx==3):
                    $w[3] = $w[3] + $range;
                    $l[3] = $l[3]+ $lt;
                    $e[3] = $e[3]+ $el;
                 elseif($wx==4):
                    $w[4] = $w[4] + $range;
                    $l[4] = $l[4]+ $lt;
                    $e[4] = $e[4]+ $el;
                 elseif($wx==5):
                    $w[5] = $w[5] + $range;  
                    $l[5] = $l[5]+ $lt; 
                    $e[5] = $e[5]+ $el;
                 endif;                     
                 
                 $row++;
                 $j++;   
            endforeach; 
            
            /** Jam Kehadiran **/
            $row = $row;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"Total Jam Kehadiran Per Minggu"); // Title
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            
            $row++;
            /**Minggu Ke **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,'Minggu');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+1,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getFill()->applyFromArray($fill);
             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
             
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+2,$row-$rowx-$day,'Work Time');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /*Desc **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+3,$row-$rowx-$day,'Keterangan');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx+3,$row-$rowx-$day,$col+$colx+4,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+5,$row-$rowx-$day,'Dtg Telat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+6,$row-$rowx-$day,'Plg Cepat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $row++;
            for($m=1;$m<=5;$m++):
                $whours = code(floor($w[$m] / 3600));
                $wmins  = code(floor(($w[$m] - ($whours*3600)) / 60));
                $wseconds = code($w[$m] % 60);
                            
                $lhours = code(floor($l[$m] / 3600));
                $lmins  = code(floor(($l[$m] - ($lhours*3600)) / 60));
                $lseconds = code($l[$m] % 60);
                            
                $ehours = code(floor($e[$m] / 3600));
                $emins  = code(floor(($e[$m] - ($ehours*3600)) / 60));
                $eseconds = code($e[$m] % 60);
                            
                $week[$m] = $whours.':'.$wmins.':'.$wseconds; 
                $wlate[$m] = $lhours.':'.$lmins.':'.$lseconds;
                $wearly[$m]= $ehours.':'.$emins.':'.$eseconds;               
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"Minggu ".$m);
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+1,$row-$rowx-$day);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);           
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2+$colx,$row-$rowx-$day,$week[$m]);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                
                if($week[$m]):
		              $v = (substr($var,0,2) * 3600) + (substr($var,3,2)*60) + (substr($var,6,2));
		              if($v<$week[$m])
						$desc = 'Memenuhi'; 
		              else
						$desc = 'Tidak Memenuhi';	
                else:
                    $desc='-';
                endif;
                /*Desc **/
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+3,$row-$rowx-$day,$desc);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx+3,$row-$rowx-$day,$col+$colx+4,$row-$rowx-$day);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5+$colx,$row-$rowx-$day,$wlate[$m]);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6+$colx,$row-$rowx-$day,$wearly[$m]);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                
                $row++;
                
            endfor; 
            
            
            /** DMK **/
            $row = $row++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"DMK ".$var); // Title
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            
            $i++;
            $c=$i%2; 
            if($c==1):
                //$row=$row++;
            elseif($c==0):
                $row=$row - (4+COUNT($days)+8) ;
            endif; 
        
        $row++;
        endforeach;
        //$row=$row-5;
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007"); 
        $file="Lap-Mingguan.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
    
    function week_pdf($recs,$start,$end,$group,$days,$pos,$var){      						
		$objPHPExcel = new PHPExcel();
        $objDrawing = new Worksheet_Drawing();  // PHPExcel_Worksheet_MemoryDrawing
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
        /** Setup Page **/    
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(Worksheet_PageSetup::PAPERSIZE_A4);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.25);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.50);
        $objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.25);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setScale(95);
        $objPHPExcel->getActiveSheet()->setShowGridlines(true);
        /** Setup Page **/  
        
        $row=1;
        $col=0;
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Laporan Mingguan Periode ".$start." s/d ".$end); // Title
        $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col,$row,$col+5,$row);
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(7)->setWidth(5);
        
        $row=$row + 2;
        $col=0;
        $i=1;
        foreach($recs as $user):
            $c = $i % 2;
                
            if($c==1):
                $colx = 0;
                $rowx = 0 ;
                $j    = 0;
                $day=0;
            elseif($c==0):
                $colx = 8;
                $rowx = 0; //4
                $j    = 0;
                $day  = 0;
            endif; 
                    
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$j-$day,$i." DAFTAR CEK CLOCK"); // Title
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $row++;
            $col=0;
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"Periode ".$start." s/d ".$end); // Periode
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            /** Nama **/
            $row++;
            $col=0;
    		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,$user['Name']);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            
            /** Column **/
            $row++;
            $col=0;
            /**Minggu **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,'M');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx)->setWidth(5);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Tanggal **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+1,$row-$rowx-$day,'Tanggal');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+1)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Datang **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+2,$row-$rowx-$day,'Datang');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+2)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**PUlang **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+3,$row-$rowx-$day,'Pulang');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+3)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Durasi **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+4,$row-$rowx-$day,'Durasi');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+4)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Telat **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+5,$row-$rowx-$day,'Dtg Telat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+5)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /**Pulang Cepat **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+6,$row-$rowx-$day,'Plg Cepat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+$colx+6)->setWidth(11);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $row++;
            $j=1;
            $w[1]=0;
            $l[1]=0;
            $e[1]=0;
            
            $w[2]=0;
            $l[2]=0;
            $e[2]=0;
            
            $w[3]=0;
            $l[3]=0;
            $e[3]=0;
            
            $w[4]=0;
            $l[4]=0;
            $e[4]=0;
            
            $w[5]=0;
            $l[5]=0;
            $e[5]=0;
            foreach($days as $rec):
                $val = $this->authprocess->getAllRecords('','',$user['ID'],'row',$rec['DAY']);
                 if($val):
                    $wx = $val['W'];
                    $date=$val['MyDate'];
                    $dstart=$val['MyTimeStart'];
                    $dend=$val['MyTimeEnd'];
                    
                    if(empty($val['MyTimeEnd']))
                        $endtime = 0;
                    else
                        $endtime = (substr($val['MyTimeEnd'],0,2) * 3600) + (substr($val['MyTimeEnd'],3,2)*60) + (substr($val['MyTimeEnd'],6,2));
                    
                    if(empty($val['MyTimeStart']))
                        $starttime = 0;
                    else
                        $starttime = (substr($val['MyTimeStart'],0,2) * 3600) + (substr($val['MyTimeStart'],3,2)*60) + (substr($val['MyTimeStart'],6,2));
                                        
                    $range = $endtime - $starttime; 
                    if($range<=0)
                        $range = 0;
                    else
                        $range=$range;        
                                
                    $hours = code(floor($range / 3600));
                    $mins  = code(floor(($range - ($hours*3600)) / 60));
                    $seconds = code($range % 60);
                    $duration  = $hours.':'.$mins.':'.$seconds; 
                    
                    $late = $val['ProcessDateLate'];
                    $early= $val['ProcessDateEarly'];
                    
                 else:
                    $range = 0;
                    $wx = '-';
                    $date='-';
                    $dstart="-";
                    $dend="-";
                    $duration="-";
                    $late = "";
                    $early="-";
                 endif;
                 
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,$wx);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1+$colx,$row-$rowx-$day,$date);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2+$colx,$row-$rowx-$day,$dstart);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                       
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3+$colx,$row-$rowx-$day,$dend);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4+$colx,$row-$rowx-$day,$duration);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5+$colx,$row-$rowx-$day,$late);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6+$colx,$row-$rowx-$day,$early);
                 $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                 
                 $lt = (substr($late,0,2) * 3600) + (substr($late,3,2)*60) + (substr($early,6,2));
                 $el = (substr($early,0,2) * 3600) + (substr($early,3,2)*60) + (substr($early,6,2));
                 if($wx==1):
                    $w[1] = $w[1] + $range;
                    $l[1] = $l[1]+ $lt;
                    $e[1] = $e[1]+ $el;
                 elseif($wx==2):
                    $w[2] = $w[2] + $range;
                    $l[2] = $l[2]+ $lt;
                    $e[2] = $e[2]+ $el;        
                 elseif($wx==3):
                    $w[3] = $w[3] + $range;
                    $l[3] = $l[3]+ $lt;
                    $e[3] = $e[3]+ $el;
                 elseif($wx==4):
                    $w[4] = $w[4] + $range;
                    $l[4] = $l[4]+ $lt;
                    $e[4] = $e[4]+ $el;
                 elseif($wx==5):
                    $w[5] = $w[5] + $range;  
                    $l[5] = $l[5]+ $lt; 
                    $e[5] = $e[5]+ $el;
                 endif;                     
                 
                 $row++;
                 $j++;   
            endforeach; 
            
            /** Jam Kehadiran **/
            $row = $row;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"Total Jam Kehadiran Per Minggu"); // Title
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            
            $row++;
            /**Minggu Ke **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,'Minggu');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+1,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getFill()->applyFromArray($fill);
             $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
             
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+2,$row-$rowx-$day,'Work Time');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            /*Desc **/
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+3,$row-$rowx-$day,'Keterangan');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx+3,$row-$rowx-$day,$col+$colx+4,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+5,$row-$rowx-$day,'Dtg Telat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+6,$row-$rowx-$day,'Plg Cepat');
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getFill()->applyFromArray($fill);
            
            $row++;
            for($m=1;$m<=5;$m++):
                $whours = code(floor($w[$m] / 3600));
                $wmins  = code(floor(($w[$m] - ($whours*3600)) / 60));
                $wseconds = code($w[$m] % 60);
                            
                $lhours = code(floor($l[$m] / 3600));
                $lmins  = code(floor(($l[$m] - ($lhours*3600)) / 60));
                $lseconds = code($l[$m] % 60);
                            
                $ehours = code(floor($e[$m] / 3600));
                $emins  = code(floor(($e[$m] - ($ehours*3600)) / 60));
                $eseconds = code($e[$m] % 60);
                            
                $week[$m] = $whours.':'.$wmins.':'.$wseconds; 
                $wlate[$m] = $lhours.':'.$lmins.':'.$lseconds;
                $wearly[$m]= $ehours.':'.$emins.':'.$eseconds;               
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"Minggu ".$m);
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+1,$row-$rowx-$day);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
   	            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);           
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2+$colx,$row-$rowx-$day,$week[$m]);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                
                if($week[$m]):
		              $v = (substr($var,0,2) * 3600) + (substr($var,3,2)*60) + (substr($var,6,2));
		              if($v<$week[$m])
						$desc = 'Memenuhi'; 
		              else
						$desc = 'Tidak Memenuhi';	
                else:
                    $desc='-';
                endif;
                /*Desc **/
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx+3,$row-$rowx-$day,$desc);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx+3,$row-$rowx-$day,$col+$colx+4,$row-$rowx-$day);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
                
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5+$colx,$row-$rowx-$day,$wlate[$m]);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                    
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6+$colx,$row-$rowx-$day,$wearly[$m]);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
                
                $row++;
                
            endfor; 
            
            
            /** DMK **/
            $row = $row++;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+$colx,$row-$rowx-$day,"DMK ".$var); // Title
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->getAlignment()->setHorizontal(Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow($col+$colx,$row-$rowx-$day,$col+$colx+6,$row-$rowx-$day);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+1,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+2,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+3,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+4,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+5,$row-$rowx-$day)->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+$colx+6,$row-$rowx-$day)->applyFromArray($styleArray);
            
            $i++;
            $c=$i%2; 
            if($c==1):
                //$row=$row++;
            elseif($c==0):
                $row=$row - (4+COUNT($days)+8) ;
            endif; 
        
        $row++;
        endforeach;
        //$row=$row-5;
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "PDF"); 
        $file="Lap-Mingguan.pdf";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
    
    function overtime_excel($recs,$var){         						
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"Laporan Lembur");
       
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
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,'Tanggal');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+3)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,'Mulai');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+4)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->getFill()->applyFromArray($fill);
        
        //Jumlah
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,'Selesai');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+5)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,'Durasi');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+6)->setWidth(10);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+7,$row,'Nominal');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+7)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+8,$row,'Uang Makan');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+8)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->getFill()->applyFromArray($fill);
        
        //Hari
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+9,$row,'Total');
        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col+9)->setWidth(12);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->getFill()->applyFromArray($fill);
        
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
			 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,$rec['MyDate']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,$rec['OvertimeStart']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,$rec['OvertimeEnd']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,$rec['OvertimeDuration']);
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+7,$row,$total = $var * $rec['OvertimeDuration']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+8,$row,$rec['OvertimeMeal']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->applyFromArray($styleArray);
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+9,$row,$sub_total=$total + $rec['OvertimeMeal']);
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->getNumberFormat()->setFormatCode("#,##0");
			$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->applyFromArray($styleArray);
            
            $i++;
            $row++;
            $grandtotal = $grandtotal + $sub_total;
        endforeach; 
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+1,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+2,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+2,$row)->applyFromArray($styleArray);
			 
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+3,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+3,$row)->applyFromArray($styleArray);
            
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+4,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+4,$row)->applyFromArray($styleArray);
                
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+5,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+5,$row)->applyFromArray($styleArray);  
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+6,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+6,$row)->applyFromArray($styleArray); 
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+7,$row,"");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+7,$row)->applyFromArray($styleArray);  
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+8,$row,"Total");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+8,$row)->applyFromArray($styleArray); 
        
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+9,$row,$grandtotal);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->getNumberFormat()->setFormatCode("#,##0");
		$objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col+9,$row)->applyFromArray($styleArray); 
        
        // Save it as an excel 2007 file
        $objWriter = IOFactory::createWriter($objPHPExcel, "Excel2007");
        $file="Lap-Lembur.xlsx";
        $objWriter->save('assets/'.$file);
        redirect(base_url('assets/'.$file),301);
    }
}    