@extends('layouts.master')
@section('content') 
<link href="{{ asset('css/usuario.css') }}" rel="stylesheet" />
<link href="{{ asset('css/galeria.css') }}" rel="stylesheet" />

<!--Inserir Menu aqui-->

<!-- cadastro -->
  <div class="main-1">
  <h1>Visualizar/Alterar dados cadastrais</h1>
    <div class="container">
      <div class="register">
        <div class="clearfix"> </div>
        <div class="register-but">


           <div class="navi">
                    <ul class="list-unstyled">
                       <li class="row toggle" id="dropdown-detail-1" data-toggle="detail-1"><a href="javascript:void(0)"><span class="glyphicon glyphicon-user"></span>&nbspInformações Pessoais</a></li>
                       <!-- pergunta-->
                       <div id="detail-1">
                          <hr>         
                            
                                 <form method="POST" action="/alterarInfoPessoais">

                                         
                                   <div class="register-top-grid">

                                      <div class="wow fadeInLeft">
                                      <span>Nome<label>*</label></span>
                                      <input name="nome" type="text" value="{{Auth::user()->nome}}" required> 
                                      </div>

                                      <div class="wow fadeInRight">
                                      <span>Sobrenome<label>*</label></span>
                                      <input name="sobrenome" type="text" value="{{Auth::user()->sobrenome}}" required> 
                                      </div>  


                                      <div class="wow fadeInLeft">
                                       <span>Email<label>*</label></span>
                                       <input name="email" type="text" value="{{Auth::user()->email}}" required> 
                                      </div>

                                      <hr>

                                    </div>
                                   <input type="submit" value="Alterar">
                                   <div class="clearfix"> </div>
                                 </form>

                        </div>

                        <li class="row toggle" id="dropdown-detail-2" data-toggle="detail-2"><a href="javascript:void(0)"><span class="glyphicon glyphicon-lock"></span>&nbspSegurança</a></li>
                       <!-- pergunta-->
                       <div id="detail-2">
                          <hr>         
                            
                                 <form method="POST" action="/alterarInfoSeguranca">

                                         
                                   <div class="register-top-grid">

                                       <div class="wow fadeInLeft">
                                            <span>Senha Atual<label>*</label></span>
                                            <input name="atual" type="password" required>
                                        </div>

                                        <div class="wow fadeInLeft">
                                            <span>Nova Senha<label>*</label></span>
                                            <input name="password" type="password" required>
                                        </div>

                                        <div class="wow fadeInRight">
                                        <span>Confirme a Nova Senha<label>*</label></span>
                                        <input name="password_confirmation" type="password" required>
                                        </div>

                                      <hr>

                                    </div>
                                   <input type="submit" value="Alterar">
                                   <div class="clearfix"> </div>
                                 </form>

                        </div>



                         <li class="row toggle" id="dropdown-detail-3" data-toggle="detail-3"><a href="javascript:void(0)"><span class="glyphicon glyphicon-home"></span>&nbspResidencial</a></li>
                       <!-- pergunta-->
                       <div id="detail-3">
                          <hr>         
                            
                                 <form method="POST" action="/alterarInfoResidencial">

                                         
                                   <div class="register-top-grid">

                                        <div class="wow fadeInLeft">
                                          <span>Endereço<label>*</label></span>
                                           <input name="endereco" type="text" value="{{Auth::user()->endereco}}" required>
                                          </div>

                                        <div class="wow fadeInRight">
                                        <span>CEP<label>*</label></span>
                                        <input name="cep" type="text" value="{{Auth::user()->cep}}" required>
                                        </div>


                                        <div class="wow fadeInLeft">
                                          <span>Cidade<label>*</label></span>
                                           <input name="cidade" type="text" value="{{Auth::user()->cidade}}" required>
                                        </div>

                                         <div class="wow fadeInRight">
                                        <span>Pais<label>*</label></span>
                                        <input name="pais" type="text" value="{{Auth::user()->pais}}" required>
                                        </div>

                                         <div class="wow fadeInRight">
                                        <span>telefone<label>*</label></span>
                                        <input name="telefone" type="text" value="{{Auth::user()->telefone}}" required>
                                        </div>

                                      <hr>

                                    </div>
                                   <input type="submit" value="Alterar">
                                   <div class="clearfix"> </div>
                                 </form>

                        </div>

                    </ul>
            </div>

    
        </div>
       </div>
     </div>


<script type="text/javascript" src="{{ asset('js/menuEffect.js') }}"></script>
<style>


body > div.main-1 > div.container > div > div.register-but > div > ul > li{
 background:#393838;
}


#detail-2 > form > div.register-top-grid > div.wow.fadeInLeft > input[type="password"],
#detail-2 > form > div.register-top-grid > div.wow.fadeInRight > input[type="password"]{
   border: 1px solid #EEE;
    outline-color: #4eaddf;
    width: -webkit-fill-available;
    font-size: 1em;
    padding: 0.5em;
    -webkit-box-shadow: -9px 10px 5px -4px rgba(0,0,0,0.44);
    -moz-box-shadow: -9px 10px 5px -4px rgba(0,0,0,0.44);
    box-shadow: -9px 10px 5px -4px rgba(0,0,0,0.44);
    margin-left:20px;
}
#detail-2 > form > input[type="submit"]{
    margin-left:20px;
}
   
#detail-2 > form > div.register-top-grid > div.wow.fadeInLeft > input[type="password"]:hover,
#detail-2 > form > div.register-top-grid > div.wow.fadeInRight > input[type="password"]:hover{
    border-color: #ee4f4f;
    transition: all 0.5s ease-in-out;
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
}
</style>
@endsection