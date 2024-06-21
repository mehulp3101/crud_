<!Doctype html> 
<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
?>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>SCP Web Application</title>
      <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   </head>
   <body class="container">
      <?php include 'connection.php'; ?> 
      <!-- Kenworth Nav Menu --> 
      <nav class="navbar navbar-dark bg-dark fixed-top text-white">
            <div class="container-fluid">
            <a class="navbar-brand" href="#">SCP Web Application</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">SCP Web Application</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <?php foreach($Result as $link): ?> 
            <li> 
               <a href="index.php?link='<?php echo $link['model']; ?>'" class="nav-link text-light"><?php echo $link['model']; ?></a> 
            </li>
            <?php endforeach; ?> 
            <li class="nav-item active"> 
               <a href="create.php" class="nav-link text-light">Add New SCP Record</a> 
            </li>
            </ul>
            </div>
            </div>
            </div>
        </nav>
        <br>
        <br>
      <h3 class="display-1 text-center">Welcome to the SCP Foundation</h3>
      <div class="rounded border shadow p-5"> 
         <?php  
            if(isset($_GET['link'])) 
            { 
                // remove single quotes from returned get value 
                // value to trim, character to trim out 
                $model = trim($_GET['link'], "'"); 
                // run sql command to retrieve record based on $model 
                // $record = $connection->query("select * from kenworth where model='$model'"); 
                // save each field in record as an array 
                // $array = $record->fetch_assoc(); 
                // prepared statement 
               
                $statement = $mysqli->prepare("select * from kenworth where model = ?"); 
            
                if(!$statement) 
                { 
                    echo "<p>Error in preparing sql statement</p>"; 
                    exit; 
                } 
            
                // bind parameters takes 2 arguments the type of data and the var to bind to. 
            
                $statement->bind_param("s", $model); 
                if($statement->execute()) 
                { 
                    $get_result = $statement->get_result(); 
                    // check if record has been retrieved 
                    if($get_result->num_rows > 0) 
                    { 
                        $array = array_map('htmlspecialchars', $get_result->fetch_assoc()); 
                        $up="update.php?update=". $array['id'];
                        $dl="index.php?delete=" . $array['id'];
                         echo "<h3 class='display-2'>{$array['model']}</h3> 
                        <h4 class='display-3'>{$array['tagline']}</h4> 
                        <p class='text-center'> 
                        <img src='{$array['image']}' alt='{$array['model']}' class='img-fluid w-50'> 
                        </p> 
                        <p>{$array['content']}</p>
                        <p>
                            <a href='{$up}' class='btn btn-info'>Update Record</a>
                            <a href='{$dl}' class='btn btn-warning'>Delete Record</a>
                        </p>
                        ";
                    } 
                    else 
                    { 
                        echo "<p>No record found for model: {$model}</p>"; 
                    } 
                } 
                else 
                { 
                    echo "<p>Error executing statement.</p>"; 
            
                }
            }
            else 
            { 
                foreach($Result as $link):
                    echo "<div class='card mb-12'>
                      <div class='row g-0'>
                        <div class='col-md-4'>
                          <img src='{$link['image']}' class='img-fluid rounded-start' alt='...'>
                        </div>
                        <div class='col-md-8'>
                          <div class='card-body'>
                            <h5 class='card-title'>{$link['model']}</h5>
                            <p class='card-text'>{$link['content']}</p>
                            <p class='card-text'><small class='text-body-secondary'>{$link['model']}</small></p>
                          </div>
                        </div>
                      </div>
                    </div>"; 
                    endforeach;
            }
            if(isset($_GET['delete']))
            {
                $del = $_GET['delete'];
                $delete = $mysqli->prepare("delete from kenworth where id=?");
                $delete->bind_param("i", $del);
                
                if($delete->execute())
                {
                    echo "<div class='alert alert-warning'>Recorded Deleted...</div>";
                }
                else
                {
                     echo "<div class='alert alert-danger'>Error deleting record {$delete->error}.</div>";
                }
            }
            ?> 
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> 
   </body>
</html>