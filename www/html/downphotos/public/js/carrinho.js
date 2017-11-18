$(document).ready(function() {
	//var carrinho = $.cookie('carrinho');

	//if (carrinho == undefined) {
	//	carrinho = {};
	//}
	//else {
	//	carrinho = JSON.parse(JSON.parse(carrinho));
	//}

/*	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
*/
$.ajax({
     url:"/carrinho/obterProdutos",
     dataType: 'json', 
     success:function(dados){   
     	var subtotal = 0;      
        $.each(dados, function(i, el){
	    	var tr = '<tr>' +
						'<td><img width=100px src="'+ el.caminho + el.nome +'"/></td>' +
							'<td>' + el.apelido + '</td>' +      							
							'<td>R$ ' + el.valor + '</td>' +
							'<td><button class="btn btn-danger" data-id="' + el.id + '">' + 
								'<span class="glyphicon glyphicon-trash"></span></button>'+
							'</td>' +
					 '</tr>';
        	$('table#carrinho tbody').append(tr);

        	subtotal = subtotal + el.valor;
        });

        $('#subtotal').append('<label>R$ ' + subtotal + '</label>');
        $('table#carrinho button').click(excluirCarrinho);	
     },
     error:function(){
         alert("Error");
     }      
});

function excluirCarrinho(){
	var idImg = $(this).data('id');
	
	$.ajax({
		     url:"/carrinho",
		     type: 'DELETE',
		     dataType: 'json',
		     data: {id : idImg},
		     success:function(dados){		         
		        console.log(dados);
		     },
		     error:function(dados){
		         console.log(dados);
		     }      
	});	
}

});