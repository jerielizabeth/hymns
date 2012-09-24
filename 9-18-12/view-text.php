<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
        <head>  
                <title>View by Text</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>
                
                <h1>View Records</h1>
                
                <p><b>View Texts</b> | <a href="view-text-paginated.php">View Texts Paginated</a> | <a href="view-author.php">View Authors</a> </p>
                
                <?php
                        // connect to the database
                        include('connect-db.php');
                        
                        // get the records from the database
                        if ($result = $mysqli->query("select distinct pk_text,textTitle,firstLine,refrainFirstLine from text inner join author_join on author_join.fk_text=text.pk_text;"))
                        {
                                // display records if there are records to display
                                if ($result->num_rows > 0)
                                {
                                        // display records in a table
                                        echo "<table border='1' cellpadding='10'>";
                                        
                                        // set table headers
                                        echo "<tr><th>Title</th><th>First Line</th><th>Refrain</th><th></th><th></th><th></th></tr>";
                                        
                                        while ($row = $result->fetch_object())
                                        {
                                                // set up a row for each record
                                                echo "<tr>";
                                                echo "<td>" . $row->textTitle . "</td>";
                                                echo "<td>" . $row->firstLine . "</td>";
                                                echo "<td>" . $row->refrainFirstLine . "</td>";
                                                echo "<td><a href='show.php?id=" .$row->id ."'>Show</a></td>";
                                                echo "<td><a href='records-text.php?id=" . $row->id . "'>Edit</a></td>";
                                                echo "<td><a href='delete-text.php?id=" . $row->id . "'>Delete</a></td>";
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
                
                <a href="records.php">Add New Record</a>
        </body>
</html>