<?php
/**
 * Created by PhpStorm.
 * User: gonghaichao
 * Date: 2017/11/17
 * Time: 上午9:59
 */

namespace Gate\Ticket;

use Gate\GateRequestBean;
use Gate\Model\TicketModelInf;

abstract class GateTicketAbs
{
    protected $isAuthenticateSuccess = false;
    protected $isNeedPassword = true;
    protected $requestBeanData;
    protected $matchingTicketData;
    private $model;

    public function __construct(GateRequestBean $requestBean, TicketModelInf $model)
    {
        $this->requestBeanData = $requestBean;
        $this->model = $model;
    }

    /**
     * 是否需要密码
     * @return boolean
     */
    abstract public function isNeedPassword();

    /**
     * 认证流程
     * @return mixed
     */
    abstract public function authentication();

    /**
     * 是否授权成功
     * @return boolean
     */
    abstract public function isAuthenticateSuccess();

    /**
     * @param bool $isAuthenticateSuccess
     */
    abstract public function setIsAuthenticateSuccess($isAuthenticateSuccess);

    /**
     * @return int
     */
    abstract public function getTicketType();

    /**
     * 检查其他票据是否存在
     * @return mixed
     */
    abstract public function checkOtherTicketExist();

    /**
     * @return mixed
     */
    public function checkBasisDataExist()
    {
        $data = $this->model->selectByTicketValueAndType($this->requestBeanData->getKeyValue(), $this->requestBeanData->getTicketType())->toArray();
        return $data ? true : false;
    }

    /**
     * 搜索票据是否存在
     * @throws \Exception
     */
    public function matchingTicket()
    {

        if (!$this->requestBeanData instanceof GateRequestBean) {
            throw new \Exception('参数类型错误');
        }
        if ($this->requestBeanData->getTicketType() != $this->getTicketType()) {
            throw new \Exception('票据类型错误');
        }
        $data = $this->model->selectByTicketValueAndType($this->requestBeanData->getKeyValue(), $this->requestBeanData->getTicketType())->toArray();
        $this->matchingTicketData = $data;
        if ($this->model->isNeedPassword()) {
            $this->isNeedPassword = true;
        }
    }

    /**
     * @return mixed
     */
    public function getMatchingTicketData()
    {
        return $this->matchingTicketData;
    }

    /**
     * @return TicketModelInf
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return GateRequestBean
     */
    public function getRequestBeanData()
    {
        return $this->requestBeanData;
    }

    /**
     * @param GateRequestBean $requestBeanData
     */
    public function setRequestBeanData(GateRequestBean $requestBeanData)
    {
        $this->requestBeanData = $requestBeanData;
    }
}
