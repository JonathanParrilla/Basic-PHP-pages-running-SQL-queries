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


# Query 2
# Output the firstname and lastname of each pair of employees who work for the same project. 
# There should not be any duplicate reversed pair and 
# no employee be paired with the same employee.

require_once 'MDB2.php';

$database = MDB2::connect('mssql://cop4722:4722@teachms.cs.fiu.edu/cop4722');

if (MDB2::isError($database)) 
{
  die("cannot connect - " . $database->getMessage() . $database->getDebugInfo());
}

$database->setErrorHandling(PEAR_ERROR_DIE);

$query = $database->query('
SELECT A.pno, e1.fname, e1.lname, e2.fname, e2.lname
FROM Works_On A, Works_On B, employee e1, employee e2
WHERE A.PNO = B.PNO AND A.ESSN < B.ESSN AND e1.ssn = a.essn AND e2.ssn = b.essn
ORDER BY PNO, e1.fname, e1.lname, e2.fname, e2.lname');

while ($record = $query->fetchRow()) 
{
  print("$record[0] &nbsp;&nbsp; $record[1] $record[2] &nbsp; - &nbsp; $record[3] $record[4] <br>");
}
?>