
CREATE TABLE IF NOT EXISTS `reports_delivery` (
  `id` int(11) NOT NULL,
  `report_name` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



ALTER TABLE `reports_delivery`
  ADD PRIMARY KEY (`id`);



ALTER TABLE `reports_delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
