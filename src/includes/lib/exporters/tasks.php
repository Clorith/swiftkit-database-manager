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

    foreach( $db->query( "SELECT * FROM `achievement_diaries`" ) AS $row )
    {
        if ( ! empty( $write ) )
            $write = "\n";

        $difficulty = '';
        switch ( $row['difficulty'] ) {
            case '0':
                $difficulty = 'Beginner';
                break;
            case '1':
                $difficulty = 'Easy';
                break;
            case '2':
                $difficulty = 'Medium';
                break;
            case '3':
                $difficulty = 'Hard';
                break;
            case '4':
                $difficulty = 'Elite';
                break;
            default:
                $difficulty = 'Easy';
        }

        $row['difficulty'] = $difficulty;

        $thisline = $bp[$export[$_GET['export']]]['line'];
		$filter = array(
			'remove'  => array(
				"\r",
				"\n"
			),
			'replace' => array(
				", ",
				", "
			)
		);

        for( $i = 0; $i < count( $items[1] ); $i++ )
        {
            $thisline = str_replace( '%' . $items[1][$i] . '%', str_replace( $filter['remove'], $filter['replace'], $row[$items[1][$i]] ), $thisline );
        }

        $write .= $thisline;
        
        fwrite( $file, $write );
    }

    fclose( $file );

    echo '
        <div class="alert alert-success">Tasks were successfully exported!</div>
    ';

    logging( 'Export', '3', 'Tasks' );