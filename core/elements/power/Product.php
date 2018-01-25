<?php

namespace  core\elements\power;

use core\entities\power\Products;

class Product
{
    public $status = Products::STATUS_LOADED;
    public $title;
    public $storageM;
    public $storageV = null;
    public $purchase;
}