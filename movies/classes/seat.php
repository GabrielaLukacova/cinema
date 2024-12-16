<?php

class Seat {
    public int $id;
    public int $number;
    public string $row;
    public bool $isBooked;
    public int $showTimeID;
    public float $price;

    public function __construct(int $id, int $number, string $row, bool $isBooked, int $showTimeID, float $price) {
        $this->id = $id;
        $this->number = $number;
        $this->row = htmlspecialchars($row, ENT_QUOTES, 'UTF-8');
        $this->isBooked = $isBooked;
        $this->showTimeID = $showTimeID;
        $this->price = $price;
    }

    public function renderSeat(bool $isSelected): string {
        $class = $this->isBooked ? 'seat-booked' : ($isSelected ? 'seat-selected' : 'seat-available');
        return "
            <button 
                type='submit' 
                name='toggle_seat' 
                value='{$this->id}' 
                class='seat $class' 
                " . ($this->isBooked ? 'disabled' : '') . ">
                {$this->row}{$this->number}
            </button>";
    }
}
?>

