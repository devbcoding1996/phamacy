-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2023 at 07:07 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(2, 'เม็ด'),
(3, 'แคปซูล'),
(4, 'หลอด'),
(5, 'ชิ้น'),
(6, 'ขวด'),
(7, 'ซอง'),
(8, 'ก้อน'),
(9, 'ชุด'),
(10, 'แผง'),
(11, '10 เม็ด/แผง'),
(12, '4 เม็ด/แผง'),
(13, 'มล.'),
(14, 'กรัม');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `discount` decimal(2,2) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `f_name`, `l_name`, `address`, `phone_number`, `email`, `discount`, `status`) VALUES
(1, 'ทดสอบ', 'ลูกค้า', '17/4 หมู่ 5 ถนนบำรุงราษฎร์ ตำบลพิบูลสงคราม อำเภอเมือง กรุงเทพมหานคร 10400', '0989997788', 'test@gmail.com', 0.00, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `drug_information`
--

CREATE TABLE `drug_information` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(20) NOT NULL,
  `use_medicine` varchar(255) NOT NULL,
  `contraindications` text NOT NULL,
  `properties` text NOT NULL,
  `drug_type_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `quantity` int(5) NOT NULL,
  `production_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  `price` decimal(4,2) NOT NULL,
  `keyword` varchar(120) NOT NULL,
  `link_images` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_information`
--

INSERT INTO `drug_information` (`id`, `name`, `size`, `use_medicine`, `contraindications`, `properties`, `drug_type_id`, `category_id`, `package_id`, `quantity`, `production_date`, `expiration_date`, `price`, `keyword`, `link_images`) VALUES
(1, 'ยาแก้ไข้ซาร่า', '500 มก.', 'รับประทานทุก 4-6 ชั่วโมง เมื่อมีอาการ ไม่ควรรับประทานเกินวันละ 4 ครั้ง ผู้ใหญ่ รับประทานครั้งละ 1-2 เม็ด', '-', 'ซาร่าคือยาบรรเทาอาการปวดลดไข้ มีตัวยาสำคัญคือ พาราเซตามอล (Paracetamol)', 7, 2, 1, 100, '2019-01-20', '2022-01-21', 99.99, 'แก้ปวด, ปวดหัว, เป็นไข้', 'https://punsuk.com/3063-6765-thickbox_default/sara-paracetamol-500-100-.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `drug_type`
--

CREATE TABLE `drug_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drug_type`
--

INSERT INTO `drug_type` (`id`, `name`) VALUES
(7, 'ยารับประทาน'),
(8, 'ครีมทาผิวหนัง'),
(9, 'วัสดุอุปกรณ์ทำแผล'),
(10, 'ยาทาผิวหนัง'),
(11, 'ยาสูดพ่นเข้าทางปาก'),
(12, 'ใช้ทำความสะอาดเชื้อโรคภายนอก'),
(13, 'ยาหยอดตา'),
(14, 'ยาสำหรับสูดดม'),
(15, 'ยารับประทานชงผสมกับน้ำ'),
(16, 'สบู่'),
(17, 'ATK'),
(18, 'ยาพ่นจมูก'),
(19, 'แผ่นแปะผิวหนัง'),
(20, 'เครื่องปั๊มนม'),
(21, 'ยาสำหรับฉีดพ่นช่องปากและลำคอ'),
(22, 'ยาสำหรับฉีดพ่นช่องปาก'),
(23, 'THC');

-- --------------------------------------------------------

--
-- Table structure for table `order_drug`
--

CREATE TABLE `order_drug` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`id`, `name`) VALUES
(1, 'กระปุก'),
(2, 'แผง'),
(3, 'กล่อง'),
(4, 'ขวด'),
(5, 'หลอด'),
(6, 'กระป๋อง');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `name` varchar(255) NOT NULL,
  `mobileNumber` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `isAdmin` int(1) NOT NULL,
  `customer_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobileNumber`, `email`, `passwd`, `isAdmin`, `customer_id`) VALUES
('bba2121d-83de-11ee-823b-38f3ab9049a5', 'John', '0978885566', 'Pokz@gamil.com', '$2y$10$5ES.3KNSInIBex7Sdv4pRu1wHCRbhmfukl8lCmz/6fX9pcr5bTBNW', 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_customer`
--

CREATE TABLE `user_customer` (
  `uc_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `customer_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_customer`
--

INSERT INTO `user_customer` (`uc_id`, `user_id`, `customer_id`) VALUES
('9b02cc43-8ae7-11ee-bc01-38f3ab9049a5', '1', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `drug_information`
--
ALTER TABLE `drug_information`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drug_type`
--
ALTER TABLE `drug_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_drug`
--
ALTER TABLE `order_drug`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `drug_id` (`drug_id`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `user_customer`
--
ALTER TABLE `user_customer`
  ADD PRIMARY KEY (`uc_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drug_information`
--
ALTER TABLE `drug_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `drug_type`
--
ALTER TABLE `drug_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `order_drug`
--
ALTER TABLE `order_drug`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drug_information`
--
ALTER TABLE `drug_information`
  ADD CONSTRAINT `drug_information_ibfk_1` FOREIGN KEY (`drug_type_id`) REFERENCES `drug_type` (`id`),
  ADD CONSTRAINT `drug_information_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `order_drug`
--
ALTER TABLE `order_drug`
  ADD CONSTRAINT `order_drug_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `order_drug_ibfk_2` FOREIGN KEY (`drug_id`) REFERENCES `drug_information` (`id`);

--
-- Constraints for table `user_customer`
--
ALTER TABLE `user_customer`
  ADD CONSTRAINT `user_customer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`customer_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
