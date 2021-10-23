<?php
    if (! isset( $_COOKIE['user_id'] ) )
        die();

    //  This is used in an ajax call, there's not an easy way of securing it, but this is a start, check if referring site is on the same host as us
    if (! stristr( $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) )
        die();

    //  The defined start of the X and Y positions
    $config['x'] = 54;
    $config['y'] = 150;

    $config['point']['x'] = 0.074;
    $config['point']['y'] = 0.072;

    $ew_time = ( $_POST['ew'] - $config['x'] ) / $config['point']['x'];
    $ew_time = $ew_time / 60;
    $ew_time = explode( ".", number_format( $ew_time, 2 ) );
    $ew['hour']   = str_replace( '-', '', $ew_time[0] );
    $ew['minute'] = ceil( ( $ew_time[1] / 100 ) * 60 );

    $ns_time = ( $_POST['ns'] - $config['y'] ) / $config['point']['y'];
    $ns_time = $ns_time / 60;
    $ns_time = explode( ".", number_format( $ns_time, 2 ) );
    $ns['hour']   = str_replace( '-', '', $ns_time[0] );
    $ns['minute'] = ceil( ( $ns_time[1] / 100 ) * 60 );

    echo $ns['hour'] . ',' . $ns['minute'] . ',' . ( $_POST['ns'] < $config['y'] ? 'North' : 'South' ) . ',' . $ew['hour'] . ',' . $ew['minute'] . ',' . ( $_POST['ew'] > $config['x'] ? 'East' : 'West' );