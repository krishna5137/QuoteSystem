-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2016 at 01:09 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quote_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `ID` int(11) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Is_Admin` tinyint(1) NOT NULL DEFAULT '0',
  `Commission` double DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`ID`, `First_Name`, `Last_Name`, `Username`, `Password`, `Is_Admin`, `Commission`, `Address`) VALUES
(2, 'Admin', 'Person', 'admin', 'admin', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `line_item`
--

CREATE TABLE `line_item` (
  `ID` int(11) NOT NULL,
  `Description` varchar(400) NOT NULL,
  `Price` double NOT NULL,
  `Quote_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `ID` int(11) NOT NULL,
  `Discount` double DEFAULT NULL,
  `Discount_Type` int(11) DEFAULT NULL,
  `Processing_Date` date DEFAULT NULL,
  `Total` double NOT NULL,
  `Quote_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quote`
--

CREATE TABLE `quote` (
  `ID` int(11) NOT NULL,
  `Customer_Email` varchar(255) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `IsFinal` tinyint(1) NOT NULL DEFAULT '0',
  `Sanctioned` tinyint(1) NOT NULL DEFAULT '0',
  `Ordered` tinyint(1) NOT NULL DEFAULT '0',
  `Notes` varchar(400) DEFAULT NULL,
  `Customer_Notes` varchar(400) DEFAULT NULL,
  `Discount` double DEFAULT NULL,
  `Discount_Type` int(11) NOT NULL,
  `Final_Discount` double NOT NULL DEFAULT '0',
  `Sales_Commission` double NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `Total` double DEFAULT NULL,
  `Last_Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `line_item`
--
ALTER TABLE `line_item`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `line_item`
--
ALTER TABLE `line_item`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=896262;
--
-- AUTO_INCREMENT for table `quote`
--
ALTER TABLE `quote`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
