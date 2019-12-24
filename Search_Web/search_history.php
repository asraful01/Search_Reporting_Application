<?php
require("functions.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<link rel="stylesheet" href="searchStyle.css">
<title>Search History and Stats</title>

<style>
	table {
	  border-collapse: collapse;
	}

	table, th, td {
	  border: 1px solid black;
	}

	table th{
		background-color: silver;
	}
	table td{
		background-color: aliceblue;
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
          <label for="form-search"><b>Search History and Stats</b></label>
          
    </div>  
 
    <div class="result">
    	<table width="80%" align="center" border="1">
    		<thead>
    			<tr>
    				<th width="40%">Terms</th>
    				<th width="20%">Number of search result</th>
    				<th width="20%">Date</th>
    				<th width="20%">Time to Search</th>
    			</tr>
    		</thead>
    		<tbody>
    			<?php 
    				echo generate_search_history();
    			?>
    		</tbody>
    	</table>
    </div>

</body>

</html>