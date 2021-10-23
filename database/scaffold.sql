SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `achievement_diaries`
--
CREATE TABLE IF NOT EXISTS `achievement_diaries` (
    `id` int(11) NOT NULL,
    `area` varchar(50) DEFAULT NULL,
    `difficulty` int(11) DEFAULT NULL,
    `task` text,
    `members` varchar(3) DEFAULT NULL,
    `quest_req` varchar(100) DEFAULT NULL,
    `quest_req_id` varchar(20) DEFAULT NULL,
    `skill_req` varchar(250) DEFAULT NULL,
    `strategy` text,
    `reward` text
) ENGINE=MyISAM AUTO_INCREMENT=500 DEFAULT CHARSET=latin1;

--
-- Table structure for table `atlas_search`
--
CREATE TABLE IF NOT EXISTS `atlas_search` (
    `id` int(11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `location` varchar(100) NOT NULL COMMENT 'This should be in SwiftKit Atlas location format'
) ENGINE=MyISAM AUTO_INCREMENT=578 DEFAULT CHARSET=latin1;

--
-- Table structure for table `atlas_transport`
--
CREATE TABLE IF NOT EXISTS `atlas_transport` (
    `id` int(11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `transport_method` varchar(100) NOT NULL,
    `requirements` tinytext,
    `notes` tinytext,
    `members` varchar(3) NOT NULL DEFAULT 'No',
    `location` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_agility`
--
CREATE TABLE IF NOT EXISTS `calc_agility` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here'
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_construction`
--
CREATE TABLE IF NOT EXISTS `calc_construction` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here'
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_cooking`
--
CREATE TABLE IF NOT EXISTS `calc_cooking` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_crafting`
--

CREATE TABLE IF NOT EXISTS `calc_crafting` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now ',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=116 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_divination`
--

CREATE TABLE IF NOT EXISTS `calc_divination` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_farming`
--

CREATE TABLE IF NOT EXISTS `calc_farming` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now ',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_fighting`
--

CREATE TABLE IF NOT EXISTS `calc_fighting` (
    `id` int(11) NOT NULL,
    `monster` varchar(100) NOT NULL,
    `level` int(11) NOT NULL,
    `hitpoints` int(11) NOT NULL,
    `exp` int(11) NOT NULL DEFAULT '0',
    `members` varchar(3) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1336 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_firemaking`
--

CREATE TABLE IF NOT EXISTS `calc_firemaking` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now ',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_fishing`
--

CREATE TABLE IF NOT EXISTS `calc_fishing` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_fletching`
--

CREATE TABLE IF NOT EXISTS `calc_fletching` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_herblore`
--

CREATE TABLE IF NOT EXISTS `calc_herblore` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_hunter`
--

CREATE TABLE IF NOT EXISTS `calc_hunter` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_invention`
--

CREATE TABLE IF NOT EXISTS `calc_invention` (
    `id` int(11) NOT NULL,
    `item` varchar(100) NOT NULL,
    `xp` double NOT NULL,
    `level` int(11) NOT NULL,
    `image_id` int(11) NOT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) NOT NULL,
    `sacred_tool_xp` double NOT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_magic`
--

CREATE TABLE IF NOT EXISTS `calc_magic` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_magic_lunar`
--

CREATE TABLE IF NOT EXISTS `calc_magic_lunar` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_mining`
--

CREATE TABLE IF NOT EXISTS `calc_mining` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_prayer`
--

CREATE TABLE IF NOT EXISTS `calc_prayer` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_runecrafting`
--

CREATE TABLE IF NOT EXISTS `calc_runecrafting` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_slayer`
--

CREATE TABLE IF NOT EXISTS `calc_slayer` (
    `id` int(11) NOT NULL,
    `monster` varchar(50) DEFAULT NULL,
    `combat` int(11) DEFAULT NULL,
    `hp` int(11) DEFAULT NULL,
    `exp` int(11) DEFAULT NULL,
    `level_req` int(11) DEFAULT NULL,
    `item` varchar(100) DEFAULT NULL,
    `location` varchar(255) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=242 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_smithing`
--

CREATE TABLE IF NOT EXISTS `calc_smithing` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=149 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_summoning`
--

CREATE TABLE IF NOT EXISTS `calc_summoning` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_thieving`
--

CREATE TABLE IF NOT EXISTS `calc_thieving` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here '
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Table structure for table `calc_woodcutting`
--

CREATE TABLE IF NOT EXISTS `calc_woodcutting` (
    `id` int(11) NOT NULL,
    `item` varchar(100) DEFAULT NULL,
    `xp` double DEFAULT NULL,
    `level` int(11) DEFAULT NULL,
    `image_id` int(11) DEFAULT NULL COMMENT 'Stidor needs to add this manually put 999 for now',
    `members` varchar(3) DEFAULT NULL,
    `sacred_tool_xp` double DEFAULT NULL COMMENT 'If this item has a clay tool, please duplicate the XP here'
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Table structure for table `change_log`
--

CREATE TABLE IF NOT EXISTS `change_log` (
    `id` mediumint(9) NOT NULL,
    `ip` varchar(20) DEFAULT NULL,
    `table_changed` varchar(20) DEFAULT NULL,
    `change_type` smallint(6) DEFAULT NULL,
    `item` varchar(100) DEFAULT NULL,
    `date` varchar(25) DEFAULT NULL,
    `user` varchar(20) NOT NULL,
    `entry_id` int(11) DEFAULT NULL,
    `entry_page` text
) ENGINE=MyISAM AUTO_INCREMENT=2483 DEFAULT CHARSET=latin1;

--
-- Table structure for table `deletes`
--

CREATE TABLE IF NOT EXISTS `deletes` (
    `delete_id` int(11) NOT NULL,
    `delete_reference` int(11) NOT NULL,
    `delete_table` varchar(255) NOT NULL,
    `delete_key` varchar(255) NOT NULL,
    `delete_value` varchar(255) NOT NULL,
    `delete_entry` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1 COMMENT='Allow for recovery form accidental deletes';

--
-- Table structure for table `fairy_rings`
--

CREATE TABLE IF NOT EXISTS `fairy_rings` (
    `code` varchar(6) DEFAULT NULL,
    `location` varchar(100) DEFAULT NULL,
    `coords` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `issues`
--

CREATE TABLE IF NOT EXISTS `issues` (
    `issue_id` int(11) NOT NULL,
    `issue_time` int(11) NOT NULL,
    `issue_severity` int(11) NOT NULL DEFAULT '3',
    `issue_assigned` int(11) NOT NULL,
    `issue_status` int(11) NOT NULL DEFAULT '0',
    `issue_version` varchar(255) NOT NULL,
    `issue_component` varchar(255) NOT NULL,
    `issue_title` varchar(255) NOT NULL,
    `issue_description` text NOT NULL,
    `issue_resolved` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `quests`
--

CREATE TABLE IF NOT EXISTS `quests` (
    `id` int(11) NOT NULL,
    `name` varchar(50) DEFAULT NULL,
    `quest_points` int(11) DEFAULT NULL,
    `members` varchar(3) DEFAULT NULL,
    `reward` text,
    `qp_reqs` varchar(10) DEFAULT NULL,
    `skill_text_reqs` text,
    `quest_id_reqs` varchar(150) DEFAULT 'None',
    `quest_text_reqs` text,
    `skill_tag_reqs` varchar(255) DEFAULT NULL,
    `quest_coords` varchar(255) DEFAULT NULL,
    `advlog_questname` varchar(50) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=latin1;

--
-- Table structure for table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
    `tid` int(11) NOT NULL,
    `token` varchar(255) NOT NULL,
    `user` int(11) NOT NULL,
    `time` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
    `uid` int(11) NOT NULL,
    `username` varchar(20) DEFAULT NULL,
    `password` varchar(255) DEFAULT NULL,
    `access` smallint(6) DEFAULT NULL,
    `auth` varchar(255) DEFAULT NULL,
    `lastip` varchar(255) DEFAULT NULL,
    `remember_auth` varchar(255) DEFAULT NULL,
    `ip_lock` varchar(255) DEFAULT NULL,
    `login_time` int(11) NOT NULL,
    `last_action` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievement_diaries`
--
ALTER TABLE `achievement_diaries`
    ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `atlas_search`
--
ALTER TABLE `atlas_search`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atlas_transport`
--
ALTER TABLE `atlas_transport`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_agility`
--
ALTER TABLE `calc_agility`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_construction`
--
ALTER TABLE `calc_construction`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_cooking`
--
ALTER TABLE `calc_cooking`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_crafting`
--
ALTER TABLE `calc_crafting`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_divination`
--
ALTER TABLE `calc_divination`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_farming`
--
ALTER TABLE `calc_farming`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_fighting`
--
ALTER TABLE `calc_fighting`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_firemaking`
--
ALTER TABLE `calc_firemaking`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_fishing`
--
ALTER TABLE `calc_fishing`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_fletching`
--
ALTER TABLE `calc_fletching`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_herblore`
--
ALTER TABLE `calc_herblore`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_hunter`
--
ALTER TABLE `calc_hunter`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_invention`
--
ALTER TABLE `calc_invention`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_magic`
--
ALTER TABLE `calc_magic`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_magic_lunar`
--
ALTER TABLE `calc_magic_lunar`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_mining`
--
ALTER TABLE `calc_mining`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_prayer`
--
ALTER TABLE `calc_prayer`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_runecrafting`
--
ALTER TABLE `calc_runecrafting`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_slayer`
--
ALTER TABLE `calc_slayer`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_smithing`
--
ALTER TABLE `calc_smithing`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_summoning`
--
ALTER TABLE `calc_summoning`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_thieving`
--
ALTER TABLE `calc_thieving`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_woodcutting`
--
ALTER TABLE `calc_woodcutting`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `change_log`
--
ALTER TABLE `change_log`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deletes`
--
ALTER TABLE `deletes`
    ADD PRIMARY KEY (`delete_id`);

--
-- Indexes for table `fairy_rings`
--
ALTER TABLE `fairy_rings`
    ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
    ADD PRIMARY KEY (`issue_id`);

--
-- Indexes for table `quests`
--
ALTER TABLE `quests`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
    ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievement_diaries`
--
ALTER TABLE `achievement_diaries`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=500;
--
-- AUTO_INCREMENT for table `atlas_search`
--
ALTER TABLE `atlas_search`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=578;
--
-- AUTO_INCREMENT for table `atlas_transport`
--
ALTER TABLE `atlas_transport`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `calc_agility`
--
ALTER TABLE `calc_agility`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `calc_construction`
--
ALTER TABLE `calc_construction`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `calc_cooking`
--
ALTER TABLE `calc_cooking`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `calc_crafting`
--
ALTER TABLE `calc_crafting`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=116;
--
-- AUTO_INCREMENT for table `calc_divination`
--
ALTER TABLE `calc_divination`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `calc_farming`
--
ALTER TABLE `calc_farming`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `calc_fighting`
--
ALTER TABLE `calc_fighting`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1336;
--
-- AUTO_INCREMENT for table `calc_firemaking`
--
ALTER TABLE `calc_firemaking`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `calc_fishing`
--
ALTER TABLE `calc_fishing`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `calc_fletching`
--
ALTER TABLE `calc_fletching`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `calc_herblore`
--
ALTER TABLE `calc_herblore`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT for table `calc_hunter`
--
ALTER TABLE `calc_hunter`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT for table `calc_invention`
--
ALTER TABLE `calc_invention`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `calc_magic`
--
ALTER TABLE `calc_magic`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `calc_magic_lunar`
--
ALTER TABLE `calc_magic_lunar`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `calc_mining`
--
ALTER TABLE `calc_mining`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `calc_prayer`
--
ALTER TABLE `calc_prayer`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `calc_runecrafting`
--
ALTER TABLE `calc_runecrafting`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT for table `calc_slayer`
--
ALTER TABLE `calc_slayer`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=242;
--
-- AUTO_INCREMENT for table `calc_smithing`
--
ALTER TABLE `calc_smithing`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT for table `calc_summoning`
--
ALTER TABLE `calc_summoning`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT for table `calc_thieving`
--
ALTER TABLE `calc_thieving`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `calc_woodcutting`
--
ALTER TABLE `calc_woodcutting`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `change_log`
--
ALTER TABLE `change_log`
    MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2483;
--
-- AUTO_INCREMENT for table `deletes`
--
ALTER TABLE `deletes`
    MODIFY `delete_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
    MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quests`
--
ALTER TABLE `quests`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=219;
--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
    MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

