<?php

namespace App\Tars\cservant\PaySystem\PayService\PayObj;

use Tars\client\CommunicatorConfig;
use Tars\client\Communicator;
use Tars\client\RequestPacket;
use Tars\client\TUPAPIWrapper;

use App\Tars\cservant\PaySystem\PayService\PayObj\classes\SimpleStruct;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\OutStruct;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\InUnify;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\Status;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\OutUnify;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\InCreateMechat;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\InUpdateMechat;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\InOrderPage;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\OutOrderPage;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\PayInfo;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\OutPosBill;
use App\Tars\cservant\PaySystem\PayService\PayObj\classes\OutPosAmount;
class PayServiceServant {
	protected $_communicator;
	protected $_iVersion;
	protected $_iTimeout;
	public $_servantName = "PaySystem.PayService.PayObj";

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

	public function test($a,$b) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putInt32("a",1,$a,$this->_iVersion);
			$encodeBufs['a'] = $__buffer;
			$__buffer = TUPAPIWrapper::putInt32("b",2,$b,$this->_iVersion);
			$encodeBufs['b'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			return TUPAPIWrapper::getString("",0,$sBuffer,$this->_iVersion);

		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function testVector($a,$v1,$v2,&$v3,&$v4) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putInt32("a",1,$a,$this->_iVersion);
			$encodeBufs['a'] = $__buffer;
			$v1_vec = new \TARS_Vector(\TARS::STRING);
			foreach($v1 as $singlev1) {
				$v1_vec->pushBack($singlev1);
			}
			$__buffer = TUPAPIWrapper::putVector("v1",2,$v1_vec,$this->_iVersion);
			$encodeBufs['v1'] = $__buffer;
			$v2_vec = new \TARS_Vector(new SimpleStruct());
			foreach($v2 as $singlev2) {
				$v2_vec->pushBack($singlev2);
			}
			$__buffer = TUPAPIWrapper::putVector("v2",3,$v2_vec,$this->_iVersion);
			$encodeBufs['v2'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$v3 = TUPAPIWrapper::getVector("v3",4,new \TARS_Vector(\TARS::INT32),$sBuffer,$this->_iVersion);
			$v4 = TUPAPIWrapper::getVector("v4",5,new \TARS_Vector(new OutStruct()),$sBuffer,$this->_iVersion);
			return TUPAPIWrapper::getString("",0,$sBuffer,$this->_iVersion);

		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function unify(InUnify $inParams,Status &$status,OutUnify &$outParams) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParams",1,$inParams,$this->_iVersion);
			$encodeBufs['inParams'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("status",2,$status,$sBuffer,$this->_iVersion);
			$ret = TUPAPIWrapper::getStruct("outParams",3,$outParams,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function integralWithdraw($amount,Status &$status) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putInt32("amount",1,$amount,$this->_iVersion);
			$encodeBufs['amount'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("status",2,$status,$sBuffer,$this->_iVersion);
			return TUPAPIWrapper::getString("",0,$sBuffer,$this->_iVersion);

		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function createMechant(InCreateMechat $inParams,Status &$status) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParams",1,$inParams,$this->_iVersion);
			$encodeBufs['inParams'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("status",2,$status,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function updateMechant(InUpdateMechat $inParams,Status &$status) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParams",1,$inParams,$this->_iVersion);
			$encodeBufs['inParams'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("status",2,$status,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function mechantOverage($shopId,&$overage) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putInt32("shopId",1,$shopId,$this->_iVersion);
			$encodeBufs['shopId'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$overage = TUPAPIWrapper::getInt32("overage",2,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function overageWithdraw($amount,Status &$status) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putInt32("amount",1,$amount,$this->_iVersion);
			$encodeBufs['amount'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("status",2,$status,$sBuffer,$this->_iVersion);
			return TUPAPIWrapper::getString("",0,$sBuffer,$this->_iVersion);

		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function orderPages(InOrderPage $inParams,OutOrderPage &$outParams) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParams",1,$inParams,$this->_iVersion);
			$encodeBufs['inParams'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParams",2,$outParams,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function orderInfo($orderId,PayInfo &$outParams) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putInt32("orderId",1,$orderId,$this->_iVersion);
			$encodeBufs['orderId'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParams",2,$outParams,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function posBill($inParams,OutPosBill &$outParams) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParams",1,$inParams,$this->_iVersion);
			$encodeBufs['inParams'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParams",2,$outParams,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

	public function posAmount($inParams,OutPosAmount &$outParams) {
		try {
			$requestPacket = new RequestPacket();
			$requestPacket->_iVersion = $this->_iVersion;
			$requestPacket->_funcName = __FUNCTION__;
			$requestPacket->_servantName = $this->_servantName;
			$encodeBufs = [];

			$__buffer = TUPAPIWrapper::putStruct("inParams",1,$inParams,$this->_iVersion);
			$encodeBufs['inParams'] = $__buffer;
			$requestPacket->_encodeBufs = $encodeBufs;

			$sBuffer = $this->_communicator->invoke($requestPacket,$this->_iTimeout);

			$ret = TUPAPIWrapper::getStruct("outParams",2,$outParams,$sBuffer,$this->_iVersion);
		}
		catch (\Exception $e) {
			throw $e;
		}
	}

}

