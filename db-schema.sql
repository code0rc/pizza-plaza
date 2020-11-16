/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `Customer` (
    `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `lastname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `street` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `streetnumber` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `zip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Extras` (
    `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `price` double NOT NULL DEFAULT 0,
    `isChoosable` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Boolean',
    PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Order` (
    `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
    `customer_ID` int(10) unsigned NOT NULL,
    PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `OrderItems` (
    `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `quantity` int(10) unsigned NOT NULL DEFAULT 1,
    `Order_ID` int(10) unsigned NOT NULL,
    `Pizzas_ID` int(10) unsigned NOT NULL,
    PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `OrderItem_has_Extra` (
    `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `OrderItems_ID` int(10) unsigned NOT NULL,
    `Extras_ID` int(10) unsigned NOT NULL,
    PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Pizzas` (
    `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `price` double NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `Pizza_has_Extra` (
    `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `Pizzas_ID` int(10) unsigned NOT NULL,
    `Extras_ID` int(10) unsigned NOT NULL,
    PRIMARY KEY (`ID`)
    ) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
