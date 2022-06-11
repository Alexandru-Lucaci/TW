<?php 
    echo "<div class=\"itemsContent\">";
    echo "<div class=\"rightplace\">";
    //construct the page
    //constants
    $nrAnimalsPerPage=10;
    if(!isset($_SESSION['search_results'])||empty($_SESSION['search_results'])){
        echo "<p>Nothing to show</p>";
    }
    else if(!isset($_SESSION['page_number'])||empty($_SESSION['page_number'])){
        echo "<p>Nothing to show</p>";
    }
    else{
        $results=$_SESSION['search_results'];
        $pageNumber=$_SESSION['page_number'];
        $nrAnimals=count($results);

        if($nrAnimals==0){
            echo "<p>No results :(</p>";
        }
        else{
            //get the start and beggining of the page
            $startPos=($pageNumber-1)*$nrAnimalsPerPage;
            $endPos=$nrAnimalsPerPage*$pageNumber-1;
            
            for($position=$startPos;$position<=$endPos&&$position<$nrAnimals;$position++){
                //get animal info to display
                $popularName=htmlentities($results[$position]['DENUMIRE_POPULARA']);
                $scientificName=htmlentities($results[$position]['DENUMIRE_STINTIFICA']);
                $smallDescription=htmlentities($results[$position]['MINI_DESCRIERE']);

                //display
                echo "<div class=\"card\">";
                    echo "<img src=\"views/images/empty.jpg\" alt=\"Imagine animal\" class=\"card-img\"/>";
                    echo "<h2 class=\"card-title\">$popularName</h2>";
                    //echo "<h2 class=\"card-title\">$scientificName</h2>";
                    echo "<div class=\"card-content\">";
                        echo "<p>$smallDescription</p>";
                    echo "</div>";
                echo "</div>";
            }

            echo "<div>";

            //display previous page form
            echo "<form action=\"index.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"load\" value=\"About/update\">";
                echo "<input type=\"hidden\" name=\"function\" value=\"change_results_page\">";
                echo "<input type=\"hidden\" name=\"change_value\" value=\"-1\">";
                echo "<input type=\"submit\" value=\"Previous Page\">";
            echo "</form>";

            //display current page
            echo "<h4>$pageNumber</h4>";

            //display next page form
            echo "<form action=\"index.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"load\" value=\"About/update\">";
                echo "<input type=\"hidden\" name=\"function\" value=\"change_results_page\">";
                echo "<input type=\"hidden\" name=\"change_value\" value=\"1\">";
                echo "<input type=\"hidden\" name=\"results_per_page\" value=\"$nrAnimalsPerPage\">";
                echo "<input type=\"submit\" value=\"Next Page\">";
            echo "</form>";

            echo "</div>";
        }
    }
            
    echo "</div>";
    echo "</div>";
?>