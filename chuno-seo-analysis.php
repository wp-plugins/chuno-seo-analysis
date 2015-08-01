<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<style>
.s-first {
	margin: 5% auto 0;
    width: 50%;
}

.s-second {
	margin: 5% auto 0;
    width: 50%;
}

.s-third {
	margin: 5% auto 0;
    width: 50%;
}

.s-fourth {
	margin: 5% auto 0;
    width: 50%;
}

.content-center {
	border: 1px solid #ccc;
    font-weight: bold;
    margin: 0 auto;
    width: 50%;
}

.content-left {
	float:left;
}

.content-right {
	float:right;
}
</style>
<script>
$(document).ready(function(){
	$(".s-first").hide();
	$(".s-second").hide();
	$(".s-third").hide();
	$(".s-fourth").hide();
	
	$(".a-first").click(function(){
		$(".s-second").hide();
		$(".s-third").hide();
		$(".s-fourth").hide();
    	$(".s-first").show();
	});
	
	$(".a-second").click(function(){
		$(".s-first").hide();
		$(".s-third").hide();
		$(".s-fourth").hide();
    	$(".s-second").show();
	});
	
	$(".a-third").click(function(){
		$(".s-first").hide();
    	$(".s-second").hide();
		$(".s-fourth").hide();
		$(".s-third").show();
	});
	
	$(".a-fourth").click(function(){
		$(".s-first").hide();
    	$(".s-second").hide();
		$(".s-third").hide();
		$(".s-fourth").show();
	});
}); 
</script>
</head>
<?php
/*
Plugin Name: Chuno-SEO-Analysis
Plugin URI: 
Description: Unique SEO analysis plugin for your wordpress website.
Version: 1.0
Author: Muhammad Junaid Iqbal
Author URI: 
License: GPL
*/

//Adding Admin Hooks
add_action('admin_menu', 'csa_chuno_menu');
 
function csa_chuno_menu(){
        add_menu_page( 'Chuno SEO Analysis', 'Chuno SEO Analysis', 'manage_options', 'chuno-seo-analysis', 'csa_analysis_menu' );
}
 
function csa_analysis_menu(){
		
        echo "<h1>Chuno SEO Analysis</h1>";
		
		//Twitter Badge Code
		echo '<a href="https://twitter.com/zebicute" class="twitter-follow-button" data-show-count="false">Follow @zebicute</a>';
		echo "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
		
		//Wordpress Tables Prefix
		global $wpdb;
		$table_prefix = $wpdb->prefix;

		// MySQL Connection
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		//Query for Duplicate Titles
		$sql_titles = 'SELECT post_title, guid FROM '.$table_prefix.'posts WHERE post_status="publish" GROUP BY post_title HAVING count(*) > 1';
		$result_titles = $conn->query($sql_titles);

		//Query for Duplicate Meta Descriptions
		//$sql_ddescs = 'SELECT '.$table_prefix.'postmeta.meta_value, '.$table_prefix.'posts.guid FROM '.$table_prefix.'postmeta WHERE meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description" GROUP BY meta_value HAVING count(*) > 1 INNER JOIN '.$table_prefix.'postmeta.post_id ON '.$table_prefix.'posts.ID' ;
		
		$sql_ddescs = 'SELECT '.$table_prefix.'postmeta.meta_value, '.$table_prefix.'postmeta.post_id, '.$table_prefix.'posts.guid FROM '.$table_prefix.'postmeta INNER JOIN '.$table_prefix.'posts ON '.$table_prefix.'posts.ID = '.$table_prefix.'postmeta.post_id WHERE meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description" GROUP BY meta_value HAVING count(*) > 1';
		
		$result_ddescs = $conn->query($sql_ddescs);

		//Query for Long Meta Descriptions
		//$sql_ldescs = 'SELECT meta_value FROM wp_postmeta WHERE LENGTH(meta_value) > 160 AND meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description"';
		
		$sql_ldescs = 'SELECT '.$table_prefix.'postmeta.meta_value, '.$table_prefix.'postmeta.post_id, '.$table_prefix.'posts.guid FROM '.$table_prefix.'postmeta INNER JOIN '.$table_prefix.'posts ON '.$table_prefix.'posts.ID = '.$table_prefix.'postmeta.post_id WHERE LENGTH(meta_value) > 160 AND meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description"';
		
		
		$result_ldescs = $conn->query($sql_ldescs);

		//Query for Short Meta Descriptions
		$sql_sdescs = 'SELECT '.$table_prefix.'postmeta.meta_value, '.$table_prefix.'postmeta.post_id, '.$table_prefix.'posts.guid FROM '.$table_prefix.'postmeta INNER JOIN '.$table_prefix.'posts ON '.$table_prefix.'posts.ID = '.$table_prefix.'postmeta.post_id WHERE LENGTH(meta_value) < 100 AND meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description"';
		
		$result_sdescs = $conn->query($sql_sdescs);

		//Counting SQL Records
		$dup_titles = $result_titles->num_rows;
		$dup_ddescs = $result_ddescs->num_rows;
		$lng_ldescs = $result_ldescs->num_rows;
		$shrt_ldescs = $result_sdescs->num_rows;

		//Printing Fetched Data
		echo "<div class='content-center'> <div class='content-left'>Duplicate Titles</div> <div class='content-right'><a href='#' class='a-first'>$dup_titles</a></div> </div> ";
		
		echo "<br>";
		echo "<div class='content-center'> <div class='content-left'>Duplicate Meta Descriptions</div> <div class='content-right'><a href='#' class='a-second'>$dup_ddescs</a></div> </div>";
		
		echo "<br>";
		echo "<div class='content-center'> <div class='content-left'>Long Meta Descriptions</div> <div class='content-right'><a href='#' class='a-third'>$lng_ldescs</a></div> </div>";
		
		echo "<br>";
		echo "<div class='content-center'> <div class='content-left'>Short Meta Descriptions</div> <div class='content-right'><a href='#' class='a-fourth'>$shrt_ldescs</a></div> </div>";
		
		echo "<br>";

		//If Robots.txt File Exists
		$hmurl = get_home_url();
		$url = "$hmurl/robots.txt";
		$header_response = get_headers($url, 1);
		if ( strpos( $header_response[0], "404" ) !== false )
		{
		  $robntex = "Not Found";
		  echo "<div class='content-center'> <div class='content-left'>Robots Directives</div> <div class='content-right'>$robntex</div> </div>";
		} 
		else 
		{
		  $robex = "Found";
		  echo "<div class='content-center'> <div class='content-left'>Robots Directives</div> <div class='content-right'>$robex</div> </div>";
		}

		echo "<br>";

		//If Sitemap.xml File Exists
		$urlxml = "$hmurl/sitemap.xml";
		$header_response = get_headers($urlxml, 1);
		if ( strpos( $header_response[0], "404" ) !== false )
		{
		  $xmlntex = "Not Found";
		  echo "<div class='content-center'> <div class='content-left'>XML Sitemap</div> <div class='content-right'>$xmlntex</div> </div>";
		} 
		else 
		{
		  $xmlex = "Found";
		  echo "<div class='content-center'> <div class='content-left'>XML Sitemap</div> <div class='content-right'>$xmlex</div> </div>";
		}

		// Printing Header Information
		$hdinfo = get_headers($hmurl,1);
		//var_dump($hdinfo);
		echo "<br>";
		
		$httpsts = $hdinfo[0];
		$hddte = $hdinfo[Date];
		if ((is_array($hddte))) { $hddte = $hdinfo[Date][0]; } else {$hddte = $hdinfo[Date]; }
		$hdserver = $hdinfo[Server];
		if ((is_array($hdserver))) { $hdserver = $hdinfo[Server][0]; } else {$hdserver = $hdinfo[Server]; }
		$hdconn = $hdinfo[Connection];
		if ((is_array($hdconn))) { $hdconn = $hdinfo[Connection][0]; } else {$hdconn = $hdinfo[Connection]; }
		$hdcntt = $hdinfo["Content-Type"];
		if ((is_array($hdcntt))) { $hdcntt = $hdinfo["Content-Type"][0]; } else {$hdcntt = $hdinfo["Content-Type"]; }
		
		echo "<div class='content-center'> <div class='content-left'>HTTP Status</div> <div class='content-right'>$httpsts</div> </div>";
		echo "<br>";
		echo "<div class='content-center'> <div class='content-left'>Date</div> <div class='content-right'>$hddte</div> </div>";
		echo "<br>";
		echo "<div class='content-center'> <div class='content-left'>Server</div> <div class='content-right'>$hdserver</div> </div>";
		echo "<br>";
		echo "<div class='content-center'> <div class='content-left'>Connection</div> <div class='content-right'>$hdconn</div> </div>";
		echo "<br>";
		echo "<div class='content-center'> <div class='content-left'>Content Type</div> <div class='content-right'>$hdcntt</div> </div>";
		
		//JQuery Divs
		// Duplicate Titles
		echo '<div class="s-first">';
		echo '<h3>Links to Duplicate Title Posts</h3>';
		if ($dup_titles <= 10) {
		while ($row = $result_titles -> fetch_assoc()) {
			$tit_an = $row["guid"];
			echo "<strong>URL:</strong><a href='$tit_an' target=_blank>$tit_an</a><br>";
			}
		}else
		{
			echo "To view more than 10 records you will need to upgrade";
			echo "<br>";
			echo '<a href="http://www.chunoa.com/product/chuno-seo-analysis-plugin/" target="blank"><h1>Go Pro</h1></a>';
		}
		echo '</div>';
		
		// Duplicate Meta Descriptions
		echo '<div class="s-second">';
		echo '<h3>Links to Duplicate Meta Description Posts</h3>';
		if ($dup_ddescs <= 10) {
		while ($rowdesc = $result_ddescs -> fetch_assoc()) {
			$desc_an = $rowdesc["guid"];
			echo "<strong>URL:</strong><a href='$desc_an' target=_blank>$desc_an</a><br>";
			}
		}else
		{
			echo "To view more than 10 records you will need to upgrade";
			echo "<br>";
			echo '<a href="http://www.chunoa.com/product/chuno-seo-analysis-plugin/" target="_blank"><h1>Go Pro</h1></a>';
		}
		echo '</div>';
		
		// Long Meta Descriptions
		echo '<div class="s-third">';
		echo '<h3>Links to Long Meta Description Posts</h3>';
		if ($lng_ldescs <= 10) {
		while ($rowldesc = $result_ldescs -> fetch_assoc()) {
			$ldesc_an = $rowldesc["guid"];
			echo "<strong>URL:</strong><a href='$ldesc_an' target=_blank>$ldesc_an</a><br>";
			}
		}else
		{
			echo "To view more than 10 records you will need to upgrade";
			echo "<br>";
			echo '<a href="http://www.chunoa.com/product/chuno-seo-analysis-plugin/" target="_blank"><h1>Go Pro</h1></a>';
		}
		echo '</div>';
		
		// Long Meta Descriptions
		echo '<div class="s-fourth">';
		echo '<h3>Links to Short Meta Description Posts</h3>';
		if ($shrt_ldescs <= 10) {
		while ($rowsdesc = $result_sdescs -> fetch_assoc()) {
			$sdesc_an = $rowsdesc["guid"];
			echo "<strong>URL:</strong><a href='$ldesc_an' target=_blank>$sdesc_an</a><br>";
			}
		}else
		{
			echo "To view more than 10 records you will need to upgrade";
			echo "<br>";
			echo '<a href="http://www.chunoa.com/product/chuno-seo-analysis-plugin/" target="_blank"><h1>Go Pro</h1></a>';
		}
		echo '</div>';

//Closing Database Connection
$conn->close();		
}
?>