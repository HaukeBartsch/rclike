<?php

  $project_name = "";
  $action = "read";
  $value = "";

  if (!isset($_GET['project_name'])) {
	  echo("Error: need a project_name");
	  return;
  } else {
	  $project_name = $_GET['project_name'];
  }
  if (isset($_GET['action'])) {
	  $action = $_GET['action'];
  }
  if (isset($_GET['value'])) { // should get an object which is stringified
    $value = json_decode($_GET['value'], TRUE);
  }
  

  $file = '../../data/'.$project_name.'/datadictionary.json';
  if ( !is_readable( $file )) {
      // lets create an empty database
	  mkdir('../../data/'.$project_name);
	  file_put_contents($file, "[]");
	  //echo("database ".$file. " is not readable");
	  //return;
  }
	
  function saveDataDictionaryDB( $d ) {
     global $file;

     // parse permissions
     if (!file_exists($file)) {
        echo ('error: permission file does not exist');
        return;
     }
     if (!is_writable($file)) {
        echo ('Error: cannot write permissions file ('.$file.')');
        return;
     }
     // be more careful here, we need to write first to a new file, make sure that this
     // works and copy the result over to the pw_file
     $testfn = $file . '_test';
     file_put_contents($testfn, json_encode($d, JSON_PRETTY_PRINT));
     if (filesize($testfn) > 0) {
        // seems to have worked, now rename this file to pw_file
   	    rename($testfn, $file);
     } else {
        syslog(LOG_EMERG, 'ERROR: could not write file into '.$testfn);
     }
  }

  
  $data = json_decode(file_get_contents($file), TRUE);
  
  if ($action == "add" ) {
    if ($value == "") {
      echo("Error: nothing to save");
      return;
    }
    $data[] = $value;
    echo("add some data to the data dictionary");
    saveDataDictionaryDB( $data );
    //file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
  
    return;  
  } else if ($action == "change" ) {
    if ($value == "") {
      echo ("Error: nothing to change");
      return;
    }
    //echo('value to change is:'.$_GET['value']);
    $listofchanged = [];
    foreach ( $value['data'] as $val) {
      if ($val[0] === null) {
        // we are adding a new row, what is the next index?
        $val[0] = count($data);
      }
      $listofchanged[] = $val[0];
      $data[$val[0]][$val[1]] = $val[3];
      if (!array_key_exists( 'required', $data[$val[0]]))
         $data[$val[0]]['required'] = false;
    }
    saveDataDictionaryDB( $data );
    //file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    echo(json_encode($listofchanged));
    return;
  } else if ($action == "removeRow" ) { // just add nothing else
    if ($value === "") {
      echo ("Error: nothing to change");
      return;
    }
    $value = intval($value);
    echo("value to remove was:".$value);
    array_splice($data, $value, 1);
    //unset($data[$value]);
    saveDataDictionaryDB( $data );
    //file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));   
    return;
  }

    
  // default is reading the file and return the content
  echo(json_encode($data, JSON_PRETTY_PRINT));
  return;
	
?>