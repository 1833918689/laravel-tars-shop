<?php

namespace App\Tars\cservant\BB\UserService\Misc;

use Tars\client\CommunicatorConfig;
use Tars\client\Communicator;
use Tars\client\RequestPacket;
use Tars\client\TUPAPIWrapper;

use App\Tars\cservant\BB\UserService\Misc\classes\CommonInParam;
use App\Tars\cservant\BB\UserService\Misc\classes\CommonOutParam;
class SmsServant {
	protected $_communicator;
	protected $_iVersion;
	protected $_iTimeout;
	public $_servantName = "BB.UserService.Misc";

	public function __construct(CommunicatorConfig $config) {
		try {
			$config->setServantName($this->_servantName);
			$this->_communicator = new Communicator($config);
			$this->_iVersion = $config->getIVersion();
			$this->_iTimeout = empty($config->getAsyncInvokeTimeout())?2:$config->getAsyncInvokeTimeout();
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public function sendSms(CommonInParam $inParam,$to,$content,$template,$data,CommonOutParam &$outParam) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParam",1,$inParam,$this->_iVersion);
			$encodeBufs['inParam'] = $__buffer;
			$__buffer = TUPAPIWrapper::putString("to",2,$to,$this->_iVersion);
			$encodeBufs['to'] = $__buffer;
			$__buffer = TUPAPIWrapper::putString("content",3,$content,$this->_iVersion);
			$encodeBufs['content'] = $__buffer;
			$__buffer = TUPAPIWrapper::putString("template",4,$template,$this->_iVersion);
			$encodeBufs['template'] = $__buffer;
			$data_vec = new \TARS_Vector(new \TARS_Map(\TARS::STRING,\TARS::STRING));
			foreach($data as $singledata) {
				$data_vec->pushBack($singledata);
			}
			$__buffer = TUPAPIWrapper::putVector("data",5,$data_vec,$this->_iVersion);
			$encodeBufs['data'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParam",6,$outParam,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

}

