<?php

    /**
     * @param string $table The table that was modified
     * @param int $change Integer for change type
     * @param string $item Thedata that was modified
     * @param mixed $id If there is an item id/reference include it for quick-access features
     * @return int log ID
     */
    function logging( $table, $change, $item, $id = 0 )
	{
		//	Do a quick check, if there's no $item there is nothing to log and we abort
		if ( empty( $item ) ) {
			return false;
		}

		//	Include the $db function which is our database class
		global $db;

		try {
            $log = $db->query("
                INSERT INTO
                    `change_log`
                        (
                            `ip`,
                            `table_changed`,
                            `change_type`,
                            `item`,
                            `date`,
                            `user`,
                            `entry_id`,
                            `entry_page`
                        )
                VALUES (
                    " . $db->quote( $_SERVER['REMOTE_ADDR'] ) . ",
                    " . $db->quote( $table ) . ",
                    " . $db->quote( $change ) . ",
                    " . $db->quote( $item ) . ",
                    " . $db->quote( time() ) . ",
                    " . $db->quote( ( isset( $_COOKIE['user_name'] ) ? $_COOKIE['user_name'] : $_COOKIE['user_id'] ) ) . ",
                    " . $id . ",
                    " . $db->quote( $_SERVER['HTTP_REFERER'] ) . "
                )
            ");

            return $db->lastInsertId();
        } catch ( PDOException $e ) {
            return $e->getMessage();
        }
	}

    /**
     * @return int
     */
    function ua()
    {
        //  Include the $db function which is our database class
        global $db;

        foreach ( $db->query( "SELECT `access`,`lastip` FROM `users` WHERE `uid` = '{$_COOKIE['user_id']}' LIMIT 1") as $user)
        {
            //  This should really only return one line, so we can safely return straight away, this is just a much cleaner way of doing the query with PDO

            //  The users IP is not the last authenticated IP, we presume foul play!
            if ( $_SERVER['REMOTE_ADDR'] != $user['lastip'] )
                return 0;

            //  Since the IP matched, we continue to this much nicer return actually providing a user level as well as recording that they just did something to record activity
            $db->query( "UPDATE `users` SET `last_action` = '" . time() . "' WHERE `uid` = '{$_COOKIE['user_id']}' LIMIT 1" );
            return $user['access'];
        }

        return 0;
    }

    /**
     * @param string $select
     * @return string
     */
    function advlog( $select = '' ) {
        $return = "";

        if (! file_exists( SKDBM_PATH . '/includes/lib/adventurelog.txt' ) )
        {
            //  Path to adventurer log to grab quest list from
            $path = "http://services.runescape.com/m=adventurers-log/display_player_profile.ws?searchName=Stidor";

            //  Get the html of the alog
            $content = file_get_contents( $path );

            //  Find the start of the quest list, and rough end
            $list_start = stripos( $content, "quest-log-view" );
            $list_end = stripos( $content, 'id="Footer"' );

            //  Filter out so we are left with only the quest area
            $content = substr( $content, $list_start, ( $list_end - $list_start ) );

            //  Regex pattern to find each quest
            $pattern = '/<th scope="row">(.+?)<\/th>/si';

            //  Run regex match and populate $quest as an array with the results
            preg_match_all( $pattern, $content, $quests );

            //  Open a file writer so we can write a cache file as fetchign the adventure log is somewhat slow
            $file = fopen( SKDBM_PATH . "/includes/lib/adventurelog.txt", "w+" );

            //  Remember that $quests[0] holds all matched strings, ignore it and go to [1] which holds an array of each result
            foreach ($quests[1] AS $qnum => $quest) {
                if (! empty( $select ) && $quest == $select )
                    $return .= '<option SELECTED>' . $quest . '</option>';
                else
                    $return .= '<option>' . $quest . '</option>';
                fwrite( $file, $quest . "\n" );
            }

        }
        else {
            $read = "";
            $file = fopen( SKDBM_PATH . "/includes/lib/adventurelog.txt", "r" );
            while (! feof( $file ) ) {
                $read .= fread( $file, 8192 );
            }
            $lines = explode( "\n", $read );
            foreach ( $lines AS $num => $read ) {
                if (! empty( $select ) && $read == $select )
                    $return .= '<option SELECTED>' . $read . '</option>';
                else
                    $return .= '<option>' . $read . '</option>';
            }
        }

        fclose( $file );

        return $return;
    }

    /**
     * @param $table
     * @param $key
     * @param $value
     * @return mixed
     */
    function deleteLog( $table, $key, $value )
    {
        //  Globalize the $db class so we can do database queries
        global $db;

        $rowdata = $db->query( "
            SELECT
                *
            FROM
                `" . $table . "`
            WHERE
                `" . $key . "` = " . $db->quote( $value ) . "
            LIMIT 1
        " );
        
        $rowdata = $rowdata->fetch();

        $backup = $db->query( "
            INSERT INTO
                `deletes`
                    (`delete_table`,`delete_key`,`delete_value`,`delete_entry`)
            VALUES(
                '" . $table . "',
                '" . $key . "',
                " . $db->quote( $value ) . ",
                '" . json_encode( $rowdata ) . "'
            )
        " );

        return $db->lastInsertId();
    }

    /**
     * @param $id
     * @param $ref
     * @return bool
     */
    function deleteLogReference( $id, $ref ) {
        global $db;
        $db->query( "
            UPDATE
                `deletes`
            SET
                `delete_reference` = '" . $ref . "'
            WHERE
                `delete_id` = '" . $id . "'
            LIMIT 1
        " );

        return true;
    }