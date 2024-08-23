<?php

namespace App\Http\Controllers;

use App\Http\Requests\InfoRequest;
use App\Models\Info;

class InfoController extends Controller
{
    public function index()
    {
        $infos = Info::all();
        return view('index', compact('infos'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(InfoRequest $request)
    {
        $fileName = time().'.'.$request->file->extension();
        //$request->file->move(public_path('images'), $fileName); Para guardar en el almacenamiento público, y mostrar en front a traves de función asset(). Los archivos se almacenan en public/ (en esta caso public/images/) 
        
        //$request->file->storeAs('images', $fileName); Para guardar en el almacenamiento privado, dentro de storage/ (en este caso storage/images/)

        //Es más recomendable guardar los archivos públic en la carpeta storage/public/ en vez que directamente en public/
        //para ello:
        $request->file->storeAs('public/images', $fileName);
        //para luego acceder a la carpeta storage/public es necesario generar un storage link mediante PHP:

        // > php artisan storage:link
    
        //Se creará un link simbólico haciendo que todo lo que almacenamos dentro de storage/public sea accesible a través del link generado public/storage/

        //Es decir se crea un "acceso directo" dentro de la carpeta public que apunta a storage/public

        //Así, para acceder al contenido guardado en storage/public/images lo haremos mediante asset(storage/images/.$file->file_uri)

        //Si quiero guardar los archivos en otro disco, ya sea s3 (AWS) o un FTP se puede indicar mediante:
        //Storage::disk('ftp')->put()

        $info = new Info;
        $info->name = $request->name;
        $info->file_uri = $fileName;
        $info->save();

        return redirect()->route('index');
        //si en vez de redirigir a una ruta, quiero generar un link de descarga, se puede hacer con:

        //return Storage::download('nombrearchivo.ext', $info->file_uri);   Es posible hacerlo con archivos no públicos, los archivos públicos deben serlo solo para mostrarlos en el front
        //Si quiero conocer la url de un archivo, se puede usar Storage::url($info->file_uri)

        //Si queremos generar un link temporal para un archivo, se puede utilizar:
        //Storage::temporaryUrl('myimage.jpg', now()->addMinutes(10));
    }
}
