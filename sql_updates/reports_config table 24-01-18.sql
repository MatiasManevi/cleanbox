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

