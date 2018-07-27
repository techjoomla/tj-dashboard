CREATE TABLE IF NOT EXISTS `#__tj_dashboards` (
  `dashboard_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `access` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `context` varchar(255) NOT NULL COMMENT 'Dashboard context',
  `parent` int(11) NOT NULL COMMENT 'dashboard id of parent dashboard',
  PRIMARY KEY (`dashboard_id`)
);

--
-- Indexes for table `__tj_dashboards`
--

ALTER TABLE `#__tj_dashboards`
  -- ADD KEY `idx_access` (`access`),
  ADD KEY `idx_checkout` (`checked_out`),
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_createdby` (`created_by`),
  ADD KEY `idx_alias` (`alias`(191));

CREATE TABLE IF NOT EXISTS `#__tj_dashboard_widgets` (
  `dashboard_widget_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dashboard_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__tj_dashboard table.',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `data_plugin` varchar(255) NOT NULL COMMENT 'Data Source Plugin',
  `renderer_plugin` varchar(255) NOT NULL COMMENT 'Renderer for data',
  `checked_out` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `size` int(11) NOT NULL COMMENT 'Screen size for widget (col-span-3/6/9/12)',
  `autorefresh` int(11) NOT NULL COMMENT 'Widget refresh time span in second',
  `default_widget` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dashboard_widget_id`),
  FOREIGN KEY (`dashboard_id`) REFERENCES `#__tj_dashboards` (`dashboard_id`)
);

--
-- Indexes for table `#__tj_dashboard_widgets`
--

ALTER TABLE `#__tj_dashboard_widgets`
  ADD KEY `idx_dashboard_id` (`dashboard_id`),
  ADD KEY `idx_checkout` (`checked_out`),
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_createdby` (`created_by`);
