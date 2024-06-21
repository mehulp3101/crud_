<!doctype html>
<?php
 //ini_set('display_errors', 1);
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SCP CRUD Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body class="container">
      <?php
      
            include "connection.php";
            
            $dd = [];
            
            if(isset($_POST['update']))
           {
                // write a prepare statement to update data
                $update = $mysqli->prepare("update kenworth set model=?, tagline=?, content=?, image=?  where id=?");
            
                $update->bind_param("ssssi", $_POST['model'], $_POST['tagline'], $_POST['content'], $_POST['image'], $_POST['id']);
                
                if($update->execute())
                {
                    echo "<div class='alert alert-success p-3 m-2'>Record updated successfully</div>";
                }
                else
                {
                    echo "<div class='alert alert-danger p-3 m-2'>Error: {$update->error}</div>";
                }
           }
           
           if(isset($_GET['update']))
            {
                $id = $_GET['update'];
                
                $record = $mysqli->prepare("select * from kenworth where id = ?"); 
                if(!$record){
                    echo "<div class='alert alert-danger p-3 m-2'>Error preparing record for updating.</div>";
                    exit;
                }
                $record->bind_param("i", $id);
                $record->execute();
                $t = $record->get_result();
                $dd = $t->fetch_assoc();
                
            }
      
      ?>
    <h1>Update record.</h1>
    <p><a href="index.php" class="btn btn-dark"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/>
</svg> Back to index page.</a></p>
    <div class="p-3 bg-light border shadow">
        <form method="post" action="update.php" class="form-group">
            <input type="hidden" name="id" value="<?php echo isset($dd['id']) ? $dd['id'] : ''; ?>">
            <label>Enter Item:</label>
            <br>
            <input type="text" name="model" placeholder="Enter Item..." class="form-control" required value="<?php echo isset($dd['model']) ? $dd['model'] : ''; ?>">
            <br><br>
            <label>Enter image:</label>
            <br>
            <input type="text" name="image" placeholder="images/nameofimage.png..." class="form-control" value="<?php echo isset($dd['image']) ? $dd['image'] : ''; ?>">
            <br><br>
            <label>Enter Object Class:</label>
            <br>
            <input type="text" name="tagline" placeholder="Enter Object Class..." class="form-control" value="<?php echo isset($dd['tagline']) ? $dd['tagline'] : ''; ?>">
            <br><br>
            <label>Enter Description:</label>
            <br>
            <textarea name="content" class="form-control" placeholder="Enter Description:"><?php echo isset($dd['content']) ? $dd['content'] : ''; ?></textarea>
            <br><br>
            <input type="submit" name="update" class="btn btn-primary">
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>