<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserController extends Controller implements FromArray, WithHeadings
{
    protected $users;

    public function report()
    {
        $this->users = User::select('name', 'email')->get()->toArray(); // Получаем данные в конструкторе

        return Excel::download($this, 'users_report.xlsx');
    }

    public function array(): array
    {
        return $this->users;
    }

     public function headings(): array
    {
        return [
            'Name',
            'Email',
        ];
    }
}