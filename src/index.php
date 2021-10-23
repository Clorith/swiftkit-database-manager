<?php
    define( 'SKDBM', true );
    define( 'SKDBM_PATH', dirname( __FILE__ ) );

    if (! isset( $_COOKIE['user_id'] ) ) {
        header( 'Location: login.php?noid' );
        $redirect_msg = 'Missing authentication, returning to login page';
        include_once( SKDBM_PATH . '/includes/redirect.php' );
        die();
    }

    require_once( SKDBM_PATH . '/includes/config.php' );
    require_once( SKDBM_PATH . '/includes/functions.php' );

    if ( ua() < 1 ) {
        $redirect_msg = 'This account has been disabled';
        include_once( SKDBM_PATH . '/includes/redirect.php' );
        die();
    }


	$section = (!isset($_GET['s']) || empty($_GET['s']) ? 'home' : $_GET['s']);
	$page = (!isset($_GET['p']) || empty($_GET['p']) ? $section : $_GET['p']);
	
	if (!is_file( 'includes/' . $section . '/' . $page . '.php' ))
	{
        $redirect_msg = 'You are attempting to browse to an unknown page';
        include_once( SKDBM_PATH . '/includes/redirect.php' );
        die();
	}
	
	require_once( 'includes/core/header.php' );
	require_once( 'includes/core/content.php' );
	require_once( 'includes/core/footer.php' );

?>