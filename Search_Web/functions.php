<?php

function url_exists( $url = NULL ) {

	if( empty( $url ) ){
		return false;
	}

	@$headers = get_headers( $url );

	if (preg_match('/^HTTP\/\d\.\d\s+(200|301|302)/', $headers[0])){
		return true;
	}
	else return false;
}

function rel2abs($rel, $base){
	if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;
	if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;
	extract(parse_url($base));
	$path = preg_replace('#/[^/]*$#', '', $path);
	if ($rel[0] == '/') $path = '';
	$abs = "$host$path/$rel";
	$re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
	for($n=1; $n>0;$abs=preg_replace($re,'/', $abs,-1,$n)){}
		$abs=str_replace("../","",$abs);
	return $scheme.'://'.$abs;
}

function perfect_url($u, $b){
	$bp=parse_url($b);
	if(($bp['path']!="/" && $bp['path']!="") || $bp['path']==''){
		if($bp['scheme']==""){$scheme="http";}else{$scheme=$bp['scheme'];}
		$b=$scheme."://".$bp['host']."/";
	}
	if(substr($u,0,2)=="//"){
		$u="http:".$u;
	}
	if(substr($u,0,4)!="http"){
		$u=rel2abs($u,$b);
	}
	return $u;
}

function crawl_site($u){

	set_time_limit(0);

	$time_start = microtime(true);

	$html = file_get_html($u);

	//Paragraphs
	$concat_paragraphs = "";
  
	foreach($html->find("body > p") as $paragraphs){

		$paragraphs = trim(strip_tags($paragraphs->innertext));

		$array_paragraphs[] = $paragraphs;
		$concat_paragraphs .= $paragraphs;
	}

	//Words
	foreach($array_paragraphs as $paragraphs){
		$arr_w_tmp = explode(" ", string_delete_symbols($paragraphs, TRUE));
		$arr_w = array_unique($arr_w_tmp);

		foreach($arr_w_tmp as $word){
			$word = trim($word);

			if($word != NULL or $word != ""){
				$array_words[] = preg_replace('([^A-Za-z0-9])', '', string_delete_symbols($word, TRUE));
				$array_words_full[] = preg_replace('([^A-Za-z0-9])', '', string_delete_symbols($word, TRUE));
			}
		}
		$array_words = array_unique($array_words);//
	}

	$time_end = microtime(true);

	$page_url = "";
	$page_title = "";
	$page_description = "";
	$page_last_modified = "";
	$page_time_to_index = "";

    //Gets Webpage URL
	$page_url = $u;

  	// Gets Webpage Title
	if(strlen($html)>0)
	{
    	preg_match("@<title>(.*)</title>@",$html,$title); // ignore case
    	$page_title=strip_tags($title[1]);
	}

  	// Gets Webpage Description
	$b = $u;
	@$p_url = parse_url( $b );
	@$tags = get_meta_tags($p_url['scheme'].'://'.$p_url['host'] );
	$page_description = $tags['description'];

	// Gets Webpage LastModified
	$last_modified = get_headers($u, 1);
	$page_last_modified = $last_modified["Last-Modified"];
	$page_last_modified = trim(str_replace("last-modified:", "", $page_last_modified));

 	// Gets Webpage Time to Index
	$page_time_to_index = $time_end - $time_start;

	//Connect to Mysqli
	require("config.php");

	//Connect to server DB
	$conex = mysqli_connect($db_host, $db_user, $db_pass) or die("Can not connect to Server: ". mysqli_error());
	mysqli_select_db($conex,$db_name ) or die("Problem selecting DB $db_name : ". mysqli_error());

 	//Insert Data Page in Mysqli
	$sqli_query = "INSERT INTO page (url, title, description, lastModified, timeToIndex)
					VALUES ('".$page_url."', '".$page_title."', '".$page_description."', '".$page_last_modified."', '".$page_time_to_index."')";
	mysqli_query($conex,$sqli_query) or die("Error: sqli Query. ".mysqli_error());

	$page_id = mysqli_insert_id();

	//Insert Data Word in Mysqli
	$array_word_id = array();
	if(count($array_words) > 0){
		//arr_w_tmp
		$str = ", " . implode(", ",$array_words_full) . ",";

		foreach($array_words as $words){
			$sqli_query = "INSERT INTO word (wordName)
			VALUES ('".$words."')";
			mysqli_query($conex,$sqli_query) or die("Error: sqli Query. ".mysqli_error());
			$array_word_id[] = mysqli_insert_id();

			$array_count_words[] = substr_count($str, " ".$words.",");
		}
	}

  	//Insert Relation Data Page - Word in Mysqli
	if(count($array_word_id) > 0){
		$i = 0;
		foreach($array_word_id as $word_id){
			$sqli_query = "INSERT INTO page_word (pageId, wordId, freq)
			VALUES('".$page_id."', '".$word_id."', '".$array_count_words[$i]."')";
			mysqli_query($conex,$sqli_query) or die("Error: sqli Query. ".mysqli_error());

			$i++;
		}
	}

}


function search_result($search = NULL, $case_insensitive = NULL, $allow_partial_match = NULL){
	$result_search = "";

	if($search == NULL){
		$result_search .= '<div class="result">You must enter any word.</div>';
	}else{
		//Connect to Mysqli
		require("config.php");

		$time_start = microtime(true);

		//Connect to server DB
		$conex = mysqli_connect($db_host, $db_user, $db_pass) or die("Can not connect to Server: ". mysqli_error());
		mysqli_select_db($conex,$db_name) or die("Problem selecting DB $db_name : ". mysqli_error());

		$search = trim(string_delete_symbols($search));

		if($case_insensitive == NULL && $allow_partial_match == NULL){
			$sqli_where = " word.wordName = '".$search."' ";
		}
		elseif($case_insensitive != NULL && $allow_partial_match == NULL){
			$sqli_where = " UPPER(word.wordName) = UPPER('".$search."') ";
		}
		elseif($case_insensitive == NULL && $allow_partial_match != NULL){
			$sqli_where = " word.wordName LIKE '%".$search."%'";
		}
		else{
			$sqli_where = " UPPER(wordName) LIKE UPPER('%".$search."%') ";
		}

		$sqli_query = "SELECT *
						FROM page, word, page_word 
						WHERE page.pageId = page_word.pageId 
							AND word.wordId = page_word.wordId 
							AND ".$sqli_where."
						ORDER BY freq";
		$query_exec = mysqli_query($conex,$sqli_query) or die("Error: sqli Query. ".mysqli_error());

		$num_rows = mysqli_num_rows($query_exec);

		if($num_rows > 0){

			for($i=0; $i < $num_rows; $i++){
				$array_result = mysqli_fetch_array($query_exec);

				$result_search .= '<div class="result">';
				//Page Title
				$result_search .= '<p><a class="title" href="'.$array_result["url"].'">'.$array_result["title"].'</a></p>';
				//Page Link / URL
				$result_search .= '<p><a class="link" href="'.$array_result["url"].'">'.$array_result["url"].'</a></p>';
				//Page Description
				$result_search .= '<p class="description">'.$array_result["description"].'</p>';
				$result_search .= '</div>';
			}

		}else{
			$result_search .= '<div class="result">No results found.</div>';
		}	

		$time_end = microtime(true);

		$time_to_search = $time_end - $time_start;

		//Insert in Mysqli Search Entry
		$sqli_query = "INSERT INTO search(terms, count, searchDate, timeToSearch)
							VALUES('".$search."', '".$num_rows."', NOW(), '".$time_to_search."')";
		$query_exec = mysqli_query($conex,$sqli_query) or die("Error: sqli Query. ".mysqli_error());


	}

	return $result_search;

}

function generate_search_history(){
	require("config.php");
	//Connect to server DB
	$conex = mysqli_connect($db_host, $db_user, $db_pass) or die("Can not connect to Server: ". mysqli_error());
	mysqli_select_db($conex,$db_name) or die("Problem selecting DB $db_name : ". mysqli_error());

	$sqli_query = "SELECT * FROM search ORDER BY searchDate";
	$query_exec = mysqli_query($conex,$sqli_query) or die("Error: sqli Query. ".mysqli_error());

	$num_rows = mysqli_num_rows($query_exec);

	$result = '';

	if($num_rows > 0){

		for($i=0; $i < $num_rows; $i++){
				$array_result = mysqli_fetch_array($query_exec);

				$result.= '
					<tr>
						<td>'.$array_result["terms"].'</td>
						<td align="center">'.$array_result["count"].'</td>
						<td>'.$array_result["searchDate"].'</td>
						<td>'.$array_result["timeToSearch"].' Sec.</td>
					</tr>
				';

				
		}

	}else{
			$result = '
			<tr>
				<td colspan="4">No Result</td>
			<tr>
			';

	}

	return $result;
}

function string_delete_symbols($string, $include_symbols = FALSE){
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
 	if($include_symbols == TRUE){
	    $string = str_replace(
	        array("\\", "¨", "º", "-", "~",
	             "#", "@", "|", "!", "\"",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "<code>", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":",
	             "."),
	        '',
	        $string
	    );
	}
return $string;
} 
