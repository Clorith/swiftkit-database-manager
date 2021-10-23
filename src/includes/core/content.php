<?php	
	if (!defined('SKDBM')) { die('Define missing'); }
?>

	<?php

        if ( strstr( '.', $section ) || strstr( '.', $page ) )
            echo "I'm pretty sure there's no pages with dots in them, nice try.";
		else
		    include_once( 'includes/' . $section . '/' . $page . '.php' );
		
	?>
