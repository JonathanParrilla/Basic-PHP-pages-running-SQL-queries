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


# Query 1
# For a given dependent name (input from a browser), output:
#	dependent name
#	firstname, lastname of the corresponding employee
#	firstname, lastname of the manager for that employee

print ("<br>");

// Obtain the status of dependent from post.
$dependent = $_POST['dependent'];

if (!($dependent )) 
{
  if ($_POST['visited']) 
  {	  
	if (! $dependent) 
	{
       $dependentmsg = 'Please enter a Dependent Name';
    }
  }

 // printing the form to enter the user input
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$dependentmsg</font><br>
 Dependent name: <input type="text" name="dependent" size="15" value="$dependent">
 <br/>
 <br>
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
	 {  

	  $query = "
	  SELECT dependent_name, employee.fname, employee.lname, manager.fname, manager.lname 
	  FROM dependent, department, employee, employee manager  
	  WHERE dependent_name = '$dependent' 
	  AND employee.ssn = dependent.essn 
	  AND (employee.ssn = mgrssn OR employee.superssn = mgrssn) 
	  AND employee.dno = manager.dno 
	  AND manager.ssn = mgrssn";
	  
	  $queryresult = $database->queryAll($query);
	  
	  print("Output for the dependent $dependent:  <br>");
	  
	  foreach ($queryresult as $record) 
	  {
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Dependent: $record[0] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Employee: $record[1] $record[2] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Manager: $record[3] $record[4] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; <br>");
	  }
	  
	  if (empty($queryresult)) 
	  {
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Invalid dependent name $dependent <br>");
	  }
	 }
}
?>