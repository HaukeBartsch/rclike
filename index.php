<?php
  // has to be the first thing in this file
  session_start();
  include("code/php/AC.php");
  $user_name = check_logged(); /// function checks if visitor is logged.
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>RC-like data collections</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Hauke Bartsch">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/handsontable.min.css" rel="stylesheet">
    <link href="css/text.css" rel="stylesheet">
        
<?php
  if (isset($_SESSION['project_name'])) {
     echo('<script type="text/javascript"> project_name = "'.$_SESSION['project_name'].'"; </script>'."\n");
  }

  echo('<script type="text/javascript"> user_name = "'.$user_name.'"; </script>'."\n");
  // print out all the permissions
  $permissions = list_permissions_for_user($user_name);
  $p = '<script type="text/javascript"> permissions = [';
  foreach($permissions as $perm) {
    $p = $p."\"".$perm."\",";
  }
  echo ($p."]; </script>\n");

  $admin = false;
  if (check_role( "admin" )) {
     $admin = true;
  }
  $can_qc = false;
  if (check_permission( "can-qc" )) {
     $can_qc = true;
  }
  echo('<script type="text/javascript"> admin = '.($admin?"true":"false").'; can_qc = '.($can_qc?"true":"false").'; </script>');
?>

    <style>


    </style>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
  </head>
  <body>
    <div id="change-password-box" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title">Change Password</h4>  
          </div>
          <div class="modal-body">
            <input type="password" id="password-field1" placeholder="*******" autofocus><br/>
            <input type="password" id="password-field2" placeholder="type again">
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="changePassword();">Submit</button>
          </div>
        </div>
      </div>
    </div>

    <div id="about-box-info" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title">About</h4>  
          </div>
          <div class="modal-body">
            <p>A simple system to capture study information</p>
            <p>Hauke Bartsch, Dr. rer. nat.</p>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div id="new-project" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4 class="modal-title">Create a new project</h4>  
          </div>
          <div class="modal-body">
             <form>
               <div class="form-group">
                 <label for="new-project-name">Project name</label>
                 <input type="text" class="form-control" id="new-project-name" placeholder="MYPROJECT">  
               </div>  
               <div class="form-group">
                 <label for="new-project-description">Project Description</label>
                 <input type="text" class="form-control" id="new-project-description" placeholder="Describe your projects goals...">  
               </div>
               <div class="form-group">
                 <label for="new-project-webpage">Project Webpage</label>
                 <input type="text" class="form-control" id="new-project-webpage" placeholder="http://myproject.webpage.edu/">  
               </div>
               <div class="form-group">
                 <label for="new-project-list-of-sites">List of sites</label>
                 <input type="text" class="form-control" id="new-project-list-of-sites" placeholder="MySiteA, MySiteB">  
               </div>
             </form>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary">Close</button>
            <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="saveProject();">Submit</button>
          </div>
        </div>
      </div>
    </div>

    <div id="contact-box-info" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
             <h4> Contacts </h4>
          </div>
          <div class="modal-body">
            <p>A small demo for data import using editable tables and csv data upload via drag&drop. Data is stored
              as json on the system.
            </p>
          </div>
          <div class="modal-footer">
             <button type="button" data-dismiss="modal" class="btn btn-primary">Close</button>
          </div>
        </div>
      </div>
    </div>

  
  <header id="top" class="navbar navbar-default navbar-fixed-top bs-docs-nav" role="banner">
    <div class="container-fluid">
      <div class="navbar-header">
        <button class="navbar-toggle collapsed" aria-expanded="false" aria-controls="bs-navbar" data-target="#bs-navbar" data-toggle="collapse" type="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" data-toggle="modal" href="#about-box-info">RC-like  </a>
      </div>
      <nav id="bs-navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="dropdown">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown">Project: <span class="current-project">unknown</span><b class="caret"></b></a>
                   <ul class="dropdown-menu" id="swatch-menu">
                   </ul>
          </li>
 <!--         <li id="about-box"><a data-toggle="modal" href="#about-box-info">About</a></li> -->
      		<li><a id="download-data" title="Download a zip file with both the data and the data dictionary">Download</a></li>
      	<!-- 	<li><a id="upload-data" title="Upload both the data and the data dictionary">Upload</a></li> -->
      		<li><a href="#contact-box-info" data-toggle="modal">About</a></li>
          <!-- <li id="current_user_name"></li> -->
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
             <a href="#" class="dropdown-toggle" data-toggle="dropdown">User: <span class="current_user">unknown</span><b class="caret"></b></a>
             <ul class="dropdown-menu" id="user-menu">
               
 <?php if ($admin != "") : ?>
                         <li><a href="#new-project" data-toggle="modal">add a new project</a></li>
                         <li><a href="/applications/User/admin.php">user administration</a></li>
 <?php endif; ?>
                         <li><a href="#change-password-box" data-toggle="modal">change password</a></li>
                         <li><a href="#" onclick="logout();">logout</a></li>
             </ul>
          </li>
         </ul>
      </nav>
     </div>
   </header>

   <div class="container-fluid" style="margin-top: 50px;">
     <div class="row-fluid row-green">
       <h3>IMPORTER <small>upload your spreadsheets</small></h3>       
          <div id="drop_zone">Drop comma-separated (.csv) files</div>
          <output id="list"></output>
          <div id="imported" style="display: none;"></div>
          <div id="import-messages" style="display: none;"></div>
          <div id="import-options" style="display: none;">
            <div class="btn-group" role="group" aria-label="...">
              <div class="btn-group" role="group">
                <button type="button" id="import-data-button" class="btn btn-info">Import Data</button>
              </div>
            </div>
          </div>
     </div>
              
     <div class="row-fluid row-yellow">
       <h3>STORE <small>data</small></h3>
       <div id="data"></div>
     </div>

     <div class="row-fluid row-blue">
       <h3>DESIGNER</h3>
       <p>The data dictionary describes each possible entry in the data. The different entries are grouped by the source area.
       </p>
       <div id="datadictionary"></div>
       
     </div>
     

     <div class="row-fluid">
      <div class="footer">
        <hr>
	<p>&copy; 2015 Hauke Bartsch,<!-- <a href="#">Privacy</a> &middot;--> <a href="#" data-toggle="modal" data-target="#legal">Terms</a></p>
      </div>
     </div>
    </div><!-- /.container -->

<?php
  include 'legal.php';
?>

    <!-- Le javascript
	 ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script src="js/jquery.csv-0.71.min.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/1.4.5/numeral.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/pikaday.js"></script>
    <script src="js/ZeroClipboard.min.js"></script>
    <script src="js/handsontable.min.js"></script>
    <script src="js/md5-min.js"></script>
    <script src="js/legal.js"></script>

<script>
  function handleFileSelect(evt) {
    evt.stopPropagation();
    evt.preventDefault();

    var files = evt.dataTransfer.files; // FileList object.

    // files is a FileList of File objects. List some properties.
    var output = [];
    for (var i = 0, f; f = files[i]; i++) {
      output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                  f.size, ' bytes, last modified: ',
                  f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                  '</li>');
                  
      // Only process csv files.
      if (!f.type.match('text/csv')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // parse the csv string
          console.log(e.target.result);
          var data;
          try {
            data = jQuery.csv.toObjects(e.target.result);
          } catch(err) {
            alert("Error during upload. Make sure to save your spreadsheet using windows line endings");
          };
          console.log(data);
          // here we should add annotations to data. This way we can capture the messages and forward them to displayImportedData
          [messages, annotations] = checkImportedData(data, datadictionary);
          jQuery('#import-messages').children().remove();
          jQuery('#import-messages').text('');
          if (messages.length > 0) {
             jQuery('#import-messages').append(messages.join("<br>"));
             jQuery('#import-messages').css('display', '');
          }
          jQuery('#import-options').css('display', '');
          jQuery('#imported').css('display', '');
          displayImportedData(data, annotations);
/*          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
          document.getElementById('list').insertBefore(span, null); */
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsBinaryString(f);
    }
    document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
  }

  function handleDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
  }

  // Setup the dnd listeners.
  var dropZone = document.getElementById('drop_zone');
  dropZone.addEventListener('dragover', handleDragOver, false);
  dropZone.addEventListener('drop', handleFileSelect, false);
</script>       



    <script type="text/javascript">
      var projects = [];
      var datadictionary = [];
      var importedTable = null;
      var storeTable = null;

      jQuery(document).ready(function() {
        checkLegal(); // should display dialog with legal information

        jQuery('#download-data').click(function(e) {
           e.preventDefault();
           jQuery.get('/code/php/downloadData.php?project_name='+project_name, function(data) {
              window.location.assign(data);
           });
        });

        loadProjects(function() {});

        jQuery('.current_user').text(user_name);
        jQuery('#import-data-button').click(function() { importData(); });
        jQuery('#import-datadictionay-button').click(function() { importDataDictionary(); });

      });

      function loadProjects( after ) { // call the function after after you are done
          jQuery.getJSON('/code/php/getProjectInfo.php', function(data) {
            projects = data;
            if (projects.length == 0) {
              console.log('no project for this user yet');
              // create a new project, if there is no project yet
              jQuery('#new-project').modal({open: true});
              return;
            }
            use_this_project = 0;
 
            jQuery('#swatch-menu').find('a').each(function() {
              if (jQuery(this).attr('type') === "project")
                jQuery(this).parent().remove();
            });
            for (var i = 0; i < data.length; i++) {
              if (admin == true || jQuery.inArray(data[i].name, permissions) != -1) { // only show projects that you are allowed to see
                jQuery('#swatch-menu').append("<li><a type='project' href='#' onclick='switchProject(\"" + i + "\");'>" + data[i].name + "</a></li>");
              }
            }
            if (projects.length > 0) {
              if (typeof project_name == "undefined") {
                switchProject(use_this_project); // use first project by default
              } else {
                // a project is known, find it and make it the current
                for (var i = 0; i < projects.length; i++) {
                  if (projects[i].name == project_name) {
                    switchProject(i);
                    break;
                  }
                }
              }
            } else {
              console.log("Error: no project for the current user");
            }
            after();
          });
      }

      
      // import the data from the currently display import data table
      function importData() {
        if (importedTable === null) {
          return; // nothing to do
        }
        
        // get the data from the table
        var data  = importedTable.getData();
        var data2 = storeTable.getData();

        // the first row of importedTable contains the real headers of the data, we need to name
        // every input column according to the potentially changed header
        var tableHeader = Object.keys(data[0]);
        for (var i = 0; i < tableHeader.length; i++) {
          if ( i > 0 && tableHeader[i] != data[0][tableHeader[i]]) {
            // the user changed this entry, go through each data entry and apply this change
            for ( var j = 1; j < data.length; j++) {
              data[j][ data[0][ tableHeader[i] ] ] = data[j][tableHeader[i]];
              delete data[j][tableHeader[i]];
            }
          }
        }
        
        // we need to match rows by index -- find out what the index rows are
        var indexFields = [];
        for (var i = 0; i < datadictionary.length; i++) {
          if (datadictionary[i].type == 'index') {
            indexFields.push(datadictionary[i].short);
          }
        }
        if (indexFields.length == 0) {
          alert("Error: No rows of type index found in the dictionary, don't know how to merge rows!");
          return;
        }
        // a field is the same if all the index variables are the same
        for (var i = 1; i < data.length; i++) { // ignore the header
          var indexVal = [];
          for (var j = 0; j < indexFields.length; j++) {
            indexVal.push(data[i][indexFields[j]]);
          }
          // check all
          var isSame = false;
          var isSameIdx = -1;
          for (var j = 0; j < data2.length; j++) {
             var allSame = true;
             for (var k = 0; k < indexVal.length; k++) {
                if (indexVal[k] !== data2[j][indexFields[k]]) {
                   allSame = false;
                }
             }
             if (allSame) { // found the correct entry 
                isSame = true;
                isSameIdx = data2[j].id; // row in the original spreadsheet
                break;
             }
          }
          // now if isSame is true in isSameIdx we have the index of the existing row
          if (isSame) { // we have this subject already, just add to its entries
            var requests = [];
            var obj = Object.keys(data[i]);
            for (var j = 0; j < obj.length; j++) {
              requests.push([isSameIdx,obj[j],,data[i][obj[j]]]);
            }
            jQuery.get('/code/php/data.php?project_name=' + project_name + '&action=change&value='+JSON.stringify({ data: requests }), function(data) {
               console.log("tried to add data, got: " + data);
            });            
          } else { // this is a new row, send it as one
            if (Object.keys(data[i]).indexOf('RC_SITE') == -1) {
              data[i]['RC_SITE'] = projects[current_project].sites[0];
            }
            jQuery.get('/code/php/data.php?project_name=' + project_name + '&action=changeRows&value='+JSON.stringify({ data: data[i] }), function(data) {
               console.log("tried to add data, got: " + data);
            });            
          }
        }
      }
      function importDataDictionary() {
        
      }

      
      // store the project information
      function saveProject() {
          var pn = jQuery('#new-project-name').val();
          var pd = jQuery('#new-project-description').val();
          var pw = jQuery('#new-project-webpage').val();
          var pl = jQuery('#new-project-list-of-sites').val();
          var listofsites = pl.split(",");
          listofsites = listofsites.map( function(a) { return a.trim(); });
          if (listofsites.length == 0) {
            alert("Error: the list of sites cannot be empty, add at least one site");
            return;
          }
          
          jQuery.get('/code/php/getProjectInfo.php?action=create&project-name='+pn
                     +'&project-description='+pd+'&project-webpage='+pw+'&project-sites='+JSON.stringify(listofsites), function(data) {
            if (data.length > 0)
               alert(data);
            else {
               // add a new permission for this project
               jQuery.getJSON('/code/php/getPermissions.php?action=create&value='+pn, function(data) {
                  // add that permission to the admin user                                 
                  jQuery.getJSON('/code/php/getRoles.php?action=addPermission&value='+pn+'&value2=admin', function(data) {
                    console.log("add permission and permission to admin role");
                  });
               });
               // add permissions for each site as well, add them to the current user also 
               //var listofsites = pl.split(",");
               //listofsites = listofsites.map( function(a) { return a.trim(); });
               // add a role for this project
               jQuery.getJSON('/code/php/getRoles.php?action=create&value=Role'+pn, function(data) {
                 for ( var i = 0; i < listofsites.length; i++ ) {

                    var f = (function(listofsites) {
                       var site = listofsites[i];
                       return function(data) {
                         jQuery.get('/code/php/getPermissions.php?action=create&value='+site, function(data) {
                         });
                       };
                    })(listofsites);

                    // we want to first create the permission and add it to the role afterwards
                    console.log("create permission: " + listofsites[i])
                    jQuery.get('/code/php/getRoles.php?action=addPermission&value='+listofsites[i]+'&value2=Role'+pn, f(data));
                    
                 }  
                 // after we are done lets load the new project
                 loadProjects( function() { switchProject( pn ); } );
               });
            }
          });
      }
      
      function roundNumber(number, digits) {
         var multiple = Math.pow(10, digits);
         var rndedNum = Math.round(number * multiple) / multiple;
         return rndedNum;
      }
      
      // return a list of messages, each stating what is wrong with the data
      // also adds annotations to each data entry for color coding errors found
      function checkImportedData( data, datadictionary) {
        // lets check each entry of both the data and the datadictionary
        // we can give the user resolvers that add data dictionary entries later
        
        // All variables in the data dictionary?
        var messages = [];
        var annotations = [];
        var dictEntries = [];
        for ( var i = 0; i < datadictionary.length; i++ ) {
          dictEntries[datadictionary[i].short] = 1;
        }
        dictEntries = Object.keys(dictEntries);
        listOfMissingEntries = {};
        listOfFoundEntries = {};
        for (var i = 0; i < data.length; i++) {
          var ok = Object.keys(data[i]);
          for (var j = 0; j < ok.length; j++) {
            if ( dictEntries.indexOf(ok[j]) == -1 ) {
              listOfMissingEntries[ok[j]] = 1;
              annotations.push( [i, ok[j], "unknown entry "+ok[j]] );
            } else {
              listOfFoundEntries[ok[j]] = 1;
            }
          }
        }
        listOfMissingEntries = Object.keys(listOfMissingEntries);
        listOfFoundEntries = Object.keys(listOfFoundEntries);
        if ( listOfMissingEntries.length > 0 ) {
            messages.push(listOfMissingEntries.length + " missing and "+ listOfFoundEntries.length +" found entries in the data dictionary. Unknown entries: [" +
              listOfMissingEntries.join(", ") + "]");
        }
        
        
        var indexVars = {};
        for (var i = 0; i < datadictionary.length; i++) {
          if (datadictionary[i].type == "index") {
            indexVars[datadictionary[i].short] = 1;
          }
        }
        indexVars = Object.keys(indexVars);

        // first check if we have all index variables in the spreadsheet
        columnMissing = [];
        colmissing = false;
        for (var j = 0; j < indexVars.length; j++) {
          if (typeof data[0][indexVars[j]] == 'undefined') {
            columnMissing.push(indexVars[j]);
          }
        }
        if (columnMissing.length > 0) {
          colmissing = true;
          messages.push("Index columns are missing for: " + columnMissing.join(", "));
        }
        
        if (!colmissing) {        
          rowsMissingIndex = [];
          // check for missing index variables - they do need to be there!
          for (var i = 0; i < data.length; i++) {
            for (var j = 0; j < indexVars.length; j++) {
              if (data[i][indexVars[j]] === "") {
                rowsMissingIndex.push(i);
                annotations.push( [i, indexVars[j], "missing index value"] );
                break;
              }
            }
          }
          if (rowsMissingIndex.length > 0)
             messages.push("Found rows with missing index variables: " + rowsMissingIndex.join(", "));
        }
        // check if all required entries are present - if we import partial data required entries missing might be
        // ok. In that case we only need to merge by SubjID and VisitID and check for after the merge. 
        requiredMissing = [];
        colmissing = false;
        for (var j = 0; j < indexVars.length; j++) {
          if (typeof data[0][indexVars[j]] == 'undefined') {
            requiredMissing.push(indexVars[j]);
          }
        }
        if (requiredMissing.length > 0) {
          colmissing = true;
          messages.push("Required columns are missing for: " + requiredMissing.join(", "));
        }
        
        if (!colmissing) {        
          rowsMissingIndex = [];
          // check for missing index variables - they do need to be there!
          for (var i = 0; i < data.length; i++) {
            for (var j = 0; j < indexVars.length; j++) {
              if (data[i][indexVars[j]] === "") {
                rowsMissingIndex.push(i);
                break;
              }
            }
          }
          if (rowsMissingIndex.length > 0)
             messages.push("Found rows with missing required variables: " + rowsMissingIndex.join(", "));
        }




        
        
        
        // What about data from one site that are identical to data from another (same SubjID and VisitID)?
        // SubjID and VisitID should be unique together - or not?
        
        // check if the import would overwrite any previous entries
        
        
        return [ messages, annotations ];
      }
      
      function displayImportedData( data, annotations ) {
           jQuery('#imported').children().remove();
           jQuery('#imported').css('height', '200px');
           var d = jQuery("<div id='imported"+0+"' style='margin-top: 10px;'></div>");
           jQuery('#imported').append(d);
             
           // colHeaders is from data and from data-dictionary
           var colHeaders = {};
           for (var i = 0; i < data.length; i++) {
               data[i].id = i; // add the number as entry id into the returned list
               var k = Object.keys(data[i]);
               for (var j = 0; j < k.length; j++) {
                 colHeaders[k[j]] = 1;
               }
           }

           colHeaders = Object.keys(colHeaders); // we should sort these based on source
           // get id to be the very first element
           var idIdx = colHeaders.indexOf('id');
           var ch;
           if (idIdx != -1) {
             ch = colHeaders.slice(0, idIdx);
             ch = ch.concat(colHeaders.slice(idIdx+1));
           } else {
             ch = colHeaders;
           }
           ch.unshift('id');
           colHeaders = ch;
           
           // add now the headers as a first row, this way we can edit the information to match the data dictionary
           var fr = { "id": "" };
           for (var i = 1; i < colHeaders.length; i++) {
             fr[colHeaders[i]] = colHeaders[i];
           }
           data.unshift(fr);
           
           // now create the columns array
           var columns = [];
           for ( var i = 0; i < colHeaders.length; i++) {
              var o = {};
              o.data = colHeaders[i];
              o.type = "text";
              if (i==0) {
                 o.readOnly = true;
                 o.type = 'text';
              }
              if (colHeaders[i] == "RC_SITE") {
                o.source = projects[current_project].sites;
                o.type = 'dropdown';
              }
              
              // look up the value in the datadictionary
              for (var j = 0; j < datadictionary.length; j++) {
                  if (datadictionary[j].short == o.data) {
                    if (datadictionary[j].type == 'number') {
                       o.type = 'text';
                    } else if (datadictionary[j].type == 'sequence') {
                       o.type = 'dropdown';
                       o.source = eval(datadictionary[j].allowed); // allowed needs to exist!  --- could be dangerous, someone can enter anything here ...
                    } else if (datadictionary[j].type == 'index') {
                       o.type = 'text';
                    } else {
                       o.type = datadictionary[j].type;
                    }
                    break;
                  }                
              }
              columns.push(o);
           }
           
           function warningValueRenderer(instance, td, row, col, prop, value, cellProperties) {
                Handsontable.renderers.TextRenderer.apply(this, arguments);

                // do we have access to annotations here? We have to look up the type for this cell
                for (var i = 0; i < annotations.length; i++) {
                  if (annotations[i][0] == row-1 && annotations[i][1] == prop){
                    jQuery(td).addClass('warning');
                    jQuery(td).attr('title', annotations[i][2]);
                    break;
                  }
                }
           }
           Handsontable.renderers.registerRenderer('warningValueRenderer', warningValueRenderer);
             
           // remember in global variable
           importedTable = new Handsontable(document.getElementById('imported'+0), {
               colHeaders: true,
               minSpareRows: 1,
               fixedRowsTop: 1,
               data: data,
               stretchH: 'all',
               columnSorting: false, // does not work with update (don't know the row anymore for change)
               columns: columns,
               cells: function(row, col, prop) {
                    var cellProperties = {};
                    cellProperties.renderer = "warningValueRenderer"; // uses lookup map
                    return cellProperties;
               },
               afterChange: function(change, source) {
                 if (source === 'loadData') {
                   return;
                 }
               }
           });
           jQuery('table').addClass("table");
           //jQuery('table').addClass("table-hover");
           jQuery('table').addClass("table-striped"); 
      }

      function loadData( datadictionary ) {
        
        // show the data for the current project in the interface (only show data that we are allowed to see)
        jQuery.getJSON('/code/php/data.php?project_name='+project_name, function(data) {
           var d = jQuery("<div id='data"+0+"' style='margin-top: 10px;'></div>");
           jQuery('#data').append(d);
             
           // now we have to collect all the entries for data to create the correct table
             
           // colHeaders is from data and from data-dictionary
           var colHeaders = {};
           for (var i = 0; i < data.length; i++) {
               data[i].id = i; // add the number as entry id into the returned list
               var k = Object.keys(data[i]);
               for (var j = 0; j < k.length; j++) {
                 colHeaders[k[j]] = 1;
               }
           }
           for (var i = 0; i < datadictionary.length; i++) {
             colHeaders[datadictionary[i].short] = 1;
           }

           colHeaders = Object.keys(colHeaders); // we should sort these based on source
           // get id to be the very first element
           var idIdx = colHeaders.indexOf('id');
           var ch;
           if (idIdx != -1) {
             ch = colHeaders.slice(0, idIdx);
             ch = ch.concat(colHeaders.slice(idIdx+1));
           } else {
             ch = colHeaders;
           }
           ch.unshift('id');
           colHeaders = ch;
           
           // add id field
           /*if (typeof colHeaders['id'] == 'undefined')
              colHeaders.unshift('id');
           else
              alert("Error: this data dictionary contains an id already..."); */

           // now create the columns array
           var columns = [];
           for ( var i = 0; i < colHeaders.length; i++) {
              var o = {};
              o.data = colHeaders[i];
              o.type = "text";
              if (i==0) {
                 o.readOnly = true;
                 o.type = 'text';
              }
              if (colHeaders[i] == "RC_SITE") {
                o.source = projects[current_project].sites;
                o.type = 'dropdown';
              }
              
              // look up the value in the datadictionary
              for (var j = 0; j < datadictionary.length; j++) {
                  if (datadictionary[j].short == o.data) {
                    if (datadictionary[j].type == 'number') {
                       o.type = 'text';
                    } else if (datadictionary[j].type == 'sequence') {
                       o.type = 'dropdown';
                       o.source = eval(datadictionary[j].allowed); // allowed needs to exist!  --- could be dangerous, someone can enter anything here ...
                    } else if (datadictionary[j].type == 'index') {
                       o.type = 'text';
                    } else {
                       o.type = datadictionary[j].type;
                    }
                    /*if (typeof datadictionary[j].source == 'undefined')
                       o.source = 'undefined';
                    else
                       o.source = datadictionary[j].source; */
                    break;
                  }                
              }
              columns.push(o);
           }
             
           storeTable = new Handsontable(document.getElementById('data'+0), {
               colHeaders: true,
               minSpareRows: 1,
               contextMenu: ['remove_row'],
               data: data,
               stretchH: 'all',
               columnSorting: false, // does not work with update (don't know the row anymore for change)
               colHeaders: colHeaders,
               columns: columns, /*[
                 { 'data': 'id', type: 'text', 'readOnly': true  },
                 { 'data': 'short', type: 'text' },
                 { 'data': 'label', type: 'text' },
                 { 'data': 'description', type: 'text' },
                 { 'data': 'source', type: 'text' },
                 { 'data': 'reference', type: 'text' },
                 { 'data': 'type', type: 'dropdown', source: ['text','date','number'] },
                 { 'data': 'required', type: 'checkbox' }
               ], */
               beforeRemoveRow: function(row, col) {
                 var rowValues = this.getDataAtRow(row);
                 if (rowValues[0] === null) {
                   console.log("could not get an index for this row: " + rowValues);
                   return; // do nothing
                 }
                 console.log('delete a real row: ' + row + ' and id is: ' + (rowValues[0]));
                 jQuery.get('/code/php/data.php?project_name=' + project_name + '&action=removeRow&value='+(rowValues[0]), function(data) {});             
               },
               afterChange: function(change, source) {
                 if (source === 'loadData') {
                   return;
                 }
                 // add as default the current source of this table
                 console.log("\nwe have:"+change+"\nsource: "+source + " id: " + this.getDataAtRow(change[0][0]));
                 //rowdata = this.getDataAtRow(change[0][0]);
                 jQuery.get('/code/php/data.php?project_name=' + project_name + '&action=change&value='+JSON.stringify({ data: change }), function(data) {
                    console.log('tried to save the data:' + data);
                    // if we added this line we also have to set the RC_SITE value to make this row visible
                    change[0][1] = "RC_SITE";
                    change[0][3] = projects[current_project].sites[0];
                    jQuery.get('/code/php/data.php?project_name=' + project_name + '&action=change&value='+JSON.stringify({ data: change }), function(data) {});
                 });
               }
           });
           jQuery('table').addClass("table");
           //jQuery('table').addClass("table-hover");
           jQuery('table').addClass("table-striped");
        });
        
      }


      function switchProject(newProject) {
        current_project = newProject;
        current_project_name = projects[newProject].name;
        project_name = current_project_name;
        console.log('project_name:' + project_name);
        jQuery.getJSON('/code/php/setCurrentProject.php?project_name=' + project_name, function(data) {
          // it either worked or it did not
          // alert('changed project (' + data.message +')');
        }).error(function() {
            alert('did not work');
        });

        jQuery('.current-project').text(current_project_name);
        
        // delete the old entries
        jQuery('#data').children().remove();        
        jQuery('#datadictionary').children().remove();
        
        // show the data for the current project in the interface
        jQuery.getJSON('/code/php/datadictionary.php?project_name='+project_name, function(data) {
           // fill in the data table for the data dictionary
           // create separate tables for each source (a source is a collection of data)
           datadictionary = data;
           sources = {};
           for (var i = 0; i < data.length; i++) {
               sources[data[i]['source']] = 1;
           }
           sources = Object.keys(sources);
           if (sources.length == 0) {
             sources.push('undefined'); // in case we don't have any data yet
           }
           
           for (var i = 0; i < sources.length; i++) {
             // subset of data with that source type
             dd = []; //global variable
             for (var j = 0; j < data.length; j++) {
               if (data[j]['source'] == sources[i] || ((typeof data[j]['source'] == 'undefined') && (sources[i] == 'undefined'))){
                  data[j].id = j;
                  dd.push(data[j]);
               }
             }
             var d = jQuery("<div id='datadictionary"+i+"' style='margin-top: 10px;'></div>");
             jQuery('#datadictionary').append(d);
             var table = new Handsontable(document.getElementById('datadictionary'+i), {
               colHeaders: true,
               minSpareRows: 1,
               id: i,
               data: dd,
               contextMenu: ['remove_row'],
               manualColumnResize: true,
               stretchH: 'all',
               columnSorting: false, // does not work with update (don't know the row anymore for change)
               colHeaders: ['id', 'short', 'label', 'description', 'source', 'reference', 'type', 'allowed', 'required'],
               columns: [
                 { 'data': 'id', type: 'text', 'readOnly': true  },
                 { 'data': 'short', type: 'text' },
                 { 'data': 'label', type: 'text' },
                 { 'data': 'description', type: 'text' },
                 { 'data': 'source', type: 'text' },
                 { 'data': 'reference', type: 'text' },
                 { 'data': 'type', type: 'dropdown', source: ['text','date','number','sequence','index'] },
                 { 'data': 'allowed', type: 'text' },
                 { 'data': 'required', type: 'checkbox' }
               ],
               beforeRemoveRow: function(row, col) {
                 var rowValues = this.getDataAtRow(row);
                 if (rowValues[0] === null) {
                   console.log("could not get an index for this row: " + rowValues);                   
                   return; // do nothing
                 }
                 console.log('delete a real row: ' + row + ' and id is: ' + (rowValues[0]));
                 jQuery.get('/code/php/datadictionary.php?project_name=' + project_name + '&action=removeRow&value='+(rowValues[0]), function(data) {});             
               },
               afterChange: function(change, source) {
                 if (source === 'loadData') {
                   return;
                 }
                 // add as default the current source of this table
                 console.log("\nwe have:"+change+"\nsource: "+source + " id: " + this.getDataAtRow(change[0][0]));
                 rowdata = this.getDataAtRow(change[0][0]);
                 //if (rowdata[0] !== null)
                 localRowIndex = change[0][0]; // for this spreadsheet only
                 change[0][0] = rowdata[0]; // use the real row in the original table
                 var t = this;
                 jQuery.getJSON('/code/php/datadictionary.php?project_name=' + project_name + '&action=change&value='+JSON.stringify({ data: change }), function(data) {
                    console.log('tried to save the data:' + data);
                    // add the returned value as row number
                    //t.setDataAtCell(change[0][0], 0, data[0]); 
                    // can we add to the change?
                    
                    // if we don't have this row yet we will end up with null as the first element, in that case add the
                    // source (assume that we want to add to the same spreadsheet)
                    if (change[0][0] === null) { // assume that we add a new measure
                      rowdatabefore = t.getDataAtRow(localRowIndex-1);
                      change[0][0] = data[0];
                      change[0][1] = 'source';
                      change[0][3] = t.getDataAtRowProp(localRowIndex-1, 'source');
                      if (change[0][3] === null)
                        change[0][3] = "";
                      // also change the ID of this row
                      change.push([ change[0][0], 'id', ,data[0] ]);
                      jQuery.get('/code/php/datadictionary.php?project_name=' + project_name + '&action=change&value='+JSON.stringify({ data: change }), function(data) {
                         console.log('added source to data:' + data);
                      });
                    }
                 });  
                 

               }
             });
           }
           jQuery('table').addClass("table");
           //jQuery('table').addClass("table-hover");
           jQuery('table').addClass("table-striped");
           
           // at this point we know what the RC_SITE id's for each of the studies are
           loadData( data );
        });
                
      }
      
      
      // logout the current user
      function logout() {
        jQuery.get('/code/php/logout.php', function(data) {
          if (data == "success") {
            // user is logged out, reload this page
            location.reload();
          } else {
            alert('something went terribly wrong during logout: ' + data);
          }
        });
      }

      // change the current user's password
      function changePassword() {
        var password = jQuery('#password-field1').val();
        var password2 = jQuery('#password-field2').val();
        if (password == "") {
          alert("Error: Password cannot be empty.");
          return; // no empty passwords
        }
        hash = hex_md5(password);
        hash2 = hex_md5(password2);
        if (hash !== hash2) {
          alert("Error: The two passwords are not the same, please type again.");
          return; // do nothing
        }
        jQuery.getJSON('/code/php/getUser.php?action=changePassword&value=' + user_name + '&value2=' + hash, function(data) {

        });
      }
    </script>
    
  </body>
</html>
