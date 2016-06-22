<?php
if (!defined('WEB_PATH')) exit();

//全局配置信息
return  array(
	//运行相关
	"RUN_LEVEL"		=>	"DEV",		//	运行模式。 DEV(开发)，GAMMA(测试)，IDC(生产)

	//日志相关
	"LOG_RECORD"	=>	true,		//	开启日志记录
	"LOG_TYPE"		=>	3,			//	1.mail  2.file 3.api
	"LOG_PATH"		=>	WEB_PATH."log/",	//文件日志目录
	"LOG_FILE_SIZE"	=>	2097152,
	"LOG_DEST"		=>	"",			//	日志记录目标
	"LOG_EXTRA"		=>	"",			//	日志记录额外信息

	//数据接口相关
	"DATAGATE"		=>	"db",		//	数据接口层 cache, db, socket
	"DB_TYPE"		=>	"mysqli",	//	mysql	mssql	postsql	mongodb		
	

	//mysql db	配置
	"DB_CONFIG"		=>	array(
		"master1"	=>	array('HOST' => 'localhost', 'USER' => 'zxh', 'PASS' => 'pdcxaje127', 'PORT' => '3306', 'DBNAME' => 'wish_order'),	
		//"master1"	=>	array('HOST' => SAE_MYSQL_HOST_M, 'USER' => SAE_MYSQL_USER, 'PASS' => SAE_MYSQL_PASS, 'PORT' => SAE_MYSQL_PORT, 'DBNAME' => SAE_MYSQL_DB)			//主DB
		//"slave1"	=>	array("localhost","root","","3306")		//从DB
	),
	'OPENTOKEN'	=> '5f5c4f8c005f09c567769e918fa5d2e3',
	/**图片系统相关变量**/
	'ORDERSTAT'	=> array(
		'APPROVED'	=> '已付款未发货',
		'SHIPPED'	=> '已发货',
		'REFUNDED'	=> '已退款',
	),
	'shipProvider'	=> array('USPS','FedEx','DHL','UPS','OnTrac','FlytExpress','CanadaPost','HongKongPost','ChinaAirPost','SingaporePost','IsraelPost','TurkishPost','EMS (China)','SwissPost','IndiaPost','BPost','IndonesiaPost','ThailandThaiPost','UPSMailInnovations','TNT','DPD','Aramex','DHLGlobalMail','Purolator','DirectLink','YRC','UPSFreight','AsendiaUSA','Evergreen','LaserShip','Estes','ABF','RLCarriers','RoyalMail','ParcelForce','FedExUK','DPDUK','TNTUK','UKMail','InterlinkExpress','YODEL','Hermes','CityLink','FastwayIreland','DeutschePost','DHLGermany','HermesGermany','DPDGermany','AustrianPost','CorreosDeEspana','NACEXSpain','MRW','SpanishSeur','InternationalSeur','PortugalCTT','ChronopostPortugal','PortugalSeur','LaPosteColissimo','ChronopostFrance','PostNL','DHLNetherlands','Selektvracht','GLS','DeltecCourier','DHLBenelux','AnPost','ItellaPosti','PostenNorge','SwedenPosten','ItalySDA','PosteItalianePaccocelere','PosteItaliane','DanmarkPost','TNTItaly','RussianPost','NovaPoshta','DHLPoland','PocztaPolska','DPDPoland','Siodemka','OPEK','UkrPoshta','CeskaPosta','EltaCourier','PTTPosta','KuehneNagel','4PX','SFExpress','DHLGlobalMailAsia','TAQBINHongKong','TGX','SingaporeSpeedpost','TAQBINSingapore','Bluedart','Safexpress','RedExpress','FirstFlightCouriers','GatiKWE','JapanPost','Sagawa','TaiwanPost','MalaysiaPost','MalaysiaPostPosDaftar','SkynetMalaysia','CambodiaPost','KoreaPost','AustraliaPost','TollPriority','TollGlobalExpress','TNTAustralia','StarTrackAustralia','NewZealandPost','BrazilCorreios','FlashCourier','SouthAfricanPostOffice','FastwaySouthAfrica','CorreosChile','SaudiPost','NiPost','CorreoArgentino','OCAArgentina','CorreosDeMexico','Estafeta','MexicoSendaExpress','MexicoRedpack','MexicoMultipack','MexicoAeroFlash','CyprusPost','SkynetWorldwideExpress','APCLogistics','IParcel','TAQBINMalaysia','HrvatskaPosta','RomaniaPost','TAQBINJapan','RedExpressWaybill','CourierPost','ProfessionalCouriers','GoJaVAS','CJGLS','ECFirstClass','BulgarianPosts','PostNLInternational','FastwayAustralia','KerryLogistics','DTDCIndia','StarTrackExpress','GDEX','JNE','RPXIndonesia','GLSItaly','DPDIreland','FirstLogistics','BRTBartolini','Belpost','Xend','_2GO','PostNordLogistics','ACSCourier','AIR21','LBCExpress','ArrowXL','VietnamPostEMS','ViettelPost','VietnamPost','PayPalPackage','LithuaniaPost','CouriersPlease','PostNLInternational3S','ColisPrive','ASM','AustrianPostRegistered','AuPostChina','TNTPostItaly','Tiki','XDPExpress','GLSNetherlands','RedurSpain','TNTFrance','JamExpress','Wahana','CollectPlus','DynamicLogistics','IndiaPostInternational','IsraelPostDomestic','BPostInternational','DX','CityLinkExpress','WeDoLogisitics','GeodisCalbersonFrance','PosIndonesiaDomestic','STOExpress','TollIPEC','JCEX','Delhivery','DHLParcelNL','Greyhound','Colissimo','Yanwen','RPXOnline','PostService','GenikiTaxydromiki','SkynetWorldwideExpressUK','GHN','Envialia','Chukou1','EPacket','RAF','YundaExpress','SpecialisedFreight','TrakPak','EquickChina','DPESouthAfrica','EmiratesPost','DPEXChina','Qxpress','CourierPlus','CourierIT','BestExpress','CityLinkInternational','DHL2MannHandling','DBSchenkerSweden','_4SquareGroup','XpressBees','Dotzot','EcomExpress','Mypostonline','Panther','TNTClickItaly','YodelInternational','BOXC','EMPSExpress','Post56','SREKorea','ChinaPost-WishPost','DHLSpainDomestic','TransMission','MagyarPosta','Exapaq','PostaPlus','TeliwaySICExpress','Packlink','CNEExpress','MyHermesUK','CanparCourier','RAM','DHLExpress','PanduLogistics','SpeedexCourier','SpeedCourier','AsendiaGermany','DMMNetwork','SGTCorriereEspresso','NationwideExpress','GlobegisticsInc','DACHSER','Ecargo','DawnWing','CorreosExpress','FERCAM','JayonExpress','DSV','SFInternational','ABXExpress','KangarooWorldwideExpress','ZJSExpress','Spreadel','AsendiaUK','APCOvernight','BertTransport','DelivreeKing','CBLLogistics','Newgistics','EasyMail','LogisticWorldwideExpress','FastrakServices','Delcart','FastwayNewZealand','NanjingWoyuan','UBILogisticsAustralia','YunExpress','HomedirectLogistics','TuffnellsParcelsExpress','NinjaVan','AirpakExpress','BondsCouriers','Nightline','JetShipWorldwide','IMXMail','NorskGlobal','NhansSolutions','CuckooExpress','BosniaandHerzegovinaPost','ColombiaPostalService','Maxcellent','ParcelPost','GoFly','PostSerbia','RZYExpress','RRDonnelley','SingParcelService','CosmeticsNow','OneWorldExpress','Cainiao','SloveniaPost','EasternExpress','CPacket','SFCService'),
	'WISHPROFIT'	=> 15,	//这里表示百分之15
);

?>
