<?php
        /*
                Allows the user to both create new records and edit existing records
        */

        // connect to the database
        include("connect-db.php");

        // creates the new/edit record form
        // since this form is used multiple times in this file, I have made it a function that is easily reusable
        function renderForm($name = '', $gender ='', $birth, $death, $error = '', $id = '')
        { ?>
                <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                <html>
                        <head>  
                                <title>
                                        <?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?>
                                </title>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                        </head>
                        <body>
                                <h1><?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?></h1>
                                <?php if ($error != '') {
                                        echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error
                                                . "</div>";
                                } ?>
                                
                                <form action="" method="post">
                                <div>
                                        <?php if ($id != '') { ?>
                                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                                <p>ID: <?php echo $id; ?></p>
                                        <?php } ?>
                                        
                                        <strong>Author Name: *</strong> <input type="text" name="personName"
                                                value="<?php echo $name; ?>"/><br/>
                                        <strong>Gender: *</strong> <input type="text" name="gender"
                                                value="<?php echo $gender; ?>"/>
                                        <strong>Birth Year: *</strong> <input type="text" name="birthYear"
                                                value="<?php echo $birth; ?>"/>
                                        <strong>Death Year: </strong> <input type="text" name="deathYear"
                                                value="<?php echo $death; ?>"/>
                                        <p>* required</p>
                                        <input type="submit" name="submit" value="Submit" />
                                </div>
                                </form>
                        </body>
                </html>
                
        <?php }



        /*

           EDIT RECORD

        */
        // if the 'id' variable is set in the URL, we know that we need to edit a record
        if (isset($_GET['id']))
        {
                // if the form's submit button is clicked, we need to process the form
                if (isset($_POST['submit']))
                {
                        // make sure the 'id' in the URL is valid
                        if (is_numeric($_POST['id']))
                        {
                                // get variables from the URL/form
                                $id = $_POST['id'];
                                $name = htmlentities($_POST['personName'], ENT_QUOTES);
                                $gender = htmlentities($_POST['gender'], ENT_QUOTES);
                                $birth = htmlentities($_POST['birthYear'], ENT_QUOTES);
                                $death = htmlentities($_POST['deathYear'], ENT_QUOTES);
                                
                                // check that name and gender are both not empty
                                if ($name == '' || $gender == '' || $birth == '')
                                {
                                        // if they are empty, show an error message and display the form
                                        $error = 'ERROR: Please fill in all required fields!';
                                        renderForm($name, $gender, $birth, $death, $error, $id);
                                }
                                else
                                {
                                        // if everything is fine, update the record in the database
                                        if ($stmt = $mysqli->prepare("UPDATE person SET personName = ?, gender = ? birthYear = ?, deathYear = ?
                                                WHERE pk_person=?"))
                                        {
                                                $stmt->bind_param("ssi", $name, $gender, $birth, $death, $id);
                                                $stmt->execute();
                                                $stmt->close();
                                        }
                                        // show an error message if the query has an error
                                        else
                                        {
                                                echo "ERROR: could not prepare SQL statement.";
                                        }
                                        
                                        // redirect the user once the form is updated
                                        header("Location: view-author.php");
                                }
                        }
                        // if the 'id' variable is not valid, show an error message
                        else
                        {
                                echo "Error!";
                        }
                }
                // if the form hasn't been submitted yet, get the info from the database and show the form
                else
                {
                        // make sure the 'id' value is valid
                        if (is_numeric($_GET['id']) && $_GET['id'] > 0)
                        {
                                // get 'id' from URL
                                $id = $_GET['id'];
                                
                                // get the record from the database
                                if($stmt = $mysqli->prepare("SELECT * FROM person WHERE pk_person=?"))
                                {
                                        $stmt->bind_param("i", $id);
                                        $stmt->execute();
                                        
                                        $stmt->bind_result($id, $name, $gender, $birth, $death);
                                        $stmt->fetch();
                                        
                                        // show the form
                                        renderForm($name, $gender, $birth, $death, NULL, $id);
                                        
                                        $stmt->close();
                                }
                                // show an error if the query has an error
                                else
                                {
                                        echo "Error: could not prepare SQL statement";
                                }
                        }
                        // if the 'id' value is not valid, redirect the user back to the view.php page
                        else
                        {
                                header("Location: view-author.php");
                        }
                }
        }



        /*

           NEW RECORD

        */
        // if the 'id' variable is not set in the URL, we must be creating a new record
        else
        {
                // if the form's submit button is clicked, we need to process the form
                if (isset($_POST['submit']))
                {
                        // get the form data
                        $name = htmlentities($_POST['personName'], ENT_QUOTES);
                        $gender = htmlentities($_POST['gender'], ENT_QUOTES);
                        $birth = htmlentities($_POST['birthYear'], ENT_QUOTES);
                        $death = htmlentities($_POST['deathYear'], ENT_QUOTES);
                        
                        // check that name and gender are both not empty
                        if ($name == '' || $gender == '')
                        {
                                // if they are empty, show an error message and display the form
                                $error = 'ERROR: Please fill in all required fields!';
                                renderForm($name, $gender, $birth, $death, $error);
                        }
                        else
                        {
                                // insert the new record into the database
                                if ($stmt = $mysqli->prepare("INSERT person (personName, gender, birthYear, deathYear) VALUES (?, ?, ?, ?)"))
                                {
                                        $stmt->bind_param("ss", $name, $gender, $birth, $death);
                                        $stmt->execute();
                                        $stmt->close();
                                }
                                // show an error if the query has an error
                                else
                                {
                                        echo "ERROR: Could not prepare SQL statement.";
                                }
                                
                                // redirec the user
                                header("Location: view-author.php");
                        }
                        
                }
                // if the form hasn't been submitted yet, show the form
                else
                {
                        renderForm();
                }
        }
        
        // close the mysqli connection
        $mysqli->close();
?>