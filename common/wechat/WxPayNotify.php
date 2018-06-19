<?php
namespace common\wechat;


/**
 *
 * 回调基础类
 * @author widyhu
 *
 */
class WxPayNotify extends WxPayNotifyReply
{
    /**
     *
     * 回调入口
     * @param bool $needSign 是否需要签名输出
     */
    final public function Handle($needSign = true)
    {
        $msg = "OK";
        //当返回false的时候，表示notify中调用NotifyCallBack回调失败获取签名校验失败，此时直接回复失败
        $result = WxPayApi::notify(array($this, 'NotifyCallBack'), $msg);
        if ($result == false) {
            $this->SetReturn_code("FAIL");
            $this->SetReturn_msg($msg);
            $this->ReplyNotify(false);
            return;
        } else {
            //该分支在成功回调到NotifyCallBack方法，处理完成之后流程
            $this->SetReturn_code("SUCCESS");
            $this->SetReturn_msg("OK");
        }
        $this->ReplyNotify($needSign);
    }

    /**
     *
     * 回调方法入口，子类可重写该方法
     * 注意：
     * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
     * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
     * @param array $data 回调解释出的参数
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] === "SUCCESS" && $data['return_code'] === "SUCCESS") {
//            $id = $data['attach'];
//            $order = Order::model()->findByPk($id);
//            if ($order->status != Order::STATUS_WAIT_PAY) {
//                Yii::log(json_encode($data), CLogger::LEVEL_PROFILE, 'pay_status_error');
//                $msg = '订单状态错误';
//                return false;
//            }
//
//            $order->status = Order::STATUS_HAS_PAY;
//            $order->pay_time = time();
//            $order->transaction_id = $data['transaction_id'];
//            if ($order->save(false)) {
//                UserLog::addLog($order->user_id, UserLog::ACTION_PAY_ORDER, $order->order_id);
//                return true;
//            } else {
//                throw new WxPayException(json_encode($order->getErrors()) . json_encode($data));
//            }
        }

//        $msg = '订单状态错误';
//        Yii::log(json_encode($data), CLogger::LEVEL_PROFILE, 'pay_notify_error');
        return false;
    }

    /**
     *
     * notify回调方法，该方法中需要赋值需要输出的参数,不可重写
     * @param array $data
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    final public function NotifyCallBack($data)
    {
        $msg = "OK";
        $result = $this->NotifyProcess($data, $msg);

        if ($result == true) {
            $this->SetReturn_code("SUCCESS");
            $this->SetReturn_msg("OK");
        } else {
            $this->SetReturn_code("FAIL");
            $this->SetReturn_msg($msg);
        }
        return $result;
    }

    /**
     *
     * 回复通知
     * @param bool $needSign 是否需要签名输出
     */
    final private function ReplyNotify($needSign = true)
    {
        //如果需要签名
        if ($needSign == true && $this->GetReturn_code() == "SUCCESS") {
            $this->SetSign();
        }
        WxpayApi::replyNotify($this->ToXml());
    }
}