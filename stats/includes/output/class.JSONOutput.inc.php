<?php
/**
 * JSON generator class
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PSI_OUTPUT
 * @author    Damien ROTH <BigMichi1@users.sourceforge.net>
 * @copyright 2013 phpSysInfo
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link      http://phpsysinfo.sourceforge.net
 */
class JSONOutput extends Output implements PSI_Interface_Output
{
    /**
     * Sysinfo object where the information retrieval methods are included
     *
     * @var PSI_Interface_OS
     */
    private $_sysinfo;

    /**
     * @var System
     */
    private $_sys = null;

    /**
     * JSON array
     *
     * @var Array
     */
    private $_xml;

    /**
     * only plugin xml
     *
     * @var boolean
     */
    private $_pluginRequest = false;

    /**
     * complete xml
     *
     * @var boolean
     */
    private $_completeXML = false;

    /**
     * name of the plugin
     *
     * @var Array
     */
    private $_plugins = array();

    /**
     * set parameters for the generation process
     *
     * @param boolean $completeXML switch for complete xml with all plugins
     * @param string  $plugin      name of the plugin
     *
     * @return void
     */
    public function __construct($completeXML, $plugin = null)
    {
        parent::__construct();

        if ($completeXML) {
            $this->_plugins = CommonFunctions::getPlugins();
        }
        if ($plugin !== null) {

            if (!is_array($plugin)) {
                $plugin = array($plugin);
            }

            foreach ($plugin as $p) {
                if (in_array(strtolower($p), CommonFunctions::getPlugins())) {
                    $this->_plugins[] = $p;
                }
                $this->_pluginRequest = true;
            }
        }

        $this->_prepare();
    }

    /**
     * generate the output
     *
     * @return void
     */
    private function _prepare()
    {
        if (!$this->_pluginRequest) {
            // Figure out which OS we are running on, and detect support
            if (!file_exists(APP_ROOT.'/includes/os/class.'.PSI_OS.'.inc.php')) {
                $this->error->addError("file_exists(class.".PSI_OS.".inc.php)", PSI_OS." is not currently supported");
            }

            // check if there is a valid sensor configuration in config.php
            $foundsp = array();
            if ( defined('PSI_SENSOR_PROGRAM') && is_string(PSI_SENSOR_PROGRAM) ) {
                if (preg_match(ARRAY_EXP, PSI_SENSOR_PROGRAM)) {
                    $sensorprograms = eval(strtolower(PSI_SENSOR_PROGRAM));
                } else {
                    $sensorprograms = array(strtolower(PSI_SENSOR_PROGRAM));
                }
                foreach ($sensorprograms as $sensorprogram) {
                    if (!file_exists(APP_ROOT.'/includes/mb/class.'.$sensorprogram.'.inc.php')) {
                        $this->error->addError("file_exists(class.".htmlspecialchars($sensorprogram).".inc.php)", "specified sensor program is not supported");
                    } else {
                        $foundsp[] = $sensorprogram;
                    }
                }
            }

            /**
             * motherboard information
             *
             * @var serialized array
             */
            define('PSI_MBINFO', serialize($foundsp));

            // check if there is a valid hddtemp configuration in config.php
            $found = false;
            if (PSI_HDD_TEMP !== false) {
                $found = true;
            }
            /**
             * hddtemp information available or not
             *
             * @var boolean
             */
            define('PSI_HDDTEMP', $found);

            // check if there is a valid ups configuration in config.php
            $found = false;
            if (PSI_UPS_PROGRAM !== false) {
                if (!file_exists(APP_ROOT.'/includes/ups/class.'.strtolower(PSI_UPS_PROGRAM).'.inc.php')) {
                    $found = false;
                    $this->error->addError("file_exists(class.".htmlspecialchars(strtolower(PSI_UPS_PROGRAM)).".inc.php)", "specified UPS program is not supported");
                } else {
                    $found = true;
                }
            }
            /**
             * ups information available or not
             *
             * @var boolean
             */
            define('PSI_UPSINFO', $found);

            // if there are errors stop executing the script until they are fixed
            if ($this->error->errorsExist()) {
                $this->error->errorsAsXML();
            }
        }
    }

    /**
     * render the output
     *
     * @return void
     */
    public function run()
    {
        $os = PSI_OS;
        $this->_sysinfo = new $os();
        $this->_sys = $this->_sysinfo->getSys();

        // Build JSON
        $this->_json = array();
        $this->_buildPSIHeader();
        $this->_buildVitals();
        $this->_buildNetwork();
        $this->_buildHardware();
        $this->_buildMemory();
        $this->_buildFilesystems();
        $this->_buildMbinfo();
        $this->_buildUpsinfo();
        $this->_buildPlugins();

        return json_encode($this->_json);
    }

    /**
     * Returns the plugins
     *
     * @return Array the loaded plugins
     */
    public function getPlugins()
    {
        return $this->_plugins;
    }

    private function _buildPSIHeader()
    {
        $this->_json = array(
            'Generation' => array(
                'version' => PSI_VERSION_STRING,
                'timestamp' => time()
            ),
            'Options' => array(
                'tempFormat' => defined('PSI_TEMP_FORMAT') ? strtolower(PSI_TEMP_FORMAT) : 'c',
                'byteFormat' => defined('PSI_BYTE_FORMAT') ? strtolower(PSI_BYTE_FORMAT) : 'auto_binary'
            )
        );

        if ( defined('PSI_REFRESH') ) {
            if (PSI_REFRESH === false) {
                $this->_json['Options']['refresh'] = 0;
            } elseif (PSI_REFRESH === true) {
                $this->_json['Options']['refresh'] = 1;
            } else {
                $this->_json['Options']['refresh'] = PSI_REFRESH;
            }
        } else {
            $this->_json['Options']['refresh'] = 60000;
        }

        if ( defined('PSI_FS_USAGE_THRESHOLD') ) {
            if (PSI_FS_USAGE_THRESHOLD === true) {
                $this->_json['Options']['threshold'] = 1;
            } elseif ((PSI_FS_USAGE_THRESHOLD !== false) && (PSI_FS_USAGE_THRESHOLD >= 1) && (PSI_FS_USAGE_THRESHOLD <= 99) ) {
                $this->_json['Options']['threshold'] = PSI_FS_USAGE_THRESHOLD;
            }
        } else {
            $this->_json['Options']['threshold'] = 90;
        }

        $this->_json['Options']['showPickListTemplate'] = defined('PSI_SHOW_PICKLIST_TEMPLATE') ? (PSI_SHOW_PICKLIST_TEMPLATE ? 'true' : 'false') : 'false';

        if (count($this->_plugins) > 0) {
            foreach ($this->_plugins as $plugin) {
                $this->_json['UsedPlugins']['Plugin'][] = $plugin;
            }
        }
    }

    private function _buildVitals()
    {
        $vitals = array(
            'Hostname' => $this->_sys->getHostname(),
            'IPAddr' => $this->_sys->getIp(),
            'Kernel' => $this->_sys->getKernel(),
            'Distro' => $this->_sys->getDistribution(),
            'Distroicon' => $this->_sys->getDistributionIcon(),
            'Uptime' => $this->_sys->getUptime(),
            'Users' => $this->_sys->getUsers(),
            'LoadAvg' => $this->_sys->getLoad()
        );

        if ($this->_sys->getLoadPercent() !== null) {
            $vitals['CPULoad'] = $this->_sys->getLoadPercent();
        }
        if ($this->_sysinfo->getLanguage() !== null) {
            $vitals['SysLang'] = $this->_sysinfo->getLanguage();
        }
        if ($this->_sysinfo->getEncoding() !== null) {
            $vitals['CodePage'] = $this->_sysinfo->getEncoding();
        }

        $this->_json['Vitals'] = $vitals;
    }

    /**
     * generate the network information
     *
     * @return void
     */
    private function _buildNetwork()
    {
        if ( defined('PSI_HIDE_NETWORK_INTERFACE') && is_string(PSI_HIDE_NETWORK_INTERFACE) ) {
            if (preg_match(ARRAY_EXP, PSI_HIDE_NETWORK_INTERFACE)) {
                $hideDevices = eval(PSI_HIDE_NETWORK_INTERFACE);
            } else {
                $hideDevices = array(PSI_HIDE_NETWORK_INTERFACE);
            }
        } else {
            $hideDevices = array();
        }

        foreach ($this->_sys->getNetDevices() as $dev) {
            if (!in_array(trim($dev->getName()), $hideDevices)) {

                $netdev = array(
                    'Name' => $dev->getName(),
                    'RxBytes' => $dev->getRxBytes(),
                    'TxBytes' => $dev->getTxBytes(),
                    'Err' => $dev->getErrors(),
                    'Drops' => $dev->getDrops()
                );
                if ( defined('PSI_SHOW_NETWORK_INFOS') && PSI_SHOW_NETWORK_INFOS && $dev->getInfo() )
                    $netdev['Info'] = $dev->getInfo();

                $this->_json['Network']['NetDevice'][] = $netdev;
            }
        }
    }

    /**
     * generate the hardware information
     *
     * @return void
     */
    private function _buildHardware()
    {
        $devices  = array(
            'PCI' => 'getPciDevices()',
            'USB' => 'getUsbDevices()',
            'IDE' => 'getIdeDevices()',
            'SCSI' => 'getScsiDevices()'
        );

        foreach ($devices as $devname=>$devfunc) {
            foreach (System::removeDupsAndCount(eval('return $this->_sys->'.$devfunc.';')) as $dev) {
                $device = array('Name' => $dev->getName());
                if ($dev->getCount() > 1) {
                    $device['Count'] = $dev->getCount();
                }
                if ($dev->getCapacity() !== null) {
                    $device['Capacity'] = $dev->getCapacity();
                }
                $this->_json['Hardware'][$devname][] = $device;
            }
        }

        foreach ($this->_sys->getCpus() as $oneCpu) {

            $cpu = array('Model' => $oneCpu->getModel());

            if ($oneCpu->getCpuSpeed() !== 0) {
                $cpu['CpuSpeed'] = $oneCpu->getCpuSpeed();
            }
            if ($oneCpu->getCpuSpeedMax() !== 0) {
                $cpu['CpuSpeedMax'] = $oneCpu->getCpuSpeedMax();
            }
            if ($oneCpu->getCpuSpeedMin() !== 0) {
                $cpu['CpuSpeedMin'] = $oneCpu->getCpuSpeedMin();
            }
            if ($oneCpu->getTemp() !== null) {
                $cpu['CpuTemp'] = $oneCpu->getTemp();
            }
            if ($oneCpu->getBusSpeed() !== null) {
                $cpu['BusSpeed'] = $oneCpu->getBusSpeed();
            }
            if ($oneCpu->getCache() !== null) {
                $cpu['Cache'] = $oneCpu->getCache();
            }
            if ($oneCpu->getVirt() !== null) {
                $cpu['Virt'] = $oneCpu->getVirt();
            }
            if ($oneCpu->getBogomips() !== null) {
                $cpu['Bogomips'] = $oneCpu->getBogomips();
            }
            if ($oneCpu->getLoad() !== null) {
                $cpu['Load'] = $oneCpu->getLoad();
            }

            $this->_json['Hardware']['CPU'][] = $cpu;
        }
    }

    /**
     * generate the memory information
     *
     * @return void
     */
    private function _buildMemory()
    {

        $memory = array(
            'Free' => $this->_sys->getMemFree(),
            'Used' => $this->_sys->getMemUsed(),
            'Total' => $this->_sys->getMemTotal(),
            'Percent' => $this->_sys->getMemPercentUsed(),
            'Details' => array()
        );

        if ($this->_sys->getMemApplication() !== null) {
            $memory['Details']['App'] = $this->_sys->getMemApplication();
            $memory['Details']['AppPercent'] = $this->_sys->getMemPercentApplication();
        }

        if ($this->_sys->getMemBuffer() !== null) {
            $memory['Details']['Buffers'] = $this->_sys->getMemBuffer();
            $memory['Details']['BuffersPercent'] = $this->_sys->getMemPercentBuffer();
        }

        if ($this->_sys->getMemCache() !== null) {
            $memory['Details']['Cached'] = $this->_sys->getMemCache();
            $memory['Details']['CachedPercent'] = $this->_sys->getMemPercentCache();
        }

        if (count($this->_sys->getSwapDevices()) > 0) {
            $memory['Swap'] = array(
                'Free' => $this->_sys->getSwapFree(),
                'Used' => $this->_sys->getSwapUsed(),
                'Total' => $this->_sys->getSwapTotal(),
                'Percent' => $this->_sys->getSwapPercentUsed(),
                'Devices' => array()
            );

            $i = 1;
            foreach ($this->_sys->getSwapDevices() as $dev) {
                $memory['Swap']['Mount'][] = $this->_fillDevice($dev, $i++);
            }
        }

        $this->_json['Memory'] = $memory;
    }

    /**
     * generate the filesysteminformation
     *
     * @return void
     */
    private function _buildFilesystems()
    {
        $hideMounts = $hideFstypes = $hideDisks = array();
        if ( defined('PSI_HIDE_MOUNTS') && is_string(PSI_HIDE_MOUNTS) ) {
            if (preg_match(ARRAY_EXP, PSI_HIDE_MOUNTS)) {
                $hideMounts = eval(PSI_HIDE_MOUNTS);
            } else {
                $hideMounts = array(PSI_HIDE_MOUNTS);
            }
        }
        if ( defined('PSI_HIDE_FS_TYPES') && is_string(PSI_HIDE_FS_TYPES) ) {
            if (preg_match(ARRAY_EXP, PSI_HIDE_FS_TYPES)) {
                $hideFstypes = eval(PSI_HIDE_FS_TYPES);
            } else {
                $hideFstypes = array(PSI_HIDE_FS_TYPES);
            }
        }
        if ( defined('PSI_HIDE_DISKS') && is_string(PSI_HIDE_DISKS) ) {
            if (preg_match(ARRAY_EXP, PSI_HIDE_DISKS)) {
                $hideDisks = eval(PSI_HIDE_DISKS);
            } else {
                $hideDisks = array(PSI_HIDE_DISKS);
            }
        }

        $i = 1;
        foreach ($this->_sys->getDiskDevices() as $disk) {
            if (!in_array($disk->getMountPoint(), $hideMounts, true)
                && !in_array($disk->getFsType(), $hideFstypes, true)
                && !in_array($disk->getName(), $hideDisks, true)) {

                $this->_json['FileSystem']['Mount'][] = $this->_fillDevice($disk, $i++);
            }
        }
    }

    /**
     * fill a xml element with atrributes from a disk device
     *
     * @param DiskDevice $dev DiskDevice
     * @param Integer    $i   counter
     *
     * @return Void
     */
    private function _fillDevice(DiskDevice $dev, $i)
    {
        $mount = array(
            'MountPointID' => $i,
            'FSType' => $dev->getFsType(),
            'Name' => $dev->getName(),
            'Free' => sprintf("%.0f", $dev->getFree()),
            'Used' => sprintf("%.0f", $dev->getUsed()),
            'Total' => sprintf("%.0f", $dev->getTotal()),
            'Percent' => $dev->getPercentUsed()
        );
        if (PSI_SHOW_MOUNT_OPTION === true && $dev->getOptions() !== null) {
            $mount['MountOptions'] = preg_replace("/,/",", ",$dev->getOptions());
        }
        if ($dev->getPercentInodesUsed() !== null) {
            $mount['Inodes'] = $dev->getPercentInodesUsed();
        }
        if (PSI_SHOW_MOUNT_POINT === true) {
            $mount['MountPoint'] = $dev->getMountPoint();
        }

        return $mount;
    }

    /**
     * generate the motherboard information
     *
     * @return void
     */
    private function _buildMbinfo()
    {
        $mbinfo = array();
        if ((sizeof(unserialize(PSI_MBINFO))>0) || PSI_HDDTEMP) {

            $mbinfo['Temperature'] = array();

            if (sizeof(unserialize(PSI_MBINFO))>0) {
                foreach (unserialize(PSI_MBINFO) as $mbinfoclass) {
                    $mbinfo_data = new $mbinfoclass();
                    $mbinfo_detail = $mbinfo_data->getMBInfo();
                    foreach ($mbinfo_detail->getMbTemp() as $dev) {

                        $item = array(
                            'Label' => $dev->getName(),
                            'Value' => $dev->getValue()
                        );
                        if ($dev->getMax() !== null) {
                            $item['Max'] = $dev->getMax();
                        }
                        if ( defined('PSI_SENSOR_EVENTS') && PSI_SENSOR_EVENTS && $dev->getEvent() !== "" ) {
                            $item['Event'] = $dev->getEvent();
                        }

                        $mbinfo['Temperature'][] = $item;
                    }
                }
            }
            if (PSI_HDDTEMP) {
                $hddtemp = new HDDTemp();
                $hddtemp_data = $hddtemp->getMBInfo();
                foreach ($hddtemp_data->getMbTemp() as $dev) {
                    $item = array(
                        'Label' => $dev->getName(),
                        'Value' => $dev->getValue()
                    );
                    if ($dev->getMax() !== null) {
                        $item['Max'] = $dev->getMax();
                    }

                    $mbinfo['Temperature'][] = $item;
                }
            }
        }

        if (sizeof(unserialize(PSI_MBINFO))>0) {
            foreach ($mbinfo_detail->getMbFan() as $dev) {
                $item = array(
                    'Label' => $dev->getName(),
                    'Value' => $dev->getValue()
                );
                if ($dev->getMin() !== null) {
                    $item['Min'] = $dev->getMin();
                }
                if ( defined('PSI_SENSOR_EVENTS') && PSI_SENSOR_EVENTS && $dev->getEvent() !== "" ) {
                    $item['Event'] = $dev->getEvent();
                }

                $mbinfo['Fans'][] = $item;
            }

            foreach ($mbinfo_detail->getMbVolt() as $dev) {
                $item = array(
                    'Label' => $dev->getName(),
                    'Value' => $dev->getValue()
                );
                if ($dev->getMin() !== null) {
                    $item['Min'] = $dev->getMin();
                }
                if ($dev->getMax() !== null) {
                    $item['Max'] = $dev->getMax();
                }
                if ( defined('PSI_SENSOR_EVENTS') && PSI_SENSOR_EVENTS && $dev->getEvent() !== "" ) {
                    $item['Event'] = $dev->getEvent();
                }

                $mbinfo['Voltage'][] = $item;
            }

            foreach ($mbinfo_detail->getMbPower() as $dev) {
                $item = array(
                    'Label' => $dev->getName(),
                    'Value' => $dev->getValue()
                );
                if ($dev->getMax() !== null) {
                    $item['Max'] = $dev->getMax();
                }
                if ( defined('PSI_SENSOR_EVENTS') && PSI_SENSOR_EVENTS && $dev->getEvent() !== "" ) {
                    $item['Event'] = $dev->getEvent();
                }

                $mbinfo['Power'][] = $item;
            }

            foreach ($mbinfo_detail->getMbCurrent() as $dev) {
                $item = array(
                    'Label' => $dev->getName(),
                    'Value' => $dev->getValue()
                );
                if ($dev->getMax() !== null) {
                    $item['Max'] = $dev->getMax();
                }
                if ( defined('PSI_SENSOR_EVENTS') && PSI_SENSOR_EVENTS && $dev->getEvent() !== "" ) {
                    $item['Event'] = $dev->getEvent();
                }

                $mbinfo['Current'][] = $item;
            }

        }

        if (count($mbinfo) > 0)
            $this->_json['MBInfo'] = $mbinfo;
    }

    /**
     * generate the ups information
     *
     * @return void
     */
    private function _buildUpsinfo()
    {
        $upsinfo = array();
        if ( defined('PSI_UPS_APCUPSD_CGI_ENABLE') && PSI_UPS_APCUPSD_CGI_ENABLE) {
            $upsinfo['ApcupsdCgiLinks'] = true;
        }
        if (PSI_UPSINFO) {
            $upsinfoclass = PSI_UPS_PROGRAM;
            $upsinfo_data = new $upsinfoclass();
            $upsinfo_detail = $upsinfo_data->getUPSInfo();
            foreach ($upsinfo_detail->getUpsDevices() as $ups) {

                $item = array(
                    'Name' => $ups->getName(),
                    'Model' => $ups->getModel(),
                    'Mode' => $ups->getMode(),
                    'StartTime' => $ups->getStartTime(),
                    'Status' => $ups->getStatus()
                );

                if ($ups->getTemperatur() !== null)
                    $item['Temperature'] = $ups->getTemperatur();

                if ($ups->getOutages() !== null)
                    $item['OutagesCount'] = $ups->getOutages();

                if ($ups->getLastOutage() !== null)
                    $item['LastOutage'] = $ups->getLastOutage();

                if ($ups->getLastOutageFinish() !== null)
                    $item['LastOutageFinish'] = $ups->getLastOutageFinish();

                if ($ups->getLineVoltage() !== null)
                    $item['LineVoltage'] = $ups->getLineVoltage();

                if ($ups->getLoad() !== null)
                    $item['LoadPercent'] = $ups->getLoad();

                if ($ups->getBatteryDate() !== null)
                    $item['BatteryDate'] = $ups->getBatteryDate();

                if ($ups->getBatteryVoltage() !== null)
                    $item['BatteryVoltage'] = $ups->getBatteryVoltage();

                if ($ups->getBatterCharge() !== null)
                    $item['BatteryChargePercent'] = $ups->getBatterCharge();

                if ($ups->getTimeLeft() !== null)
                    $item['TimeLeftMinutes'] = $ups->getTimeLeft();

                $upsinfo[] = $item;
            }
        }

        if (count($upsinfo) > 0)
            $this->_json['UPSInfo']['UPS'] = $upsinfo;
    }

    /**
     * Includes the plugins to the main data array
     *
     * @return void
     */
    private function _buildPlugins()
    {
        if (count($this->_plugins) > 0) {
            $plugins = array();
            foreach ($this->_plugins as $plugin) {
                $object = new $plugin($this->_sysinfo->getEncoding());
                $object->execute();
                $data = $object->getData();

                if ($data !== null) {
                    $plugins[get_class($object)] = $data;
                }
            }
            if (count($plugins) > 0) {
                $this->_json['Plugins'] = $plugins;
            }
        }
    }
}
