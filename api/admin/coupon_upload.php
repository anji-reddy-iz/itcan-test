<?php
ob_start();
session_start();
require_once('../../db.php');
$db = new Db();
if($_FILES['file']['name'] != ''){


    $test = explode('.', $_FILES['file']['name']);
    $extension = end($test);    
    $name = time()."_".rand(100,999).'.'.$extension;

    $filePath = '../../uploads/coupons/'.$name;
    move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
    
    $created_on_1  =$db -> quote(date("Y-m-d H:i:s"));
    $noofrows="0";  $data = array(); $errors = array();  $output_josn = array();  $message = ""; $output_result = "";          
    $file = fopen($filePath, "r");
    $numberOfRows = 0; $successfulRows = 0; $failedRows = 0;
    while (($fileData = fgetcsv($file, ",")) !== FALSE){
        $numberOfRows++;
        if($numberOfRows > 1){
            $app_name = $db->quote($fileData[0]);
            $client_name = $db->quote($fileData[1]); 
            $title_en = $db->quote($fileData[2]); 
            $title_ar = $db->quote($fileData[3]);
            $code = $db->quote($fileData[4]);
            $status = $db->quote(strtolower($fileData[5])); 
            $tyoe = $db->quote($fileData[6]);
            $discount = $db->quote($fileData[7]);
            $category = $db->quote($fileData[8]);
            $tag = $db->quote($fileData[9]);
            $sqlQuery = $db -> query("call `coupons_add`(".$app_name.",".$client_name.",".$title_en.",".$title_ar.",".$code.",".$status.",
            ".$tyoe.",".$discount.",".$category.",".$tag.",".$db->quote($numberOfRows).")");
            if($sqlQuery === false)
            {
                $errors[] =  $db->error();
                $failedRows++;
            }else{
                $successfulRows++;
            }  
        }
        
    }
    fclose($file); 
    unlink($filePath);
    
    
    $output_josn['result'] = "success";
    $output_josn["msg"] = "Coupons processed";
    $output_josn['errors'] = $errors;
    $output_josn['totalRows'] = --$numberOfRows;
	$output_josn['successfulRows'] = $successfulRows;
    $output_josn['failedRows'] = $failedRows;
	
	if(sizeof($data)==0)
    {
        $output_josn['data'] =null;
    }else{
        $output_josn['data'] = $data;
    }
	
    header('Content-type: application/json');
    echo json_encode( $output_josn );
}
?>
