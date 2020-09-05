-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-05-2020 a las 16:33:57
-- Versión del servidor: 10.1.34-MariaDB
-- Versión de PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gostock`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `welcome_text` varchar(200) NOT NULL,
  `welcome_subtitle` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `result_request` int(10) UNSIGNED NOT NULL COMMENT 'The max number of images per request',
  `limit_upload_user` int(10) UNSIGNED NOT NULL,
  `status_page` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Offline, 1 Online',
  `message_length` int(11) UNSIGNED NOT NULL,
  `comment_length` int(11) UNSIGNED NOT NULL,
  `registration_active` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 No, 1 Yes',
  `email_verification` enum('0','1') NOT NULL COMMENT '0 Off, 1 On',
  `email_no_reply` varchar(200) NOT NULL,
  `email_admin` varchar(200) NOT NULL,
  `captcha` enum('on','off') NOT NULL DEFAULT 'on',
  `file_size_allowed` int(11) UNSIGNED NOT NULL COMMENT 'Size in Bytes',
  `facebook_login` enum('on','off') NOT NULL DEFAULT 'off',
  `google_analytics` text NOT NULL,
  `invitations_by_email` enum('on','off') NOT NULL DEFAULT 'on',
  `twitter` varchar(200) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `googleplus` varchar(200) NOT NULL,
  `linkedin` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `google_adsense` text NOT NULL,
  `auto_approve_images` enum('on','off') NOT NULL DEFAULT 'off',
  `tags_limit` int(10) UNSIGNED NOT NULL,
  `downloads` enum('all','users') NOT NULL DEFAULT 'all',
  `google_ads_index` enum('on','off') NOT NULL DEFAULT 'off',
  `description_length` int(10) UNSIGNED NOT NULL,
  `min_width_height_image` varchar(25) NOT NULL,
  `google_adsense_index` text NOT NULL,
  `link_privacy` varchar(200) NOT NULL,
  `link_terms` varchar(200) NOT NULL,
  `twitter_login` enum('on','off') NOT NULL DEFAULT 'off',
  `paypal_sandbox` enum('true','false') NOT NULL DEFAULT 'true',
  `paypal_account` varchar(200) NOT NULL,
  `fee_commission` int(10) UNSIGNED NOT NULL,
  `stripe_secret_key` varchar(200) NOT NULL,
  `stripe_public_key` varchar(200) NOT NULL,
  `max_deposits_amount` int(10) UNSIGNED NOT NULL,
  `min_deposits_amount` int(10) UNSIGNED NOT NULL,
  `min_sale_amount` int(10) UNSIGNED NOT NULL,
  `max_sale_amount` int(10) UNSIGNED NOT NULL,
  `enable_paypal` enum('0','1') NOT NULL DEFAULT '0',
  `enable_stripe` enum('0','1') NOT NULL DEFAULT '0',
  `currency_position` enum('left','right') NOT NULL DEFAULT 'left',
  `currency_symbol` varchar(200) NOT NULL,
  `currency_code` varchar(200) NOT NULL,
  `amount_min_withdrawal` int(10) UNSIGNED NOT NULL,
  `sell_option` enum('on','off') NOT NULL DEFAULT 'on',
  `show_images_index` enum('latest','featured','both') NOT NULL DEFAULT 'latest',
  `show_watermark` enum('1','0') NOT NULL DEFAULT '1',
  `file_size_allowed_vector` int(10) UNSIGNED NOT NULL DEFAULT '1024',
  `link_license` varchar(200) NOT NULL,
  `decimal_format` enum('comma','dot') NOT NULL DEFAULT 'dot',
  `version` varchar(5) NOT NULL,
  `title_length` int(10) UNSIGNED NOT NULL,
  `daily_limit_downloads` int(10) UNSIGNED NOT NULL,
  `fee_commission_non_exclusive` int(10) UNSIGNED NOT NULL,
  `who_can_sell` enum('all','admin') NOT NULL DEFAULT 'all',
  `who_can_upload` enum('all','admin') NOT NULL DEFAULT 'all',
  `show_counter` enum('on','off') NOT NULL DEFAULT 'on',
  `show_categories_index` enum('on','off') NOT NULL DEFAULT 'on',
  `free_photo_upload` enum('on','off') NOT NULL DEFAULT 'on',
  `price_formats` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Manual, 1 Automatic',
  `logo` varchar(100) NOT NULL,
  `favicon` varchar(100) NOT NULL,
  `image_header` varchar(100) NOT NULL,
  `image_bottom` varchar(100) NOT NULL,
  `watermark` varchar(100) NOT NULL,
  `header_colors` varchar(100) NOT NULL,
  `header_cameras` varchar(100) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `cover` varchar(100) NOT NULL,
  `img_category` varchar(100) NOT NULL,
  `img_collection` varchar(100) NOT NULL,
  `youtube` varchar(200) NOT NULL,
  `pinterest` varchar(200) NOT NULL,
  `lightbox` enum('on','off') NOT NULL DEFAULT 'on'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `title`, `description`, `welcome_text`, `welcome_subtitle`, `keywords`, `result_request`, `limit_upload_user`, `status_page`, `message_length`, `comment_length`, `registration_active`, `email_verification`, `email_no_reply`, `email_admin`, `captcha`, `file_size_allowed`, `facebook_login`, `google_analytics`, `invitations_by_email`, `twitter`, `facebook`, `googleplus`, `linkedin`, `instagram`, `google_adsense`, `auto_approve_images`, `tags_limit`, `downloads`, `google_ads_index`, `description_length`, `min_width_height_image`, `google_adsense_index`, `link_privacy`, `link_terms`, `twitter_login`, `paypal_sandbox`, `paypal_account`, `fee_commission`, `stripe_secret_key`, `stripe_public_key`, `max_deposits_amount`, `min_deposits_amount`, `min_sale_amount`, `max_sale_amount`, `enable_paypal`, `enable_stripe`, `currency_position`, `currency_symbol`, `currency_code`, `amount_min_withdrawal`, `sell_option`, `show_images_index`, `show_watermark`, `file_size_allowed_vector`, `link_license`, `decimal_format`, `version`, `title_length`, `daily_limit_downloads`, `fee_commission_non_exclusive`, `who_can_sell`, `who_can_upload`, `show_counter`, `show_categories_index`, `free_photo_upload`, `price_formats`, `logo`, `favicon`, `image_header`, `image_bottom`, `watermark`, `header_colors`, `header_cameras`, `avatar`, `cover`, `img_category`, `img_collection`, `youtube`, `pinterest`, `lightbox`) VALUES
(1, 'GoStock | Free Stock Photos', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut tortor rutrum massa efficitur tincidunt vel nec lacus. Curabitur porta aliquet diam, eu gravida neque lacinia in. Praesent eget orci id sem commodo aliquet.', 'GoStock!', 'Free Stock Photos', 'images free,stock images,stock free images', 12, 2, '1', 180, 180, '1', '0', 'no-reply@miguelvasquez.net', 'admin@miguelvasquez.net', 'off', 2048, 'off', '', 'off', 'https://www.twitter.com/', 'https://www.facebook.com/', 'https://plus.google.com/', 'https://www.linkedin.com/', 'https://www.instagram.com/', '', 'on', 10, 'users', 'off', 160, '1600x900', '', 'https://yousite.com/page/privacy', 'https://yousite.com/page/terms', 'off', 'true', '', 5, '', '', 100, 5, 100, 5, '0', '0', 'left', '$', 'USD', 25, 'on', 'latest', '1', 1024, '', 'dot', '3.9', 50, 0, 70, 'all', 'all', 'on', 'on', 'on', '1', 'logo.png', 'favicon.png', 'header_index.jpg', 'cover.jpg', 'watermark.png', 'header_colors.jpg', 'header_cameras.jpg', 'default.jpg', 'cover.jpg', 'default.jpg', 'img-collection.jpg', '', '', 'on');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `thumbnail` varchar(150) NOT NULL,
  `mode` enum('on','off') NOT NULL DEFAULT 'on'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `thumbnail`, `mode`) VALUES
(1, 'Uncategorized', 'uncategorized', '', 'on'),
(2, 'Animals', 'animals', '', 'on'),
(3, 'Architecture', 'architecture', '', 'on'),
(4, 'Foods & Drinks', 'foods-drinks', '', 'on'),
(5, 'Music', 'music', '', 'on'),
(6, 'People', 'people', '', 'on'),
(7, 'Places', 'places', '', 'on'),
(8, 'Sports', 'sports', '', 'on'),
(9, 'Travel', 'travel', '', 'on'),
(10, 'Fashion', 'fashion', '', 'on'),
(11, 'Transportation / Cars', 'cars', '', 'on'),
(12, 'Nature / Landscapes', 'nature-landscapes', '', 'on'),
(13, 'Backgrounds / Textures', 'backgrounds', '', 'on'),
(14, 'Business / Finance', 'business', '', 'on'),
(15, 'Computer / Communication', 'computer', '', 'on'),
(16, 'Education', 'education', '', 'on'),
(17, 'Emotions', 'emotions', '', 'on'),
(18, 'Health / Medical', 'health', '', 'on'),
(19, 'Industry / Craft', 'industry', '', 'on'),
(20, 'Religion', 'religion', '', 'on'),
(21, 'Science / Technology', 'science-technology', '', 'on');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `collections`
--

CREATE TABLE `collections` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` enum('public','private') NOT NULL DEFAULT 'public'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `collections_images`
--

CREATE TABLE `collections_images` (
  `id` int(11) UNSIGNED NOT NULL,
  `collections_id` int(10) UNSIGNED NOT NULL,
  `images_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `images_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reply` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Trash, 1 Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments_likes`
--

CREATE TABLE `comments_likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `comment_id` int(11) UNSIGNED NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 trash, 1 active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'AF', 'Afghanistan'),
(4, 'AL', 'Albania'),
(5, 'DZ', 'Algeria'),
(6, 'DS', 'American Samoa'),
(7, 'AD', 'Andorra'),
(8, 'AO', 'Angola'),
(9, 'AI', 'Anguilla'),
(10, 'AQ', 'Antarctica'),
(11, 'AG', 'Antigua and/or Barbuda'),
(12, 'AR', 'Argentina'),
(13, 'AM', 'Armenia'),
(14, 'AW', 'Aruba'),
(15, 'AU', 'Australia'),
(16, 'AT', 'Austria'),
(17, 'AZ', 'Azerbaijan'),
(18, 'BS', 'Bahamas'),
(19, 'BH', 'Bahrain'),
(20, 'BD', 'Bangladesh'),
(21, 'BB', 'Barbados'),
(22, 'BY', 'Belarus'),
(23, 'BE', 'Belgium'),
(24, 'BZ', 'Belize'),
(25, 'BJ', 'Benin'),
(26, 'BM', 'Bermuda'),
(27, 'BT', 'Bhutan'),
(28, 'BO', 'Bolivia'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British lndian Ocean Territory'),
(34, 'BN', 'Brunei Darussalam'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TP', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'ID', 'Indonesia'),
(101, 'IR', 'Iran (Islamic Republic of)'),
(102, 'IQ', 'Iraq'),
(103, 'IE', 'Ireland'),
(104, 'IL', 'Israel'),
(105, 'IT', 'Italy'),
(106, 'CI', 'Ivory Coast'),
(107, 'JM', 'Jamaica'),
(108, 'JP', 'Japan'),
(109, 'JO', 'Jordan'),
(110, 'KZ', 'Kazakhstan'),
(111, 'KE', 'Kenya'),
(112, 'KI', 'Kiribati'),
(113, 'KP', 'Korea, Democratic People\'s Republic of'),
(114, 'KR', 'Korea, Republic of'),
(115, 'XK', 'Kosovo'),
(116, 'KW', 'Kuwait'),
(117, 'KG', 'Kyrgyzstan'),
(118, 'LA', 'Lao People\'s Democratic Republic'),
(119, 'LV', 'Latvia'),
(120, 'LB', 'Lebanon'),
(121, 'LS', 'Lesotho'),
(122, 'LR', 'Liberia'),
(123, 'LY', 'Libyan Arab Jamahiriya'),
(124, 'LI', 'Liechtenstein'),
(125, 'LT', 'Lithuania'),
(126, 'LU', 'Luxembourg'),
(127, 'MO', 'Macau'),
(128, 'MK', 'Macedonia'),
(129, 'MG', 'Madagascar'),
(130, 'MW', 'Malawi'),
(131, 'MY', 'Malaysia'),
(132, 'MV', 'Maldives'),
(133, 'ML', 'Mali'),
(134, 'MT', 'Malta'),
(135, 'MH', 'Marshall Islands'),
(136, 'MQ', 'Martinique'),
(137, 'MR', 'Mauritania'),
(138, 'MU', 'Mauritius'),
(139, 'TY', 'Mayotte'),
(140, 'MX', 'Mexico'),
(141, 'FM', 'Micronesia, Federated States of'),
(142, 'MD', 'Moldova, Republic of'),
(143, 'MC', 'Monaco'),
(144, 'MN', 'Mongolia'),
(145, 'ME', 'Montenegro'),
(146, 'MS', 'Montserrat'),
(147, 'MA', 'Morocco'),
(148, 'MZ', 'Mozambique'),
(149, 'MM', 'Myanmar'),
(150, 'NA', 'Namibia'),
(151, 'NR', 'Nauru'),
(152, 'NP', 'Nepal'),
(153, 'NL', 'Netherlands'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NC', 'New Caledonia'),
(156, 'NZ', 'New Zealand'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Niger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Norfork Island'),
(162, 'MP', 'Northern Mariana Islands'),
(163, 'NO', 'Norway'),
(164, 'OM', 'Oman'),
(165, 'PK', 'Pakistan'),
(166, 'PW', 'Palau'),
(167, 'PA', 'Panama'),
(168, 'PG', 'Papua New Guinea'),
(169, 'PY', 'Paraguay'),
(170, 'PE', 'Peru'),
(171, 'PH', 'Philippines'),
(172, 'PN', 'Pitcairn'),
(173, 'PL', 'Poland'),
(174, 'PT', 'Portugal'),
(175, 'PR', 'Puerto Rico'),
(176, 'QA', 'Qatar'),
(177, 'RE', 'Reunion'),
(178, 'RO', 'Romania'),
(179, 'RU', 'Russian Federation'),
(180, 'RW', 'Rwanda'),
(181, 'KN', 'Saint Kitts and Nevis'),
(182, 'LC', 'Saint Lucia'),
(183, 'VC', 'Saint Vincent and the Grenadines'),
(184, 'WS', 'Samoa'),
(185, 'SM', 'San Marino'),
(186, 'ST', 'Sao Tome and Principe'),
(187, 'SA', 'Saudi Arabia'),
(188, 'SN', 'Senegal'),
(189, 'RS', 'Serbia'),
(190, 'SC', 'Seychelles'),
(191, 'SL', 'Sierra Leone'),
(192, 'SG', 'Singapore'),
(193, 'SK', 'Slovakia'),
(194, 'SI', 'Slovenia'),
(195, 'SB', 'Solomon Islands'),
(196, 'SO', 'Somalia'),
(197, 'ZA', 'South Africa'),
(198, 'GS', 'South Georgia South Sandwich Islands'),
(199, 'ES', 'Spain'),
(200, 'LK', 'Sri Lanka'),
(201, 'SH', 'St. Helena'),
(202, 'PM', 'St. Pierre and Miquelon'),
(203, 'SD', 'Sudan'),
(204, 'SR', 'Suriname'),
(205, 'SJ', 'Svalbarn and Jan Mayen Islands'),
(206, 'SZ', 'Swaziland'),
(207, 'SE', 'Sweden'),
(208, 'CH', 'Switzerland'),
(209, 'SY', 'Syrian Arab Republic'),
(210, 'TW', 'Taiwan'),
(211, 'TJ', 'Tajikistan'),
(212, 'TZ', 'Tanzania, United Republic of'),
(213, 'TH', 'Thailand'),
(214, 'TG', 'Togo'),
(215, 'TK', 'Tokelau'),
(216, 'TO', 'Tonga'),
(217, 'TT', 'Trinidad and Tobago'),
(218, 'TN', 'Tunisia'),
(219, 'TR', 'Turkey'),
(220, 'TM', 'Turkmenistan'),
(221, 'TC', 'Turks and Caicos Islands'),
(222, 'TV', 'Tuvalu'),
(223, 'UG', 'Uganda'),
(224, 'UA', 'Ukraine'),
(225, 'AE', 'United Arab Emirates'),
(226, 'GB', 'United Kingdom'),
(227, 'UM', 'United States minor outlying islands'),
(228, 'UY', 'Uruguay'),
(229, 'UZ', 'Uzbekistan'),
(230, 'VU', 'Vanuatu'),
(231, 'VA', 'Vatican City State'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Virgin Islands (British)'),
(235, 'VI', 'Virgin Islands (U.S.)'),
(236, 'WF', 'Wallis and Futuna Islands'),
(237, 'EH', 'Western Sahara'),
(238, 'YE', 'Yemen'),
(239, 'YU', 'Yugoslavia'),
(240, 'ZR', 'Zaire'),
(241, 'ZM', 'Zambia'),
(242, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deposits`
--

CREATE TABLE `deposits` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `txn_id` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `payment_gateway` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `downloads`
--

CREATE TABLE `downloads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `images_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(25) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(5) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `followers`
--

CREATE TABLE `followers` (
  `id` int(10) UNSIGNED NOT NULL,
  `follower` int(11) UNSIGNED NOT NULL,
  `following` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '0 Delete, 1 Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `preview` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `categories_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','pending') NOT NULL DEFAULT 'pending',
  `token_id` varchar(255) NOT NULL,
  `tags` text NOT NULL,
  `extension` varchar(25) NOT NULL,
  `colors` text NOT NULL,
  `exif` varchar(255) NOT NULL,
  `camera` varchar(255) NOT NULL,
  `featured` enum('yes','no') NOT NULL DEFAULT 'no',
  `featured_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `how_use_image` enum('free','free_personal','editorial_only','web_only') NOT NULL DEFAULT 'free',
  `attribution_required` enum('yes','no') NOT NULL DEFAULT 'no',
  `original_name` varchar(255) NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `item_for_sale` enum('free','sale') NOT NULL DEFAULT 'free',
  `vector` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `images_reporteds`
--

CREATE TABLE `images_reporteds` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `image_id` int(10) UNSIGNED NOT NULL,
  `reason` enum('copyright','privacy_issue','violent_sexual_content') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `languages`
--

INSERT INTO `languages` (`id`, `name`, `abbreviation`) VALUES
(1, 'English', 'en');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `likes`
--

CREATE TABLE `likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `images_id` int(11) UNSIGNED NOT NULL,
  `status` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '1' COMMENT '0 trash, 1 active',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `destination` int(10) UNSIGNED NOT NULL,
  `author` int(10) UNSIGNED NOT NULL,
  `target` int(10) UNSIGNED NOT NULL,
  `type` enum('1','2','3','4','5','6','7') NOT NULL COMMENT '1 Follow, 2  Like, 3 reply, 4 Like Comment',
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 unseen, 1 seen',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trash` enum('0','1') NOT NULL DEFAULT '0' COMMENT '''0 No'',''1Yes'''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `slug`) VALUES
(1, 'Help', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'help'),
(2, 'Terms', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets</p>\r\n', 'terms-of-service'),
(3, 'Privacy', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'privacy'),
(5, 'About', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'about');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `token` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `sandbox` enum('true','false') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'true',
  `fee` decimal(3,1) NOT NULL,
  `fee_cents` decimal(2,2) NOT NULL,
  `email` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_info` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `name`, `type`, `enabled`, `sandbox`, `fee`, `fee_cents`, `email`, `token`, `key`, `key_secret`, `bank_info`) VALUES
(1, 'PayPal', 'normal', '0', 'true', '5.4', '0.30', 'paypal@yoursite.com', '02bGGfD9bHevK3eJN06CdDvFSTXsTrTG44yGdAONeN1R37jqnLY1PuNF0mJRoFnsEygyf28yePSCA1eR0alQk4BX89kGG9Rlha2D2KX55TpDFNR5o774OshrkHSZLOFo2fAhHzcWKnwsYDFKgwuaRg', '', '', ''),
(2, 'Stripe', 'card', '0', 'true', '2.9', '0.30', '', 'asfQSGRvYzS1P0X745krAAyHeU7ZbTpHbYKnxI2abQsBUi48EpeAu5lFAU2iBmsUWO5tpgAn9zzussI4Cce5ZcANIAmfBz0bNR9g3UfR4cserhkJwZwPsETiXiZuCixXVDHhCItuXTPXXSA6KITEoT', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchases`
--

CREATE TABLE `purchases` (
  `id` int(10) UNSIGNED NOT NULL,
  `images_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `approved` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `earning_net_seller` decimal(10,2) NOT NULL,
  `earning_net_admin` decimal(10,2) NOT NULL,
  `type` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `replies`
--

CREATE TABLE `replies` (
  `id` int(11) UNSIGNED NOT NULL,
  `comment_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `reply` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Trash, 1 Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserved`
--

CREATE TABLE `reserved` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `reserved`
--

INSERT INTO `reserved` (`id`, `name`) VALUES
(14, 'account'),
(31, 'api'),
(2, 'app'),
(30, 'bootstrap'),
(34, 'categories'),
(36, 'collections'),
(29, 'comment'),
(25, 'contact'),
(41, 'core'),
(35, 'featured'),
(38, 'feed'),
(32, 'freebies'),
(9, 'goods'),
(1, 'gostock1'),
(11, 'jobs'),
(21, 'join'),
(16, 'latest'),
(37, 'likes'),
(20, 'login'),
(33, 'logout'),
(27, 'members'),
(13, 'messages'),
(19, 'notifications'),
(15, 'popular'),
(6, 'porn'),
(26, 'programs'),
(12, 'projects'),
(3, 'public'),
(23, 'register'),
(17, 'search'),
(7, 'sex'),
(8, 'tags'),
(42, 'update'),
(24, 'upgrade'),
(28, 'upload'),
(4, 'vendor'),
(5, 'xxx');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `id` int(10) UNSIGNED NOT NULL,
  `images_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('small','medium','large','vector') NOT NULL,
  `extension` varchar(25) NOT NULL,
  `resolution` varchar(100) NOT NULL,
  `size` varchar(50) NOT NULL,
  `token` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `bio` varchar(200) NOT NULL,
  `countries_id` char(25) NOT NULL,
  `password` char(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(70) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `status` enum('pending','active','suspended','delete') NOT NULL DEFAULT 'pending',
  `type_account` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 Buyer, 2 Seller',
  `role` enum('normal','admin') NOT NULL DEFAULT 'normal',
  `website` varchar(255) NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `google` varchar(200) NOT NULL,
  `paypal_account` varchar(200) NOT NULL,
  `activation_code` varchar(150) NOT NULL,
  `oauth_uid` varchar(200) DEFAULT NULL,
  `oauth_provider` varchar(200) DEFAULT NULL,
  `token` varchar(80) NOT NULL,
  `authorized_to_upload` enum('yes','no') NOT NULL DEFAULT 'yes',
  `instagram` varchar(200) NOT NULL,
  `funds` int(10) UNSIGNED NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `payment_gateway` varchar(50) NOT NULL,
  `bank` text NOT NULL,
  `ip` varchar(30) NOT NULL,
  `author_exclusive` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `bio`, `countries_id`, `password`, `email`, `date`, `avatar`, `cover`, `status`, `type_account`, `role`, `website`, `remember_token`, `twitter`, `facebook`, `google`, `paypal_account`, `activation_code`, `oauth_uid`, `oauth_provider`, `token`, `authorized_to_upload`, `instagram`, `funds`, `balance`, `payment_gateway`, `bank`, `ip`, `author_exclusive`) VALUES
(1, 'Admin', 'Admin', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut tortor rutrum massa efficitur tincidunt vel nec lacus.', '1', '$2y$10$wjbCfnJZ07JcBEb4aGD58.Urd0yAFfHsAyofPE3w9W61y05MgZBJa', 'admin@example.com', '2020-05-09 23:16:43', 'default.jpg', 'cover.jpg', 'active', '1', 'admin', 'http://gostock.com', 'SLBSUImhTBuTedXwm7DHqnNlQqHkxfvr3jtAPoIBtSOMhcoP1I67pOjWLyvS', 'https://www.twitter.com/', 'https://www.facebook.com/', 'https://plus.google.com/+admin', 'miguelvasquezweb@gmail.com', '', NULL, NULL, '', 'yes', '', 0, '0.00', '', '', '', 'yes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_reporteds`
--

CREATE TABLE `users_reporteds` (
  `id` int(10) UNSIGNED NOT NULL,
  `reason` enum('copyright','privacy_issue','violent_sexual_content','spoofing') NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `id_reported` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visits`
--

CREATE TABLE `visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `images_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(25) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `amount` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gateway` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug` (`slug`);

--
-- Indices de la tabla `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `collections_images`
--
ALTER TABLE `collections_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`collections_id`,`images_id`);

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post` (`images_id`,`user_id`,`status`);

--
-- Indices de la tabla `comments_likes`
--
ALTER TABLE `comments_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`comment_id`);

--
-- Indices de la tabla `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publicacion_id` (`images_id`),
  ADD KEY `usr_id` (`user_id`),
  ADD KEY `ip` (`ip`);

--
-- Indices de la tabla `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower` (`follower`,`following`);

--
-- Indices de la tabla `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_id` (`token_id`),
  ADD KEY `author_id` (`user_id`,`status`,`token_id`),
  ADD KEY `image` (`preview`),
  ADD KEY `category_id` (`categories_id`);
ALTER TABLE `images` ADD FULLTEXT KEY `title` (`title`,`tags`);

--
-- Indices de la tabla `images_reporteds`
--
ALTER TABLE `images_reporteds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user_id`,`image_id`);

--
-- Indices de la tabla `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usr` (`user_id`,`images_id`,`status`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination` (`destination`,`author`,`target`,`status`),
  ADD KEY `trash` (`trash`);

--
-- Indices de la tabla `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_hash` (`token`),
  ADD KEY `email` (`email`);

--
-- Indices de la tabla `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post` (`comment_id`,`user_id`,`status`);

--
-- Indices de la tabla `reserved`
--
ALTER TABLE `reserved`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`) USING BTREE;

--
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_shot` (`images_id`,`type`),
  ADD KEY `token` (`token`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `username` (`username`,`status`),
  ADD KEY `activation_code` (`activation_code`),
  ADD KEY `role` (`role`),
  ADD KEY `users_avatar_index` (`avatar`),
  ADD KEY `users_cover_index` (`cover`);

--
-- Indices de la tabla `users_reporteds`
--
ALTER TABLE `users_reporteds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user_id`,`id_reported`);

--
-- Indices de la tabla `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publicacion_id` (`images_id`),
  ADD KEY `usr_id` (`user_id`),
  ADD KEY `ip` (`ip`);

--
-- Indices de la tabla `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `collections_images`
--
ALTER TABLE `collections_images`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comments_likes`
--
ALTER TABLE `comments_likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT de la tabla `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `images_reporteds`
--
ALTER TABLE `images_reporteds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserved`
--
ALTER TABLE `reserved`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users_reporteds`
--
ALTER TABLE `users_reporteds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `visits`
--
ALTER TABLE `visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
