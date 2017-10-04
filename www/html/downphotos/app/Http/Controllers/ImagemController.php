<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Auth;
use Validator;
use ZipArchive;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use URL;
use Response;

class ImagemController extends Controller
{
    //
    public function preview($fileId){


        $file = \App\Imagem::find($fileId);
     
        $finalPath = $file->caminho.'/'.$file->nome;

         $mime = mime_content_type($finalPath);

           if($mime == "image/jpeg")
            {

                  return $image = Image::make($finalPath)
                  ->encode('data-url',0)
                  ->resize(50, 50)
                  ->orientate()->response();
            }
            else{
                
                  return $image = Image::make($finalPath)
                  ->resize(50, 50)
                  ->encode('data-url',0)
                  ->response();

          }

    }


     public function previewMedium($fileId){


        $file = \App\Imagem::find($fileId);
     
        $finalPath = $file->caminho.'/'.$file->nome;

         $mime = mime_content_type($finalPath);

           if($mime == "image/jpeg")
            {

                  return $image = Image::make($finalPath)
                  ->encode('data-url',0)
                  ->resize(250, 250)
                  ->orientate()->response();
            }
            else{
                
                  return $image = Image::make($finalPath)
                  ->resize(250, 150)
                  ->encode('data-url',0)
                  ->response();

          }

    }

    public function previewLarge($fileId){




        $file = \App\Imagem::find($fileId);
     
        $finalPath = $file->caminho.'/'.$file->nome;



        return $image = Image::make($finalPath)
        ->orientate()
        ->encode('data-url',100)->response();

    }


    public function actions(){

    
      $userId = Auth::id();

      $request = \Request::all();
      //dd($request);

      $validatorExcluir = Validator::make($request, [

         'files' => 'required',
         'Excluir' => 'required'

      ]);
       $validatorBaixar = Validator::make($request, [

         'files' => 'required',
         'Baixar' => 'required'

      ]);


      if (!$validatorExcluir->fails()) {
          //dd(\Request::all());
          $this -> destroyN($request, $userId);
          return redirect('/envio');
      }
      if (!$validatorBaixar->fails()) {
          //dd(\Request::all());
          if(file_exists(public_path() . "/zip.zip")){

            unlink(public_path() . "/zip.zip");
          }
         
           $arquivo = $this -> downloadN($request, $userId);
          //dd($arquivo);
          return response()->download(public_path() . "/zip.zip");
      }
      else{
        return back()->withErrors([ 
                
                'Selecione para efeutar as ações' 
            ]);
      }
     
         


      }
    public function destroyN($request, $userId)
    {
    
      $request = request(['files']);
      //dd($request);

       foreach($request as $key => $value)
        {
           
            foreach($value as $i){

                //session()->flash('Mensagem', 'teste '. ' ' .$i );
                //return redirect()->back();
                //dd($i);
                $this->destroy($userId, $i);


            }

        }
         

    }

    public function destroy($userID, $fileId)
    {
      $file = \App\Imagem::find($fileId);
     
      $finalPath = $file->caminho;

      $file->delete();

      unlink($finalPath.'/'.$file->nome); 

      session()->flash('Mensagem', 'Arquivo excluído com sucesso' );

       return redirect()->back();

    }

    public function downloadN($request, $userId)
    {

     
    $zip = new ZipArchive();

    if ($zip->open(public_path() . "/zip.zip", ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$filename>\n");
    }

     //dd($zipName);
      //dd($zipPath);
     //dd($zip);
      $request = $request['files'];
      //$zip->add(("zip");
      //dd($request);
      //dd($file);
      
       foreach($request as $key => $value)
        {  
          //dd($key);
            

          $file = \App\Imagem::find($value);


          $zip->addFile( $file->caminho.$file->nome, $file->nome  );
           //dd($zip);
          //$zip->add($file->caminho.$file->nome);
          //$this ->download($finalPath);

            

        }
        //dd($request);
        //return redirect()->back();
        $zip->close();
        //dd($zip);
      //dd(public_path() . "/zip.zip");

      //return response()->download(public_path() . "/zip.zip");
      //return redirect()->back();
      return $zip;

    }


    public function download($finalPath)
    {
      
      
      Zipper::make(public_path("zip.zip"))->add($finalPath);


    }


    public function cancela(){

        return back()->withErrors([ 
                
                'Upload Cancelado' 
            ]);


    }

    public function editarFoto($fotoId)
    {

        $user = Auth::user();
       
       //dd($request);
       if($file = $user->files->find($fotoId) == null){

         return back()->withErrors([ 
                
                'Algo deu errado!' 
            ]);
       }
       else{
        $file = $user->files->find($fotoId);
        //return view('layouts.usuario.editarImagem', compact('file'));
        return view('layouts.usuario.editarImagem', compact('file'));
        //return $file;
       } 
    }

    public function editarDadosFoto(){

      $user = Auth::user();
      $request = \Request::all();
     

      $validatorEditar = Validator::make($request, [

         'nome' => 'required|min:4|max:10',
         'valor' => 'required|numeric|min:1|max:50',
         'description' => 'required|min:11|max:50',
         'foto' => 'required'

      ]);

      if (!$validatorEditar->fails()) {
        //dd($request);
        $foto = $user->files->find($request['foto']);
        //dd($foto);

        $foto->apelido = $request['nome'];
        $foto->valor = $request['valor'];
        $foto->descricao = $request['description'];
        $foto->save();
        session()->flash('Mensagem', ' Editaro com Sucesso' );
        return back();
         
      }else{

          return back()->withErrors($validatorEditar);
            //return redirect('/fotos/editar/19');
      }

    }

    public function publicarFoto($fotoId)
    {

       $user = Auth::user();
       
       //dd($request);
       if($foto = $user->files->find($fotoId) == null){

         return back()->withErrors([ 
                
                'Algo deu errado!' 
            ]);
       }
       else{
        $foto = $user->files->find($fotoId);
        return view('layouts.usuario.publicarImagem', compact('foto'));
       } 
     
    }

     public function publicarDadosFoto(){

      $user = Auth::user();
      $request = \Request::all();
      //$request = $request['descrição'];
      //dd($request['foto']);
      $validatorPublicar = Validator::make($request, [
         'foto' => 'required'
      ]);



      if (!$validatorPublicar->fails()) {
        //dd($request);
        $foto = $user->files->find($request['foto'])->toArray();
        //dd($foto);
        $validator = Validator::make($foto, [
          'apelido' => 'required|min:4|max:10',
          'valor' => 'required|numeric|min:1|max:50',
          'descricao' => 'required|min:11|max:50'
          ]
        
        );

        if (!$validator->fails()) {
          $foto = $user->files->find($request['foto']);
          $foto->situacao = "ag";
          $foto->save();
          session()->flash('Mensagem', ' Enviado para Aprovação' );
          return back();
         }
         else{
            return back()->withErrors($validator);
         }

       
         
      }else{

          return back()->withErrors($validatorPublicar);

      }

    }

    public function filtro($filtro)
    {
        $user = Auth::user();
       
       

       if($filtro == 'Novos'){
         $filtro = 'nv';
       }
       else if($filtro == 'Aguardando'){
        $filtro = 'ag';

       }
       else if($filtro == 'Aprovados'){

        $filtro = 'ap';
       }
       else if($filtro == 'Reprovados'){

        $filtro = 're';
       }
       else{
          return back()->withErrors([ 
                
                'Filtro não existe' 
            ]);
       }
       


        $files = \App\Imagem::where('user_id', '=', $user->id);
        $files =  $files->where('situacao', '=', $filtro)->paginate(5);
        //dd($files);

        return view('layouts.usuario.upload', compact('user', 'files'));

    }

    public function pesquisar(){
      $user = Auth::user();

      $request = \Request::all();
      

      $validatorPesquisar = Validator::make($request, [

         'pesquisa' => 'required'

      ]);

       if (!$validatorPesquisar->fails()) {

        $files = \App\Imagem::where('user_id', '=', $user->id);
  

        $files = $files->where('apelido', 'like', '%'.$request['pesquisa'].'%')
        ->orWhere('valor','LIKE','%'.$request['pesquisa'].'%')
        ->orWhere('descricao','LIKE','%'.$request['pesquisa'].'%')
        ->orWhere('situacao','LIKE','%'.$request['pesquisa'].'%')
        ->paginate(5);
       
       
      


       $filtroON = "Pesquisa: ".$request['pesquisa']. ", Resultado: " .$files->count() . " items";

       }else{
         return back()->withErrors([ 
                
                'Não Localizado' 
            ]);

       }

      return view('layouts.usuario.upload', compact('user', 'files', 'filtroON'));


    }



  
}
