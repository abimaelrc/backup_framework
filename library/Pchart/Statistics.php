<?php
class Pchart_Statistics
{
	private $_db;




	public function __construct()
	{
		$this->_db = new PDO('mysql:host=localhost;dbname=ezticket', 'root', 'DrL9GeT1xg', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}




	/**
	 * Retrieve a member of the $_GET superglobal
	 *
	 * If no $key is passed, returns the entire $_GET array.
	 *
	 * @todo How to retrieve from nested arrays
	 * @param string $key
	 * @param mixed $default Default value to use if key not found
	 * @return mixed Returns null if key does not exist
	 */
	public function getQuery($key = null, $default = null)
	{
		if (null === $key) {
			return $_GET;
		}

		return (isset($_GET[$key])) ? $_GET[$key] : $default;
	}




	/**
	 * Retrieve a member of the $_POST superglobal
	 *
	 * If no $key is passed, returns the entire $_POST array.
	 *
	 * @todo How to retrieve from nested arrays
	 * @param string $key
	 * @param mixed $default Default value to use if key not found
	 * @return mixed Returns null if key does not exist
	 */
	public function getPost($key = null, $default = null)
	{
		if (null === $key) {
			return $_POST;
		}

		return (isset($_POST[$key])) ? $_POST[$key] : $default;
	}




	public function getImgCounterAllReportsQry($days = 30, $width = 597, $height = 200, $serieWeightRegular = 1.2)
	{
		//ini_set('memory_limit', '1024M');
		$days = (int)$days;
		$data = array();

		/**
		 * Internet
		 */
		$dbQuery = 'SELECT COUNT(*) as counter, DATE_FORMAT(created_datetime, "%a %d/%b") dt
			FROM calls_internet
			GROUP BY DATE_FORMAT(created_datetime, "%Y-%m-%d")
			ORDER BY calls_internet_id DESC
			LIMIT ' . $days;
		$p = $this->_db->prepare($dbQuery);
		$p->execute();
		$rows = array_reverse($p->fetchAll(PDO::FETCH_ASSOC));

		foreach($rows as $v){
			$data['internet'][$v['dt']] = $v['counter'];
			$data['internet/mobile/tv'][$v['dt']] = $v['counter'];
		}

		/**
		 * Mobile
		 */
		$dbQuery = 'SELECT COUNT(*) as counter, DATE_FORMAT(created_datetime, "%a %d/%b") dt
			FROM calls_mobile
			GROUP BY DATE_FORMAT(created_datetime, "%Y-%m-%d")
			ORDER BY calls_mobile_id DESC
			LIMIT ' . $days;
		$p = $this->_db->prepare($dbQuery);
		$p->execute();
		$rows = array_reverse($p->fetchAll(PDO::FETCH_ASSOC));

		foreach($rows as $v){
			$data['mobile'][$v['dt']] = $v['counter'];
			$data['internet/mobile/tv'][$v['dt']] = ( empty($data['internet/mobile/tv'][$v['dt']]) === false ) 
												  ? ( $data['internet/mobile/tv'][$v['dt']] + $v['counter'] )
												  : $v['counter'];
		}

		/**
		 * TV
		 */
		$dbQuery = 'SELECT COUNT(*) as counter, DATE_FORMAT(created_datetime, "%a %d/%b") dt
			FROM calls_tv
			GROUP BY DATE_FORMAT(created_datetime, "%Y-%m-%d")
			ORDER BY calls_tv_id DESC
			LIMIT ' . $days;
		$p = $this->_db->prepare($dbQuery);
		$p->execute();
		$rows = array_reverse($p->fetchAll(PDO::FETCH_ASSOC));

		foreach($rows as $v){
			$data['tv'][$v['dt']] = $v['counter'];
			$data['internet/mobile/tv'][$v['dt']] = ( empty($data['internet/mobile/tv'][$v['dt']]) === false ) 
												  ? ( $data['internet/mobile/tv'][$v['dt']] + $v['counter'] )
												  : $v['counter'];;
		}


		$myData = new Pchart_Pdata();

		$myData->addPoints($data['internet'], 'Internet');
		$myData->setSerieWeight('Internet', $serieWeightRegular);
		$myData->setPalette('Internet', array('R'=>255, 'G'=>0, 'B'=>0, 'Alpha'=>30));

		$myData->addPoints($data['mobile'], 'Móvil');
		$myData->setSerieWeight('Móvil', $serieWeightRegular);
		$myData->setPalette('Móvil', array('R'=>0, 'G'=>255, 'B'=>0, 'Alpha'=>30));

		$myData->addPoints($data['tv'], 'TV');
		$myData->setSerieWeight('TV', $serieWeightRegular);
		$myData->setPalette('TV', array('R'=>0, 'G'=>0, 'B'=>255, 'Alpha'=>30));

		$myData->addPoints($data['internet/mobile/tv'], 'Internet/Mobile/TV');
		$myData->setSerieWeight('Internet/Mobile/TV', $serieWeightRegular);
		$myData->setPalette('Internet/Mobile/TV', array('R'=>255, 'G'=>255, 'B'=>0, 'Alpha'=>30));

		// $myData->setAxisName(0,'Reportes');

		$myData->addPoints(array_keys($data['internet']), 'Labels');
		$myData->setSerieDescription('Labels', 'Days');
		$myData->setAbscissa('Labels');

		/* Create the pChart object */
		$myPicture = new Pchart_Pimage($width, $height, $myData);

		/* Turn of Antialiasing */
		$myPicture->Antialias = false;

		/* Draw the background */
		$Settings = array('R'=>255, 'G'=>255, 'B'=>255, 'Dash'=>1, 'DashR'=>255, 'DashG'=>255, 'DashB'=>255);
		$myPicture->drawFilledRectangle(0, 0, $width, $height, $Settings);

		/* Overlay with a gradient */
		// $Settings = array('StartR'=>255, 'StartG'=>255, 'StartB'=>255, 'EndR'=>1, 'EndG'=>138, 'EndB'=>68, 'Alpha'=>50);
		// $myPicture->drawGradientArea(0,0,$width,$height,DIRECTION_VERTICAL,$Settings);
		$myPicture->drawGradientArea(0, 0, $width, 26, DIRECTION_VERTICAL, array('StartR'=>0,'StartG'=>0,'StartB'=>0,'EndR'=>50,'EndG'=>50,'EndB'=>50,'Alpha'=>80));

		/* Add a border to the picture */
		$myPicture->drawRectangle(0, 0, ($width - 1), ($height - 1), array('R'=>0, 'G'=>0, 'B'=>0));

		/* Write the chart title */
		$myPicture->setFontProperties(array('FontName'=>'../library/Pchart/fonts/Forgotte.ttf', 'FontSize'=>8, 'R'=>255, 'G'=>255, 'B'=>255));
		$myPicture->drawText(10, 14, 'Reportes creados últimos ' . $days . ' días', array('FontSize'=>11, 'Align'=>TEXT_ALIGN_BOTTOMLEFT));

		/* Set the default font */
		$myPicture->setFontProperties(array('FontName'=>'../library/Pchart/fonts/pf_arma_five.ttf', 'FontSize'=>6, 'R'=>0, 'G'=>0, 'B'=>0));

		/* Define the chart area */
		$myPicture->setGraphArea(30, 20, ($width), ($height - 60));

		/* Draw the scale */
		$scaleSettings = array(
			'Mode' => SCALE_MODE_START0,
			'XMargin'=>10,
			'YMargin'=>10,
			'Floating'=>true,
			'GridR'=>150,
			'GridG'=>150,
			'GridB'=>150,
			'DrawSubTicks'=>true,
			'CycleBackground'=>true,
			'LabelRotation'=>90
		); 
		$myPicture->drawScale($scaleSettings);

		/* Turn on Antialiasing */
		$myPicture->Antialias = true;

		/* Enable shadow computing */
		$myPicture->setShadow(true, array('X'=>1, 'Y'=>1, 'R'=>0, 'G'=>0, 'B'=>0, 'Alpha'=>5));

		/* Draw the line chart */
		$lineSettings = array(
			'DisplayValues'=>true,
			'DisplayOffset'=>6,
			'ForceAlpha' => 10
		);
		$myPicture->drawLineChart($lineSettings);

		$myPicture->setFontProperties(array('FontName'=>'../library/Pchart/fonts/calibri.ttf','FontSize'=>9,'R'=>0,'G'=>0,'B'=>0));
		/* Write the chart legend */
		$myPicture->drawLegend(10, 18, array('Style'=>LEGEND_NOBORDER, 'Mode'=>LEGEND_HORIZONTAL, 'FontR'=>255, 'FontG'=>255, 'FontB'=>255));

		/* Render the picture (choose the best way) */
		$myPicture->autoOutput();
	}
}