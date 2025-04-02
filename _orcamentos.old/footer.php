    </div> <!-- Fechamento do container -->
    
    <script>
    $(document).ready(function() {
        // Inicializa DataTables em todas as tabelas
        $('.table').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
            }
        });
        
        // Inicializa Select2 em todos os selects
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
        
        // Inicializa máscaras
        $('.cpf').mask('000.000.000-00');
        $('.cnpj').mask('00.000.000/0000-00');
        $('.telefone').mask('(00) 00000-0000');
        $('.cep').mask('00000-000');
    });
    </script>
</body>
</html> 