<?php
    if ( ! defined( 'SKDBM' ) ) { die( 'Define missing' ); }
    if ( ua() < 20 ) { die( 'Insufficient access' ); }

    $filepath = $exportfolder . $export[$_GET['skill']] . '.dat';

    $file = fopen( $filepath, "w" )
        or exit( 'Unable to open file path; ' . $filepath );

    $bp = parse_ini_file( 'blueprint.ini', true )
        or exit( 'Unable to read blueprint.ini' );

    $write = "";

    preg_match_all( "/%(.+?)%/", $bp[$export[$_GET['skill']]]['line'], $items );

	$sql = "SELECT * FROM calc_" . $_GET['skill'] . " s ";

	/**
	 * A dirty little hack for slayer having special requirements
	 */
	if ( $_GET['skill'] == 'slayer' ) {
		$sql .= " ORDER BY s.monster ASC";
	}
	else {
		$sql .= " ORDER BY s.level ASC";
	}

    foreach( $db->query( $sql ) AS $row )
    {
        if ( ! empty( $write ) )
            $write = "\n";

        $thisline = $bp[$export[$_GET['skill']]]['line'];

        for( $i = 0; $i < count( $items[1] ); $i++ )
        {
            $thisline = str_replace( '%' . $items[1][$i] . '%', str_replace( $forbidden['block'], $forbidden['replace'], $row[$items[1][$i]] ), $thisline );
        }

        $write .= $thisline;

        fwrite( $file, $write );
    }

    fclose( $file );

    echo '
        <div class="alert alert-success">' . ucfirst( $_GET['skill'] ) . ' was successfully exported!</div>
    ';

    logging( 'Export', '3', ucfirst( $_GET['skill'] ) . ' export' );