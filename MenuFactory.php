<?php

interface MenuFactory {
    public function createMenuItem($name, $description, $price, $specialOffer, $imagePath);
}

class StarterFactory implements MenuFactory {
    public function createMenuItem($name, $description, $price, $specialOffer, $imagePath) {
        return new MenuItem($name, $description, $price, 'Starter', $specialOffer, $imagePath);
    }
}

class MainCourseFactory implements MenuFactory {
    public function createMenuItem($name, $description, $price, $specialOffer, $imagePath) {
        return new MenuItem($name, $description, $price, 'Main Course', $specialOffer, $imagePath);
    }
}

class DessertFactory implements MenuFactory {
    public function createMenuItem($name, $description, $price, $specialOffer, $imagePath) {
        return new MenuItem($name, $description, $price, 'Dessert', $specialOffer, $imagePath);
    }
}

class BeverageFactory implements MenuFactory {
    public function createMenuItem($name, $description, $price, $specialOffer, $imagePath) {
        return new MenuItem($name, $description, $price, 'Beverage', $specialOffer, $imagePath);
    }
}

class SpecialFactory implements MenuFactory {
    public function createMenuItem($name, $description, $price, $specialOffer, $imagePath) {
        return new MenuItem($name, $description, $price, 'Special', $specialOffer, $imagePath);
    }
}

class MenuItem {
    public $name;
    public $description;
    public $price;
    public $category;
    public $specialOffer;
    public $imagePath;

    public function __construct($name, $description, $price, $category, $specialOffer, $imagePath) {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->category = $category;
        $this->specialOffer = $specialOffer;
        $this->imagePath = $imagePath;
    }
}
