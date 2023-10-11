<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;
use App\TallaRopa;

class TallaRopaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $tallas = TallaRopa::select('talla_ropas.*')
            ->orderBy('talla_ropas.id', 'desc')->paginate(3);
        }
        else{
            $tallas = TallaRopa::select('talla_ropas.*')
            ->where('talla_ropas.' .$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('talla_ropas.id', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $tallas->total(),
                'current_page' => $tallas->currentPage(),
                'per_page'     => $tallas->perPage(),
                'last_page'    => $tallas->lastPage(),
                'from'         => $tallas->firstItem(),
                'to'           => $tallas->lastItem(),
            ],
            'talla' => $tallas
        ];
    }

    public function selectTalla(Request $request){
        if (!$request->ajax()) return redirect('/');
        $tallas = TallaRopa::select('id','talla')->orderBy('id', 'asc')->get();
        return ['talla
        ' => $tallas];
    }

    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $tallas = new TallaRopa();
        $tallas->id = $request->id;
        $tallas->talla = $request->talla;
        $tallas->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tallas = TallaRopa::findOrFail($request->id);
        $tallas->talla = $request->talla;
        $tallas->save();
    }
}
