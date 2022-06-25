<?php 
    echo "<div class=\"itemsContent\">";
    echo "<div class=\"rightplace\">";
    //construct the page
    //constants
    // $nrAnimalsPerPage=50;
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

            foreach($results as $result)
            {
                //get animal info to display
                $popularName=htmlentities($result['DENUMIRE_POPULARA']);
                $scientificName=htmlentities($result['DENUMIRE_STINTIFICA']);
                $smallDescription=$result['MINI_DESCRIERE'];

                //display
                
                echo "<div id=\"$popularName\" class=\"card\" onclick=\"markAnimal('$popularName')\" >";
                    
                    $path="views/images/animals/".ucwords(strtolower($popularName)).".png";
                    if(file_exists($path)){
                        echo "<img src=\"$path\" alt=\"Imagine animal cu $popularName\" class=\"card-img\"/>";
                    }
                    else{
                        echo "<img src=\"views/images/animals/empty.jpg\" alt=\"Imagine animal\" class=\"card-img\"/>";
                    }


                    //echo "<img src=\"views/images/animals/empty.jpg\" alt=\"Imagine animal\" class=\"card-img\"/>";
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

                    if(isset($_SESSION['login'])&&!empty($_SESSION['login'])&&$_SESSION['login']==1&&isset($_SESSION['name'])&&!empty($_SESSION['name'])){
                        //check to see if the animal is already saved
                        $username=$_SESSION['name'];

                        $response=AnimalsModel::animal_saved($username,$popularName);
                        // echo $response;
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