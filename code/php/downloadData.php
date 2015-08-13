<?php

  // TODO: only download data for sites that the current user has permissions for
  // TODO: convert json to csv

  $project_name = "";
  if (!isset($_GET['project_name'])) {
    echo("Error: require project_name");
    return;
  } else {
    $project_name = $_GET['project_name'];
  }
  
  if (!extension_loaded('zip')) {
    dl('zip.so');
  }

  $dest = 'project_'.$project_name.'.zip';
  $zip = new ZipArchive();
  if ($zip->open($dest, ZIPARCHIVE::OVERWRITE) !== true) {
    echo("ERROR: could not create zip file");
    return;
  }
  $zip->addFile('../../data/'.$project_name.'/data.json');
  $zip->addFile('../../data/'.$project_name.'/datadictionary.json');
  $zip->close();
  echo('/code/php/'.$dest);
		
?>
