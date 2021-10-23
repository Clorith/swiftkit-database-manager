<?php
/**
 * User: Marius Jensen
 * Date: 05.10.12
 * Time: 09:55
 */
?>

<?php
    $severity = array();
    foreach ( $db->query( "SELECT DISTINCT `issue_severity` FROM `issues` WHERE `issue_resolved` = '0' ORDER BY `severity` ASC" ) AS $sevlist )
    {
        $severity[] = $sevlist['severity'];
    }
?>
<ul class="nav nav-tabs">
    <?php
        for ( $i = 0; $i < count( $severity ); $i++ )
        {
            echo '
                <li class="' . ( $i == 0 ? 'active' : '' ) . '">
                    <a href="#' . $severity[$i] . '" data-toggle="tab">' . ucfirst( $severity[$i] ) . '</a>
                </li>
            ';
        }
    ?>
</ul>

<div class="tab-content">
    <?php
        for ( $i = 0; $i < count( $severity ); $i++ )
        {
            echo '
                <div class="tab-pane' . ( $i == 0 ? ' active' : '' ) . '" id="' . $severity[$i] . '">

                </div>
            ';
        }
    ?>
</div>

<?php
    if ( count( $severity ) <= 0 )
    {
?>
<div class="hero-unit">
    <h1>No unresolved issues!</h1>
    <p>
        There are currently no unresolved issues in the tracker, good job!
    </p>
    <p>
        <a href="index.php?s=issues&p=new" class="btn btn-primary btn-large">
            New issue
        </a>
    </p>
</div>
<?php
    }
?>