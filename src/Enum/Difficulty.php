<?php
namespace App\Enum;

enum Difficulty: string
{
	case Easy = 'facile';
	case Medium = 'moyen';
	case Hard = 'difficile';

	public function variant(): string{
		return match($this){
			self::Easy => 'success',
			self::Medium => 'warning',
			self::Hard => 'error',
		};
	} 
}

