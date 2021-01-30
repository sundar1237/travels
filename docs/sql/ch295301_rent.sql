-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 30. Jan 2021 um 10:12
-- Server-Version: 5.7.32-0ubuntu0.16.04.1
-- PHP-Version: 7.2.34-9+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `ch295301_rent`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `apartments`
--

CREATE TABLE `apartments` (
  `id` int(11) NOT NULL,
  `apartment_no` smallint(6) NOT NULL,
  `apartment_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rent` decimal(10,0) DEFAULT NULL,
  `extra_cost` decimal(10,0) DEFAULT NULL,
  `advance` decimal(10,0) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `house_id` int(11) DEFAULT NULL,
  `modified_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `apartments`
--

INSERT INTO `apartments` (`id`, `apartment_no`, `apartment_name`, `rent`, `extra_cost`, `advance`, `status`, `house_id`, `modified_date`) VALUES
(1, 1, 'Ground Floor left', '3600', NULL, '15000', 'Occupied', 1, NULL),
(2, 2, 'Ground Floor middle', '5000', NULL, '25000', 'Occupied', 1, NULL),
(3, 3, 'Ground Floor right', '5600', NULL, '25000', 'Occupied', 1, '2020-12-24'),
(4, 4, 'First Floor left', '4100', NULL, '25000', 'Occupied', 1, NULL),
(5, 5, 'First Floor middle', '3100', NULL, '15000', 'Occupied', 1, NULL),
(6, 6, 'First Floor right', '4500', NULL, '25000', 'Occupied', 1, NULL),
(7, 1, 'Ground Floor right', '5100', NULL, '25000', 'Occupied', 2, NULL),
(8, 2, 'Ground Floor left', '5100', NULL, '25000', 'Occupied', 2, NULL),
(9, 3, 'First Floor right', '4100', NULL, '20000', 'Occupied', 2, NULL),
(10, 4, 'First Floor left', '2000', NULL, '15000', 'Occupied', 2, NULL),
(11, 1, 'Ground Floor (Owner House)', '4000', NULL, '5000', 'Occupied', 3, '2020-11-01'),
(12, 2, 'First Floor', '0', NULL, '0', 'Empty', 3, '2020-10-01'),
(13, 3, 'GF Ottu veedu 1', '2300', NULL, '8000', 'Occupied', 3, '2021-01-18'),
(14, 4, 'GF Ottu veedu 2', '1000', NULL, '2000', 'Occupied', 3, NULL),
(16, 1, 'MKP', '2500', NULL, '5000', 'Occupied', 4, NULL),
(17, 1, 'Shop', '2500', NULL, '10000', 'Occupied', 5, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bill_months`
--

CREATE TABLE `bill_months` (
  `id` int(11) NOT NULL,
  `name` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bill_month` date NOT NULL,
  `bill_month_no` smallint(6) NOT NULL,
  `expected` int(11) DEFAULT NULL,
  `actual` int(11) DEFAULT NULL,
  `status` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `completed_in` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `bill_months`
--

INSERT INTO `bill_months` (`id`, `name`, `bill_month`, `bill_month_no`, `expected`, `actual`, `status`, `completed_in`) VALUES
(33, 'Jun-2020', '2020-06-01', 6, 36900, 36900, 'Completed', NULL),
(34, 'Jul-2020', '2020-07-01', 7, 36900, 36900, 'Completed', NULL),
(35, 'Aug-2020', '2020-08-01', 8, 36900, 36900, 'Completed', NULL),
(36, 'Sep-2020', '2020-09-01', 9, 49400, 49400, 'Completed', NULL),
(37, 'Oct-2020', '2020-10-01', 10, 50400, 38800, 'Pending', NULL),
(38, 'Nov-2020', '2020-11-01', 11, 50400, 31200, 'Pending', NULL),
(39, 'Dec-2020', '2020-12-01', 12, 52100, 29900, 'Open', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `parent_table` varchar(25) NOT NULL,
  `img_path` varchar(500) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `documents`
--

INSERT INTO `documents` (`id`, `parent_id`, `parent_table`, `img_path`, `description`) VALUES
(4, 1, 'tenants', './images/documents/tenants/1/WhatsApp Image 2021-01-03 at 12.07.56.jpeg', 'Rent Contract');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `houses`
--

CREATE TABLE `houses` (
  `id` int(11) NOT NULL,
  `order_no` smallint(6) DEFAULT NULL,
  `house_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_apartments` smallint(6) DEFAULT NULL,
  `ward_no` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google_map_src` text COLLATE utf8_unicode_ci,
  `eb_service_no` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `houses`
--

INSERT INTO `houses` (`id`, `order_no`, `house_name`, `address`, `no_of_apartments`, `ward_no`, `google_map_src`, `eb_service_no`) VALUES
(1, 1, 'Gandhi Theru Veedu', '20,20A, 20B, 20C,GANDHI STREET, SANGILIYANDAPURAM.', 6, 'WD-33', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d489.8952516707597!2d78.7031394163527!3d10.798900607967967!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3baaf4e1daf6e6eb%3A0xf73362d8e24190ab!2s20%2C%20Gandhi%20St%2C%20Sangliandapuram%2C%20Sangaliandalpuram%2C%20Tiruchirappalli%2C%20Tamil%20Nadu%20620001%2C%20India!5e0!3m2!1sen!2sch!4v1600767164164!5m2!1sen!2sch', 'Region - 06, Consumer No - 209143927'),
(2, 2, 'Saranya Veedu', '12/3,MARAIMALAI ADIGAL STREET,SANGILIYANDAPURAM', 4, 'WD-27', 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d489.8930133178971!2d78.704724!3d10.800273!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x58ea9f4d96575a5f!2sM.M.S%20INDUSTRIES%20.%20Wet%20grinder%2C%20grinder%20stand%2C%20wooden%20furniture.!5e0!3m2!1sen!2sch!4v1600769115300!5m2!1sen!2sch', '062091421042'),
(3, 3, 'Pathira kadai veedu', '7,8,9 MARAIMALAI ADIGAL STREET,MARAIMALAI ADIGAL STREET,SANGILIYANDAPURAM', 4, 'WD-27', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d489.89517986816605!2d78.70449862194685!3d10.798944634670756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3baaf56b054c2669%3A0x9220bcd7f928492a!2sB.G.%20Naidu%20Sweets!5e0!3m2!1sen!2sch!4v1601664847986!5m2!1sen!2sch', NULL),
(4, 4, 'Malaikudipatti veedu', 'Malaikudipatti', 1, '', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d490.29217725243353!2d78.5786819158383!3d10.552747082606245!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3baa7c8f4f4fd8f3%3A0xa924e23c5e7ebd12!2sSri%20Siddhivinyagar%20Temple!5e0!3m2!1sen!2sch!4v1600768957485!5m2!1sen!2sch', NULL),
(5, 5, 'kadai keelaputhur', '25, Keelaputhur mainroad,Palakkarai,Trichy', 1, 'WD-25', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d979.7724474645792!2d78.698914629226!3d10.80443481684965!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3baaf51d1e7ab769%3A0x12569347438847fa!2sArulmigu%20Munikannan%20Temple!5e0!3m2!1sen!2sch!4v1600764738462!5m2!1sen!2sch', 'Region 06- Consumer No - 2091491169');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `payment_mode` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_details` varchar(4000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paid_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comments` varchar(4000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` decimal(10,0) DEFAULT NULL,
  `paid_after` int(11) DEFAULT NULL,
  `fully_paid` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `payment_mode`, `payment_details`, `paid_date`, `comments`, `action`, `reason`, `balance`, `paid_after`, `fully_paid`, `parent_id`) VALUES
(312, 1, '3600', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '3600', NULL, 'NO', 33),
(313, 2, '5000', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '5000', NULL, 'NO', 33),
(314, 3, '5500', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '5500', NULL, 'NO', 33),
(315, 4, '4500', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '4500', NULL, 'NO', 33),
(316, 5, '3000', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '3000', NULL, 'NO', 33),
(317, 6, '4100', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '4100', NULL, 'NO', 33),
(318, 7, '4100', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '4100', NULL, 'NO', 33),
(319, 8, '5100', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '5100', NULL, 'NO', 33),
(320, 9, '2000', NULL, NULL, '2020-09-17 12:51:26', 'Rent is Added for Jun-2020', 'Added', 'Rent', '2000', NULL, 'NO', 33),
(321, 1, '3600', 'gpay', NULL, '2020-09-17 12:54:16', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(322, 2, '5000', 'gpay', NULL, '2020-09-17 12:54:30', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(323, 3, '5500', 'gpay', NULL, '2020-09-17 12:54:38', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(324, 4, '4500', 'gpay', NULL, '2020-09-17 12:54:48', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(325, 5, '3000', 'gpay', NULL, '2020-09-17 12:54:57', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(326, 7, '4100', 'gpay', NULL, '2020-09-17 12:55:09', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(327, 9, '2000', 'gpay', NULL, '2020-09-17 12:55:50', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(328, 1, '3600', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '3600', NULL, 'NO', 34),
(329, 2, '5000', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '5000', NULL, 'NO', 34),
(330, 3, '5500', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '5500', NULL, 'NO', 34),
(331, 4, '4500', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '4500', NULL, 'NO', 34),
(332, 5, '3000', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '3000', NULL, 'NO', 34),
(333, 6, '4100', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '4100', NULL, 'NO', 34),
(334, 7, '4100', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '4100', NULL, 'NO', 34),
(335, 8, '5100', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '5100', NULL, 'NO', 34),
(336, 9, '2000', NULL, NULL, '2020-09-17 12:56:41', 'Rent is Added for Jul-2020', 'Added', 'Rent', '2000', NULL, 'NO', 34),
(337, 1, '1600', 'gpay', NULL, '2020-09-17 12:57:15', NULL, 'Paid', 'Rent', '2000', NULL, 'Partial', 34),
(338, 3, '5500', 'gpay', NULL, '2020-09-17 12:57:34', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(339, 5, '3000', 'gpay', NULL, '2020-09-17 12:57:50', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(340, 1, '3600', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '3600', NULL, 'NO', 35),
(341, 2, '5000', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '5000', NULL, 'NO', 35),
(342, 3, '5500', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '5500', NULL, 'NO', 35),
(343, 4, '4500', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '4500', NULL, 'NO', 35),
(344, 5, '3000', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '3000', NULL, 'NO', 35),
(345, 6, '4100', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '4100', NULL, 'NO', 35),
(346, 7, '4100', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '4100', NULL, 'NO', 35),
(347, 8, '5100', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '5100', NULL, 'NO', 35),
(348, 9, '2000', NULL, NULL, '2020-09-17 12:58:29', 'Rent is Added for Aug-2020', 'Added', 'Rent', '2000', NULL, 'NO', 35),
(349, 7, '3100', 'gpay', NULL, '2020-09-17 12:59:21', NULL, 'Paid', 'Rent', '1000', NULL, 'Partial', 34),
(350, 9, '2000', 'gpay', NULL, '2020-09-17 12:59:43', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(351, 1, '2000', 'gpay', '2	14/09/2020	UPI/025613258586/Four thousand s/premapr	Cr.	4,600.00', '2020-09-17 13:14:38', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(352, 1, '2600', 'gpay', '2	14/09/2020	UPI/025613258586/Four thousand s/premapr	Cr.	4,600.00', '2020-09-17 13:14:54', NULL, 'Paid', 'Rent', '1000', NULL, 'Partial', 35),
(353, 2, '5000', 'gpay', 'MMT/IMPS/025820598114/cash/RAFICMOH\r\nAM/Karur Vysya Ban', '2020-09-17 13:25:12', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(354, 6, '4100', 'gpay', 'By Cash - By Sundari - CAM/61341SRY/CASH DEP/14-09-20 CAM/61341SRY/CASH DEP/14-09-20', '2020-09-17 13:27:48', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(355, 7, '1000', 'gpay', 'UPI/026019374736/Rubas Trichy ho/3a\r\nrun1995@okhdf/City Union Bank - 5100', '2020-09-17 13:29:32', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(356, 7, '4100', 'gpay', 'UPI/026019374736/Rubas Trichy ho/3a\r\nrun1995@okhdf/City Union Bank - 5100', '2020-09-17 13:30:03', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(357, 9, '2000', 'gpay', 'By cash - By sundari - CAM/61341SRY/CASH DEP/14-09-20 - CAM/61341SRY/CASH DEP/14-09-20', '2020-09-17 13:30:53', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(358, 8, '5100', 'gpay', 'UPI/026019374736/Rubas Trichy ho/3a\r\nrun1995@okhdf/City Union Bank - 5100', '2020-09-17 13:31:48', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 33),
(359, 5, '3000', 'gpay', 'Dear Customer, your Account XX883 has been credited with INR 3,000.00 on 21-Sep-20. Info: CAM*61341SRY*CA. Available Balance: INR 6,01,440.61', '2020-09-21 06:09:49', 'Pay via cash', 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(360, 4, '4500', 'gpay', 'Dear Customer, your Acct XX883 has been credited with INR 24,500.00 on 25-Sep-20. Info:BY CASH-TRICHY - PALAKKARAI. The Avbl Bal is INR 6,21,129.81\r\n\r\n4500 from jegathesan', '2020-09-25 08:51:14', '3600\r\n5000\r\n4100\r\n\r\n4500\r\n3100\r\n\r\n\r\n4100\r\n2000\r\n5100\r\n\r\n\r\nVia cash to aunt\r\nPaid on sep 25', 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(361, 1, '3600', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '3600', NULL, 'NO', 36),
(362, 2, '5000', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '5000', NULL, 'NO', 36),
(363, 3, '5500', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '5500', NULL, 'NO', 36),
(364, 4, '4500', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '4500', NULL, 'NO', 36),
(365, 5, '3000', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '3000', NULL, 'NO', 36),
(366, 6, '4100', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '4100', NULL, 'NO', 36),
(367, 7, '4100', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '4100', NULL, 'NO', 36),
(368, 8, '5100', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '5100', NULL, 'NO', 36),
(369, 9, '2000', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '2000', NULL, 'NO', 36),
(370, 11, '2000', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '2000', NULL, 'NO', 36),
(371, 12, '1000', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '1000', NULL, 'NO', 36),
(372, 1, '1000', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.3600.00 on 05-Oct-20 from premapriya7393-1@okaxis. UPI Ref no 027913242647', '2020-10-05 08:21:49', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(373, 1, '2600', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.3600.00 on 05-Oct-20 from premapriya7393-1@okaxis. UPI Ref no 027913242647', '2020-10-05 08:23:19', NULL, 'Paid', 'Rent', '1000', NULL, 'Partial', 36),
(374, 2, '5000', 'gpay', 'By cash ', '2020-10-13 11:37:57', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(375, 3, '5500', 'gpay', 'By bank', '2020-10-13 11:39:41', 'Dear Customer, your Acct XX883 has been credited with INR 5,500.00 on 09-Oct-20. Info:BY CASH  - TRICHY THILLAINAGAR 6134. The Avbl Bal is INR 3,03,739.43', 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(376, 4, '4500', 'gpay', 'By cash', '2020-10-13 11:40:03', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(377, 6, '4100', 'gpay', 'By cash', '2020-10-13 11:40:31', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(378, 8, '5100', 'gpay', 'By cash', '2020-10-13 11:40:53', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 34),
(379, 9, '2000', 'gpay', 'By bank', '2020-10-13 11:41:23', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(380, 5, '3000', 'gpay', 'By cash to palanisamy house ', '2020-10-15 06:20:00', 'Dear Customer, acct XXX883 is credited with Rs.3100.00 on 15-Oct-20 from premapriya7393-1@okaxis. UPI Ref no 028908081987', 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(381, 7, '4100', 'gpay', NULL, '2020-10-16 16:11:26', 'Dear Customer, acct XXX883 is credited with Rs.5000.00 on 16-Oct-20 from 65pandi@okaxis. UPI Ref no 029020980068', 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(382, 13, '2500', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '2500', NULL, 'NO', 36),
(383, 10, '2500', NULL, NULL, '2020-10-02 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '2500', NULL, 'NO', 36),
(384, 13, '2500', 'gpay', '[18:12, 10/16/2020] Saranya G: Dear Customer, acct XXX883 is credited with Rs.500.00 on 16-Oct-20 from veeramani3761@okaxis. UPI Ref no 029021018004\r\nDear Customer, acct XXX883 is credited with Rs.2000.00 on 16-Oct-20 from veeramani3761@okaxis. UPI Ref no 029011435748', '2020-10-17 20:51:42', 'cash to veeramani', 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(385, 12, '1000', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.6099.00 on 13-Oct-20 from saran.guru.123-1@okaxis. UPI Ref no 028720009952 ', '2020-10-17 20:53:21', 'perumal 1000+ rubesh money 5100', 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(386, 11, '2000', 'gpay', 'paid from advance', '2020-10-17 20:54:11', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(387, 15, '4500', NULL, NULL, '2020-10-01 11:46:17', 'Rent is Added for Sep-2020', 'Added', 'Rent', '4500', NULL, 'NO', 36),
(388, 15, '4500', 'gpay', NULL, '2020-10-18 07:28:50', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(389, 1, '1000', 'gpay', 'Online gpay', '2020-11-09 05:31:36', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(390, 1, '3500', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '3500', NULL, 'NO', 37),
(391, 2, '5000', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '5000', NULL, 'NO', 37),
(392, 3, '5500', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '5500', NULL, 'NO', 37),
(393, 4, '4500', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '4500', NULL, 'NO', 37),
(394, 5, '3100', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '3100', NULL, 'NO', 37),
(395, 6, '4100', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '4100', NULL, 'NO', 37),
(396, 7, '5100', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '5100', NULL, 'NO', 37),
(397, 8, '5100', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '5100', NULL, 'NO', 37),
(398, 9, '2000', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '2000', NULL, 'NO', 37),
(399, 10, '2500', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '2500', NULL, 'NO', 37),
(401, 12, '1000', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '1000', NULL, 'NO', 37),
(402, 13, '2500', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '2500', NULL, 'NO', 37),
(403, 15, '4500', NULL, NULL, '2020-11-09 05:31:51', 'Rent is Added for Oct-2020', 'Added', 'Rent', '4500', NULL, 'NO', 37),
(404, 1, '3500', 'gpay', NULL, '2020-11-09 05:32:20', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(405, 3, '5500', 'gpay', NULL, '2020-11-09 05:32:38', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(406, 3, '5500', 'gpay', NULL, '2020-11-09 05:32:50', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(407, 10, '2500', 'gpay', 'To raja mama', '2020-11-09 05:33:24', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(408, 12, '1000', 'gpay', 'Clean the grass and make it Tally', '2020-11-09 05:34:04', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(409, 15, '4500', 'gpay', 'Online transaction', '2020-11-09 05:34:29', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(410, 10, '2500', 'gpay', NULL, '2020-11-11 06:38:01', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(411, 2, '5000', 'gpay', NULL, '2020-11-13 12:11:38', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(412, 9, '2000', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.2000.00 on 16-Nov-20 from sarotrichy71@okaxis. UPI Ref no 032118832334', '2020-11-16 15:54:08', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(413, 5, '3000', 'gpay', 'Via Karthik house', '2020-11-18 07:13:28', NULL, 'Paid', 'Rent', '100', NULL, 'Partial', 37),
(414, 6, '4100', 'gpay', NULL, '2020-11-18 07:13:49', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(415, 7, '5100', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.5100.00 on 18-Nov-20 from 65pandi@okaxis. UPI Ref no 032307335197', '2020-11-18 07:14:22', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(416, 8, '5100', 'gpay', 'Via cash to veeramani', '2020-11-18 07:14:48', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 35),
(417, 1, '3500', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '3500', NULL, 'NO', 38),
(418, 2, '5000', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '5000', NULL, 'NO', 38),
(419, 3, '5500', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '5500', NULL, 'NO', 38),
(420, 4, '4500', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '4500', NULL, 'NO', 38),
(421, 5, '3100', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '3100', NULL, 'NO', 38),
(422, 6, '4100', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '4100', NULL, 'NO', 38),
(423, 7, '5100', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '5100', NULL, 'NO', 38),
(424, 8, '5100', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '5100', NULL, 'NO', 38),
(425, 9, '2000', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '2000', NULL, 'NO', 38),
(426, 10, '2500', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '2500', NULL, 'NO', 38),
(428, 12, '1000', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '1000', NULL, 'NO', 38),
(429, 13, '2500', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '2500', NULL, 'NO', 38),
(430, 15, '4500', NULL, NULL, '2020-12-04 22:05:03', 'Rent is Added for Nov-2020', 'Added', 'Rent', '4500', NULL, 'NO', 38),
(431, 4, '4500', 'gpay', NULL, '2020-12-04 22:08:56', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(432, 15, '4100', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.4100.00 on 04-Dec-20 from vinothmariyaraj1990@okicici. UPI Ref no 033911241206', '2020-12-04 22:10:15', NULL, 'Paid', 'Rent', '400', NULL, 'Partial', 38),
(433, 1, '3500', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.3600.00 on 05-Dec-20 from premapriya7393-1@okaxis. UPI Ref no 034011696991\r\n', '2020-12-05 14:32:16', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(434, 2, '5000', 'gpay', NULL, '2020-12-09 06:31:43', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(435, 7, '5100', 'gpay', NULL, '2020-12-10 17:27:06', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(436, 9, '2000', 'gpay', NULL, '2020-12-15 20:54:11', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(437, 10, '2500', 'gpay', NULL, '2020-12-15 20:54:30', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(438, 15, '300', 'gpay', NULL, '2020-12-15 20:55:09', NULL, 'Paid', 'Rent', '100', NULL, 'Partial', 38),
(439, 6, '4100', 'gpay', NULL, '2020-12-16 06:21:42', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(440, 5, '3100', 'gpay', NULL, '2020-12-16 06:22:03', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(441, 8, '5100', 'gpay', NULL, '2020-12-20 08:08:33', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 36),
(442, 4, '4500', 'gpay', NULL, '2020-12-21 15:04:19', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(443, 5, '100', 'gpay', NULL, '2020-12-31 15:17:53', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(444, 1, '3500', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '3500', NULL, 'NO', 39),
(445, 2, '5000', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '5000', NULL, 'NO', 39),
(446, 4, '4500', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '4500', NULL, 'NO', 39),
(447, 5, '3100', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '3100', NULL, 'NO', 39),
(448, 6, '4100', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '4100', NULL, 'NO', 39),
(449, 7, '5100', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '5100', NULL, 'NO', 39),
(450, 8, '5100', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '5100', NULL, 'NO', 39),
(451, 9, '2000', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '2000', NULL, 'NO', 39),
(452, 10, '2500', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '2500', NULL, 'NO', 39),
(453, 12, '1000', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '1000', NULL, 'NO', 39),
(454, 13, '2500', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '2500', NULL, 'NO', 39),
(455, 15, '4100', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '4100', NULL, 'NO', 39),
(456, 16, '4000', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '4000', NULL, 'NO', 39),
(457, 17, '5600', NULL, NULL, '2021-01-03 11:01:18', 'Rent is Added for Dec-2020', 'Added', 'Rent', '5600', NULL, 'NO', 39),
(458, 15, '100', 'gpay', NULL, '2021-01-05 07:18:50', NULL, 'Paid', 'Rent', '4000', NULL, 'Partial', 38),
(459, 15, '4100', 'gpay', NULL, '2021-01-05 07:19:17', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39),
(460, 17, '5600', 'gpay', 'Dear Customer, your Acct XX883 has been credited with INR 5,600.00 on 05-Jan-21. Info:SELF DEPOSIT. The Avbl Bal is INR 1,03,036.27', '2021-01-05 09:15:38', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39),
(461, 1, '3500', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.3600.00 on 06-Jan-21 from premapriya7393-1@okaxis. UPI Ref no 100612487150', '2021-01-06 08:25:49', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39),
(462, 2, '5000', 'gpay', 'Online transaction', '2021-01-11 06:32:35', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39),
(463, 12, '1000', 'gpay', NULL, '2021-01-12 17:00:42', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(464, 12, '1000', 'gpay', 'Gave to veera', '2021-01-12 17:01:35', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39),
(465, 10, '2500', 'gpay', NULL, '2021-01-12 17:18:55', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39),
(466, 6, '4100', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.4100.00 on 18-Jan-21 from premapriya7393-1@okaxis. UPI Ref no 101811020984', '2021-01-18 06:34:03', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 37),
(467, 7, '5100', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.5100.00 on 19-Jan-21 from 65pandi@okicici. UPI Ref no 101922417998', '2021-01-19 17:18:08', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39),
(468, 4, '4500', 'gpay', 'Dear Customer, acct XXX883 is credited with Rs.4500.00 on 28-Jan-21 from jmanoharjmanohar30@okicici. UPI Ref no 102821089218', '2021-01-28 16:04:00', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 38),
(469, 5, '3100', 'gpay', NULL, '2021-01-28 17:45:30', NULL, 'Paid', 'Rent', '0', NULL, 'Yes', 39);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_no_1` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_no_2` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pending_amount` decimal(10,0) DEFAULT NULL,
  `occupied_since` date DEFAULT NULL,
  `occupation` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8_unicode_ci,
  `aadhar_card_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apartment_id` int(11) DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  `img_path` varchar(500) COLLATE utf8_unicode_ci DEFAULT '/tenants/person.jpg',
  `lag_percent` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `tenants`
--

INSERT INTO `tenants` (`id`, `first_name`, `last_name`, `mobile_no_1`, `mobile_no_2`, `pending_amount`, `occupied_since`, `occupation`, `notes`, `aadhar_card_no`, `apartment_id`, `comments`, `img_path`, `lag_percent`) VALUES
(1, 'Palaniswamy', '', '82 70 41 30 36', NULL, '0', '2018-06-01', NULL, NULL, NULL, 1, NULL, 'images/tenants/person.jpg', '4'),
(2, 'Priya', 'vasanthi', '70 94 71 77 13', NULL, '5000', '2017-09-14', 'Teacher', NULL, NULL, 2, NULL, 'images/tenants/person.jpg', '4'),
(4, 'Jagadeesan ', NULL, '90 95 62 42 93', NULL, '4500', '2017-09-14', 'Carpenter', NULL, NULL, 6, NULL, 'images/tenants/person.jpg', '5'),
(5, 'Sumathi ', NULL, '98 43 00 85 41', NULL, '0', '2017-09-14', 'Chitthal', NULL, NULL, 5, NULL, 'images/tenants/person.jpg', '5'),
(6, 'Gopal  ', NULL, '93 61 05 67 30', NULL, '8200', '2017-09-14', 'Plumber & working with soda bottels', NULL, NULL, 4, 'Going to vacate in 2 months', 'images/tenants/person.jpg', '4'),
(7, 'Mohan', 'Appalam', '97 89 73 79 13', NULL, '0', '2017-09-14', 'Taxi driver', NULL, NULL, 8, NULL, 'images/tenants/person.jpg', '5'),
(8, 'Juli', 'Rubesh', NULL, NULL, '15300', '2017-09-14', 'Teacher', NULL, NULL, 7, NULL, 'images/tenants/person.jpg', '3'),
(9, 'Shanthi', NULL, '63 83 23 15 85\r\n', NULL, '2000', '2017-09-14', 'Teacher', NULL, NULL, 10, NULL, 'images/tenants/person.jpg', '8'),
(10, 'Lakshmi', NULL, '', NULL, '0', '2018-09-14', 'Teacher', NULL, NULL, 16, NULL, 'images/tenants/person.jpg', '2'),
(11, 'Bhagya Lakshmi', 'Paatti', '9943426898', '90251 04745', '4000', '2018-10-19', 'Pathirakadai veedu', 'Oottu veedu 1', NULL, 13, NULL, 'images/tenants/person.jpg', '1'),
(12, 'Perumal', NULL, '90950 94795', '90251 04745', '0', '2018-10-19', 'Pathirakadai veedu', 'Using as a Godown', NULL, 14, NULL, 'images/tenants/person.jpg', '2'),
(13, 'Murugan', NULL, '98944 46290', '', '7500', '2018-10-19', 'Cycle Repaire Shop', '', NULL, 17, NULL, 'images/tenants/person.jpg', '2'),
(15, 'Sagaya', 'Mery', '9789486724', NULL, '0', '2020-09-01', '', NULL, NULL, 9, NULL, 'images/tenants/person.jpg', '5'),
(16, 'Menaka', NULL, '+919688280527', '+919688280527', '4000', '2020-10-15', NULL, NULL, NULL, 11, NULL, 'images/tenants/person.jpg', '1'),
(17, 'Margaret', NULL, '00916374767585', '00919976615074', '0', '2020-11-03', 'Teacher', NULL, '123', 3, NULL, 'images/tenants/person.jpg', '4');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(4000) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$FwkOp/onEtFbnMCWv3by7uQurHtilXYZ./S.U53RXOTXHGzJ9BoMC', 'admin');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `apartments`
--
ALTER TABLE `apartments`
  ADD PRIMARY KEY (`id`,`apartment_no`);

--
-- Indizes für die Tabelle `bill_months`
--
ALTER TABLE `bill_months`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `apartments`
--
ALTER TABLE `apartments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `bill_months`
--
ALTER TABLE `bill_months`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT für Tabelle `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=470;

--
-- AUTO_INCREMENT für Tabelle `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
