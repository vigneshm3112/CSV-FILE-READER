<?php
 //table Name
$tableName = "MyTable";
//database name
$dbName = "MyDatabase";


 $conn = mysql_connect("localhost", "root", "") or die(mysql_error()); 
 mysql_select_db($dbName) or die(mysql_error()); 

//get the first row fields 
$fields = "";
$fieldsInsert = "";
if (($handle = fopen("test.csv", "r")) !== FALSE) {
    if(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $fieldsInsert .= '(';
        for ($c=0; $c < $num; $c++) {
            $fieldsInsert .=($c==0) ? '' : ', ';
            $fieldsInsert .="`".$data[$c]."`";
            $fields .="`".$data[$c]."` varchar(500) DEFAULT NULL,";
        }

        $fieldsInsert .= ')';
    }


    //drop table if exist
    if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$tableName."'"))>=1) {
      mysql_query('DROP TABLE IF EXISTS `'.$tableName.'`') or die(mysql_error());
    }

    //create table
    $sql = "CREATE TABLE `".$tableName."` (
              `".$tableName."Id` int(100) unsigned NOT NULL AUTO_INCREMENT,
              ".$fields."
              PRIMARY KEY (`".$tableName."Id`)
            ) ";

    $retval = mysql_query( $sql, $conn );

    if(! $retval )
    {
      die('Could not create table: ' . mysql_error());
    }
    else {
        while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $num = count($data);
                $fieldsInsertvalues="";
                //get field values of each row
                for ($c=0; $c < $num; $c++) {
                    $fieldsInsertvalues .=($c==0) ? '(' : ', ';
                    $fieldsInsertvalues .="'".$data[$c]."'";
                }
                $fieldsInsertvalues .= ')';
                //insert the values to table
                $sql = "INSERT INTO ".$tableName." ".$fieldsInsert."  VALUES  ".$fieldsInsertvalues;
                mysql_query($sql,$conn);    
        }
        echo 'Table Created';   
    }

    fclose($handle);

}

?>