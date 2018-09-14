-- --------------------------------------------------------
-- Manual Schema Dump
-- Maian Music
-- --------------------------------------------------------

-- Dumping structure for table mm_accounts
DROP TABLE IF EXISTS `mm_accounts`;
CREATE TABLE IF NOT EXISTS `mm_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `email` varchar(250) NOT NULL DEFAULT '',
  `pass` varchar(40) NOT NULL DEFAULT '',
  `ip` varchar(250) NOT NULL DEFAULT '',
  `ts` int(30) NOT NULL DEFAULT '0',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `system1` varchar(250) NOT NULL DEFAULT '',
  `system2` varchar(250) NOT NULL DEFAULT '',
  `notes` text default null,
  `timezone` varchar(50) NOT NULL DEFAULT '',
  `country` int(5) NOT NULL DEFAULT '183',
  `shipping` int(5) NOT NULL DEFAULT '0',
  `token` varchar(50) NOT NULL DEFAULT '',
  `bypass` enum('yes','no') NOT NULL DEFAULT 'no',
  `login` enum('yes','no') not null default 'yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_accounts_addr
DROP TABLE IF EXISTS `mm_accounts_addr`;
CREATE TABLE IF NOT EXISTS `mm_accounts_addr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` int(30) NOT NULL DEFAULT '0',
  `address1` varchar(250) NOT NULL DEFAULT '',
  `address2` varchar(250) NOT NULL DEFAULT '',
  `city` varchar(250) NOT NULL DEFAULT '',
  `county` varchar(250) NOT NULL DEFAULT '',
  `postcode` varchar(250) NOT NULL DEFAULT '',
  `country` int(5) NOT NULL DEFAULT '183',
  `default` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`),
  KEY `acc` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_api
DROP TABLE IF EXISTS `mm_api`;
CREATE TABLE IF NOT EXISTS `mm_api` (
  `id` INT(5) NOT NULL AUTO_INCREMENT,
  `desc` VARCHAR(50) NOT NULL DEFAULT '',
  `param` TEXT DEFAULT NULL,
  `value` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `descK` (`desc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_accounts_login
DROP TABLE IF EXISTS `mm_accounts_login`;
CREATE TABLE IF NOT EXISTS `mm_accounts_login` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`account` INT(7) NOT NULL DEFAULT '0',
	`ip` VARCHAR(250) NOT NULL DEFAULT '',
	`ts` INT(30) NOT NULL DEFAULT '0',
	`iso` CHAR(2) NOT NULL DEFAULT '',
	`country` VARCHAR(250) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	KEY `accid` (`account`),
  KEY `accip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_collections
DROP TABLE IF EXISTS `mm_collections`;
CREATE TABLE IF NOT EXISTS `mm_collections` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `title` text default null,
  `metakeys` text default null,
  `metadesc` text default null,
  `slug` varchar(50) NOT NULL DEFAULT '',
  `information` text default null,
  `searchtags` text default null,
  `social` text default null,
  `coverart` text default null,
  `coverartother` text default null,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `released` int(30) NOT NULL DEFAULT '0',
  `catnumber` varchar(200) NOT NULL DEFAULT '',
  `cost` varchar(10) NOT NULL DEFAULT '',
  `costcd` varchar(10) NOT NULL DEFAULT '',
  `added` int(30) NOT NULL DEFAULT '0',
  `updated` int(30) NOT NULL DEFAULT '0',
  `related` text default null,
  `length` varchar(10) NOT NULL DEFAULT '',
  `bitrate` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_collection_styles
DROP TABLE IF EXISTS `mm_collection_styles`;
CREATE TABLE IF NOT EXISTS `mm_collection_styles` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `style` int(8) NOT NULL DEFAULT '0',
  `collection` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `style` (`style`),
  KEY `col` (`collection`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_countries
DROP TABLE IF EXISTS `mm_countries`;
CREATE TABLE IF NOT EXISTS `mm_countries` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `iso` varchar(3) NOT NULL DEFAULT '',
  `iso2` char(2) NOT NULL DEFAULT '',
  `iso4217` varchar(50) NOT NULL DEFAULT '0',
  `tax` char(2) NOT NULL DEFAULT '',
  `tax2` char(2) not null default '',
  `display` enum('yes','no') NOT NULL DEFAULT 'yes',
  `eu` enum('yes','no') not null default 'no',
  PRIMARY KEY (`id`),
  KEY `iso` (`iso`)
) ENGINE=MyISAM AUTO_INCREMENT=265 DEFAULT CHARSET=utf8;

-- Dumping data for table mm_countries: 239 rows
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(1, 'Afghanistan', 'AFG', 'AF', '004', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(2, 'Albania', 'ALB', 'AL', '008', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(3, 'Algeria', 'DZA', 'DZ', '012', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(4, 'Andorra', 'AND', 'AD', '20', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(5, 'Angola', 'AGO', 'AO', '024', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(6, 'Antigua and Barbuda', 'ATG', 'AG', '028', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(7, 'Argentina', 'ARG', 'AR', '032', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(8, 'Armenia', 'ARM', 'AM', '051', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(9, 'Australia', 'AUS', 'AU', '036', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(10, 'Austria', 'AUT', 'AT', '040', '', '20', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(11, 'Azerbaijan', 'AZE', 'AZ', '031', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(12, 'Bahamas', 'BHS', 'BS', '044', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(13, 'Bahrain', 'BHR', 'BH', '048', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(14, 'Bangladesh', 'BGD', 'BD', '050', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(15, 'Barbados', 'BRB', 'BB', '052', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(16, 'Belarus', 'BLR', 'BY', '112', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(17, 'Belgium', 'BEL', 'BE', '056', '', '21', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(18, 'Belize', 'BLZ', 'BZ', '084', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(19, 'Benin', 'BEN', 'BJ', '204', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(20, 'Bhutan', 'BTN', 'BT', '064', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(21, 'Bolivia', 'BOL', 'BO', '068', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(22, 'Bosnia and Herzegovina', 'BIH', 'BA', '070', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(23, 'Botswana', 'BWA', 'BW', '072', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(24, 'Brazil', 'BRA', 'BR', '076', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(25, 'Brunei', 'BRN', 'BN', '096', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(26, 'Bulgaria', 'BGR', 'BG', '100', '', '20', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(27, 'Burkina Faso', 'BFA', 'BF', '854', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(28, 'Burundi', 'BDI', 'BI', '108', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(29, 'Cambodia', 'KHM', 'KH', '116', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(30, 'Cameroon', 'CMR', 'CM', '120', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(31, 'Canada', 'CAN', 'CA', '124', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(32, 'Cape Verde', 'CPV', 'CV', '132', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(33, 'Central African Republic', 'CAF', 'CF', '140', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(34, 'Chad', 'TCD', 'TD', '148', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(35, 'Chile', 'CHL', 'CL', '152', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(36, 'China', 'CHN', 'CN', '156', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(37, 'Colombia', 'COL', 'CO', '170', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(38, 'Comoros', 'COM', 'KM', '174', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(39, 'Congo', 'COG', 'CG', '178', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(41, 'Costa Rica', 'CRI', 'CK', '184', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(42, 'Cote d\'Ivoire', 'CIV', 'CI', '188', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(43, 'Croatia', 'HRV', 'HR', '191', '', '25', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(44, 'Cuba', 'CUB', 'CU', '192', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(45, 'Cyprus', 'CYP', 'CY', '196', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(46, 'Czech Republic', 'CZE', 'CZ', '203', '', '19', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(47, 'Denmark', 'DNK', 'DK', '208', '', '25', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(48, 'Djibouti', 'DJI', 'DJ', '262', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(49, 'Dominica', 'DMA', 'DM', '212', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(50, 'Dominican Republic', 'DOM', 'DO', '214', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(51, 'Ecuador', 'ECU', 'EC', '218', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(52, 'Egypt', 'EGY', 'EG', '818', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(53, 'El Salvador', 'SLV', 'SV', '222', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(54, 'Equatorial Guinea', 'GNQ', 'GQ', '226', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(55, 'Eritrea', 'ERI', 'ER', '232', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(56, 'Estonia', 'EST', 'EE', '233', '', '20', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(57, 'Ethiopia', 'ETH', 'ET', '231', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(58, 'Fiji', 'FJI', 'FJ', '242', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(59, 'Finland', 'FIN', 'FI', '246', '', '24', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(60, 'France', 'FRA', 'FR', '250', '', '20', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(61, 'Gabon', 'GAB', 'GA', '266', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(62, 'Gambia', 'GMB', 'GM', '270', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(63, 'Georgia', 'GEO', 'GE', '268', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(64, 'Germany', 'DEU', 'DE', '276', '', '19', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(65, 'Ghana', 'GHA', 'GH', '288', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(66, 'Greece', 'GRC', 'GR', '300', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(67, 'Grenada', 'GRD', 'GD', '308', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(68, 'Guatemala', 'GTM', 'GT', '320', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(69, 'Guinea', 'GIN', 'GN', '324', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(70, 'Guinea-Bissau', 'GNB', 'GW', '624', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(71, 'Guyana', 'GUY', 'GY', '328', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(72, 'Haiti', 'HTI', 'HT', '332', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(73, 'Honduras', 'HND', 'HN', '340', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(74, 'Hungary', 'HUN', 'HU', '348', '', '27', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(75, 'Iceland', 'ISL', 'IS', '352', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(76, 'India', 'IND', 'IN', '356', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(77, 'Indonesia', 'IDN', 'ID', '360', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(78, 'Iran', 'IRN', 'IR', '364', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(79, 'Iraq', 'IRQ', 'IQ', '368', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(80, 'Ireland', 'IRL', 'IE', '372', '', '23', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(81, 'Israel', 'ISR', 'IL', '376', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(82, 'Italy', 'ITA', 'IT', '380', '', '22', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(83, 'Jamaica', 'JAM', 'JM', '388', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(84, 'Japan', 'JPN', 'JP', '392', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(85, 'Jordan', 'JOR', 'JO', '400', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(86, 'Kazakhstan', 'KAZ', 'KZ', '398', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(87, 'Kenya', 'KEN', 'KE', '404', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(88, 'Kiribati', 'KIR', 'KI', '296', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(89, 'South Korea', 'KOR', 'KR', '410', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(90, 'North Korea', 'PRK', 'KP', '408', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(91, 'Kuwait', 'KWT', 'KW', '414', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(92, 'Kyrgyzstan', 'KGZ', 'KG', '417', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(93, 'Laos', 'LAO', 'LA', '418', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(94, 'Latvia', 'LVA', 'LV', '428', '', '21', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(95, 'Lebanon', 'LBN', 'LB', '422', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(96, 'Lesotho', 'LSO', 'LS', '426', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(97, 'Liberia', 'LBR', 'LR', '430', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(98, 'Libya', 'LBY', 'LY', '434', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(99, 'Liechtenstein', 'LIE', 'LI', '438', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(100, 'Lithuania', 'LTU', 'LT', '440', '', '21', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(101, 'Luxembourg', 'LUX', 'LU', '442', '', '17', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(102, 'Macedonia', 'MKD', 'MK', '807', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(103, 'Madagascar', 'MDG', 'MG', '450', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(104, 'Malawi', 'MWI', 'MW', '454', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(105, 'Malaysia', 'MYS', 'MY', '458', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(106, 'Maldives', 'MDV', 'MV', '462', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(107, 'Mali', 'MLI', 'ML', '466', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(108, 'Malta', 'MLT', 'MT', '470', '', '18', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(109, 'Marshall Islands', 'MHL', 'MH', '584', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(110, 'Mauritania', 'MRT', 'MR', '478', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(111, 'Mauritius', 'MUS', 'MU', '480', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(112, 'Mexico', 'MEX', 'MX', '484', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(113, 'Micronesia', 'FSM', 'FM', '583', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(114, 'Moldova', 'MDA', 'MD', '498', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(115, 'Monaco', 'MCO', 'MC', '492', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(116, 'Mongolia', 'MNG', 'MN', '496', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(117, 'Montenegro', 'MNE', 'ME', '499', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(118, 'Morocco', 'MAR', 'MA', '504', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(119, 'Mozambique', 'MOZ', 'MZ', '508', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(120, 'Myanmar (Burma)', 'MMR', 'MM', '104', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(121, 'Namibia', 'NAM', 'NA', '516', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(122, 'Nauru', 'NRU', 'NR', '520', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(123, 'Nepal', 'NPL', 'NP', '524', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(124, 'Netherlands', 'NLD', 'NL', '528', '', '21', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(125, 'New Zealand', 'NZL', 'NZ', '554', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(126, 'Nicaragua', 'NIC', 'NI', '558', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(127, 'Niger', 'NER', 'NE', '562', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(128, 'Nigeria', 'NGA', 'NG', '566', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(129, 'Norway', 'NOR', 'NO', '578', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(130, 'Oman', 'OMN', 'OM', '512', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(131, 'Pakistan', 'PAK', 'PK', '586', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(132, 'Palau', 'PLW', 'PW', '585', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(133, 'Panama', 'PAN', 'PA', '591', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(134, 'Papua New Guinea', 'PNG', 'PG', '598', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(135, 'Paraguay', 'PRY', 'PY', '600', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(136, 'Peru', 'PER', 'PE', '604', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(137, 'Philippines', 'PHL', 'PH', '608', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(138, 'Poland', 'POL', 'PL', '616', '', '23', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(139, 'Portugal', 'PRT', 'PT', '620', '', '23', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(140, 'Qatar', 'QAT', 'QA', '634', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(141, 'Romania', 'ROU', 'RO', '642', '', '20', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(142, 'Russian Federation', 'RUS', 'RU', '643', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(143, 'Rwanda', 'RWA', 'RW', '646', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(144, 'Saint Kitts and Nevis', 'KNA', 'KN', '659', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(145, 'Saint Lucia', 'LCA', 'LC', '662', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(146, 'Saint Vincent and the Grenadines', 'VCT', 'VC', '670', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(147, 'Samoa', 'WSM', 'WS', '882', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(148, 'San Marino', 'SMR', 'SM', '674', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(149, 'Sao Tome and Principe', 'STP', 'ST', '678', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(150, 'Saudi Arabia', 'SAU', 'SA', '682', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(151, 'Senegal', 'SEN', 'SN', '686', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(152, 'Serbia', 'SRB', 'RS', '688', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(153, 'Seychelles', 'SYC', 'SC', '690', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(154, 'Sierra Leone', 'SLE', 'SL', '694', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(155, 'Singapore', 'SGP', 'SG', '702', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(156, 'Slovakia', 'SVK', 'SK', '703', '', '20', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(157, 'Slovenia', 'SVN', 'SI', '705', '', '22', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(159, 'Somalia', 'SOM', 'SO', '706', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(160, 'South Africa', 'ZAF', 'ZA', '710', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(161, 'Spain', 'ESP', 'ES', '724', '', '21', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(162, 'Sri Lanka', 'LKA', 'LK', '144', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(163, 'Sudan', 'SDN', 'SD', '736', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(164, 'Suriname', 'SUR', 'SR', '740', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(165, 'Swaziland', 'SWZ', 'SZ', '748', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(166, 'Sweden', 'SWE', 'SE', '752', '', '25', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(167, 'Switzerland', 'CHE', 'CH', '756', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(168, 'Syrian Arab Republic', 'SYR', 'SY', '760', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(169, 'Tajikistan', 'TJK', 'TJ', '762', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(170, 'Tanzania', 'TZA', 'TZ', '834', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(171, 'Thailand', 'THA', 'TH', '764', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(172, 'Timor-Leste (East Timor)', 'TLS', 'TL', '626', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(173, 'Togo', 'TGO', 'TG', '768', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(174, 'Tonga', 'TON', 'TO', '776', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(175, 'Trinidad and Tobago', 'TTO', 'TT', '780', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(176, 'Tunisia', 'TUN', 'TN', '788', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(177, 'Turkey', 'TUR', 'TR', '792', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(178, 'Turkmenistan', 'TKM', 'TM', '795', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(179, 'Tuvalu', 'TUV', 'TV', '798', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(180, 'Uganda', 'UGA', 'UG', '800', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(181, 'Ukraine', 'UKR', 'UA', '804', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(182, 'United Arab Emirates', 'ARE', 'AE', '784', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(183, 'United Kingdom', 'GBR', 'GB', '826', '', '20', 'yes', 'yes');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(184, 'United States', 'USA', 'US', '840', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(185, 'Uruguay', 'URY', 'UY', '858', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(186, 'Uzbekistan', 'UZB', 'UZ', '860', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(187, 'Vanuatu', 'VUT', 'VU', '548', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(188, 'Vatican City', 'VAT', 'VA', '336', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(189, 'Venezuela', 'VEN', 'VE', '862', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(190, 'Vietnam', 'VNM', 'VN', '704', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(191, 'Yemen', 'YEM', 'YE', '887', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(192, 'Zambia', 'ZMB', 'ZM', '894', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(193, 'Zimbabwe', 'ZWE', 'ZW', '716', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(202, 'Christmas Island', 'CXR', 'CX', '162', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(203, 'Cocos (Keeling) Islands', 'CCK', 'CC', '166', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(205, 'Heard Island and McDonald Islands', 'HMD', 'HM', '334', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(206, 'Norfolk Island', 'NFK', 'NF', '574', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(207, 'New Caledonia', 'NCL', 'NC', '540', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(208, 'French Polynesia', 'PYF', 'PF', '258', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(209, 'Mayotte', 'MYT', 'YT', '175', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(210, 'Saint Barthelemy', 'GLP', 'BL', '652', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(211, 'Saint Martin', 'GLP', 'MF', '663', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(212, 'Saint Pierre and Miquelon', 'SPM', 'PM', '666', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(213, 'Wallis and Futuna', 'WLF', 'WF', '876', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(214, 'French Southern and Antarctic Lands', 'ATF', 'TF', '260', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(216, 'Bouvet Island', 'BVT', 'BV', '074', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(217, 'Cook Islands', 'COK', 'CD', '180', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(218, 'Niue', 'NIU', 'NU', '570', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(219, 'Tokelau', 'TKL', 'TK', '772', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(220, 'Guernsey', 'GGY', 'GG', '831', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(221, 'Isle of Man', 'IMN', 'IM', '833', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(222, 'Jersey', 'JEY', 'JE', '832', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(223, 'Anguilla', 'AIA', 'AI', '660', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(224, 'Bermuda', 'BMU', 'BM', '060', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(225, 'British Indian Ocean Territory', 'IOT', 'IO', '086', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(227, 'British Virgin Islands', 'VGB', 'VG', '092', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(228, 'Cayman Islands', 'CYM', 'KY', '136', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(229, 'Falkland Islands (Islas Malvinas)', 'FLK', 'FK', '238', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(230, 'Gibraltar', 'GIB', 'GI', '292', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(231, 'Montserrat', 'MSR', 'MS', '500', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(232, 'Pitcairn Islands', 'PCN', 'PN', '612', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(233, 'Saint Helena', 'SHN', 'SH', '654', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(234, 'South Georgia & South Sandwich Islands', 'SGS', 'GS', '239', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(235, 'Turks and Caicos Islands', 'TCA', 'TC', '796', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(236, 'Northern Mariana Islands', 'MNP', 'MP', '580', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(237, 'Puerto Rico', 'PRI', 'PR', '630', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(238, 'American Samoa', 'ASM', 'AS', '016', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(240, 'Guam', 'GUM', 'GU', '316', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(248, 'US Virgin Islands', 'VIR', 'VI', '850', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(250, 'Hong Kong', 'HKG', 'HK', '344', '', '5', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(251, 'Macau', 'MAC', 'MO', '446', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(252, 'Faroe Islands', 'FRO', 'FO', '234', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(253, 'Greenland', 'GRL', 'GL', '304', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(254, 'French Guiana', 'GUF', 'GF', '254', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(255, 'Guadeloupe', 'GLP', 'GP', '312', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(256, 'Martinique', 'MTQ', 'MQ', '474', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(257, 'Reunion', 'REU', 'RE', '638', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(259, 'Aruba', 'ABW', 'AW', '533', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(260, 'Netherlands Antilles', 'ANT', 'AN', '530', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(261, 'Svalbard and Jan Mayen', 'SJM', 'SJ', '744', '', '', 'yes', 'no');
INSERT INTO `mm_countries` (`id`, `name`, `iso`, `iso2`, `iso4217`, `tax`, `tax2`, `display`, `eu`) VALUES
	(264, 'Australian Antarctic Territory', 'ATA', 'AQ', '010', '', '', 'yes', 'no');

-- Dumping structure for table mm_coupons
DROP TABLE IF EXISTS `mm_coupons`;
CREATE TABLE IF NOT EXISTS `mm_coupons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL DEFAULT '',
  `discount` varchar(10) NOT NULL DEFAULT '',
  `expiry` int(30) NOT NULL DEFAULT '0',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `accounts` text default null,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_gateways
DROP TABLE IF EXISTS `mm_gateways`;
CREATE TABLE IF NOT EXISTS `mm_gateways` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `display` varchar(100) NOT NULL DEFAULT '',
  `liveserver` varchar(250) NOT NULL DEFAULT '',
  `sandboxserver` varchar(250) NOT NULL DEFAULT '',
  `image` varchar(100) NOT NULL DEFAULT '',
  `webpage` varchar(100) NOT NULL DEFAULT '',
  `status` enum('yes','no') NOT NULL DEFAULT 'yes',
  `class` varchar(100) NOT NULL DEFAULT '',
  `default` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Dumping data for table mm_gateways: 20 rows
INSERT INTO `mm_gateways` (`id`, `display`, `liveserver`, `sandboxserver`, `image`, `webpage`, `status`, `class`, `default`) VALUES
	(1, 'Paypal', 'https://www.paypal.com/cgi-bin/webscr', 'https://www.sandbox.paypal.com/cgi-bin/webscr', 'paypal.png', 'https://www.paypal.com', 'yes', 'class.paypal.php', 'yes'),
	(2, '2Checkout', 'https://www.2checkout.com/checkout/purchase', 'https://www.2checkout.com/checkout/purchase', '2checkout.png', 'https://www.2checkout.com', 'no', 'class.2checkout.php', 'no'),
	(3, 'Skrill', 'https://www.moneybookers.com/app/payment.pl', 'https://www.moneybookers.com/app/payment.pl', 'skrill.png', 'https://www.moneybookers.com', 'no', 'class.skrill.php', 'no'),
	(4, 'Payza', 'https://www.payza.com/PayProcess.aspx', 'https://sandbox.payza.com/sandbox/payprocess.aspx', 'payza.png', 'https://www.payza.com', 'no', 'class.payza.php', 'no'),
	(5, 'CardSave', 'https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx', 'https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx', 'cardsave.png', 'http://www.cardsave.net', 'no', 'class.cardsave.php', 'no'),
	(6, 'Sage Pay', 'https://live.sagepay.com/gateway/service/vspform-register.vsp', 'https://test.sagepay.com/Simulator/VSPFormGateway.asp', 'sagepay.png', 'http://www.sagepay.com', 'no', 'class.sagepay.php', 'no'),
	(7, 'eWay UK', 'https://payment.ewaygateway.com/Request', 'https://payment.ewaygateway.com/Results', 'eway.png', 'http://www.eway.co.uk', 'no', 'class.eway.php', 'no'),
	(8, 'WorldPay', 'https://secure.worldpay.com/wcc/purchase', 'https://secure-test.worldpay.com/wcc/purchase', 'worldpay.png', 'http://www.worldpay.com', 'no', 'class.worldpay.php', 'no'),
	(9, 'Cardstream', 'https://gateway.cardstream.com/hosted/', 'https://gateway.cardstream.com/hosted/', 'cardstream.png', 'http://www.cardstream.com', 'no', 'class.cardstream.php', 'no'),
	(10, 'Paypoint', 'https://www.secpay.com/java-bin/ValCard', 'https://www.secpay.com/java-bin/ValCard', 'paypoint.png', 'http://www.paypoint.net', 'no', 'class.paypoint.php', 'no'),
	(11, 'Authorize.net', 'https://secure.authorize.net/gateway/transact.dll', 'https://test.authorize.net/gateway/transact.dll', 'authnet.png', 'http://www.authorize.net', 'no', 'class.authnet.php', 'no'),
	(12, 'Fetch', 'https://my.fetchpayments.co.nz/webpayments/default.aspx', 'https://demo.fetchpayments.co.nz/webpayments/default.aspx', 'fetch.png', 'https://www.fetchpayments.co.nz', 'no', 'class.fetch.php', 'no'),
	(13, 'Realex Payments', 'https://epage.payandshop.com/epage.cgi', 'https://epage.payandshop.com/epage.cgi', 'realex.png', 'http://www.realexpayments.co.uk', 'no', 'class.realex.php', 'no'),
	(14, 'Beanstream', 'https://www.beanstream.com/scripts/payment/payment.asp', 'https://www.beanstream.com/scripts/payment/payment.asp', 'beanstream.png', 'https://www.beanstream.com', 'no', 'class.beanstream.php', 'no'),
	(15, 'Charity Clear', 'https://gateway.charityclear.com/hosted/', 'https://gateway.charityclear.com/hosted/', 'charity.png', 'http://www.charityclear.com', 'no', 'class.charity.php', 'no'),
	(16, 'IcePay', 'https://pay.icepay.eu/Checkout.aspx', 'https://pay.icepay.eu/Checkout.aspx', 'icepay.png', 'http://www.icepay.com', 'no', 'class.icepay.php', 'no'),
	(17, 'CCNow', 'https://www.ccnow.com/cgi-local/transact.cgi', 'https://www.ccnow.com/cgi-local/transact.cgi', 'ccnow.png', 'http://www.ccnow.com', 'no', 'class.ccnow.php', 'no'),
	(18, 'Paytrail', 'https://payment.paytrail.com', 'https://payment.paytrail.com', 'paytrail.png', 'http://www.paytrail.com/en/', 'no', 'class.paytrail.php', 'no'),
	(19, 'Iridium Corporation', 'https://mms.iridiumcorp.net/Pages/PublicPages/PaymentForm.aspx', 'https://mms.iridiumcorp.net/Pages/PublicPages/PaymentForm.aspx', 'iridium.png', 'http://www.iridiumcorp.co.uk', 'no', 'class.iridium.php', 'no'),
	(20, 'Global Iris', 'https://redirect.globaliris.com/epage.cgi', 'https://redirect.globaliris.com/epage.cgi', 'iris.png', 'http://www.globalpaymentsinc.co.uk/global-iris.html', 'no', 'class.iris.php', 'no'),
  (21, 'JamboPay (Kenya)', 'https://www.jambopay.com/JPExpress.aspx', 'https://www.jambopay.com/JPExpress.aspx', 'jambo.png', 'https://www.jambopay.com', 'no', 'class.jambo.php', 'no'),
  (22, 'Secure Trading', 'https://payments.securetrading.net/process/payments/choice', 'https://payments.securetrading.net/process/payments/choice', 'sectrading.png', 'https://www.securetrading.com', 'no', 'class.sectrading.php', 'no'),
  (23, 'JamboPay (Tanzania)', 'https://www.jambopay.com/JPExpressTz.aspx', 'https://www.jambopay.com/JPExpressTz.aspx', 'jambo.png', 'https://www.jambopay.com', 'no', 'class.jambo2.php', 'no');

-- Dumping structure for table mm_gateways_params
DROP TABLE IF EXISTS `mm_gateways_params`;
CREATE TABLE IF NOT EXISTS `mm_gateways_params` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `gateway` int(6) NOT NULL DEFAULT '0',
  `param` varchar(200) NOT NULL DEFAULT '',
  `value` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `mthd_index` (`gateway`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- Dumping data for table mm_gateways_params: 59 rows
INSERT INTO `mm_gateways_params` (`id`, `gateway`, `param`, `value`) VALUES
	(1, 11, 'login-id', ''),
	(2, 11, 'transaction-key', ''),
	(3, 11, 'response-key', ''),
	(4, 14, 'merchant-id', ''),
	(5, 14, 'language', 'ENG'),
	(6, 14, 'hash-value', ''),
	(7, 5, 'password', ''),
	(8, 5, 'merchant-id', ''),
	(9, 5, 'pre-share-key', ''),
	(10, 9, 'merchant-id', ''),
	(11, 17, 'secret-key', ''),
	(12, 17, 'language', 'en'),
	(13, 17, 'login-id', ''),
	(14, 17, 'activation-key', ''),
	(15, 15, 'merchant-id', ''),
	(16, 7, 'company-logo', ''),
	(17, 7, 'page-banner', ''),
	(18, 7, 'page-title', ''),
	(19, 7, 'page-footer', ''),
	(20, 7, 'page-desc', ''),
	(21, 7, 'language', 'EN'),
	(22, 7, 'username', ''),
	(23, 7, 'customer-id', ''),
	(24, 16, 'merchant-id', ''),
	(25, 16, 'encryption-code', ''),
	(26, 16, 'language', 'EN'),
	(27, 19, 'merchant-id', ''),
	(28, 19, 'pre-share-key', ''),
	(29, 19, 'password', ''),
	(30, 20, 'secret-key', ''),
	(31, 20, 'merchant-id', ''),
	(32, 20, 'sub-account', ''),
	(33, 3, 'logo', ''),
	(34, 3, 'secret', ''),
	(35, 3, 'email', ''),
	(36, 3, 'language', 'EN'),
	(37, 1, 'locale', ''),
	(38, 1, 'pagestyle', ''),
	(39, 1, 'email', ''),
	(40, 10, 'logo', ''),
	(41, 10, 'pass-remote', ''),
	(42, 10, 'merchant-id', ''),
	(43, 4, 'ipncode', ''),
	(44, 4, 'email', ''),
	(45, 13, 'merchant-id', ''),
	(46, 13, 'sub-account', ''),
	(47, 13, 'secret-key', ''),
	(48, 6, 'vendor', ''),
	(49, 6, 'encryption', 'aes'),
	(50, 6, 'xor-password', ''),
	(51, 18, 'merchant-id', ''),
	(52, 18, 'language', 'en_US'),
	(53, 18, 'auth-hash', ''),
	(54, 2, 'account', ''),
	(55, 2, 'secret', ''),
	(56, 2, 'language', 'EN'),
	(57, 8, 'install-id', ''),
	(59, 12, 'account-id', ''),
  (60, 12, 'secret-key', ''),
  (61, 9, 'signature-key', ''),
  (62, 15, 'signature-key', ''),
  (63, 21, 'business-address', ''),
  (64, 21, 'shared-key', ''),
  (65, 22, 'site-reference', ''),
  (66, 22, 'notify-password', ''),
  (67, 22, 'merchant-password', '');

-- Dumping structure for table mm_geo_ipv4
DROP TABLE IF EXISTS `mm_geo_ipv4`;
CREATE TABLE IF NOT EXISTS `mm_geo_ipv4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_ip` varchar(100) NOT NULL DEFAULT '',
  `to_ip` varchar(100) NOT NULL DEFAULT '',
  `loc_start` varchar(100) NOT NULL DEFAULT '0',
  `loc_end` varchar(100) NOT NULL DEFAULT '0',
  `country_iso` char(2) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=101604 DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_geo_ipv6
DROP TABLE IF EXISTS `mm_geo_ipv6`;
CREATE TABLE IF NOT EXISTS `mm_geo_ipv6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_ip` varchar(100) NOT NULL DEFAULT '',
  `to_ip` varchar(100) NOT NULL DEFAULT '',
  `loc_start` varchar(100) NOT NULL DEFAULT '0',
  `loc_end` varchar(100) NOT NULL DEFAULT '0',
  `country_iso` char(2) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21524 DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_music
DROP TABLE IF EXISTS `mm_music`;
CREATE TABLE IF NOT EXISTS `mm_music` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `collection` int(7) NOT NULL DEFAULT '0',
  `mp3file` text default null,
  `previewfile` text default null,
  `length` varchar(10) NOT NULL DEFAULT '',
  `bitrate` varchar(100) NOT NULL DEFAULT '',
  `samplerate` varchar(100) NOT NULL DEFAULT '',
  `cost` varchar(10) NOT NULL DEFAULT '',
  `order` int(9) NOT NULL DEFAULT '0',
  `ts` int(30) NOT NULL DEFAULT '0',
  `updated` int(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `col` (`collection`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_music_styles
DROP TABLE IF EXISTS `mm_music_styles`;
CREATE TABLE IF NOT EXISTS `mm_music_styles` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `slug` varchar(50) NOT NULL DEFAULT '',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `type` int(10) not null default '0',
  `orderby` varchar(10) not null default '0',
  `collection` int(7) not null default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Dumping data for table mm_music_styles: 15 rows
INSERT INTO `mm_music_styles` (`id`, `name`, `slug`, `enabled`, `type`, `orderby`) VALUES
	(1, 'Electronic', '', 'yes', '0', '01'),
	(2, 'Instrumental', '', 'yes', '0', '02'),
	(3, 'Classical', '', 'yes', '0', '03'),
	(4, 'Country', '', 'yes', '0', '04'),
	(5, 'World Music', '', 'yes', '0', '05'),
	(6, 'Easy Listening', '', 'yes', '0', '06'),
	(7, 'Singer/Songwriter', '', 'yes', '6', '01'),
	(8, 'House Music', '', 'yes', '1', '01'),
	(9, 'New Age', '', 'yes', '2', '01'),
	(10, 'Pop Music', '', 'yes', '1', '02'),
	(11, 'Soundtracks', '', 'yes', '2', '02'),
	(12, 'Japanese Pop', '', 'yes', '5', '01'),
	(13, 'Traditional', '', 'yes', '4', '01'),
	(14, 'Techno/Trance', '', 'yes', '1', '03'),
	(15, 'Korean Pop', '', 'yes', '5', '02'),
  (16, '80s Electro', '', 'yes', '1', '03'),
  (17, 'Chinese Pop', '', 'yes', '5', '03'),
  (18, 'Ambient', '', 'yes', '2', '03');

-- Dumping structure for table mm_offers
DROP TABLE IF EXISTS `mm_offers`;
CREATE TABLE IF NOT EXISTS `mm_offers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `discount` varchar(10) NOT NULL DEFAULT '',
  `expiry` int(30) NOT NULL DEFAULT '0',
  `type` enum('collections','tracks','all','cd') NOT NULL DEFAULT 'collections',
  `collections` text default null,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_pages
DROP TABLE IF EXISTS `mm_pages`;
CREATE TABLE IF NOT EXISTS `mm_pages` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `info` text default null,
  `keys` text default null,
  `desc` text default null,
  `title` text default null,
  `orderby` int(5) NOT NULL DEFAULT '0',
  `template` varchar(250) NOT NULL DEFAULT '',
  `landing` enum('yes','no') NOT NULL DEFAULT 'no',
  `slug` varchar(250) NOT NULL DEFAULT '',
  `enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Dumping data for table mm_pages: 4 rows
INSERT INTO `mm_pages` (`id`, `name`, `info`, `keys`, `desc`, `title`, `orderby`, `template`, `landing`, `slug`, `enabled`) VALUES
	(1, 'About Our Music', 'This is an additional page. Add your own text default null, edit or delete.', '', '', '', 1, '', 'no', 'about-page', 'yes'),
	(2, 'Licence', 'This is an additional page. Add your own text default null, edit or delete.', '', '', '', 4, '', 'no', 'licence', 'yes'),
	(3, 'Company Info', 'This is an additional page. Add your own text default null, edit or delete.', '', '', '', 3, '', 'no', 'company-info', 'yes'),
	(4, 'Contact Us', '', '', '', 'How to Contact Us', 2, 'contact.tpl.php', 'no', 'contact', 'yes');

-- Dumping structure for table mm_sales
DROP TABLE IF EXISTS `mm_sales`;
CREATE TABLE IF NOT EXISTS `mm_sales` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `invoice` varchar(64) NOT NULL DEFAULT '',
  `account` int(8) NOT NULL DEFAULT '0',
  `ip` text default null,
  `iso` char(2) NOT NULL DEFAULT '',
  `ts` int(30) NOT NULL DEFAULT '0',
  `gateway` int(11) not null default '0',
  `paytotal` varchar(20) not null default '0.00',
  `subtotal` varchar(20) not null default '0.00',
  `shipping` varchar(10) not null default '0.00',
  `tax` varchar(10) not null default '0.00',
  `tax2` varchar(10) not null default '0.00',
  `taxRate` varchar(10) not null default '0',
  `taxRate2` varchar(10) not null default '0',
  `taxCountry` int(3) not null default '0',
  `taxCountry2` int(3) not null default '0',
  `shipID` int(6) not null default '0',
  `shippingAddr` text default null,
  `transaction` varchar(250) NOT NULL DEFAULT '',
  `locked` enum('yes','no') NOT NULL DEFAULT 'no',
  `lockreason` text default null,
  `enabled` enum('yes','no') NOT NULL DEFAULT 'no',
  `status` varchar(250) NOT NULL DEFAULT '',
  `notes` text default null,
  `code` varchar(40) NOT NULL DEFAULT '',
  `refcode` varchar(250) NOT NULL DEFAULT '',
  `gateparams` text default null,
  `coupon` text default null,
  `sys1` varchar(200) not null default '',
  `sys2` varchar(200) not null default '',
  PRIMARY KEY (`id`),
  KEY `acc` (`account`),
  KEY `code` (`code`),
  KEY `iso` (`iso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_sales_click
DROP TABLE IF EXISTS `mm_sales_click`;
CREATE TABLE IF NOT EXISTS `mm_sales_click` (
  `id` int(7) unsigned NOT NULL AUTO_INCREMENT,
  `sale` int(7) NOT NULL DEFAULT '0',
  `trackcol` int(7) NOT NULL DEFAULT '0',
  `ip` varchar(250) NOT NULL DEFAULT '',
  `ts` int(30) NOT NULL DEFAULT '0',
  `action` varchar(250) NOT NULL DEFAULT '',
  `type` enum('admin','visitor') NOT NULL DEFAULT 'visitor',
  `iso` char(2) NOT NULL DEFAULT '',
  `country` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `saleid_index` (`sale`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_sales_clipboard
DROP TABLE IF EXISTS `mm_sales_clipboard`;
CREATE TABLE IF NOT EXISTS `mm_sales_clipboard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `trackcol` int(7) NOT NULL DEFAULT '0',
  `type` enum('track','collection') NOT NULL DEFAULT 'track',
  `physical` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_sales_items
DROP TABLE IF EXISTS `mm_sales_items`;
CREATE TABLE IF NOT EXISTS `mm_sales_items` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `sale` int(9) NOT NULL DEFAULT '0',
  `item` int(9) NOT NULL DEFAULT '0',
  `collection` int(9) NOT NULL DEFAULT '0',
  `type` enum('collection','track','none') NOT NULL DEFAULT 'none',
  `physical` enum('yes','no') NOT NULL DEFAULT 'no',
  `expiry` int(30) NOT NULL DEFAULT '0',
  `cost` varchar(10) NOT NULL DEFAULT '0.00',
  `clicks` int(30) NOT NULL DEFAULT '0',
  `token` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `sale` (`sale`),
  KEY `item` (`item`),
  KEY `col` (`collection`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping structure for table mm_settings
DROP TABLE IF EXISTS `mm_settings`;
CREATE TABLE IF NOT EXISTS `mm_settings` (
  `id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `website` varchar(100) NOT NULL DEFAULT '',
  `email` text default null,
  `httppath` varchar(250) NOT NULL DEFAULT '',
  `secfolder` varchar(250) NOT NULL DEFAULT '',
  `dateformat` varchar(20) NOT NULL DEFAULT '',
  `timeformat` varchar(10) NOT NULL DEFAULT '',
  `jsformat` varchar(15) NOT NULL DEFAULT 'DD-MM-YYYY',
  `timezone` varchar(50) NOT NULL DEFAULT '',
  `weekstart` enum('sun','mon') NOT NULL DEFAULT 'sun',
  `zip` enum('yes','no') NOT NULL DEFAULT 'yes',
  `rewrite` enum('yes','no') NOT NULL DEFAULT 'no',
  `paymode` enum('test','live') NOT NULL DEFAULT 'test',
  `responselog` enum('yes','no') NOT NULL DEFAULT 'yes',
  `propend` enum('yes','no') NOT NULL DEFAULT 'no',
  `version` varchar(10) NOT NULL DEFAULT '',
  `prodkey` varchar(60) NOT NULL DEFAULT '',
  `smtp_host` varchar(100) NOT NULL DEFAULT 'localhost',
  `smtp_user` varchar(100) NOT NULL DEFAULT '',
  `smtp_pass` varchar(100) NOT NULL DEFAULT '',
  `smtp_port` varchar(10) NOT NULL DEFAULT '25',
  `smtp_from` varchar(250) NOT NULL DEFAULT '',
  `smtp_email` varchar(250) NOT NULL DEFAULT '',
  `smtp_security` varchar(5) NOT NULL DEFAULT '',
  `smtp_other` text default null,
  `sysstatus` enum('yes','no') NOT NULL DEFAULT 'no',
  `autoenable` int(30) NOT NULL DEFAULT '0',
  `reason` text default null,
  `allowip` text default null,
  `currency` char(3) NOT NULL DEFAULT 'GBP',
  `invoice` int(8) NOT NULL DEFAULT '0',
  `curdisplay` varchar(250) NOT NULL DEFAULT '',
  `access` varchar(200) NOT NULL DEFAULT '',
  `afoot` text default null,
  `pfoot` text default null,
  `theme` varchar(250) NOT NULL DEFAULT '_theme_default',
  `featured` text default null,
  `metakeys` text default null,
  `metadesc` text default null,
  `minpass` int(5) NOT NULL DEFAULT '8',
  `emnotify` text default null,
  `deftax` tinyint(2) NOT NULL DEFAULT '0',
  `deftax2` tinyint(2) not null default '0',
  `defCountry` int(3) not null default '0',
  `defCountry2` int(3) not null default '0',
  `resip` enum('yes','no') NOT NULL DEFAULT 'no',
  `facebook` enum('yes','no') NOT NULL DEFAULT 'yes',
  `social` text default null,
  `licsubj` varchar(100) NOT NULL DEFAULT '',
  `licmsg` text default null,
  `licenable` enum('yes','no') NOT NULL DEFAULT 'no',
  `maxupdate` int(30) NOT NULL DEFAULT '0',
  `geoip` enum('yes','no') NOT NULL DEFAULT 'yes',
  `statistics` text default null,
  `minpurchase` varchar(20) NOT NULL DEFAULT '',
  `cdpur` enum('yes','no') not null default 'no',
  `rss` enum('yes','no') not null default 'yes',
  `hideparams` enum('yes','no') not null default 'no',
  `acclogin` enum('yes','no') not null default 'yes',
  `accloginflag` int(5) not null default '0',
  `termsmsg` text default null,
  `termsenable` enum('yes','no') not null default 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table mm_settings: 1 rows
INSERT INTO `mm_settings` (`id`, `website`, `email`, `httppath`, `secfolder`, `dateformat`, `timeformat`, `jsformat`, `timezone`, `weekstart`, `zip`, `rewrite`, `paymode`, `responselog`, `propend`, `version`, `prodkey`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_from`, `smtp_email`, `smtp_security`, `smtp_other`, `sysstatus`, `autoenable`, `reason`, `allowip`, `currency`, `invoice`, `curdisplay`, `access`, `afoot`, `pfoot`, `theme`, `featured`, `metakeys`, `metadesc`, `minpass`, `emnotify`, `deftax`, `resip`, `facebook`, `social`, `licsubj`, `licmsg`, `licenable`, `maxupdate`, `geoip`, `statistics`, `minpurchase`, `cdpur`, `rss`, `hideparams`) VALUES
	(1, 'Music Store', 'email@example.com', 'FirstRun', '', 'j M Y', 'H:i:s', 'DD-MM-YYYY', 'Europe/London', 'sun', 'no', 'no', 'test', 'yes', 'no', '2.0', '', '', '', '', '587', '', '', '', '', 'yes', 0, '', '', 'GBP', 0, '&pound;{AMOUNT}', 'a:8:{i:0;i:24;i:1;s:3:"hrs";i:2;i:5;i:3;s:3:"yes";i:4;s:2:"no";i:5;i:5;i:6;s:3:"yes";i:7;s:3:"tmp";}', '', '', '_theme_default', '', '', '', 8, 'a:8:{s:7:"salecus";s:1:"1";s:7:"saleweb";s:1:"1";s:10:"salecuspen";s:1:"1";s:10:"salewebpen";s:1:"1";s:7:"cusprof";s:1:"1";s:7:"webprof";s:1:"1";s:5:"cuscr";s:1:"1";s:5:"webcr";s:1:"1";}', 0, 'no', 'yes', 'a:8:{s:2:"fb";s:0:"";s:2:"gg";s:0:"";s:2:"tw";s:0:"";s:2:"li";s:0:"";s:2:"yt";s:0:"";s:2:"sc";s:0:"";s:2:"sp";s:0:"";s:2:"fm";s:0:"";}', '[Music Store] License Agreement - Please Read', '', 'yes', 0, 'no', 'a:4:{s:5:"years";s:9:"";s:4:"best";s:2:"20";s:5:"month";s:4:"this";s:6:"legacy";s:2:"15";}', '', 'no', 'yes', 'no');

-- Dumping structure for table mm_shipping
DROP TABLE IF EXISTS `mm_shipping`;
CREATE TABLE IF NOT EXISTS `mm_shipping` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL DEFAULT '',
  `cost` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Dumping data for table mm_shipping: 3 rows
INSERT INTO `mm_shipping` (`id`, `name`, `cost`) VALUES
	(1, 'Europe', '1.00'),
	(2, 'America', '10%'),
	(3, 'Asia', '5.00');