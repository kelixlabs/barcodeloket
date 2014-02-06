<?php

class BuQueue extends Eloquent {
	protected $connection = 'antrian';
	protected $table = 'buqueue';
	protected $fillable = array('formno', 'kodebu');
}