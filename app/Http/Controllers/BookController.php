<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Book;

// use Illuminate\Pagination\Paginator;
class BookController extends Controller
{
    /**
    * Index
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $fields = [
            'id',
            'name',
            'author',
            'avg_rating',
        ];
        $books = Book::with('borrows')->select($fields)->paginate(Book::ROW_LIMIT);
        return view('backend.layouts.books.list', compact('books'));
    }
}
