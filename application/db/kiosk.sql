-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 04, 2015 at 09:16 PM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.5.9-1ubuntu4.14

-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kiosk`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `del_inglink`(IN `id` INT, IN `staff` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
	INSERT INTO `log_menu_ingredients` (`log_staff_id`,`log_type`,`rel_id`,`menu_id`,`ing_id`) SELECT staff as s , 'delete' as t, id as ident, `i`.`menu_id`, `i`.`ing_id` FROM `menu_ingredients` AS `i` WHERE `i`.`rel_id` = id;

	DELETE FROM `menu_ingredients` WHERE `rel_id` = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_ingredients`(IN `id` INT, IN `staff` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
	INSERT INTO `log_ingredients` (`log_staff_id`,`log_type`,`ing_id`,`ing_name`,`ing_available`,`ing_stock`) SELECT staff as s , 'delete' as t, id as ident, `i`.`ing_name`, `i`.`ing_available`, `i`.`ing_stock` FROM `ingredients` AS `i` WHERE `i`.`ing_id` = id;

	DELETE FROM `ingredients` WHERE `ing_id` = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `mod_inglink`(IN `id` INT, IN `m_id` INT, IN `i_id` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
	UPDATE `menu_ingredients` SET `menu_id`=m_id, `ing_id`=i_id WHERE `rel_id` = id;

	INSERT INTO `log_menu_ingredients` (`log_staff_id`,`log_type`,`rel_id`,`menu_id`,`ing_id`) VALUES (staff, 'modify', id, m_id, i_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `mod_ingredients`(IN `id` int, IN `ig_name` VARCHAR(64), IN `available` BOOLEAN, IN `stock` INT, IN `staff` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN
	UPDATE `ingredients` SET `ing_name`=ig_name, `ing_available`=available, `ing_stock`=stock WHERE `ing_id` = id;

	INSERT INTO `log_ingredients` (`log_staff_id`,`log_type`,`ing_id`,`ing_name`,`ing_available`,`ing_stock`) VALUES (staff, 'modify', id, ig_name, available, stock);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_inglink`(IN `m_id` INT, IN `i_id` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN	 
	INSERT INTO `menu_ingredients` (`menu_id`, `ing_id`) VALUES (m_id, i_id);

	INSERT INTO `log_menu_ingredients` (`log_staff_id`,`log_type`,`rel_id`,`menu_id`,`ing_id`) VALUES (staff, 'insert', LAST_INSERT_ID(), m_id, i_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `new_ingredients`(IN `ig_name` VARCHAR(64), IN `available` BOOLEAN, IN `stock` INT, IN `staff` INT)
    MODIFIES SQL DATA
    SQL SECURITY INVOKER
BEGIN	 
	INSERT INTO `ingredients` (`ing_name`, `ing_available`, `ing_stock`) VALUES (ig_name, available, stock);

	INSERT INTO `log_ingredients` (`log_staff_id`,`log_type`,`ing_id`,`ing_name`,`ing_available`,`ing_stock`) VALUES (staff, 'insert', LAST_INSERT_ID(), ig_name, available, stock);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `ing_id` int(11) NOT NULL AUTO_INCREMENT,
  `ing_name` varchar(64) NOT NULL,
  `ing_available` tinyint(1) NOT NULL,
  `ing_stock` int(11) NOT NULL,
  PRIMARY KEY (`ing_id`),
  KEY `ing_available` (`ing_available`,`ing_stock`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_ingredients`
--

CREATE TABLE IF NOT EXISTS `log_ingredients` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_staff_id` int(11) NOT NULL,
  `log_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_type` enum('insert','modify','delete') NOT NULL,
  `ing_id` int(11) NOT NULL,
  `ing_name` varchar(64) NOT NULL,
  `ing_available` tinyint(1) NOT NULL,
  `ing_stock` int(11) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_staff_id` (`log_staff_id`,`log_datetime`,`log_type`,`ing_id`),
  KEY `ing_id` (`ing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_menu_ingredients`
--

CREATE TABLE IF NOT EXISTS `log_menu_ingredients` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_staff_id` int(11) NOT NULL,
  `log_datetime` datetime NOT NULL,
  `log_type` enum('insert','modify','delete') NOT NULL,
  `rel_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `ing_id` int(11) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_staff_id` (`log_staff_id`,`log_datetime`,`log_type`,`rel_id`,`menu_id`,`ing_id`),
  KEY `rel_id` (`rel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log_menu_items`
--

CREATE TABLE IF NOT EXISTS `log_menu_items` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_staff_id` int(11) NOT NULL,
  `log_datetime` datetime NOT NULL,
  `log_type` enum('insert','modify','delete') NOT NULL,
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(64) NOT NULL,
  `menu_price` float NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_staff_id` (`log_staff_id`,`log_datetime`,`log_type`,`menu_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_ingredients`
--

CREATE TABLE IF NOT EXISTS `menu_ingredients` (
  `rel_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `ing_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_id`),
  KEY `menu_id` (`menu_id`,`ing_id`),
  KEY `ing_id` (`ing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE IF NOT EXISTS `menu_items` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(64) NOT NULL,
  `menu_price` float NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE IF NOT EXISTS `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_username` varchar(64) NOT NULL,
  `staff_firstname` varchar(64) NOT NULL,
  `staff_lastname` varchar(64) NOT NULL,
  `staff_passwordhash` varchar(128) NOT NULL,
  `staff_salt` blob NOT NULL,
  `staff_admin` tinyint(1) NOT NULL,
  `staff_lastlogged` datetime DEFAULT NULL,
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `staff_username` (`staff_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `staff_username`, `staff_firstname`, `staff_lastname`, `staff_passwordhash`, `staff_salt`, `staff_admin`, `staff_lastlogged`) VALUES
(0, 'test', 'Rashawn', 'Clarke', '098f6bcd4621d373cade4e832627b4f6', '', 1, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_ingredients`
--
ALTER TABLE `log_ingredients`
  ADD CONSTRAINT `log_ingredients_ibfk_2` FOREIGN KEY (`log_staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `log_menu_ingredients`
--
ALTER TABLE `log_menu_ingredients`
  ADD CONSTRAINT `log_menu_ingredients_ibfk_2` FOREIGN KEY (`log_staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `log_menu_items`
--
ALTER TABLE `log_menu_items`
  ADD CONSTRAINT `log_menu_items_ibfk_2` FOREIGN KEY (`log_staff_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `menu_ingredients`
--
ALTER TABLE `menu_ingredients`
  ADD CONSTRAINT `menu_ingredients_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu_items` (`menu_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_ingredients_ibfk_2` FOREIGN KEY (`ing_id`) REFERENCES `ingredients` (`ing_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
