<?php
class Seat {
    public $id;
    public $number;
    public $row;
    public $isBooked;
    public $showTimeID;
    public $price;

    public function __construct($id, $number, $row, $isBooked, $showTimeID, $price) {
        $this->id = $id;
        $this->number = $number;
        $this->row = $row;
        $this->isBooked = $isBooked;
        $this->showTimeID = $showTimeID;
        $this->price = $price; // Ensure price is set here
    }

    public function renderSeat($isSelected) {
        $status = $this->isBooked ? 'booked' : ($isSelected ? 'selected' : 'available');
        return "
            <button 
                type='submit' 
                name='toggle_seat' 
                value='" . htmlspecialchars($this->id, ENT_QUOTES, 'UTF-8') . "' 
                class='seat $status' 
                " . ($this->isBooked ? 'disabled' : '') . ">
                " . htmlspecialchars($this->row . $this->number, ENT_QUOTES, 'UTF-8') . "
            </button>
        ";
    }
}
?>
