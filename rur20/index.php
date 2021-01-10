<?php
    
    $l1=133;
    $l2=121;
    $l3=113;
    $found=false;

    if($_GET['certificate_id'] == null){
        header('Location: http://www.areyouready.uom.lk/');
    }else{
        $certificate_id = $_GET['certificate_id'];
        include_once('data.php');

        $arr = json_decode($f,true)['Certificate'];

        $startIndex = 0;
        $stopIndex = count($arr);
        $middle = floor(($stopIndex + $startIndex)/2);
 
        while($arr[$middle]['certificate_id'] != $certificate_id && $startIndex < $stopIndex){
    
            //adjust search area
            if ($certificate_id < $arr[$middle]['certificate_id']){
                $stopIndex = $middle - 1;
            } else if ($certificate_id > $arr[$middle]['certificate_id']){
                $startIndex = $middle + 1;
            }
    
            //recalculate $middle
            $middle = floor(($stopIndex + $startIndex)/2);
        }
        
        if($arr[$middle]['certificate_id'] != $certificate_id){
            $found = false;
            header('Location: http://www.areyouready.uom.lk/');
        }else{
            $found = true;
            $entry =  $arr[$middle];

            require('fpdf/fpdf.php');            

            $pdf = new FPDF('L','mm','A4');
            $pdf->SetTitle('Are You Ready? 2020 | Certificate Verification');
            $pdf->SetTopMargin(105);
            $pdf->AddFont('montserrat','B','montserrat.regular.php');
            $pdf->AddFont('montserrat','','montserrat.light.php');
            $pdf->AddPage();

            $pdf->Image('certificate/RUR_Certificate_'.$entry['type'].'.jpg',0,0,-288,-288);
            
            $pdf->SetFont('montserrat','B',18);
            $pdf->MultiCell(0,8,$entry['name'],0,'C');
            $pdf->Output('I', 'Are You Ready? 2020 - '.$entry['name'].'.pdf');
        }
    }
?>