<?php
error_reporting(0); //抑制所有错误信息
@header("content-Type: text/html; charset=utf-8"); //语言强制
ob_start();
date_default_timezone_set('Asia/Shanghai');//此句用于消除时间差

$title = 'Server Overwrite';
$version = ""; //版本号

define('HTTP_HOST', preg_replace('~^www\.~i', '', $_SERVER['HTTP_HOST']));

$time_start = microtime_float();

function memory_usage()
{
	$memory	 = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
	return $memory;
}

// 计时
function microtime_float()
{
	$mtime = microtime();
	$mtime = explode(' ', $mtime);
	return $mtime[1] + $mtime[0];
}

//单位转换
function formatsize($size)
{
	$danwei=array(' B ',' K ',' M ',' G ',' T ');
	$allsize=array();
	$i=0;

	for($i = 0; $i <5; $i++)
	{
		if(floor($size/pow(1024,$i))==0){break;}
	}

	for($l = $i-1; $l >=0; $l--)
	{
		$allsize1[$l]=floor($size/pow(1024,$l));
		$allsize[$l]=$allsize1[$l]-$allsize1[$l+1]*1024;
	}

	$len=count($allsize);

	for($j = $len-1; $j >=0; $j--)
	{
		$fsize=$fsize.$allsize[$j].$danwei[$j];
	}
	return $fsize;
}

function valid_email($str)
{
	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}

//检测PHP设置参数
function show($varName)
{
	switch($result = get_cfg_var($varName))
	{
		case 0:
			return '<font color="red">×</font>';
		break;

		case 1:
			return '<font color="green">√</font>';
		break;

		default:
			return $result;
		break;
	}
}

//保留服务器性能测试结果
$valInt = isset($_POST['pInt']) ? $_POST['pInt'] : "未测试";

if ($_GET['act'] == "phpinfo")
{
	phpinfo();
	exit();
}
//网速测试-开始
elseif($_POST['act']=="开始测试")
{
?>
	<script language="javascript" type="text/javascript">
		var acd1;
		acd1 = new Date();
		acd1ok=acd1.getTime();
	</script>
	<?php
	for($i=1;$i<=100000;$i++)
	{
		echo "<!--567890#########0#########0#########0#########0#########0#########0#########0#########012345-->";
	}
	?>
	<script language="javascript" type="text/javascript">
		var acd2;
		acd2 = new Date();
		acd2ok=acd2.getTime();
		window.location = '?speed=' +(acd2ok-acd1ok)+'#w_networkspeed';
	</script>
<?php
}
//网速测试-结束
elseif($_GET['act'] == "Function")
{
	$arr = get_defined_functions();
	Function php()
	{
	}
	echo "<pre>";
	Echo "这里显示系统所支持的所有函数,和自定义函数\n";
	print_r($arr);
	echo "</pre>";
	exit();
}elseif($_GET['act'] == "disable_functions")
{
	$disFuns=get_cfg_var("disable_functions");
	if(empty($disFuns))
	{
		$arr = '<font color=red>×</font>';
	}
	else
	{
		$arr = $disFuns;
	}
	Function php()
	{
	}
	echo "<pre>";
	Echo "这里显示系统被禁用的函数\n";
	print_r($arr);
	echo "</pre>";
	exit();
}


//网络速度测试
if(isset($_POST['speed']))
{
	$speed=round(100/($_POST['speed']/1000),2);
}
elseif($_GET['speed']=="0")
{
	$speed=6666.67;
}
elseif(isset($_GET['speed']) and $_GET['speed']>0)
{
	$speed=round(100/($_GET['speed']/1000),2); //下载速度：$speed kb/s
}
else
{
	$speed="<font color=\"red\">&nbsp;未探测&nbsp;</font>";
}


function GetCoreInformation() {$data = file('/proc/stat');$cores = array();foreach( $data as $line ) {if( preg_match('/^cpu[0-9]/', $line) ){$info = explode(' ', $line);$cores[]=array('user'=>$info[1],'nice'=>$info[2],'sys' => $info[3],'idle'=>$info[4],'iowait'=>$info[5],'irq' => $info[6],'softirq' => $info[7]);}}return $cores;}
function GetCpuPercentages($stat1, $stat2) {if(count($stat1)!==count($stat2)){return;}$cpus=array();for( $i = 0, $l = count($stat1); $i < $l; $i++) {	$dif = array();	$dif['user'] = $stat2[$i]['user'] - $stat1[$i]['user'];$dif['nice'] = $stat2[$i]['nice'] - $stat1[$i]['nice'];	$dif['sys'] = $stat2[$i]['sys'] - $stat1[$i]['sys'];$dif['idle'] = $stat2[$i]['idle'] - $stat1[$i]['idle'];$dif['iowait'] = $stat2[$i]['iowait'] - $stat1[$i]['iowait'];$dif['irq'] = $stat2[$i]['irq'] - $stat1[$i]['irq'];$dif['softirq'] = $stat2[$i]['softirq'] - $stat1[$i]['softirq'];$total = array_sum($dif);$cpu = array();foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 2);$cpus['cpu' . $i] = $cpu;}return $cpus;}
$stat1 = GetCoreInformation();sleep(1);$stat2 = GetCoreInformation();$data = GetCpuPercentages($stat1, $stat2);
$cpu_show = $data['cpu0']['user']."%us,  ".$data['cpu0']['sys']."%sy,  ".$data['cpu0']['nice']."%ni, ".$data['cpu0']['idle']."%id,  ".$data['cpu0']['iowait']."%wa,  ".$data['cpu0']['irq']."%irq,  ".$data['cpu0']['softirq']."%softirq";
function makeImageUrl($title, $data) {$api='http://api.yahei.net/tz/cpu_show.php?id=';$url.=$data['user'].',';$url.=$data['nice'].',';$url.=$data['sys'].',';$url.=$data['idle'].',';$url.=$data['iowait'];$url.='&chdl=User|Nice|Sys|Idle|Iowait&chdlp=b&chl=';$url.=$data['user'].'%25|';$url.=$data['nice'].'%25|';$url.=$data['sys'].'%25|';$url.=$data['idle'].'%25|';$url.=$data['iowait'].'%25';$url.='&chtt=Core+'.$title;return $api.base64_encode($url);}
if($_GET['act'] == "cpu_percentage"){echo "<center><b><font face='Microsoft YaHei' color='#666666' size='3'>图片加载慢，请耐心等待！</font></b><br /><br />";foreach( $data as $k => $v ) {echo '<img src="' . makeImageUrl( $k, $v ) . '" style="width:360px;height:240px;border: #CCCCCC 1px solid;background: #FFFFFF;margin:5px;padding:5px;" />';}echo "</center>";exit();}

// 根据不同系统取得CPU相关信息
switch(PHP_OS)
{
	case "Linux":
		$sysReShow = (false !== ($sysInfo = sys_linux()))?"show":"none";
	break;

	case "FreeBSD":
		$sysReShow = (false !== ($sysInfo = sys_freebsd()))?"show":"none";
	break;

	default:
	break;
}

//linux系统探测
function sys_linux()
{
    // CPU
    if (false === ($str = @file("/proc/cpuinfo"))) return false;
    $str = implode("", $str);
    @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s", $str, $model);
    @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
    @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
    @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $bogomips);
    if (false !== is_array($model[1]))
	{
        $res['cpu']['num'] = sizeof($model[1]);
		/*
        for($i = 0; $i < $res['cpu']['num']; $i++)
        {
            $res['cpu']['model'][] = $model[1][$i].'&nbsp;('.$mhz[1][$i].')';
            $res['cpu']['mhz'][] = $mhz[1][$i];
            $res['cpu']['cache'][] = $cache[1][$i];
            $res['cpu']['bogomips'][] = $bogomips[1][$i];
        }*/
		if($res['cpu']['num']==1)
			$x1 = '';
		else
			$x1 = ' ×'.$res['cpu']['num'];
		$mhz[1][0] = ' | 频率:'.$mhz[1][0];
		$cache[1][0] = ' | 二级缓存:'.$cache[1][0];
		$bogomips[1][0] = ' | Bogomips:'.$bogomips[1][0];
		$res['cpu']['model'][] = $model[1][0].$mhz[1][0].$cache[1][0].$bogomips[1][0].$x1;
        if (false !== is_array($res['cpu']['model'])) $res['cpu']['model'] = implode("<br />", $res['cpu']['model']);
        if (false !== is_array($res['cpu']['mhz'])) $res['cpu']['mhz'] = implode("<br />", $res['cpu']['mhz']);
        if (false !== is_array($res['cpu']['cache'])) $res['cpu']['cache'] = implode("<br />", $res['cpu']['cache']);
        if (false !== is_array($res['cpu']['bogomips'])) $res['cpu']['bogomips'] = implode("<br />", $res['cpu']['bogomips']);
	}

    // NETWORK

    // UPTIME
    if (false === ($str = @file("/proc/uptime"))) return false;
    $str = explode(" ", implode("", $str));
    $str = trim($str[0]);
    $min = $str / 60;
    $hours = $min / 60;
    $days = floor($hours / 24);
    $hours = floor($hours - ($days * 24));
    $min = floor($min - ($days * 60 * 24) - ($hours * 60));
    if ($days !== 0) $res['uptime'] = $days."天";
    if ($hours !== 0) $res['uptime'] .= $hours."小时";
    $res['uptime'] .= $min."分钟";

    // MEMORY
    if (false === ($str = @file("/proc/meminfo"))) return false;
    $str = implode("", $str);
    preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
	preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buffers);

    $res['memTotal'] = round($buf[1][0]/1024, 2);
    $res['memFree'] = round($buf[2][0]/1024, 2);
    $res['memBuffers'] = round($buffers[1][0]/1024, 2);
	$res['memCached'] = round($buf[3][0]/1024, 2);
    $res['memUsed'] = $res['memTotal']-$res['memFree'];
    $res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

    $res['memRealUsed'] = $res['memTotal'] - $res['memFree'] - $res['memCached'] - $res['memBuffers']; //真实内存使用
	$res['memRealFree'] = $res['memTotal'] - $res['memRealUsed']; //真实空闲
    $res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0; //真实内存使用率

	$res['memCachedPercent'] = (floatval($res['memCached'])!=0)?round($res['memCached']/$res['memTotal']*100,2):0; //Cached内存使用率

    $res['swapTotal'] = round($buf[4][0]/1024, 2);
    $res['swapFree'] = round($buf[5][0]/1024, 2);
    $res['swapUsed'] = round($res['swapTotal']-$res['swapFree'], 2);
    $res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;

    // LOAD AVG
    if (false === ($str = @file("/proc/loadavg"))) return false;
    $str = explode(" ", implode("", $str));
    $str = array_chunk($str, 4);
    $res['loadAvg'] = implode(" ", $str[0]);

    return $res;
}

//FreeBSD系统探测
function sys_freebsd()
{
	//CPU
	if (false === ($res['cpu']['num'] = get_key("hw.ncpu"))) return false;
	$res['cpu']['model'] = get_key("hw.model");
	//LOAD AVG
	if (false === ($res['loadAvg'] = get_key("vm.loadavg"))) return false;
	//UPTIME
	if (false === ($buf = get_key("kern.boottime"))) return false;
	$buf = explode(' ', $buf);
	$sys_ticks = time() - intval($buf[3]);
	$min = $sys_ticks / 60;
	$hours = $min / 60;
	$days = floor($hours / 24);
	$hours = floor($hours - ($days * 24));
	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
	if ($days !== 0) $res['uptime'] = $days."天";
	if ($hours !== 0) $res['uptime'] .= $hours."小时";
	$res['uptime'] .= $min."分钟";
	//MEMORY
	if (false === ($buf = get_key("hw.physmem"))) return false;
	$res['memTotal'] = round($buf/1024/1024, 2);

	$str = get_key("vm.vmtotal");
	preg_match_all("/\nVirtual Memory[\:\s]*\(Total[\:\s]*([\d]+)K[\,\s]*Active[\:\s]*([\d]+)K\)\n/i", $str, $buff, PREG_SET_ORDER);
	preg_match_all("/\nReal Memory[\:\s]*\(Total[\:\s]*([\d]+)K[\,\s]*Active[\:\s]*([\d]+)K\)\n/i", $str, $buf, PREG_SET_ORDER);

	$res['memRealUsed'] = round($buf[0][2]/1024, 2);
	$res['memCached'] = round($buff[0][2]/1024, 2);
	$res['memUsed'] = round($buf[0][1]/1024, 2) + $res['memCached'];
	$res['memFree'] = $res['memTotal'] - $res['memUsed'];
	$res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;

	$res['memRealPercent'] = (floatval($res['memTotal'])!=0)?round($res['memRealUsed']/$res['memTotal']*100,2):0;

	return $res;
}

//取得参数值 FreeBSD
function get_key($keyName)
{
	return do_command('sysctl', "-n $keyName");
}

//确定执行文件位置 FreeBSD
function find_command($commandName)
{
	$path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
	foreach($path as $p)
	{
		if (@is_executable("$p/$commandName")) return "$p/$commandName";
	}
	return false;
}

//执行系统命令 FreeBSD
function do_command($commandName, $args)
{
	$buffer = "";
	if (false === ($command = find_command($commandName))) return false;
	if ($fp = @popen("$command $args", 'r'))
	{
		while (!@feof($fp))
		{
			$buffer .= @fgets($fp, 4096);
		}
		return trim($buffer);
	}
	return false;
}

//windows系统探测
function sys_windows()
{
	if (PHP_VERSION >= 5)
	{
		$objLocator = new COM("WbemScripting.SWbemLocator");
		$wmi = $objLocator->ConnectServer();
		$prop = $wmi->get("Win32_PnPEntity");
	}
	else
	{
		return false;
	}

	//CPU
	$cpuinfo = GetWMI($wmi,"Win32_Processor", array("Name","L2CacheSize","NumberOfCores"));
	$res['cpu']['num'] = $cpuinfo[0]['NumberOfCores'];
	if (null == $res['cpu']['num'])
	{
		$res['cpu']['num'] = 1;
	}/*
	for ($i=0;$i<$res['cpu']['num'];$i++)
	{
		$res['cpu']['model'] .= $cpuinfo[0]['Name']."<br />";
		$res['cpu']['cache'] .= $cpuinfo[0]['L2CacheSize']."<br />";
	}*/
	$cpuinfo[0]['L2CacheSize'] = ' ('.$cpuinfo[0]['L2CacheSize'].')';
	if($res['cpu']['num']==1)
		$x1 = '';
	else
		$x1 = ' ×'.$res['cpu']['num'];
	$res['cpu']['model'] = $cpuinfo[0]['Name'].$cpuinfo[0]['L2CacheSize'].$x1;
	// SYSINFO
	$sysinfo = GetWMI($wmi,"Win32_OperatingSystem", array('LastBootUpTime','TotalVisibleMemorySize','FreePhysicalMemory','Caption','CSDVersion','SerialNumber','InstallDate'));
	$sysinfo[0]['Caption']=iconv('GBK', 'UTF-8',$sysinfo[0]['Caption']);
	$sysinfo[0]['CSDVersion']=iconv('GBK', 'UTF-8',$sysinfo[0]['CSDVersion']);
	$res['win_n'] = $sysinfo[0]['Caption']." ".$sysinfo[0]['CSDVersion']." 序列号:{$sysinfo[0]['SerialNumber']} 于".date('Y年m月d日H:i:s',strtotime(substr($sysinfo[0]['InstallDate'],0,14)))."安装";
	//UPTIME
	$res['uptime'] = $sysinfo[0]['LastBootUpTime'];

	$sys_ticks = 3600*8 + time() - strtotime(substr($res['uptime'],0,14));
	$min = $sys_ticks / 60;
	$hours = $min / 60;
	$days = floor($hours / 24);
	$hours = floor($hours - ($days * 24));
	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
	if ($days !== 0) $res['uptime'] = $days."天";
	if ($hours !== 0) $res['uptime'] .= $hours."小时";
	$res['uptime'] .= $min."分钟";

	//MEMORY
	$res['memTotal'] = round($sysinfo[0]['TotalVisibleMemorySize']/1024,2);
	$res['memFree'] = round($sysinfo[0]['FreePhysicalMemory']/1024,2);
	$res['memUsed'] = $res['memTotal']-$res['memFree'];	//上面两行已经除以1024,这行不用再除了
	$res['memPercent'] = round($res['memUsed'] / $res['memTotal']*100,2);

	$swapinfo = GetWMI($wmi,"Win32_PageFileUsage", array('AllocatedBaseSize','CurrentUsage'));

	// LoadPercentage
	$loadinfo = GetWMI($wmi,"Win32_Processor", array("LoadPercentage"));
	$res['loadAvg'] = $loadinfo[0]['LoadPercentage'];

	return $res;
}

function GetWMI($wmi,$strClass, $strValue = array())
{
	$arrData = array();

	$objWEBM = $wmi->Get($strClass);
	$arrProp = $objWEBM->Properties_;
	$arrWEBMCol = $objWEBM->Instances_();
	foreach($arrWEBMCol as $objItem)
	{
		@reset($arrProp);
		$arrInstance = array();
		foreach($arrProp as $propItem)
		{
			eval("\$value = \$objItem->" . $propItem->Name . ";");
			if (empty($strValue))
			{
				$arrInstance[$propItem->Name] = trim($value);
			}
			else
			{
				if (in_array($propItem->Name, $strValue))
				{
					$arrInstance[$propItem->Name] = trim($value);
				}
			}
		}
		$arrData[] = $arrInstance;
	}
	return $arrData;
}

//比例条
function bar($percent)
{
?>
	<div class="bar"><div class="barli" style="width:<?php echo $percent?>%">&nbsp;</div></div>
<?php
}

$uptime = $sysInfo['uptime']; //在线时间
$stime = date('Y-m-d H:i:s'); //系统当前时间

//硬盘
$dt = round(@disk_total_space(".")/(1024*1024*1024),3); //总
$df = round(@disk_free_space(".")/(1024*1024*1024),3); //可用
$du = $dt-$df; //已用
$hdPercent = (floatval($dt)!=0)?round($du/$dt*100,2):0;

$load = $sysInfo['loadAvg'];	//系统负载


//判断内存如果小于1G，就显示M，否则显示G单位
if($sysInfo['memTotal']<1024)
{
	$memTotal = $sysInfo['memTotal']." M";
	$mt = $sysInfo['memTotal']." M";
	$mu = $sysInfo['memUsed']." M";
	$mf = $sysInfo['memFree']." M";
	$mc = $sysInfo['memCached']." M";	//cache化内存
	$mb = $sysInfo['memBuffers']." M";	//缓冲
	$st = $sysInfo['swapTotal']." M";
	$su = $sysInfo['swapUsed']." M";
	$sf = $sysInfo['swapFree']." M";
	$swapPercent = $sysInfo['swapPercent'];
	$memRealUsed = $sysInfo['memRealUsed']." M"; //真实内存使用
	$memRealFree = $sysInfo['memRealFree']." M"; //真实内存空闲
	$memRealPercent = $sysInfo['memRealPercent']; //真实内存使用比率
	$memPercent = $sysInfo['memPercent']; //内存总使用率
	$memCachedPercent = $sysInfo['memCachedPercent']; //cache内存使用率
}
else
{
	$memTotal = round($sysInfo['memTotal']/1024,3)." G";
	$mt = round($sysInfo['memTotal']/1024,3)." G";
	$mu = round($sysInfo['memUsed']/1024,3)." G";
	$mf = round($sysInfo['memFree']/1024,3)." G";
	$mc = round($sysInfo['memCached']/1024,3)." G";
	$mb = round($sysInfo['memBuffers']/1024,3)." G";
	$st = round($sysInfo['swapTotal']/1024,3)." G";
	$su = round($sysInfo['swapUsed']/1024,3)." G";
	$sf = round($sysInfo['swapFree']/1024,3)." G";
	$swapPercent = $sysInfo['swapPercent'];
	$memRealUsed = round($sysInfo['memRealUsed']/1024,3)." G"; //真实内存使用
	$memRealFree = round($sysInfo['memRealFree']/1024,3)." G"; //真实内存空闲
	$memRealPercent = $sysInfo['memRealPercent']; //真实内存使用比率
	$memPercent = $sysInfo['memPercent']; //内存总使用率
	$memCachedPercent = $sysInfo['memCachedPercent']; //cache内存使用率
}

//网卡流量
$strs = @file("/proc/net/dev");

for ($i = 2; $i < count($strs); $i++ )
{
	preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
	$NetOutSpeed[$i] = $info[10][0];
	$NetInputSpeed[$i] = $info[2][0];
	$NetInput[$i] = formatsize($info[2][0]);
	$NetOut[$i]  = formatsize($info[10][0]);
}

//ajax调用实时刷新
if ($_GET['act'] == "rt")
{
	$arr=array('useSpace'=>"$du",'freeSpace'=>"$df",'hdPercent'=>"$hdPercent",'barhdPercent'=>"$hdPercent%",'TotalMemory'=>"$mt",'UsedMemory'=>"$mu",'FreeMemory'=>"$mf",'CachedMemory'=>"$mc",'Buffers'=>"$mb",'TotalSwap'=>"$st",'swapUsed'=>"$su",'swapFree'=>"$sf",'loadAvg'=>"$load",'uptime'=>"$uptime",'freetime'=>"$freetime",'bjtime'=>"$bjtime",'stime'=>"$stime",'memRealPercent'=>"$memRealPercent",'memRealUsed'=>"$memRealUsed",'memRealFree'=>"$memRealFree",'memPercent'=>"$memPercent%",'memCachedPercent'=>"$memCachedPercent",'barmemCachedPercent'=>"$memCachedPercent%",'swapPercent'=>"$swapPercent",'barmemRealPercent'=>"$memRealPercent%",'barswapPercent'=>"$swapPercent%",'NetOut2'=>"$NetOut[2]",'NetOut3'=>"$NetOut[3]",'NetOut4'=>"$NetOut[4]",'NetOut5'=>"$NetOut[5]",'NetOut6'=>"$NetOut[6]",'NetOut7'=>"$NetOut[7]",'NetOut8'=>"$NetOut[8]",'NetOut9'=>"$NetOut[9]",'NetOut10'=>"$NetOut[10]",'NetInput2'=>"$NetInput[2]",'NetInput3'=>"$NetInput[3]",'NetInput4'=>"$NetInput[4]",'NetInput5'=>"$NetInput[5]",'NetInput6'=>"$NetInput[6]",'NetInput7'=>"$NetInput[7]",'NetInput8'=>"$NetInput[8]",'NetInput9'=>"$NetInput[9]",'NetInput10'=>"$NetInput[10]",'NetOutSpeed2'=>"$NetOutSpeed[2]",'NetOutSpeed3'=>"$NetOutSpeed[3]",'NetOutSpeed4'=>"$NetOutSpeed[4]",'NetOutSpeed5'=>"$NetOutSpeed[5]",'NetInputSpeed2'=>"$NetInputSpeed[2]",'NetInputSpeed3'=>"$NetInputSpeed[3]",'NetInputSpeed4'=>"$NetInputSpeed[4]",'NetInputSpeed5'=>"$NetInputSpeed[5]");
	$jarr=json_encode($arr);
	$_GET['callback'] = htmlspecialchars($_GET['callback']);
	echo $_GET['callback'],'(',$jarr,')';
	exit;
}
?>
<!doctype>
<html>
<head>
<title><?php echo $title.$version; ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function(){getJSONData();});
var OutSpeed2=<?php echo floor($NetOutSpeed[2]) ?>;
var OutSpeed3=<?php echo floor($NetOutSpeed[3]) ?>;
var OutSpeed4=<?php echo floor($NetOutSpeed[4]) ?>;
var OutSpeed5=<?php echo floor($NetOutSpeed[5]) ?>;
var InputSpeed2=<?php echo floor($NetInputSpeed[2]) ?>;
var InputSpeed3=<?php echo floor($NetInputSpeed[3]) ?>;
var InputSpeed4=<?php echo floor($NetInputSpeed[4]) ?>;
var InputSpeed5=<?php echo floor($NetInputSpeed[5]) ?>;
function getJSONData()
{
	setTimeout("getJSONData()", 1000);
	$.getJSON('?act=rt&callback=?', displayData);
}
function ForDight(Dight,How)
{
  if (Dight<0){
  	var Last=0+"B/s";
  }else if (Dight<1024){
  	var Last=Math.round(Dight*Math.pow(10,How))/Math.pow(10,How)+"B/s";
  }else if (Dight<1048576){
  	Dight=Dight/1024;
  	var Last=Math.round(Dight*Math.pow(10,How))/Math.pow(10,How)+"K/s";
  }else{
  	Dight=Dight/1048576;
  	var Last=Math.round(Dight*Math.pow(10,How))/Math.pow(10,How)+"M/s";
  }
	return Last;
}
function displayData(dataJSON)
{
	$("#useSpace").html(dataJSON.useSpace);
	$("#freeSpace").html(dataJSON.freeSpace);
	$("#hdPercent").html(dataJSON.hdPercent);
	$("#barhdPercent").width(dataJSON.barhdPercent);
	$("#TotalMemory").html(dataJSON.TotalMemory);
	$("#UsedMemory").html(dataJSON.UsedMemory);
	$("#FreeMemory").html(dataJSON.FreeMemory);
	$("#CachedMemory").html(dataJSON.CachedMemory);
	$("#Buffers").html(dataJSON.Buffers);
	$("#TotalSwap").html(dataJSON.TotalSwap);
	$("#swapUsed").html(dataJSON.swapUsed);
	$("#swapFree").html(dataJSON.swapFree);
	$("#swapPercent").html(dataJSON.swapPercent);
	$("#loadAvg").html(dataJSON.loadAvg);
	$("#uptime").html(dataJSON.uptime);
	$("#freetime").html(dataJSON.freetime);
	$("#stime").html(dataJSON.stime);
	$("#bjtime").html(dataJSON.bjtime);
	$("#memRealUsed").html(dataJSON.memRealUsed);
	$("#memRealFree").html(dataJSON.memRealFree);
	$("#memRealPercent").html(dataJSON.memRealPercent);
	$("#memPercent").html(dataJSON.memPercent);
	$("#barmemPercent").width(dataJSON.memPercent);
	$("#barmemRealPercent").width(dataJSON.barmemRealPercent);
	$("#memCachedPercent").html(dataJSON.memCachedPercent);
	$("#barmemCachedPercent").width(dataJSON.barmemCachedPercent);
	$("#barswapPercent").width(dataJSON.barswapPercent);
	$("#NetOut2").html(dataJSON.NetOut2);
	$("#NetOut3").html(dataJSON.NetOut3);
	$("#NetOut4").html(dataJSON.NetOut4);
	$("#NetOut5").html(dataJSON.NetOut5);
	$("#NetOut6").html(dataJSON.NetOut6);
	$("#NetOut7").html(dataJSON.NetOut7);
	$("#NetOut8").html(dataJSON.NetOut8);
	$("#NetOut9").html(dataJSON.NetOut9);
	$("#NetOut10").html(dataJSON.NetOut10);
	$("#NetInput2").html(dataJSON.NetInput2);
	$("#NetInput3").html(dataJSON.NetInput3);
	$("#NetInput4").html(dataJSON.NetInput4);
	$("#NetInput5").html(dataJSON.NetInput5);
	$("#NetInput6").html(dataJSON.NetInput6);
	$("#NetInput7").html(dataJSON.NetInput7);
	$("#NetInput8").html(dataJSON.NetInput8);
	$("#NetInput9").html(dataJSON.NetInput9);
	$("#NetInput10").html(dataJSON.NetInput10);
	$("#NetOutSpeed2").html(ForDight((dataJSON.NetOutSpeed2-OutSpeed2),3));	OutSpeed2=dataJSON.NetOutSpeed2;
	$("#NetOutSpeed3").html(ForDight((dataJSON.NetOutSpeed3-OutSpeed3),3));	OutSpeed3=dataJSON.NetOutSpeed3;
	$("#NetOutSpeed4").html(ForDight((dataJSON.NetOutSpeed4-OutSpeed4),3));	OutSpeed4=dataJSON.NetOutSpeed4;
	$("#NetOutSpeed5").html(ForDight((dataJSON.NetOutSpeed5-OutSpeed5),3));	OutSpeed5=dataJSON.NetOutSpeed5;
	$("#NetInputSpeed2").html(ForDight((dataJSON.NetInputSpeed2-InputSpeed2),3));	InputSpeed2=dataJSON.NetInputSpeed2;
	$("#NetInputSpeed3").html(ForDight((dataJSON.NetInputSpeed3-InputSpeed3),3));	InputSpeed3=dataJSON.NetInputSpeed3;
	$("#NetInputSpeed4").html(ForDight((dataJSON.NetInputSpeed4-InputSpeed4),3));	InputSpeed4=dataJSON.NetInputSpeed4;
	$("#NetInputSpeed5").html(ForDight((dataJSON.NetInputSpeed5-InputSpeed5),3));	InputSpeed5=dataJSON.NetInputSpeed5;
}
-->
</script>
<style>
    div.wall { width: 1000px; margin:50px auto; }
    table { font-size:90%; }
</style>
</head>
<body>
<div id="page" class="wall">
    <!-- Github Fork Me -->
    <a href="https://github.com/582033/monitor"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://dn-yjiang-cdn.qbox.me/forkme.png" alt="Fork me on GitHub" data-canonical-src="https://dn-yjiang-cdn.qbox.me/forkme.png"></a>
    <!-- Github Fork Me -->
<p>
    <iframe src="https://ghbtns.com/github-btn.html?user=582033&repo=monitor&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px"></iframe>
</p>
<table class="table table-condensed  table-bordered">
  <tr><th colspan="6">服务器实时数据</th></tr>
  <tr>
    <td width="13%" >服务器当前时间</td>
    <td width="37%" ><span id="stime"><?php echo $stime;?></span></td>
    <td width="16%" >服务器已运行时间</td>
    <td width="37%" colspan="3"><span id="uptime"><?php echo $uptime;?></span></td>
  </tr>
  <tr>
    <td width="13%">CPU型号 [<?php echo $sysInfo['cpu']['num'];?>核]</td>
    <td width="87%" colspan="5"><?php echo $sysInfo['cpu']['model'];?></td>
  </tr>
  <tr>
    <td>CPU使用状况</td>
    <td colspan="5"><?php if('/'==DIRECTORY_SEPARATOR){echo $cpu_show;}else{echo "暂时只支持Linux系统";}?>
    </td>
  </tr>
  <tr>
    <td>硬盘使用状况</td>
    <td colspan="5">
        总空间 <?php echo $dt;?>&nbsp;G，
        已用 <font color='#333333'><span id="useSpace"><?php echo $du;?></span></font>&nbsp;G，
        空闲 <font color='#333333'><span id="freeSpace"><?php echo $df;?></span></font>&nbsp;G，
        使用率 <span id="hdPercent"><?php echo $hdPercent;?></span>%
        <div class="bar"><div id="barhdPercent" class="barli_black" style="width:<?php echo $hdPercent;?>%" >&nbsp;</div> </div>
    </td>
  </tr>
  <tr>
        <td>内存使用状况</td>
        <td colspan="5">
          物理内存：共
          <font color='#CC0000'><?php echo $memTotal;?> </font>
           , 已用
          <font color='#CC0000'><span id="UsedMemory"><?php echo $mu;?></span></font>
          , 空闲
          <font color='#CC0000'><span id="FreeMemory"><?php echo $mf;?></span></font>
          , 使用率
          <span id="memPercent"><?php echo $memPercent;?></span>
            <div class="progress">
              <div id="barmemPercent" class="progress-bar" role="progressbar progress-bar-success" aria-valuenow="<?php echo $memPercent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $memPercent?>;">
                <span class="sr-only"><?php echo $memPercent?></span>
              </div>
            </div>
<?php
//判断如果cache为0，不显示
if($sysInfo['memCached']>0) {
?>
          Cache化内存为 <span id="CachedMemory"><?php echo $mc;?></span>
          , 使用率
          <span id="memCachedPercent"><?php echo $memCachedPercent;?></span>
          %	| Buffers缓冲为  <span id="Buffers"><?php echo $mb;?></span>
            <div class="progress">
              <div id="barmemCachedPercent" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $memCachedPercent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $memCachedPercent?>;">
                <span class="sr-only"><?php echo $memCachedPercent?></span>
              </div>
            </div>

          真实内存使用
          <span id="memRealUsed"><?php echo $memRealUsed;?></span>
          , 真实内存空闲
          <span id="memRealFree"><?php echo $memRealFree;?></span>
          , 使用率
          <span id="memRealPercent"><?php echo $memRealPercent;?></span>
          %
            <div class="progress">
              <div id="barmemRealPercent" class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $memRealPercent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $memRealPercent?>;">
                <span class="sr-only"><?php echo $memRealPercent?></span>
              </div>
            </div>
<?php
}
//判断如果SWAP区为0，不显示
if($sysInfo['swapTotal']>0)
{
?>
          SWAP区：共
          <?php echo $st;?>
          , 已使用
          <span id="swapUsed"><?php echo $su;?></span>
          , 空闲
          <span id="swapFree"><?php echo $sf;?></span>
          , 使用率
          <span id="swapPercent"><?php echo $swapPercent;?></span>
          %
            <div class="progress">
              <div id="barswapPercent" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $swapPercent?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $swapPercent?>;">
                <span class="sr-only"><?php echo $swapPercent?></span>
              </div>
            </div>

<?php
}
?>
      </td>
    </tr>
      <tr>
        <td>系统平均负载</td>
        <td colspan="5" class="w_number"><span id="loadAvg"><?php echo $load;?></span></td>
    </tr>
</table>

<?php if (false !== ($strs = @file("/proc/net/dev"))) : ?>
<table class="table table-condensed  table-bordered">
    <tr><th colspan="5">网络使用状况</th></tr>
<?php for ($i = 2; $i < count($strs); $i++ ) : ?>
<?php preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );?>
     <tr>
        <td width="13%"><?php echo $info[1][0]?> : </td>
        <td width="29%">入网: <font color='#CC0000'><span id="NetInput<?php echo $i?>"><?php echo $NetInput[$i]?></span></font></td>
        <td width="14%">实时: <font color='#CC0000'><span id="NetInputSpeed<?php echo $i?>">0B/s</span></font></td>
        <td width="29%">出网: <font color='#CC0000'><span id="NetOut<?php echo $i?>"><?php echo $NetOut[$i]?></span></font></td>
        <td width="14%">实时: <font color='#CC0000'><span id="NetOutSpeed<?php echo $i?>">0B/s</span></font></td>
    </tr>
<?php endfor; ?>
</table>
<?php endif; ?>
</form>
</div>
</body>
</html>
