<?php
    if ( ! defined( 'SKDBM' ) ) { die( 'Define missing' ); }
    if ( ua() < 50 ) { die( 'Insufficient access' ); }

    $exportfolder = SKDBM_PATH . "/export/";
    if ( isset( $_GET['export'] ) ) {
        $forbidden = array(
            'block'   => array(
                ','
            ),
            'replace' => array(
                '.'
            )
        );

        include_once( SKDBM_PATH . '/includes/lib/exporters/' . $_GET['export'] . '.php' );

        echo '
            <script type="text/javascript">
                redirect = setInterval(function (e) {
                    clearInterval(redirect);
                    window.location.href = "https://skdbm.clorith.space/export/' . $export[( ! isset( $_GET['skill'] ) ? $_GET['export'] : $_GET['skill'] )] . '.dat?' . time() . '";
                }, 1000);
            </script>
        ';
    }
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Skill / Activity</th>
            <th>Last export</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php

            foreach ($menus['skills'] AS $snum => $skill)
            {
                $skillfile = SKDBM_PATH . '/export/' . $export[$skill] . '.dat';

                echo '
                    <tr>
                        <td>
                            ' . ucfirst( $skill ) . '
                        </td>
                        <td>' . ( is_file( $skillfile ) ? date( "d.m.Y H:i", filemtime( $skillfile ) ) : 'No export found' ) . '</td>
                        <td>
                            <a href="index.php?s=admin&p=export&export=skill&skill=' . $skill . '" class="btn btn-mini btn-primary">Export data</a>
                        </td>
                    </tr>
                ';
            }

        ?>
        <tr>
            <td colspan="3">
                <hr />
            </td>
        </tr>
        <tr>
            <td>Quests</td>
            <td><?php echo ( is_file( SKDBM_PATH . '/export/' . $export['quests'] . '.dat' ) ? date( "d.m.Y H:i", filemtime( SKDBM_PATH . '/export/' . $export['quests'] . '.dat' ) ) : 'No export found' ); ?></td>
            <td>
                <a href="index.php?s=admin&p=export&export=quests" class="btn btn-mini btn-primary">Export data</a>
            </td>
        </tr>
        <tr>
            <td>Tasks</td>
            <td><?php echo ( is_file( SKDBM_PATH . '/export/' . $export['tasks'] . '.dat' ) ? date( "d.m.Y H:i", filemtime( SKDBM_PATH . '/export/' . $export['tasks'] . '.dat' ) ) : 'No export found' ); ?></td>
            <td>
                <a href="index.php?s=admin&p=export&export=tasks" class="btn btn-mini btn-primary">Export data</a>
            </td>
        </tr>
        <tr>
            <td>Fairy rings</td>
            <td><?php echo ( is_file( SKDBM_PATH . '/export/' . $export['fairyrings'] . '.dat' ) ? date( "d.m.Y H:i", filemtime( SKDBM_PATH . '/export/' . $export['fairyrings'] . '.dat' ) ) : 'No export found' ); ?></td>
            <td>
                <a href="index.php?s=admin&p=export&export=fairyrings" class="btn btn-mini btn-primary">Export data</a>
            </td>
        </tr>
    </tbody>
</table>
