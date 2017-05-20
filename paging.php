<?php
echo "<div class=\"pull-left pagination\">";
echo "<ul class=\"pagination\">";

// button for first page
if($page>1){
    echo "<li>";
		echo "<a href='{$page_url}' title='Revenir à la première page.'>";
			echo "Première page";
		echo "</a>";
	echo "</li>";
}

// count all products in the database to calculate total pages
$total_pages = ceil($total_rows / $records_per_page);

// range of links to show
$range = 2;

// display links to 'range of pages' around 'current page'
$initial_num = $page - $range;
$condition_limit_num = ($page + $range)  + 1;

for ($x=$initial_num; $x<$condition_limit_num; $x++) {
    
    // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
    if (($x > 0) && ($x <= $total_pages)) {
    
        // current page
        if ($x == $page) {
            echo "<li class='active'><a href=\"#\">$x <span class=\"sr-only\">(current)</span></a></li>";
        } 
        
        // not current page
        else {
            echo "<li><a href='{$page_url}page=$x'>$x</a></li>";
        }
    }
}

// button for last page
if($page<$total_pages){
	echo "<li>";
		echo "<a href='{$page_url}page={$total_pages}' title='Dernière page est {$total_pages}.'>";
			echo "Dernière Page";
		echo "</a>";
	echo "</li>";
}

echo "</ul>";
echo "</div>";