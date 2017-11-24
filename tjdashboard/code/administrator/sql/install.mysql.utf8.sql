CREATE TABLE `#__tj_dashboards` (
  `dashboard_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `alias` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `state` int(5) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `context` varchar(255) NOT NULL,
  `core` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`dashboard_id`)
)

CREATE TABLE IF NOT EXISTS `#__tj_dashboard_widgets` (
  `dashboard_widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `dashboard_id` int(11) NOT NULL,
  `data_plugin` varchar(255) NOT NULL,
  `renderer_plugin` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `params` text NOT NULL,
  `autorefresh` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`dashboard_widget_id`)
)
