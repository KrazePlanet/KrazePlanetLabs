<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Edit Book</title>
</head>
<body>
    <div class="container my-5">
    <header class="d-flex justify-content-between my-4">
            <h1>Edit Book</h1>
            <div>
            <a href="index.php" class="btn btn-primary">Back</a>
            </div>
        </header>
        <form action="process.php" method="post">
            <?php 
            
            if (isset($_GET['id'])) {
                include("connect.php");
                $id = $_GET['id'];
                $sql = "SELECT * FROM books WHERE id=$id";
                $result = mysqli_query($conn,$sql);
                $row = mysqli_fetch_array($result);
                ?>
                     <div class="form-elemnt my-4">
                <input type="text" class="form-control" name="title" placeholder="Book Title" value="<?php echo $row["title"]; ?>">
            </div>
            <div class="form-elemnt my-4">
                <input type="text" class="form-control" name="author" placeholder="Author Name" value="<?php echo $row["author"]; ?>">
            </div>
            <div class="form-elemnt my-4">
                <select name="type" id="" class="form-control">
                    <option value="">Select Book Type</option>
                    <option value="Dystopian" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Dystopian</option>
                    <option value="Action & Adventure" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Action & Adventure</option>
                    <option value="Crime" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Crime</option>
                    <option value="Fantasy" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Fantasy</option>
                    <option value="Science Fiction" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Science Fiction</option>
                    <option value="Horror" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Horror</option>
                    <option value="Hacking & BugBounty" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Hacking & BugBounty</option>
                    <option value="Mystery" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Mystery</option>
                    <option value="Thriller & Suspense" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Thriller & Suspense</option>
                    <option value="Historical Fiction" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Historical Fiction</option>
                    <option value="Romance" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Romance</option>
                    <option value="Graphic Novel" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Graphic Novel</option>
                    <option value="Magical Realism" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Magical Realism</option>
                    <option value="Non-fiction" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Non-fiction</option>
                    <option value="Young Adult" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Young Adult</option>
                    <option value="Classic" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Classic</option>
                    <option value="Poetry" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Poetry</option>
                    <option value="Biography" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Biography</option>
                    <option value="Autobiography" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Autobiography</option>
                    <option value="Memoir" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Memoir</option>
                    <option value="Cookbook" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Cookbook</option>
                    <option value="Travel" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Travel</option>
                    <option value="Nature" <?php if($row["type"]=="Horror"){echo "selected";} ?>>Nature</option>
                </select>
            </div>
            <div class="form-element my-4">
                <textarea name="description" id="" class="form-control" placeholder="Book Description"><?php echo $row["description"]; ?></textarea>
            </div>
            <input type="hidden" value="<?php echo $id; ?>" name="id">
            <div class="form-element my-4">
                <input type="submit" name="edit" value="Edit Book" class="btn btn-primary">
            </div>
                <?php
            }else{
                echo "<h3>Book Does Not Exist</h3>";
            }
            ?>
           
        </form>
        
        
    </div>
</body>
</html>