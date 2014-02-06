<?php

class LoketEntry extends Eloquent {
	protected $connection = 'antrian';
	protected $table = 'loketentry';
	protected $fillable = array('formno', 'antrian');
}