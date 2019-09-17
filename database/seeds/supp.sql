CREATE TABLE `vl_site_suppression_datim` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dateupdated` date DEFAULT NULL,
  `facility` int(10) unsigned NOT NULL,
  
  `below1_m_sup` int(10) unsigned DEFAULT NULL,
  `below1_f_sup` int(10) unsigned DEFAULT NULL,
  `below1_u_sup` int(10) unsigned DEFAULT NULL,
  `below1_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below1_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below1_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below5_m_sup` int(10) unsigned DEFAULT NULL,
  `below5_f_sup` int(10) unsigned DEFAULT NULL,
  `below5_u_sup` int(10) unsigned DEFAULT NULL,
  `below5_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below5_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below5_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below10_m_sup` int(10) unsigned DEFAULT NULL,
  `below10_f_sup` int(10) unsigned DEFAULT NULL,
  `below10_u_sup` int(10) unsigned DEFAULT NULL,
  `below10_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below10_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below10_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below15_m_sup` int(10) unsigned DEFAULT NULL,
  `below15_f_sup` int(10) unsigned DEFAULT NULL,
  `below15_u_sup` int(10) unsigned DEFAULT NULL,
  `below15_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below15_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below15_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below20_m_sup` int(10) unsigned DEFAULT NULL,
  `below20_f_sup` int(10) unsigned DEFAULT NULL,
  `below20_u_sup` int(10) unsigned DEFAULT NULL,
  `below20_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below20_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below20_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below25_m_sup` int(10) unsigned DEFAULT NULL,
  `below25_f_sup` int(10) unsigned DEFAULT NULL,
  `below25_u_sup` int(10) unsigned DEFAULT NULL,
  `below25_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below25_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below25_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below30_m_sup` int(10) unsigned DEFAULT NULL,
  `below30_f_sup` int(10) unsigned DEFAULT NULL,
  `below30_u_sup` int(10) unsigned DEFAULT NULL,
  `below30_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below30_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below30_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below35_m_sup` int(10) unsigned DEFAULT NULL,
  `below35_f_sup` int(10) unsigned DEFAULT NULL,
  `below35_u_sup` int(10) unsigned DEFAULT NULL,
  `below35_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below35_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below35_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below40_m_sup` int(10) unsigned DEFAULT NULL,
  `below40_f_sup` int(10) unsigned DEFAULT NULL,
  `below40_u_sup` int(10) unsigned DEFAULT NULL,
  `below40_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below40_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below40_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below45_m_sup` int(10) unsigned DEFAULT NULL,
  `below45_f_sup` int(10) unsigned DEFAULT NULL,
  `below45_u_sup` int(10) unsigned DEFAULT NULL,
  `below45_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below45_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below45_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `below50_m_sup` int(10) unsigned DEFAULT NULL,
  `below50_f_sup` int(10) unsigned DEFAULT NULL,
  `below50_u_sup` int(10) unsigned DEFAULT NULL,
  `below50_m_nonsup` int(10) unsigned DEFAULT NULL,
  `below50_f_nonsup` int(10) unsigned DEFAULT NULL,
  `below50_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `above50_m_sup` int(10) unsigned DEFAULT NULL,
  `above50_f_sup` int(10) unsigned DEFAULT NULL,
  `above50_u_sup` int(10) unsigned DEFAULT NULL,
  `above50_m_nonsup` int(10) unsigned DEFAULT NULL,
  `above50_f_nonsup` int(10) unsigned DEFAULT NULL,
  `above50_u_nonsup` int(10) unsigned DEFAULT NULL,
  
  `total50_m_sup` int(10) unsigned DEFAULT NULL,
  `total50_f_sup` int(10) unsigned DEFAULT NULL,
  `total50_u_sup` int(10) unsigned DEFAULT NULL,
  `total50_m_nonsup` int(10) unsigned DEFAULT NULL,
  `total50_f_nonsup` int(10) unsigned DEFAULT NULL,
  `total50_u_nonsup` int(10) unsigned DEFAULT NULL,


  PRIMARY KEY (`id`),
  KEY(`facility`)
) ENGINE=InnoDB;


