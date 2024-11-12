<html>
    <head>
        <h1 align="center">Beatmap Listing</h1>
    </head>
    
    <body>
        <form name="employee" method="POST">
            <table cellpadding="5" cellspacing="3" align="center">
                <tr>
                    <td>Employee Name:</td>
                    <td><input type="text" name="empName" maxlength="20" placeholder="Enter Employee Name:"></td>
                </tr>
                
                <tr>
                <td>Employee Salary:</td>
                <td><input type="text" name="empSalary" maxlength="100" placeholder="Enter Employee 
                Salary:"></td>
                </tr>
                
                <tr>
                    <td>Employee ID:</td>
                    <td><input type="text" name="empID" maxlength="4" placeholder="Enter Employee ID:"></td>
                </tr>
            </table>
            <center>
                <input type="submit" name="insertSub" value="INSERT" onclick="insertFunc()"> 
                <input type="submit" name="deleteSub" value="DELETE" onclick="deleteFunc()">
                <input type="submit" name="updateSub" value="UPDATE" onclick="updateFunc()">
                <input type="submit" name="viewSub" value="VIEW" >
                <input type="submit" name="searchSub" value="SEARCH" >
            </center>
        </form>
    
        <?php
            $DBHost = "localhost"; // hostname
            $DBUser = "root"; // username
            $DBPass = ""; //password
            $DBName = "test_db"; //database
            $conn = mysqli_connect($DBHost, $DBUser, $DBPass, $DBName); //connection string to connect Database.
            if(!$conn){
                die("Connection failed: " . mysqli_error()); //check connection 
            }
            if(isset($_POST['insertSub'])!=''){
                $errZip = "";
                if(preg_match("/^[0-9]{4}$/", $_POST['empID'])){
                    $sql = "INSERT into tbl_employee (emp_ID,`emp_name`,`emp_Salary`) values 
                    ('$_POST[empID]','$_POST[empName]','$_POST[empSalary]')";
                    $result = mysqli_query($conn,$sql);
                    echo "<br>Recorded";
                }
                else{
                $errZip = '<p class="errText">Employee ID must be 4 digits</p>';
                echo $errZip;
                }
            }

            if(isset($_POST['deleteSub'])!=''){
                if($_POST['empID']==""){}
                
                else{
                    $sql = "DELETE from employee WHERE `emp_ID`='$_POST[empID]'";
                    $result = mysqli_query($conn,$sql);
                        if($result){
                            echo "<br>Record Deleted";
                        }
                        else{
                            die ("Record can not find in the database". mysqli_error());
                        }
                    }
            }

            if(isset($_POST['viewSub'])!=''){
                echo "<center>";
                $sql = "Select * from tbl_employee";
                $result = mysqli_query($conn,$sql);
                if(mysqli_num_rows($result) > 0 ){
                    echo "<table border = 3>" . "<th>Employee ID</th> <th>Employee Name</th> 
                    <th>Employee Salary</th>";
                    while($rows = mysqli_fetch_assoc($result)){
                        echo "
                        <tr>
                        <td>" . $rows["emp_ID"] ." </td>
                        <td> ". $rows["emp_name"] ."</td>
                        <td> ". $rows["emp_Salary"] . "</td>
                        </tr>";
                    }
                    echo "</table>";
                }
                if($result){
                    echo "<br>Record View";
                }
                else{
                    die ("Record can not find in the database". mysqli_error());
                }
                echo "</center>";
            }
            if(isset($_POST['updateSub'])!=''){
                if($_POST['empID']=="" && $_POST['empSalary']==""){}
                
                else{
                    $sql = "UPDATE employee SET `emp_Salary`='$_POST[empSalary]' WHERE 
                    `emp_ID`='$_POST[empID]'";
                    $result = mysqli_query($conn,$sql);
                    echo "<br>Record Updated";
                }
            }

            if(isset($_POST['searchSub'])!=''){
                if($_POST['empName']==""){
                    echo "Fill Employee Name field you want to search.";
                }
                else{
                //if(preg_match("/^d{5}/", $_POST['empID'])){
                if(preg_match("/[A-Z | a-z]+/", $_POST['empName'])){
                    $empname = $_POST['empName'];
                    $empid = $_POST['empID'];
                    $sql = "SELECT emp_ID, emp_name, emp_Salary FROM employee WHERE 
                    Firstname LIKE '%". $empname . "%' OR Lastname LIKE '%" . $empname ."%'";
                    $sql = "SELECT * FROM employee WHERE `emp_ID`=" . $empid ;
                    $sql = "SELECT emp_ID, emp_name, emp_Salary FROM employee WHERE 
                    emp_name LIKE '%". $empname ."%'";
                    $result = mysqli_query($conn,$sql);
                    echo "<table align=center border=1 cellspacing=3 cellpadding=5>";
                    echo "<th>Employee ID</th><th>Employee Name</th><th>Employee Salary</th>";
                    while($row = mysqli_fetch_assoc($result)){
                        $empid_ = $row["emp_ID"];
                        $empname_ = $row["emp_name"];
                        $empsalary_ = $row["emp_Salary"];
                        echo "
                        <tr>
                        <td>" . $empid_ ." </td>
                        <td> ". $empname_ ."</td>
                        <td> ". $empsalary_ . "</td>
                        </tr>";
                    }
                    echo "</table>";
                    }
                    echo "Record Searched";
                }
            }
        ?>
    </body>
</html>