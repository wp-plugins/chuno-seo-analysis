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
		$sql_titles = 'SELECT post_title FROM '.$table_prefix.'posts WHERE post_status="publish" GROUP BY post_title HAVING count(*) > 1';
		$result_titles = $conn->query($sql_titles);

		//Query for Duplicate Meta Descriptions
		$sql_ddescs = 'SELECT meta_value FROM '.$table_prefix.'postmeta WHERE meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description" GROUP BY meta_value HAVING count(*) > 1';
		$result_ddescs = $conn->query($sql_ddescs);

		//Query for Long Meta Descriptions
		$sql_ldescs = 'SELECT meta_value FROM wp_postmeta WHERE LENGTH(meta_value) > 160 AND meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description"';
		$result_ldescs = $conn->query($sql_ldescs);

		//Query for Short Meta Descriptions
		$sql_sdescs = 'SELECT meta_value FROM wp_postmeta WHERE LENGTH(meta_value) < 100 AND meta_key = "_aioseop_description" OR meta_key = "_yoast_wpseo_metadesc" OR meta_key = "_amt_description"';
		$result_sdescs = $conn->query($sql_sdescs);

		//Counting SQL Records
		$dup_titles = $result_titles->num_rows;
		$dup_ddescs = $result_ddescs->num_rows;
		$lng_ldescs = $result_ldescs->num_rows;
		$shrt_ldescs = $result_sdescs->num_rows;

		//Printing Fetched Data
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Duplicate Titles</div> <div style='float:right;'>$dup_titles</div> </div>";
		
		echo "<br>";
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Duplicate Meta Descriptions</div> <div style='float:right;'>$dup_ddescs</div> </div>";
		
		echo "<br>";
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Long Meta Descriptions</div> <div style='float:right;'>$lng_ldescs</div> </div>";
		
		echo "<br>";
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Short Meta Descriptions</div> <div style='float:right;'>$shrt_ldescs</div> </div>";
		
		echo "<br>";

		//If Robots.txt File Exists
		$hmurl = get_home_url();
		$url = "$hmurl/robots.txt";
		$header_response = get_headers($url, 1);
		if ( strpos( $header_response[0], "404" ) !== false )
		{
		  $robntex = "Not Found";
		  echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Robots Directives</div> <div style='float:right;'>$robntex</div> </div>";
		} 
		else 
		{
		  $robex = "Found";
		  echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Robots Directives</div> <div style='float:right;'>$robex</div> </div>";
		}

		echo "<br>";

		//If Sitemap.xml File Exists
		$urlxml = "$hmurl/sitemap.xml";
		$header_response = get_headers($urlxml, 1);
		if ( strpos( $header_response[0], "404" ) !== false )
		{
		  $xmlntex = "Not Found";
		  echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>XML Sitemap</div> <div style='float:right;'>$xmlntex</div> </div>";
		} 
		else 
		{
		  $xmlex = "Found";
		  echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>XML Sitemap</div> <div style='float:right;'>$xmlex</div> </div>";
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
		
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>HTTP Status</div> <div style='float:right;'>$httpsts</div> </div>";
		echo "<br>";
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Date</div> <div style='float:right;'>$hddte</div> </div>";
		echo "<br>";
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Server</div> <div style='float:right;'>$hdserver</div> </div>";
		echo "<br>";
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Connection</div> <div style='float:right;'>$hdconn</div> </div>";
		echo "<br>";
		echo "<div style='width:50%; margin:0 auto; font-weight:bold; border:1px solid #ccc;'> <div style='float:left;'>Content Type</div> <div style='float:right;'>$hdcntt</div> </div>";

//Closing Database Connection
$conn->close();		
}
?>