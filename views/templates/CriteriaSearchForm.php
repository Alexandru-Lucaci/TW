<?php
    $criterias=array(
    "ordonare"=>array('denumire_populara','denumire_stintifica','origine','clasa','invaziva','stare_de_conservare','regim_alimentar','mod_de_inmultire','nr_accesari','nr_salvari','nr_descarcari'),
    "origine"=>array('america de nord','america de sud','europa','africa','asia','australia','antarctida'),
    "clasa"=>array('amfibieni','arahnide','asteroidea','bivalve','cefalopode','chilopoda','diplopoda','gasteropode','hydrozoa','insecte','malacostraca','mamifere','ostracode','pasari','pesti','polichete','reptile'),
    "invaziva"=>array('da','nu'),
    "stare_de_conservare"=>array('disparuta, extincta','disparuta din salbaticie','in pericol critic de disparitie','in pericol de disparitie','vulnerabila','aproape amenintată cu disparitia','neamenintata cu disparitia','date insuficiente','neevaluata'),
    "regim_alimentar"=>array('ierbivor','omnivor','carnivor'),
    "mod_de_inmultire"=>array('reproducere sexuală','reproducere asexuala')
    );

    echo "<form action=\"index.php\" method=\"post\">";
        
        echo "<input type=\"hidden\" name=\"load\" value=\"Animals/show\" />";
        echo "<input type=\"hidden\" name=\"function\" value=\"multicriterial_search\" />";
        
        foreach($criterias as $name=>$values){
            
            if($name=="ordonare"){
                $displayName="Ordoneaza ascendent dupa:";
            }
            else{
                $displayName=ucfirst(str_replace('_',' ',$name));
            }

            echo "<br><br>";

            echo "<div class=\"categorieFiltru\">";
            
            echo "<ul class=\"todos\">";

            echo "<div class=\"toggler\">";
            echo "<label>$displayName </label>";
            echo "</div>";
            echo "<ul class=\"toggler-target active\">";
            
            foreach($values as $value){

                $displayValue=ucfirst($value);

                echo "<br>";
                
                echo "<input type=\"checkbox\" name=\"$name",'[]',"\" value=\"$value\"";
                if(isset($_SESSION[$name])&&!empty($_SESSION[$name])&&in_array($value,$_SESSION[$name])){
                    echo " checked ";
                }
                echo "/>";

                
                echo "<label>$displayValue</label>";

                
            }
            echo "</ul>";
            echo "</ul>";
            echo "</div>";
            
        }
        echo "<br>";

        echo "<input type=\"submit\" />";

    echo "</form>";
?>