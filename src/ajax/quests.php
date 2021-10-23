<?php
    @session_start();

    // No user session, not a logged in user, kill it.
    if (! isset( $_COOKIE['user_id'] ) )
        die();

    //  This is used in an ajax call, there's not an easy way of securing it, but this is a start, check if referring site is on the same host as us
    if (! stristr( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) )
        die();

    //  Path to adventurer log to grab quest list from
    $path = "http://services.runescape.com/m=adventurers-log/quests?searchName=Stidor";

    //  Get the html of the alog
    $content = file_get_contents( $path );

    //  Regex pattern to find each quest
    $pattern = '/class="quest__title">(.+?)<\//si';

    //  Run regex match and populate $quest as an array with the results
    preg_match_all( $pattern, $content, $quests );

    //  Open a file writer so we can write a cache file as fetchign the adventure log is somewhat slow
    $file = fopen( dirname( __FILE__ ) . "/../includes/lib/adventurelog.txt", "w+" );

    //  Remember that $quests[0] holds all matched strings, ignore it and go to [1] which holds an array of each result
    foreach ($quests[1] AS $qnum => $quest) {
        echo '<option' . ( $_POST['sel'] == $quest ? ' selected="selected"' : '' ) . '>' . $quest . '</option>';
        fwrite( $file, $quest . "\n" );
    }

    fclose( $file );