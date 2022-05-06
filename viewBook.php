<!-- Author: Alex Spalvieri
     ID: 200403578 -->
<?php
    session_start();
    if (isset($_SESSION['userid'])) {
        header("Location: modifyBook.php");
        exit();
    }
    require_once("dbconnect.php");

    $usersearch = filter_input(INPUT_GET, "usersearch");

    //Set initial search query
    $query = "SELECT * FROM books ";

    //Split usersearch by spaces
    $searches = explode(" ", $usersearch);
    //Delete array entries that have spaces or nothing
    for ($i = 0; $i < count($searches); $i++) {
        if ($searches[$i] == " " || $searches[$i] == "") {
            array_splice($searches, $i, 1);
            $i--;
        }
    }

    if (count($searches) > 0) {
        $query .= "WHERE ";
    }

    //Add like term searches to query
    for ($i = 0; $i < count($searches); $i++) {
        $query .= "bookname LIKE :q".$i." ";
        if ($i+1 != count($searches)) {
            $query .= "OR ";
        }
    }

    $query .= "ORDER BY book_id DESC";
    
    $cmd = $conn->prepare($query);
    //Bind wildcard searchterm wildcard to each query
    for ($i = 0; $i < count($searches); $i++) {
        $linkvar = ":q".$i;
        $paramvar = "%".$searches[$i]."%";
        $cmd->bindValue($linkvar, $paramvar, PDO::PARAM_STR);
    }
    $cmd->execute();

    $result = $cmd->fetchAll();

    $conn = NULL;
?>

<?php require_once("header.php"); ?>
<main class="panel panel-default">
    <div class="panel-body">
        <?php if (!empty($alert)) : ?>
        <div class="alert alert-success alert-dismissible fade in">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> <?=$alert?>
        </div>
        <?php endif; ?>
        <form action="viewBook.php" method="GET">
            <div class="form-group">
                <label for="usersearch">Book Name:</label>
                <input type="text" name="usersearch" class="searchpanel form-control" value="<?=filter_input(INPUT_GET, "usersearch")?>">
                <input type="submit" name="submit" class="searchbutton btn btn-primary" value="Search">
            </div>
        </form>
        <table class="table table-striped table-bordered table-hover" style="margin-bottom: 0px">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Genre</th>
                    <th>Review</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Purchase Link</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $r) {
                    echo "<tr><td class='col-xs-1'>".$r['bookname']."</td>";
                    echo "<td class='col-xs-1'>".$r['genre']."</td>";
                    echo "<td class='col-xs-2'>".$r['review']."</td>";
                    echo "<td class='col-xs-1'>".$r['username']."</td>";
                    echo "<td class='col-xs-1'>".$r['email']."</td>";
                    echo "<td class='col-xs-1'><a href='".$r['booklink']."'>".$r['booklink']."</a></td>";
                    echo "<td class='col-xs-2'><img src='images/".$r['image']."' alt='".$r['image']."' class='img-thumbnail bookImage'></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
<?php require_once("footer.php"); ?>