<?php

 date_default_timezone_set('America/Los_Angeles');
 ini_set("memory_limit","128M");

 session_start(); /// initialize session
 include("AC.php");
 $user_name = check_logged(); /// function checks if visitor is logged.
 if (!$user_name)
    return; // do nothing

 $_file = "../table.json";

 if (isset($_GET['action'])) {
     $action = $_GET['action'];
     if ($action == 'create')  {
         if (!isset($_GET['project-name'])) {
             echo("Error: no project name");
             return;
         }
         if (!isset($_GET['project-description'])) {
             echo("Error: no project description");
             return;
         }
         if (!isset($_GET['project-webpage'])) {
             echo("Error: no project website");
             return;
         }
         if (!isset($_GET['project-sites'])) {
             echo("Error: no sites for this project");
             return;
         }
         $pn = $_GET['project-name'];
         $pd = $_GET['project-description'];
         $pw = $_GET['project-webpage'];
         $ps = json_decode($_GET['project-sites'], TRUE);
         
         $projects = json_decode( file_get_contents($_file), true);
         foreach ($projects as $project) {
             if ($project['name'] == $pn) {
                 echo("Error: project already exists");
                 return;
             }
         }
         $projects[] = array('name' => $pn, 'description' => $pd, 'webpage' => $pw, 'sites' => $ps);
         file_put_contents($_file, json_encode($projects, JSON_PRETTY_PRINT));
         
         return;
     }
 }

 $projects = json_decode( file_get_contents($_file), true);
 $allowedProjects = array();
 foreach( $projects as $project ) {
    if ($user_name == "admin" || check_permission( $project['name'] )) {
       $allowedProjects[] = $project;
    }
 }
 echo json_encode( $allowedProjects );
 return;

?>