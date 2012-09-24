<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>  
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <h1>Author Page </h2>

<?php

    // connect to the database
    include("connect-db.php");

    if (isset($_GET['id']) && is_numeric($_GET['id']))
        {   
            // get the 'id' variable from the URL
            $id = $_GET['id'];
        }
        
        if($stmt = $mysqli->query("SELECT personName, gender, birthYear, deathYear FROM person WHERE pk_person = ?"))
            {
                           
                if ($stmt->num_rows > 0 )
                // display records in a table
                    echo "<table border='1' cellpadding='10'>";
                    
                    // set table headers
                    echo "<tr><th>Name</th><th>Gender</th><th>Year of Birth</th><th>Year of Death</th></tr>";
                    
                    while ($row = $stmt->fetch_object())
                    {
                            // set up a row for each record
                            echo "<tr>";
                            echo "<td>" . $row->personName . "</td>";
                            echo "<td>" . $row->gender . "</td>";
                            echo "<td>" . $row->birthYear . "</td>"; 
                            echo "<td>" . $row->deathYear . "</td>"; 
                    }
                    echo "</table>"; 
                        
            }   
        else 
            {   
               echo "Error: could not prepare SQL statement";
            } 
?>
        <p>ID: <?php echo $id;?></p>


    <?php
    // close the mysqli connection
    
        $mysqli->close();
    ?>
                                
</body>
</html>
                
        



       