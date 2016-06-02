<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\PacientesRequest;
use App\Patient;
use App\Tipo;

use Laracasts\Flash\Flash;
use Illuminate\Support\Str as Str;

class PacientesController extends Controller
{
        public function index()
    {	
    	$pacientes = Patient::orderBy('rfc', 'ASC')->get();
        $pacientes->each(function($pacientes) {
            $pacientes->tipo;
        });
       
    	return view('admin.pacientes.index')->with('pacientes', $pacientes);
    }

    public function create()
    {
        $tipos = Tipo::all()->lists('tipo', 'id')->toArray();
        asort($tipos);
    
        return view('admin.pacientes.createorupdate')->with('tipos', $tipos);
    }

    public function edit($id)
    {
        $paciente = Patient::find($id);
        $tipos = Tipo::all()->lists('tipo', 'id')->toArray();

        return view('admin.pacientes.createorupdate')->with('paciente', $paciente)->with('tipos', $tipos);;
    }

    public function update(Request $request, $id)
    {
        $paciente = Patient::find($id);
        $paciente->fill($request->all());
        $paciente->fecha_nacimiento = fecha_ymd($request->fecha_nacimiento);

        $paciente->save();
        Flash::success('Paciente editado con exito!');
        return redirect()->route('pacientes.index');
    }

    public function store(PacientesRequest $request)
    {
        $paciente = new Patient($request->all());
        $paciente->fecha_nacimiento = fecha_ymd($request->fecha_nacimiento);
        $paciente->slug = Str::slug($request->apellido_pat.' '.$request->apellido_mat.' '.$request->nombres);
        $paciente->save();

        Flash::success('Patient registrado con exito!');
        return redirect()->route('pacientes.index');
    }  

    public function destroy($id)
    {
        $paciente = Patient::find($id);
        $paciente->delete();

        Flash::error('Paciente ' . $paciente->rfc . ' ha sido borrado con exito!');
        return redirect()->route('pacientes.index');
    }
}
