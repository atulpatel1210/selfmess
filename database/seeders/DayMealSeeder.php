<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DayMeal;

class DayMealSeeder extends Seeder
{
    public function run(): void
    {
        $meals = [
            ['day' => 'monday', 'breakfast' => 'Poha', 'lunch' => 'Gujarati Thali', 'dinner' => 'Khichdi Kadhi'],
            ['day' => 'tuesday', 'breakfast' => 'Upma', 'lunch' => 'Roti Sabzi', 'dinner' => 'Dal Fry Rice'],
            ['day' => 'wednesday', 'breakfast' => 'Thepla', 'lunch' => 'Kathiyawadi Thali', 'dinner' => 'Vaghareli Khichdi'],
            ['day' => 'thursday', 'breakfast' => 'Idli Sambhar', 'lunch' => 'Punjabi Thali', 'dinner' => 'Dosa'],
            ['day' => 'friday', 'breakfast' => 'Paratha', 'lunch' => 'Mix Veg Roti', 'dinner' => 'Pav Bhaji'],
            ['day' => 'saturday', 'breakfast' => 'Bataka Vada', 'lunch' => 'Dal Bati', 'dinner' => 'Pizza/Pasta'],
            ['day' => 'sunday', 'breakfast' => 'Special Puri Bhaji', 'lunch' => 'Rajvadi Thali', 'dinner' => 'Light Dinner'],
        ];

        foreach ($meals as $meal) {
            DayMeal::updateOrCreate(['day' => $meal['day']], $meal);
        }
    }
}