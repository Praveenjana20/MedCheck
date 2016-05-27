use test_jpr;


SELECT UsrDispNm,IFNULL(UsrMob,'') UsrMob,IFNULL(UsrLoc,'') UsrLoc,UsrPk FROM McUsrMas where UsrPk = 11;

CREATE TABLE `MCDocSerVDtls` (
  `DSUsrFk` bigint(20) NOT NULL,
  `DSName` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `DSDocNm` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `DSQuaf` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `DSCost` bigint(20) NOT NULL,
  `DSLocation` varchar(1000) COLLATE latin1_general_ci NOT NULL,
  `DSLocLng` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `DSLocLat` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `DSDesc` varchar(5000) COLLATE latin1_general_ci NOT NULL,
  `DSPk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`DSPk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `MCDocSerVDtls`
--


-- --------------------------------------------------------

--
-- Table structure for table `MCPatAppDtls`
--

CREATE TABLE `MCPatAppDtls` (
  `PAUsrFk` bigint(20) NOT NULL,
  `PAServFk` bigint(20) NOT NULL,
  `PADate` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `PAStartTm` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `PAEndTm` varchar(500) COLLATE latin1_general_ci NOT NULL,
  `PAPatRmks` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `PADOCRmks` varchar(1000) COLLATE latin1_general_ci DEFAULT NULL,
  `PADOCSts` bigint(20) DEFAULT NULL,
  `PAPk` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`PAPk`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;



	CREATE TABLE `McUsrMas` (
   `UsrTyp` tinyint(11) NOT NULL,
   `UsrDispNm` varchar(100) COLLATE latin1_general_ci NOT NULL,
   `UsrEmail` varchar(100) COLLATE latin1_general_ci NOT NULL,
   UsrMob bigint null,
   UsrLoc VARCHAR(500) null,
   `UsrPwd` varchar(16) COLLATE latin1_general_ci NOT NULL,
   `UsrIsCnf` int(11) NOT NULL,
   `UsrPicPath` varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
   `UsrPk` bigint(20) NOT NULL AUTO_INCREMENT,
   `UsrActDt` datetime NOT NULL,
   `UsrDelId` tinyint(4) NOT NULL,
   PRIMARY KEY (`UsrPk`)
 ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1	
 
 
 
 IF EXISTS(SELECT 1 FROM McUsrMas)
 BEGIN 
 SELECT * FROM McUsrMas
 END