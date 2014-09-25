<?php
 #========================================================

 #Name: _________________________

 #Panther-ID: x x x -_______

 #Course: COP 4722

 #Assignment#: 3

 #Due: Thursday, Mar 6, 2014

 #I hereby certify that this work is my own and none of
 #it is the work of any other person.

 #Signature: ______________________
 #=========================================================


# Query 4
# For a given department name (input from a browser), output:
# 	department name
# 	firstname, lastname of the manager
# 	total number of dependents of all employees who work for the department

# Note from professor in class: DO NOT USE ONE SUPER QUERY. 
# Run separate queries, store the results.

print ("<br>");
$departmentname = $_POST['departmentname'];

if (!($departmentname )) 
{
  if ($_POST['visited']) 
  {	  
    if (! $departmentname) 
	{
       $departmentnamemessage = 'Please complete department name for the search';
    }
	
  }

 // printing the form to enter the user input
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$departmentnamemessage</font><br>
 Department name: <input type="text" name="departmentname" size="15" value="$departmentname">
 <br>
 <p>
 <INPUT type="submit" value=" Submit ">
 <INPUT type="hidden" name="visited" value="true">
 </FORM>
_HTML_;
 
}

else 
{
  require_once 'MDB2.php';

$database = MDB2::connect('mssql://cop4722:4722@teachms.cs.fiu.edu/cop4722');
if (MDB2::isError($database)) 
{
  die("cannot connect - " . $database->getMessage() . $database->getDebugInfo());
}
$database->setErrorHandling(PEAR_ERROR_DIE);
  
  //Store the query for looking up the department name in querydepartmentname
  $querydepartmentname = "
					SELECT dname
					FROM department
					WHERE dname = '$departmentname';";

  //Store the query for looking up the first name and last name in queryfirstandlastname					
  $queryfirstandlastname = "
					SELECT fname, lname
					FROM department dptA, department dptB, employee
					WHERE dptA.dname = '$departmentname'
					AND dptA.dnumber = dptB.dnumber
					AND ssn = dptA.mgrssn;";
   
  //Store the query for looking up the number of dependents as querydependents  
  $querydependents = "	    
					SELECT COUNT(dependent_name) AS NumberOfDependents
					FROM dependent, department, employee
					WHERE dname = '$departmentname'
					AND ssn = mgrssn
					AND essn = ssn;";
					
  $departmentnametable = $database->query($querydepartmentname);
  
  $firstandlastnametable = $database->query($queryfirstandlastname);
  
  $dependentstable = $database->query($querydependents);
  
  print("Output for the department name $departmentname:<br>");
  
  if($departmentname)
	{
		while (($departmentnamerecord = $departmentnametable->fetchRow()) 
		&& ($namerecord = $firstandlastnametable->fetchRow()) 
		&& ($dependentsrecord = $dependentstable->fetchRow())) 
		{
			$finaltable .=  
			"&nbsp; &nbsp; &nbsp; &nbsp; Department: $departmentnamerecord[0] <br>
			&nbsp; &nbsp; &nbsp; &nbsp; Manager: $namerecord[0] $namerecord[1] <br> 
			&nbsp; &nbsp; &nbsp; &nbsp; Dependents: $dependentsrecord[0] <br><br>";
		}
	}
  if (!empty($finaltable)) 
    {	
		print $finaltable;	
    }
  else
    {
		print("&nbsp; &nbsp; &nbsp; &nbsp; Invalid Department Name $departmentname");
	}
	
}
?>