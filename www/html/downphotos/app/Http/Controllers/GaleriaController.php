<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Image;
use Carbon\Carbon;


class GaleriaController extends Controller
{
    public function create(Request $request)
    {

    	$imagens = \App\Imagem::latest();
      $info = ""; 

       if($month = request('mes')){ //mes é o valor do request da url da página
            //whereMonth é um built-in do laravel
            $imagens->whereMonth('created_at', Carbon::parse($month)->month); //aqui estamos convertendo a string da url (mes) para um valor inteiro equivalente a mes
            $info =  "Em: " . Carbon::parse($month)->month;
        }
        
        if($year = request('ano')){//ano é o valor do request da url da página
            //whereYear é um built-in do laravel
            $imagens->whereYear('created_at', $year);
            $info =  $info."/" . $year;
        }
        

         $archives = \App\Imagem::selectRaw('year(created_at) ano, monthname(created_at) mes, count(*) publicados')
            ->groupBy('ano','mes')
            ->orderByRaw('min(created_at) desc')
            ->get()
            ->toArray();

     

      $files = $imagens->where('situacao', '=', 'ap');
      $qt = "Quantidade de fotos: ".$files->count() . " " . $info;

    	$files = $imagens->where('situacao', '=', 'ap')->paginate(20);
      $request->session()->put('url.intended',url()->full());

    	return view('layouts.galeria.galeria', compact('files', 'qt', 'archives'));
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
    public function preview($fileId, $resize)
    {


        $file = \App\Imagem::find($fileId);
   
     
        $finalPath = $file->caminho.'/'.$file->nome;
            
         $mime = mime_content_type($finalPath);


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

                           if($resize == 250){

                             return $image->insert($watermark, 'center')
                           ->encode('data-url',0)
                           ->orientate()
                           ->resize($resize, $resize)
                           ->response();

                           }else{


                           if($resize == 0){
                            return $image->insert($watermark, 'center')
                           ->encode('data-url',0)
                           ->orientate()
                           ->response();
                           
                           }
                          

                         }

                           

                    }
                    else{


                        if($resize == 250){

                             return $image->insert($watermark, 'center')
                           ->encode('data-url',0)
                           ->resize($resize, $resize)
                           ->response();

                           }else{


                           if($resize == 0){
                            return $image->insert($watermark, 'center')
                           ->encode('data-url',0)
                           ->response();
                           
                           }
                          

                         }
                          

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

        $img = Image::make($file->caminho . $file->nome);
        $filesize =  $img->filesize();
        $filesize = $filesize/1024; //kb
        $filesize = $filesize/1024; //mb
        $filesize = number_format ( $filesize , 2  );
        //$filesize = filesize($file->caminho . $file->nome );

        $height =   $img->height();
        $width =  $img->width();
        $mime =   $img->mime();



        //return view('layouts.usuario.editarImagem', compact('file'));
        return view('layouts.galeria.painelCompra', compact('file', 'filesize', 'height', 'width', 'mime'));
        //return $file;
       } 
      }



}
