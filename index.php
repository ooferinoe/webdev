<html>
    <head>
        <title>Sign up</title>
        <meta charset="UTF=-8">
        <link rel="stylesheet" href="index.css">
    </head>
    
    <body>
        <div class="container">
            <div class="listing">
                <h1>Beatmap Listing</h1>
                <form name="beatmap" method="POST">
                    <center>
                        <table cellpadding="5" cellspacing="3">
                            <tr>
                                <td class="label">Beatmap Name:</td>
                                <td><input type="text" name="beatName" maxlength="50" placeholder="Enter Beatmap Name:"></td>
                            </tr>

                            <tr>
                                <td class="label">Beatmap Artist:</td>
                                <td><input type="text" name="beatArtist" maxlength="50" placeholder="Enter Beatmap Artist:"></td>
                            </tr>

                            <tr>
                                <td class="label">Beatmap Difficulty:</td>
                                <td><input type="text" name="beatDiff" maxlength="6" placeholder="Enter Beatmap Difficulty:"></td>
                            </tr>
                        </table>
                        <input class="button" type="submit" name="insertSub" value="INSERT" onclick="insertFunc()"> 
                        <input class="button" type="submit" name="deleteSub" value="DELETE" onclick="deleteFunc()">
                        <input class="button" type="submit" name="updateSub" value="UPDATE" onclick="updateFunc()">
                        <input class="button" type="submit" name="viewSub" value="VIEW" >
                        <!--<input class="button" type="submit" name="searchSub" value="SEARCH" >-->
                    </center>
                    <center>
                        <input class="logout" type="submit" name="logout" value="LOGOUT" >
                    </center>
                </form>
            </div>
            
            <?php
            $mysqli = require __DIR__ . "/database.php";
            
            if(isset($_POST['insertSub'])){
                $errZip = "";
                if(!empty($_POST["beatName"])){
                    $sql = "INSERT into tbl_beatmap (beat_name,`beat_artist`,`beat_diff`) values 
                    (?,?,?)";
                    
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("sss", $_POST['beatName'], $_POST['beatArtist'], $_POST['beatDiff']);
//                    $result = mysqli_query($mysqli,$sql);
                    
                    if($stmt->execute()){
                        echo "<p class='message'>Beatmap successfully recorded</p>";
                    }
                    else{
                        echo "<p class='err_message'>Error: " . mysqli_error($mysqli) . "</p>";
                    }
                    
                    $stmt->close();
                }
                else{
                $errZip = "<p class='err_message'>Must contain a beatmap name</p>";
                echo $errZip;
                }
            }

            if(isset($_POST['deleteSub'])!=''){
                if($_POST['beatName']==""){}
                
                else{
                    $sql = "DELETE from tbl_beatmap WHERE `beat_id`='$_POST[beatName]'";
                    $result = mysqli_query($mysqli,$sql);
                        if($result){
                            echo "<p class='message'>Beatmap successfully deleted!</p>";
                        }
                        else{
                            die ("<p class='err_message'>Beatmap cannot be found." . mysqli_error($mysqli) . "</p>");
                        }
                    }
            }

            if(isset($_POST['viewSub'])!=''){
                echo "<center>";
                $sql = "Select * from tbl_beatmap";
                $result = mysqli_query($mysqli,$sql);
                if(mysqli_num_rows($result) > 0 ){
                    echo "<table class='view' cellpadding = 5 cellspacing = 3 border = 1>" . "<th>Beatmap Name</th> <th>Beatmap Artist</th> 
                    <th>Beatmap Difficulty</th>";
                    while($rows = mysqli_fetch_assoc($result)){
                        echo "
                        <tr>
                        <td>" . $rows["beat_name"] ." </td>
                        <td> ". $rows["beat_artist"] ."</td>
                        <td> ". $rows["beat_diff"] . "</td>
                        </tr>";
                    }
                    echo "</table>";
                }
                if($result){
                    echo "<p class='message'>Viewing beatmap records.</p>";
                }
                else{
                    die ("<p class='err_message'>Beatmap cannot be found." . mysqli_error($mysqli) . "</p>");
                }
                echo "</center>";
            }
            
            if(isset($_POST['updateSub'])!=''){
                if($_POST['beatName']=="" && $_POST['beatDiff']==""){}
                
                else{
                    $sql = "UPDATE tbl_beatmap SET `beat_diff`='$_POST[beatDiff]' WHERE 
                    `beat_name`='$_POST[beatName]'";
                    $result = mysqli_query($mysqli,$sql);
                    echo "<p class='message'>Beatmap has been updated!</p>";
                }
            }

            if(isset($_POST['logout'])){
                header("Location: login.php");
            }
//            if(isset($_POST['searchSub'])!=''){
//                if($_POST['empName']==""){
//                    echo "Fill Employee Name field you want to search.";
//                }
//                else{
//                //if(preg_match("/^d{5}/", $_POST['empID'])){
//                if(preg_match("/[A-Z | a-z]+/", $_POST['empName'])){
//                    $empname = $_POST['empName'];
//                    $empid = $_POST['empID'];
//                    $sql = "SELECT emp_ID, emp_name, emp_Salary FROM employee WHERE 
//                    Firstname LIKE '%". $empname . "%' OR Lastname LIKE '%" . $empname ."%'";
//                    $sql = "SELECT * FROM employee WHERE `emp_ID`=" . $empid ;
//                    $sql = "SELECT emp_ID, emp_name, emp_Salary FROM employee WHERE 
//                    emp_name LIKE '%". $empname ."%'";
//                    $result = mysqli_query($conn,$sql);
//                    echo "<table align=center border=1 cellspacing=3 cellpadding=5>";
//                    echo "<th>Employee ID</th><th>Employee Name</th><th>Employee Salary</th>";
//                    while($row = mysqli_fetch_assoc($result)){
//                        $empid_ = $row["emp_ID"];
//                        $empname_ = $row["emp_name"];
//                        $empsalary_ = $row["emp_Salary"];
//                        echo "
//                        <tr>
//                        <td>" . $empid_ ." </td>
//                        <td> ". $empname_ ."</td>
//                        <td> ". $empsalary_ . "</td>
//                        </tr>";
//                    }
//                    echo "</table>";
//                    }
//                    echo "Record Searched";
//                }
//            }
        ?>
        </div>
    </body>
</html>