<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Image;

class GaleriaController extends Controller
{
    public function create()
    {

    	$imagens = \App\Imagem::latest();

    	$files = $imagens->where('situacao', '=', 'ap')->paginate(25);

    	return view('layouts.galeria.galeria', compact('files'));
    }

     public function pesquisar(){

      $request = \Request::all();
      

      $validatorPesquisar = Validator::make($request, [

         'pesquisa' => 'required'

      ]);

       if (!$validatorPesquisar->fails()) {

       	$imagens = \App\Imagem::latest();

    	$fotos = $imagens->where('situacao', '=', 'ap');

        $files = $fotos->where('apelido', 'like', '%'.$request['pesquisa'].'%')
        ->orWhere('valor','LIKE','%'.$request['pesquisa'].'%')
        ->orWhere('descricao','LIKE','%'.$request['pesquisa'].'%')
        ->orWhere('situacao','LIKE','%'.$request['pesquisa'].'%')
        ->paginate(25);
        //dd($files);


        $filtroON = "Pesquisa: ".$request['pesquisa']. ", Resultado: " .$files->count() . " items";

       }else{
         return back()->withErrors([ 
                
                'Não Localizado' 
            ]);

       }

      return view('layouts.galeria.galeria', compact('user', 'files', 'filtroON'));


    }
    public function preview($fileId)
    {


       $file = \App\Imagem::find($fileId);
   
     
        $finalPath = $file->caminho.'/'.$file->nome;
            
         $mime = mime_content_type($finalPath);


         $resize = 250;

          $watermark = Image::make('fotos/secure/watermark.png');
                    $image = Image::make($finalPath);

                    $watermarkSize = $image->width() - 20; 
                    $watermarkSize = $image->width() / 2; 



                    $resizePercentage = 10;
                    $watermarkSize = round($image->width() * ((100 - $resizePercentage) / 100), 2);

                    $watermark->resize($watermarkSize, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });


           if($mime == "image/jpeg")
            {

                   

                    return $image->insert($watermark, 'center')
                   ->encode('data-url',0)
                   ->orientate()
                   ->resize($resize, $resize)
                   ->response();

            }
            else{

                  

                   return $image->insert($watermark, 'center')
                   ->encode('data-url',0)
                   ->resize($resize, $resize)
                   ->response();
                  

            }
      }


      public function painel($fotoId){


        if($file = \App\Imagem::find($fotoId)->situacao != 'ap'){

         return back()->withErrors([ 
                
                'Algo deu errado!' 
            ]);
       }
       else{
        $file = \App\Imagem::find($fotoId);
        //dd($file);
        //return view('layouts.usuario.editarImagem', compact('file'));
        return view('layouts.galeria.painelCompra', compact('file'));
        //return $file;
       } 
      }



}
