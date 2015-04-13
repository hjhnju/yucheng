<?php
class Awards_TicketList extends Awards_List_Ticket {
    
    /**
     * 获取数据对象格式
     * @param string $object
     * @return array
     */
    public function getObjects($object = 'Awards_Ticket') {
        $data = $this->getData();
        if (empty($object) || empty($data)) {
            return $data;
        }
        
        $objects = array();
        foreach ($data as $row) {
            $obj = new $object();
            $obj->setData($row);
            $objects[] = $obj;
        }
        return $objects;
    }
}
