DELETE FROM `#__tj_dashboard_widgets`;
ALTER TABLE `#__tj_dashboard_widgets` AUTO_INCREMENT = 1;
DELETE FROM `#__tj_dashboards`;
ALTER TABLE `#__tj_dashboards` AUTO_INCREMENT = 1;

ALTER TABLE `#__tj_dashboard_widgets` Add `core` tinyint(1) NOT NULL DEFAULT '0' AFTER `autorefresh`;
