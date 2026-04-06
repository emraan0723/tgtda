<?php
/*
 * Created By BV
 */
class FileHandling{
public function __construct(){

}
//Reading XML file into array
public function readXMLFile($file, $sheet_columns){
		if($file['uploaded_file']['name']!="")
		{
				$max_file_size = 2097152;
				$file_size = $_FILES['uploaded_file']['size'];
				$allowedExtensions = array("xml");
				if(!in_array(end(explode(".", strtolower($file['uploaded_file']['name']))), $allowedExtensions)) 
				{
					return 'Invalid file. Please upload XML file. Please save your excel file to xml using 2003 XML spread sheet format';
				}
				else if($file_size >= $max_file_size)
				{
					return 'The file Size Exceeds the Prescribed Limit of : 2097152 Bytes (2 MB)! <br/> The Current File Size is : '.$file['uploaded_file']['size']." Bytes.";
				}
			
				$dom = DOMDocument::load($file['uploaded_file']['tmp_name']);
				$worksheets = $dom->getElementsByTagName('Worksheet');
				$records = array();
				foreach($worksheets as $worksheet)
				{
					$rows = $worksheet->getElementsByTagName('Row');
					$column_count = $this->getColumnCount($rows);
					if($column_count!=$sheet_columns){
						return 'Sheet does not have sufficient columns.';
					}
					$isFirstRow = true;
					
					foreach($rows as $row){
						$isEmptyRow = false;
						if(!$isFirstRow){
							$inner_record = array();
							$cells = $row->getElementsByTagName( 'Cell' );
							foreach( $cells as $cell )
							{
								$inner_record[]=trim($cell->nodeValue);
							}
							$records[] = $inner_record;
						}
						$isFirstRow = false;
					}
				}
        
				return $records;
		}else{
		
				return 'Please upload the file.';
		}

}

//Reading XML file into  an array with index
public function readXMLFileWithIndex($file, $sheet_columns){
		if($file['uploaded_file']['name']!="")
		{
				$max_file_size = 2097152;
				$file_size = $_FILES['uploaded_file']['size'];
				$allowedExtensions = array("xml");
				if(!in_array(end(explode(".", strtolower($file['uploaded_file']['name']))), $allowedExtensions)) 
				{
					return 'Invalid file. Please upload XML file. Please save your excel file to xml using 2003 XML spread sheet format';
				}
				else if($file_size >= $max_file_size)
				{
					return 'The file Size Exceeds the Prescribed Limit of : 2097152 Bytes (2 MB)! <br/> The Current File Size is : '.$file['uploaded_file']['size']." Bytes.";
				}
			
				//$dom = DOMDocument::load($file['uploaded_file']['tmp_name']);
				$dom=new DOMDocument();
				$dom->load($file['uploaded_file']['tmp_name']);
				$worksheets = $dom->getElementsByTagName('Worksheet');
				$records = array();
			
				foreach($worksheets as $worksheet)
				{
					$rows = $worksheet->getElementsByTagName('Row');
					$isFirstRow = true;
									
					foreach($rows as $row){
                                           
						$index =0;
                                                $i = 0;
						$isEmptyRow = false;
						if(!$isFirstRow){
							$inner_record = array();
							$cells = $row->getElementsByTagName( 'Cell' );
                                                       
							foreach( $cells as $cell )
							{   
								 $ind = $cell->getAttribute( 'Index' );
								 $indb = $cell->getAttribute( 'ss:Index' );
								if ( $ind != null ) $index = $ind;
								if ( $indb != null ) $index = $indb;
								$inner_record[$i]=$cell->nodeValue;
								$index++;	
                                $i++;
                                                                
                            }
							
							$records[] = $inner_record;
						}
						$isFirstRow = false;
					}
				}
        
				return $records;
		}else{
		
				return 'Please upload the file.';
		}

}


//Reading CSV file into array
public function readCSVFile($file, $sheet_columns){
		if($file['file']['name']!="")
		{
				$max_file_size = 2097152;
				$file_size = $_FILES['file']['size'];
				$allowedExtensions = array("csv");
				
				
				if(!in_array(end(explode(".", strtolower($file['file']['name']))), $allowedExtensions)) 
				{
					
					return 'Invalid file. Please upload CSV file.';
				}
				else if($file_size >= $max_file_size)
				{
					return 'The file Size Exceeds the Prescribed Limit of : 2097152 Bytes (2 MB)! <br/> The Current File Size is : '.$file['file']['size']." Bytes.";
				}
				
				$x = 0;
				$file = $_FILES['file']['tmp_name'];
				$handle = fopen($file,"r");
				$records = array();
				
		
                                while(! feof($handle))
                                {
                                        $records[$x] = fgetcsv($handle);
                                        $column_count = count($records[$x]);
                                        if($column_count != $sheet_columns && $x == 0){
                                                return 'Sheet does not have sufficient columns.';
                                        }
                                        $x = $x+1;
                                }
                                unset($records[0]);

								if(count($records) > 1){
									array_pop($records);
								}else{
									 return 'No data found.';
								}
                                return $records;
		}else{
		
				return 'Please upload the file.';
		}

}

//Get column count
function getColumnCount($rows){

    foreach($rows as $row){
        $cells = $row->getElementsByTagName('Cell');
        $i=0;
        foreach($cells as $cell){$i++;}
        return $i;
    }
}



public function csvFileReading($file,$path='',$max_file_size='',$file_name='uploaded_file'){

	
    
	if($file[$file_name]['name']!="")
	{
		if($max_file_size!=''){
			$max_file_size = $max_file_size;
			$msg = $max_file_size." Bytes";	
		}else{
			$max_file_size = 2097152;	
			$msg = "2097152 Bytes (2 MB)";	
		}		
		$file_size = $_FILES[$file_name]['size'];
		$allowedExtensions = array("xlsx");
		$tmp = explode('.', $file[$file_name]['name']);
        $file_extension = end($tmp);



		if(!in_array($file_extension, $allowedExtensions)) {
			return 'Invalid file. Please upload CSV file.';
		}
		else if($file_size >= $max_file_size) {
			return 'The file Size Exceeds the Prescribed Limit of : '.$msg.'! <br/> The Current File Size is : '.$file['uploaded_file']['size']." Bytes.";
		}
		
		$x = 0;
		if($path == ''){
		$file = $_FILES[$file_name]['tmp_name'];
		}
		else
		{
			$file = $path;
		}
		$handle = fopen($file,"r");
		$csvfile = array();
		//$header = NULL;
		$header = fgetcsv($handle, 1000, ",");
		//print_r($header);die;
		while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) 
		{
			//if(!$header)
			//$header = $row;
			//else
			$csvfile[] = array_combine($header, $row);
		}
		return $csvfile;
	}else{
			return 'Please upload the file.';
	}
}

//function for csv reading from file path by BV 
public function csvFileReadingFromFilePath($file_path){
	$handle = fopen($file_path,"r");
	$csvfile = array();
	$header = fgetcsv($handle, 1000, ",");
	while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) 
	{
		$csvfile[] = array_combine($header, $row);
	}
	return $csvfile;
}

}