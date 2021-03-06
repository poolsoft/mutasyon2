<?php
class DB
{
    /*** EG Insert or UPDATE
        $table = 'customer';
     	$values = array(
     		'id_customer' => 1,
     		'customer_name' => "Eyl�l BENEK",
     		'customer_britday' => "15.09.2014",
     		'customer_active' => 1
     	);
     	$insert = $dbase->insert($table, $values );
     **/
    
    /*** INSER INTO database  **/
    public static function insert($table,  $values) {
        global $db;
        $prep = array();
        foreach($values as $k => $v ) {
            $prep[':'.$k] = $v;
        }
        $sth = $db->prepare("INSERT INTO ".$table." ( " . implode(', ',array_keys($values)) . ") VALUES (" . implode(', ',array_keys($prep)) . ")");
        $res = $sth->execute($prep);
        return $db->lastInsertId();
    }
    
    /*** UPDATE database **/
    public static function update($table,  $values, $condition) {
        global $db;
        $prep = array();
        foreach($values as $k => $v ) {
            $prep[$k.' = :'.$k] = $v;
        }
        
        $sth = $db->prepare("UPDATE ".$table." SET ".  implode(', ',array_keys($prep)) ."  WHERE ".$condition."");
        $res = $sth->execute($values);
    }
    
    /** Update one row Value eg: $dbase->updateOneRow('product', 'product_name = "New Name" ', 'id_product = 8') **/
    public static function updateOneRow($table,  $row, $condition) {
        global $db;
        $updateOneRow = $db->prepare("UPDATE ".$table." SET ".  $row ."  WHERE ".$condition."");
        $res = $updateOneRow->execute();
    }
    
    /** DELETE Value eg: $dbase->delete('product', 'id_product = 8') **/
    public static function delete($table,  $condition) {
        global $db;
        $deleteRow = $db->query('DELETE FROM '.$table.' WHERE '.$condition.' LIMIT 1', PDO::FETCH_ASSOC);
        return $deleteRow;
    }
    
    /** GET eg: $dbase->get('*', 'customer', 'id_customer = 1') **/
    public static function get($which, $table,  $condition) {
        global $db;
        $gettable = $db->query('SELECT '.$which.' FROM '.$table.' WHERE '.$condition.'', PDO::FETCH_ASSOC);
        return $gettable;
    }
    
    /** GET CONFIGS eg: $dbase->getAdminInf('Config name') ***/
    public static function getAdminInf($value)
    {
        global $db;
        $value = Check::clearCode($value);
        $asd = $db->query('SELECT * FROM superuser WHERE superuser_active = 1 AND superuser_email = "'.@$_SESSION ["email"].'" LIMIT 1');
        foreach ($asd as $cn)
        {
            return $cn[$value];
        }
    }
    
    /** Is exist eg: $dbase->isExist('customer', 'id_customer = 1') **/
    public static function isExist($table,  $condition) {
        global $db;
        $gettable = $db->query('SELECT COUNT( * ) AS result FROM '.$table.' WHERE '.$condition.'', PDO::FETCH_ASSOC);
        foreach($gettable as $g){
            $result = $g['result'];
        }
        if($result > 0){
            return 1;
        }
        else{
            return 0;
        }
    }
    
    /** GET ROW eg: $dbase->getRow('product', 'id_product = 12', 'product_name') **/
    public static function getRow($table,  $condition, $value) {
        global $db;
        $getRow = $db->query('SELECT * FROM '.$table.' WHERE '.$condition.' LIMIT 1', PDO::FETCH_ASSOC);
        foreach ($getRow as $row)
        {
            return $row[$value];
        }
    }
    
    
    /** GET CONFIGS eg: $dbase->config('Config name') ***/
    public static function config($value) {
        global $db;
        $value = Check::clearCode($value);
        $asd = $db->query('SELECT * FROM configs WHERE name = "'.$value.'" LIMIT 1');
        foreach ($asd as $cn)
        {
            return $cn['value'];
        }
    }
}