<?php
class Seat {
    public $id;
    public $number;
    public $row;
    public $isBooked;
    public $showTimeID;
    public $price;

    public function __construct($id, $number, $row, $isBooked, $showTimeID, $price) {
        $this->id = (int) $id;
        $this->number = (int) $number;
        $this->row = htmlspecialchars($row, ENT_QUOTES, 'UTF-8');
        $this->isBooked = (bool) $isBooked;
        $this->showTimeID = (int) $showTimeID;
        $this->price = (float) $price;
    }

    /**
     * Renders a seat as an HTML button element.
     */
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

