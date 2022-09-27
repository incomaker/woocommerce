<?php

namespace Incomaker\Api\Controller;

use Incomaker\Api\Data\Order;

/**
 * Description of OrderController
 */
class OrderController extends Controller {

    /**
     * 
     * @param type $permId
     * @return Event
     */
    public function createOrderObject($permId) {
        return new Order($permId);
    }

    /**
     * Adds order
     * 
     * @param Order $order
     * @return response
     */
    public function addOrder(Order $order) {
        return $this->curlPostQuery("order/", "", json_encode(array("order" => $data->getArrayData())));
    }

    /**
     * Updates order
     * 
     * @param Order $order
     * @return reponse
     */
    public function updateOrder(Order $order) {
        return $this->curlPutQuery("order/", "", json_encode(array("order" => $data->getArrayData())));
    }

    /**
     * Deletes order
     * 
     * @param type $orderId
     * @return response
     */
    public function deleteOrder($orderId) {
        return $this->curlDeleteQuery("order/", "&orderId=" . urlencode($orderId));
    }

}
