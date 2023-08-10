
function CRUD(dados, url){
    $.ajax({
        dataType: 'JSON',
        type: 'POST',
        assync: true,
        url: url,
        data: dados,
        success: function(dados){
          if(dados.mensagem != ''){

            Swal.fire ({
                icon: dados.type,
                title: 'SysPed',
                text: dados.mensagem
            })
            $('#modal-fPagamento').modal('hide')
        }
         else if(dados.type == 'view'){
            $('#NOME').val(dados.dados.NOME)
            $('#ID').val(dados.dados.ID)
        }
        }
    })
}




$(document).ready(function(){
    
    $('#table-fPagamento').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "api/models/formadepagamentocontroller.php?operacao=read",
            "type": "POST"
        },
        "language": {
            "url": "assets/vendor/DataTables/pt-BR.json"
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [{
                "data": 'ID',
                "className": 'text-center'
            },
            {
                "data": 'NOME',
                "className": 'text-left'
            },
            {
                "data": 'ID',
                "orderable": false,
                "searchable": false,
                "className": 'text-center',
                "render": function(data, type, row, meta) {
                    return `
                    <button id="${data}" class="btn btn-info btn-sm btn-view">Visualizar</button>
                    <button id="${data}" class="btn btn-primary btn-sm btn-edit">Editar</button>
                    <button id="${data}" class="btn btn-danger btn-sm btn-delete">Excluir</button>
                    `
                }
            }
        ]
    })




$('.btn-new').click(function(e){
    e.preventDefault()
    $('.modal-title').empty()
    $('.modal-title').append('Cadastro de Forma de Pagamento')
    $('#form-fPagamento :input').val('')
    $('.btn-save').attr('data-operation', 'create')
    $('#modal-fPagamento').modal('show')
    $('input').prop('disabled', false)
})
$('.btn-save').click(function(e){
   e.preventDefault()

   let dados = $('#form-fPagamento').serialize()
   dados += `&operacao=${$('.btn-save').attr('data-operation')}` 
   
   let url = 'api/models/formadepagamentocontroller.php'
   
   CRUD(dados, url)

   $('#table-fPagamento').DataTable().ajax.reload()
})

$('#table-fPagamento').on('click', 'button.btn-view', function(e){
    e.preventDefault()
    
    $('.modal-title').empty()
    $('.modal-title').append('Visualização de registros')
    let dados = `ID=${$(this).attr('id')}&operacao=view`
    let url = 'api/models/formadepagamentocontroller.php'

    CRUD(dados, url)
    $('.btn-save').hide()
    $('input').prop('disabled', true)
    $('#modal-fPagamento').modal('show')
})

$('#table-fPagamento').on('click', 'button.btn-edit', function(e){
    e.preventDefault()
    
    $('.modal-title').empty()
    $('.modal-title').append('Edição de registros')
    let dados = `ID=${$(this).attr('id')}&operacao=view`
    let url = 'api/models/formadepagamentocontroller.php'

    CRUD(dados, url)
    $('.btn-save').attr('data-operation', 'update')
    $('.btn-save').show()
    $('input').prop('disabled', false)
    $('#modal-fPagamento').modal('show')
    $('#table-fPagamento').DataTable().ajax.reload()
})

$('#table-fPagamento').on('click', 'button.btn-delete', function(e){
    e.preventDefault()
    
    Swal.fire ({
        icon: 'warning',
        title: 'Você tem certeza que deseja excluir?',
        text: 'Esta operação é irreverível',
        showCancelButton: true,
        confirmButtonText: 'Sim, desejo excluir',
        cancelButtonText: 'Não, desejo cancelar'
    }) .then((result => {
        if(result.isConfirmed){
            let dados = `ID=${$(this).attr('id')}&operacao=delete`
    let url = 'api/models/formadepagamentocontroller.php'

    CRUD(dados, url)
    $('#table-fPagamento').DataTable().ajax.reload()
        }
        else if (result.dismiss === Swal.DismissReason.cancel){
            Swal.fire ({
                icon: 'error',
                title: 'SysPed',
                text: 'Operação cancelada',
            }) 
        } 
    }))

})

})