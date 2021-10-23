<?php
    if ( ! defined( 'SKDBM' ) ) { die( 'Define missing' ); }
    if ( ua() < 20 ) { die( 'Insufficient access' ); }

    $filepath = $exportfolder . $export[$_GET['export']] . '.dat';

    $file = fopen( $filepath, "w" )
        or exit( 'Unable to open file path; ' . $filepath );

    $bp = parse_ini_file( 'blueprint.ini', true )
        or exit( 'Unable to read blueprint.ini' );

    $write = "";

    preg_match_all( "/%(.+?)%/", $bp[$export[$_GET['export']]]['line'], $items );

    foreach( $db->query( "SELECT * FROM `quests` ORDER BY `members` ASC, `name` ASC" ) AS $row )
    {
        if ( ! empty( $write ) )
            $write = "\n";

        $thisline = $bp[$export[$_GET['export']]]['line'];

        for( $i = 0; $i < count( $items[1] ); $i++ )
        {
            $thisline = str_replace( array( '%' . $items[1][$i] . '%', "\n", "\r" ), array( $row[$items[1][$i]], " ", " " ), $thisline );
        }

        $write .= $thisline;

        fwrite( $file, $write );
    }

    fclose( $file );

    echo '
        <div class="alert alert-success">Quests were successfully exported!</div>
    ';

    logging( 'Export', '3', 'Quests' );