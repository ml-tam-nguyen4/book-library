<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\User;

class UserController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return mixed
     */
    public function index()
    {
        $field = [
            'users.id',
            'users.employee_code',
            'users.name',
            'users.email',
            DB::raw('count(distinct(borrowings.id)) as total_borrowed'),
            DB::raw('count(distinct(donators.id)) as total_donated'),
        ];
        $users = User::leftJoin('borrowings', 'borrowings.user_id', '=', 'users.id')
        ->leftJoin('donators', 'donators.user_id', '=', 'users.id')
        ->select($field)
        ->groupBy('users.id')
        ->get();
        return view('backend.users.index', compact('users'));
    }

    /**
     * Display User Detail.
     *
     * @return mixed
     */
    public function show()
    {
        //
    }
}
