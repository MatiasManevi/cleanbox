-- phpMyAdmin SQL Dump
-- version 4.3.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jan 24, 2018 at 04:10 PM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cleanbox`
--

-- --------------------------------------------------------

--
-- Table structure for table `reports_config`
--

CREATE TABLE IF NOT EXISTS `reports_config` (
  `id` int(11) NOT NULL,
  `report_name` varchar(255) NOT NULL,
  `frequency` varchar(255) NOT NULL,
  `data` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reports_config`
--

INSERT INTO `reports_config` (`id`, `report_name`, `frequency`, `data`) VALUES
(1, 'Reporte mensual de balances', 'monthly', ''),
(2, 'Reporte Pago de Honorarios', 'monthly', ''),
(3, 'Reporte de Cuentas Corrientes especificas', 'monthly', 'a:2:{i:0;s:1:"4";i:1;s:1:"1";}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reports_config`
--
ALTER TABLE `reports_config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reports_config`
--
ALTER TABLE `reports_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
