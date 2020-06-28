<?php
namespace WGRedmond\FraudFighter\Interfaces;

interface EventsInterface {
    public function addBasicProperties($entity);
    public function  addCustomProperties($entity);
    public function  addOptions();
}