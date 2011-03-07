<?php
/**
 * Plugin Name: Popular Posts by Views
 * Plugin URI: http://net.tutsplus.com
 * Description: Create a widget to show your most popular articles bassed on views.
 * Version: 1.0
 * Author: Jonathan Wolfe
 * Author URI: http://fire-studios.com
 * License: GPL2
 * .
 * This plugin and it's accompanying tutorial are written for NETTUTS at http://net.tutsplus.com
 * .
 */

global $wpdb; // call global so we can get the database prefix
$ppbv_tablename = $wpdb->prefix.'popular_by_views'; // combine the prefix with our tablename

function ppbv_create_table(){
    global $wpdb, $ppbv_tablename; // call global so we can use them within the function
    $ppbv_table = $wpdb->get_results("SHOW TABLES LIKE '$ppbv_tablename'" , ARRAY_N); // returns null if no results
    if(is_null($ppbv_table)){ // if we don't have a table
        $create_table_sql = "CREATE TABLE $ppbv_tablename (
            id BIGINT(50) NOT NULL AUTO_INCREMENT, 
            post_id VARCHAR(255) NOT NULL, 
            views BIGINT(50) NOT NULL, 
            PRIMARY KEY (id), 
            UNIQUE (id)
        );"; // be careful with SQL syntax, it can be VERY finiky
        $wpdb->query($create_table_sql); // run the SQL statement on the database
        $wpdb->flush(); // clense the DB interface
    }
}
register_activation_hook(__FILE__,'ppbv_create_table'); // run the function 'ppbv_create_table' at plugin activation

function ppbv_page_viewed(){
    if(is_single() && !is_page()){ // only run on posts and not pages
        global $wpdb, $post, $ppbv_tablename; // call global for use in funciton
        $wpdb->flush(); // clense the DB interface
        $data = $wpdb->get_row("SELECT * FROM $ppbv_tablename WHERE post_id='$post->ID'", ARRAY_A); // get the data row that has the matching post ID
        if(!is_null($data)){ // if we have a matching data row
            $new_views = $data['views'] + 1; // increase the views by 1
            $wpdb->query("UPDATE $ppbv_tablename SET views='$new_views' WHERE post_id='$post->ID';"); // update the data row with the new views
            $wpdb->flush(); // clense the DB interface
        }
        else { // if we don't have a matching data row (nobody's viewed the post yet)
            $wpdb->query("INSERT INTO $ppbv_tablename (post_id, views) VALUES ('$post->ID','1');"); // add a new data row into the DB with the post ID and 1 view
            $wpdb->flush(); // clense the DB interface
        }
    }
}
add_action('wp_head','ppbv_page_viewed'); // attach ppbv_page_viewed to the wp_head hook

function ppbv_admin_widget(){
    echo "<ol id='popular_by_views_admin_list'>"; // create an unordered list
        global $wpdb, $ppbv_tablename; // call global for use in function
        $popular = $wpdb->get_results("SELECT * FROM $ppbv_tablename ORDER BY views DESC LIMIT 0,10",ARRAY_N); // Order our table by largest to smallest views then get the first 10 (i.e. the top 10 most viewed)
        foreach($popular as $post){ // loop through the returned array of popular posts
            $ID = $post[1]; // store the data in a variable to save a few characters and keep the code cleaner
            $views = number_format($post[2]); // number_format adds the commas in the right spots for numbers (ex: 12543 to 12,543)
            $post_url = get_permalink($ID); // get the URL of the current post in the loop
            $title = get_the_title($ID); // get the title of the current post in the loop
            echo "<li><a href='$post_url'>$title</a> - $views views</li>"; // echo out the information in a list-item
        } // end the loop
    echo "</ol>"; // close out the unordered list
}
function ppbv_add_admin_widget(){
    wp_add_dashboard_widget('popular_by_views', 'Most Popular Posts by Views', 'ppbv_admin_widget'); // creates an admin area widget || wp_add_dashboard_widget([id of div],[title in div],[function to run inside of div])
}
add_action('wp_dashboard_setup','ppbv_add_admin_widget'); // attach ppbv_add_admin_widget to wp_dashboard_setup

function ppbv_display_widget($args){
    global $wpdb, $ppbv_tablename; // call global for use in function
    extract($args); // gives us the default settings of widgets
    
    echo $before_widget; // echos the container for the widget || obtained from $args
        echo $before_title."Most Popular by Views".$after_title; // echos the title of the widget || $before_title/$after_title obtained from $args
        echo "<ol id='popular_by_views_list'>"; // create an ordered list
            $popular = $wpdb->get_results("SELECT * FROM $ppbv_tablename ORDER BY views DESC LIMIT 0,10",ARRAY_N); // Order our table by largest to smallest views then get the first 10 (i.e. the top 10 most viewed)
            foreach($popular as $post){ // loop through the returned array of popular posts
                $ID = $post[1]; // store the data in a variable to save a few characters and keep the code cleaner
                $views = number_format($post[2]); // number_format adds the commas in the right spots for numbers (ex: 12543 to 12,543)
                $post_url = get_permalink($ID); // get the URL of the current post in the loop
                $title = get_the_title($ID); // get the title of the current post in the loop
                echo "<li><a href='$post_url}'>$title</a> - $views views</li>"; // echo out the information in a list-item
            } // end the loop
        echo "</ol>"; // close the ordered list
    echo $after_widget; // close the container || obtained from $args
}
wp_register_sidebar_widget('popular_by_views', 'Most Popular Posts by Views', 'ppbv_display_widget'); // add the widget to the select menu || wp_register_sidebar_widget([id of the option],[title of the option],[function to run from the widget]))

function ppbv_display() {
    global $wpdb, $ppbv_tablename; // call global for use in function
    
    echo "<div id='popular_by_views'>"; // create a container
        echo "<h2>Most Popular by Views</h2>"; // write the title
        echo "<ol id='popular_by_views_list'>"; // create an ordered list
            $popular = $wpdb->get_results("SELECT * FROM $ppbv_tablename ORDER BY views DESC LIMIT 0,10",ARRAY_N);
            foreach($popular as $post){ // loop through the returned array of popular posts
                $ID = $post[1]; // store the data in a variable to save a few characters and keep the code cleaner
                $views = number_format($post[2]); // number_format adds the commas in the right spots for numbers (ex: 12543 to 12,543)
                $post_url = get_permalink($ID); // get the URL of the current post in the loop
                $title = get_the_title($ID); // get the title of the current post in the loop
                echo "<li><a href='$post_url'>$title</a> - $views views</li>"; // echo out the information in a list-item
            } // end the loop
        echo "</ol>"; // close the ordered list
    echo "</div>"; // close the container
}

function dgm_ppbv_display($number = 10) {
	/* DGM customized version of ppbv_display()
	 * Display X most popular posts, each wrapped in <li> tags
	 * v1.0
	 */
    global $wpdb, $ppbv_tablename; // call global for use in function
    $popular = $wpdb->get_results("SELECT * FROM $ppbv_tablename ORDER BY views DESC LIMIT 0,$number",ARRAY_N);
    
    foreach($popular as $post)
    { // loop through the returned array of popular posts
	    $ID = $post[1]; // store the data in a variable to save a few characters and keep the code cleaner
	    $views = number_format($post[2]); // number_format adds the commas in the right spots for numbers (ex: 12543 to 12,543)
	    $post_url = get_permalink($ID); // get the URL of the current post in the loop
	    $title = get_the_title($ID); // get the title of the current post in the loop
	    echo "<li><a href='$post_url'>$title</a> - $views views</li>"; // echo out the information in a list-item
    } // end the loop
     

}


?>