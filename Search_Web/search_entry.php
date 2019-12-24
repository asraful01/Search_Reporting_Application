<?php
require("functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="searchStyle.css">
<title>Search Entry</title>
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
        <form method="post" action="search_entry.php">
          <label for="form-search">Enter a word or words</label>
          <input type="text" name="search" id="form-search" placeholder="Enter a word or words" >
          <button type="submit" name="submit" id="google_search" class = "button small dark flat">Search</button>
          <br/>
          <div style="width: 45%; float: left; text-align: right; margin-right: 5%;">
            <input type="checkbox" name="chk-case-insensitive" value="1" /> <label> <small>Case Insensitive</small></label>
          </div>
          <div style="width: 50%;float: right; text-align: left;">
            <input type="checkbox" name="chk-allow-partial-match" value="1"/><label> <small>Allow Partial match</small></label>
          </div>
        </form>
      </div>  
 
    <div>
        <?php
        if(isset($_POST['submit'])){
            $search = trim($_POST['search']);
            $case_insensitive = $_POST['chk-case-insensitive'];
            $allow_partial_match = $_POST['chk-allow-partial-match'];

            echo search_result($search, $case_insensitive, $allow_partial_match);
        }
        ?>
        
    
        
    
           


    </div>

</body>

</html>