<?php
require("simple_html_dom.php");
require("functions.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="searchStyle.css">
<title>ADMIN Indexing</title>


<style>
    h2{
        font-size: 40px;
        color: black;
    }
</style>
</head>

<body>
    <nav>
        <div id="logo">CS355</div>
    
        <label for="drop" class="toggle">Menu</label>
        <input type="checkbox" id="drop" >
            <ul class="menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="https://learn.zybooks.com/library">ZYBOOKS</a></li>
                <li>
                  <!-- First Tier Drop Down -->
                  <label for="drop-3" class="toggle">Browser+</label>
                  <a href="browser.html">browser</a>
                  <input type="checkbox" id="drop-3"/>
                  <ul>
                      <li><a href="navigator.html">Navigator</a></li>
                      <li><a href="screen.html">Screen</a></li>
                      <li><a href="window.html">Window</a></li>
                      <li><a href="location.html">Location</a></li>
                  </ul> 
    
              </li>
                <li>
                    <!-- First Tier Drop Down -->
                    <label for="drop-1" class="toggle">Search +</label>
                    <a href="search.html">Search</a>
                    <input type="checkbox" id="drop-1"/>
                    <ul>
                        <li><a href="SearchFixed.html">Fixed Search</a></li>
                        <li><a href="#">FormFile</a></li>
                        <li><a href="#">GoogleAPi</a></li>
                        <li><a href="#">SearchEngine</a></li>
                    </ul> 
    
                </li>
                <li>
                    <!-- First Tier Drop Down -->
                    <label for="drop-1" class="toggle">Search Engine</label>
                    <a href="#">Search Engine</a>
                    <input type="checkbox" id="drop-1"/>
                    <ul>
                        <li><a href="search_entry.php">Search Entry</a></li>
                        <li><a href="admin_indexing.php">ADMIN Indexing</a></li>
                        <li><a href="search_history.php">ADMIN Search History and Stats</a></li>
                    </ul> 
    
                </li>
                <li>
                <label for="drop-2" class="toggle">Contact +</label>
                <a href="#">Contact</a>
                <input type="checkbox" id="drop-2"/>
                <ul>
                    <li><a href="about.html">About</a></li>
                    <li><a href="#">Information</a></li>
                    
                  </ul>
       
                </li>
                
                
    
            </ul>
        </nav>
    

    <br>
    <br>
    <div class="form">  
        <form action="admin_indexing.php" method="POST">
          <label for="form-search"><b>Enter Valid URL</b></label>
          <input type="text" name="url" id="form-search" placeholder="http://mywebsite.com" >
          <button type="submit" id="start_crawling" name="submit" value="crawling" class = "button small dark flat">Start Crawling</button>
        </form>
        <small>The URL's you submit for crawling are recorded.</small><br/><small>See All Crawled URL's <a href="url-crawled.html" target="_blank">here</a></small>.
    </div>  
    <br/>
    <div class="form">
        <?php

        if(isset($_POST['submit'])){
            $url=$_POST['url'];
            if($url==''){
                echo "<h2>A valid URL please.</h2>";
            }else{
                $url_validate = url_exists($url); 
                if($url_validate){
                    /*$f=fopen("url-crawled.html","a+");
                    fwrite($f,"<div><a href='$url'>$url</a> - ".date("Y-m-d H:i:s")."</div>");
                    fclose($f);*/
                    echo "<h2>Result: URL Found</h2>";
                    echo"<h3>Crawler successfully!</h3>";
                    crawl_site($url);
                }else{
                    echo "<h2>Result: URL NOT Found</h2>";
                } 
            }
        }
        ?>
    </div>
 
    

</body>

</html>