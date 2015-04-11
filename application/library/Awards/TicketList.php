<?php
class Awards_TicketList extends Awards_List_Ticket {
    public function getObjects() {
        return parent::getObjects('Awards_Ticket');
    }
}
