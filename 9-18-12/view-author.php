<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
        <head>  
                <title>View Records</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
                
                <h1>View Records</h1>
                
                <p><b>View Authors</b> | <a href="view-author-paginated.php">View Authors Paginated</a> | <a href="view-text-paginated.php">View Texts</a> </p>
                
                <?php
                        // connect to the database
                        include('connect-db.php');
                        
                        // get the records from the database
                        if ($result = $mysqli->query("select distinct pk_person, personName, gender, birthYear, deathYear from person inner join author_join on author_join.fk_person=person.pk_person order by person.personName asc;"))
                        {
                                // display records if there are records to display
                                if ($result->num_rows > 0)
                                {
                                        // display records in a table
                                        echo "<table border='1' cellpadding='10'>";
                                        
                                        // set table headers
                                        echo "<tr><th>Author Name</th><th>Gender</th><th>Born</th><th>Died</th><th></th><th></th><th></th></tr>";
                                        
                                        while ($row = $result->fetch_object())
                                        {
                                                // set up a row for each record
                                                echo "<tr>";
                                                echo "<td>" . $row->personName . "</td>";
                                                echo "<td>" . $row->gender . "</td>";
                                                echo "<td>" . $row->birthYear . "</td>";
                                                echo "<td>" . $row->deathYear . "</td>";
                                                echo "<td><a href='show.php?id=" .$row->pk_person ."'>Show</a></td>";
                                                echo "<td><a href='records-author.php?id=" . $row->pk_person . "'>Edit</a></td>";
                                                echo "<td><a href='delete-author.php?id=" . $row->pk_person . "'>Delete</a></td>";
                                                echo "</tr>";
                                        }
                                        
                                        echo "</table>";
                                }
                                // if there are no records in the database, display an alert message
                                else
                                {
                                        echo "No results to display!";
                                }
                        }
                        // show an error if there is an issue with the database query
                        else
                        {
                                echo "Error: " . $mysqli->error;
                        }
                        
                        // close database connection
                        $mysqli->close();
                
                ?>
                
                <a href="records-author.php">Add New Record</a>
        </body>
</html>