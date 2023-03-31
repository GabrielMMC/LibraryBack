<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Gender;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();
        try {
            $book = new Book();
            $book->fill($data);

            $document = $request->file('thumb');
            $name = uniqid('cover_') . '.' . $document->getClientOriginalExtension();
            $book->thumb = $document->storeAs('book_covers', $name, ['disk' => 'public']);

            $book->save();

            return response()->json(['status' => true, 'message' => 'Livro criado com sucesso!']);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => 'Erro ao criar livro!']);
        }
    }

    /**
     * List all resources.
     */
    public function list(Request $request)
    {
        try {
            $genders = Gender::all();
            $books = Book::orderBy('created_at', 'desc')->where(function ($q) use ($request) {
                if (isset($request->dateOf) && isset($request->dateFor)) {
                    $dateOf = new DateTime($request->dateOf);
                    $dateFor = new DateTime($request->dateFor);

                    $q->whereRaw('lower(title) LIKE lower(?)', ['%' . $request->search . '%'])
                        ->whereBetween('created_at', [$dateOf->format('Y-m-d\ H:i:s'), $dateFor->format('Y-m-d\ H:i:s')]);
                    if ($request->gender !== null) {
                        $q->where('gender_id', '=', $request->gender);
                    }
                } else {
                    $q->whereRaw('lower(title) LIKE lower(?)', ['%' . $request->search . '%']);
                    if ($request->gender !== null) {
                        $q->where('gender_id', '=', $request->gender);
                    }
                }
            })->paginate($request->sizeData);

            return response()->json([
                'books' => BookResource::collection($books),
                'genders' => $genders,
                'pagination' => [
                    'current_page' => $books->currentPage(),
                    'last_page' => $books->lastPage(),
                    'total_pages' => $books->total(),
                    'per_page' => $books->perPage(),
                ],
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => 'Erro ao listar livros!'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function get($id)
    {
        try {
            $book = Book::findOrFail($id);

            return response()->json(['status' => true, 'book' => $book]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => 'Erro ao carregar livro!']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data = $request->validated();
        try {
            $book = Book::findOrFail($data['id']);
            $book->fill($data)->save();

            return response()->json(['status' => true, 'message' => 'Livro alterado com sucesso!']);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => 'Erro ao criar livro!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {
            Book::findOrFail($id)->delete();

            return response()->json(['status' => true, 'message' => 'Livro deletado com sucesso!']);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => 'Erro ao deletar livro!']);
        }
    }
}
