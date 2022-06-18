<?php 
    echo "<div class=\"itemsContent\">";
    echo "<div class=\"rightplace\">";
    //construct the page
    //constants
    $nrAnimalsPerPage=5;
    if(!isset($_SESSION['search_results'])||empty($_SESSION['search_results'])){
        echo "<p>Nimic de aratat</p>";
    }
    else if(!isset($_SESSION['page_number'])||empty($_SESSION['page_number'])){
        echo "<p>Nimic de aratat</p>";
    }
    else{
        $results=$_SESSION['search_results'];
        $pageNumber=$_SESSION['page_number'];
        $nrAnimals=count($results);

        if($nrAnimals==0){
            echo "<p>Niciun rezultat :(</p>";
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
                
                echo "<div id=\"$popularName\" class=\"card\" onclick=\"markAnimal('$popularName')\" >";
                    echo "<img src=\"views/images/animals/empty.jpg\" alt=\"Imagine animal\" class=\"card-img\"/>";
                    echo "<h2 class=\"card-title\">$popularName</h2>";
                    //echo "<h2 class=\"card-title\">$scientificName</h2>";
                    echo "<div class=\"card-content\">";
                        echo "<p>$smallDescription</p>";
                    echo "</div>";

                    //buttons to download animal info as xml
                    echo "<div>";
                        echo "<form action=\"index.php\" method=\"post\">";
                            echo "<input type=\"hidden\" name=\"load\" value=\"MoreInfo/download\" />";
                            echo "<input type=\"hidden\" name=\"file_format\" value=\"xml\" />";
                    
                            echo "<input type=\"hidden\" name=\"animals_names\" value=\"$popularName\" />";
                            echo "<div class=\"card-content\">";
                                echo "<input type=\"submit\" value=\"Descarca ca XML\" class=\"filtbutt\"/>";
                            echo "</div>";
                        echo "</form>";
                    echo "</div>";

                    //buttons to download animal info as json
                    echo "<div>";
                        echo "<form action=\"index.php\" method=\"post\">";
                            echo "<input type=\"hidden\" name=\"load\" value=\"MoreInfo/download\" />";
                            echo "<input type=\"hidden\" name=\"file_format\" value=\"json\" />";
                    
                            echo "<input type=\"hidden\" name=\"animals_names\" value=\"$popularName\" />";

                            echo "<div class=\"card-content\">";
                                echo "<input type=\"submit\" value=\"Descarca ca JSON\" class=\"filtbutt\"/>";
                            echo "</div>";
                            
                        echo "</form>";
                    echo "</div>";

                    //button for more info about this animal
                    echo "<div>";
                        echo "<form action=\"index.php\" method=\"post\" >";
                            echo "<input type=\"hidden\" name=\"load\" value=\"MoreInfo/show\" />";
                            echo "<input type=\"hidden\" name=\"function\" value=\"get_animal_information\" />";
                            echo "<input type=\"hidden\" name=\"animal_name\" value=\"$popularName\" />";

                            echo "<div class=\"card-content\">";
                                echo "<input type=\"submit\" value=\"Mai multe\" class=\"filtbutt\"/>";
                            echo "</div>";

        
                        echo "</form>";
                    echo "</div>";

                    if(isset($_SESSION['loggedIn'])&&!empty($_SESSION['loggedIn'])&&$_SESSION['loggedIn']==1&&isset($_SESSION['username'])&&!empty($_SESSION['username'])){
                        //check to see if the animal is already saved
                        $username=$_SESSION['username'];

                        $response=AnimalsModel::animal_saved($username,$popularName);

                        if(!is_string($response)){

                            if($response){
                                //button for deleting an animal from savings
                                echo "<div>";
                                    echo "<form action=\"index.php\" method=\"post\" >";
                                        echo "<input type=\"hidden\" name=\"load\" value=\"Animals/update\" />";
                                        echo "<input type=\"hidden\" name=\"function\" value=\"delete_animal_from_savings\" />";
                                        echo "<input type=\"hidden\" name=\"animal_name\" value=\"$popularName\" />";

                                        echo "<div class=\"card-content\">";
                                            echo "<input type=\"submit\" value=\"Sterge(de la salvari)\" class=\"filtbutt\"/>";
                                        echo "</div>";
                                        
                                    echo "</form>";
                                echo "</div>";
                            }
                            else{
                                //button for saving info about an animal to the apporpiate account
                                echo "<div>";
                                    echo "<form action=\"index.php\" method=\"post\" >";
                                        echo "<input type=\"hidden\" name=\"load\" value=\"Animals/update\" />";
                                        echo "<input type=\"hidden\" name=\"function\" value=\"save_animals\" />";
                                        echo "<input type=\"hidden\" name=\"animal_names\" value=\"$popularName\" />";
                                        echo "<div class=\"card-content\">";
                                            echo "<input type=\"submit\" value=\"Salveaza\" class=\"filtbutt\"/>";
                                        echo "</div>";
                                        
                                    echo "</form>";
                                echo "</div>";
                            }
                        }
                        else{
                            echo "<div>";
                                echo "<p>$response</p>";
                            echo "</div>";
                        }
                    }

                echo "</div>";
            }

            echo "<div>";

            //display previous page form
            echo "<form action=\"index.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"load\" value=\"Animals/update\">";
                echo "<input type=\"hidden\" name=\"function\" value=\"change_results_page\">";
                echo "<input type=\"hidden\" name=\"change_value\" value=\"-1\">";
                echo "<input type=\"submit\" value=\"Pagina Anterioara\" class =\"filtbutt\">";
            echo "</form>";

            //display current page
            echo "<h2 style = \"text-align: center\" >$pageNumber</h2>";

            //display next page form
            echo "<form action=\"index.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"load\" value=\"Animals/update\">";
                echo "<input type=\"hidden\" name=\"function\" value=\"change_results_page\">";
                echo "<input type=\"hidden\" name=\"change_value\" value=\"1\" >";
                echo "<input type=\"hidden\" name=\"results_per_page\" value=\"$nrAnimalsPerPage\">";
                echo "<input type=\"submit\" value=\"Pagina Urmatoare\" class=\"filtbutt\">";
            echo "</form>";

            echo "</div>";
        }
    }

    if(is_string($data)){
        // echo "<p>Message:$data</p>";
    }
            
    echo "</div>";
    echo "</div>";
?>
<!--Used to remark animals the animals already selected after changing the page -->
<script>
    remarkAnimals();
</script>